<?php
/**
 * Settlement Engine - Auto-Settlement System
 * 
 * Core settlement logic for evaluating pending bets against declared results,
 * crediting winners, and marking losses. Called by the settlement API endpoint
 * when the scraper detects a result status transition.
 * 
 * Requirements: 5.1, 5.2, 5.3
 */

// Ensure database connection is available
if (!isset($con) || !$con) {
	$include_path = __DIR__ . '/connect.php';
	if (is_file($include_path)) {
		require_once $include_path;
	}
}

/**
 * settle_market - Main orchestrator for settling a market's bets
 * 
 * Executes all bet evaluations and balance credits for a single market
 * settlement within a database transaction. On failure, rolls back all
 * changes and logs the error.
 * 
 * @param int $scraped_market_id  The scraped_markets.id
 * @param string $settlement_type  'open' or 'close'
 * @return array ['success' => bool, 'bets_processed' => int, 'winners' => int, 'total_credited' => float, 'error' => string]
 */
function settle_market($scraped_market_id, $settlement_type) {
	global $con;

	$result = [
		'success' => false,
		'bets_processed' => 0,
		'winners' => 0,
		'total_credited' => 0.0,
		'error' => ''
	];

	$scraped_market_id = (int) $scraped_market_id;
	$settlement_type = (string) $settlement_type;

	// Validate settlement_type
	if (!in_array($settlement_type, ['open', 'close'], true)) {
		$result['error'] = 'Invalid settlement_type: ' . $settlement_type;
		settle_log_error($scraped_market_id, '', $settlement_type, $result['error']);
		return $result;
	}

	// Begin transaction for atomic settlement
	mysqli_begin_transaction($con);

	try {
		// Load market data from scraped_markets
		$stmt = mysqli_prepare($con, "SELECT * FROM scraped_markets WHERE id = ? LIMIT 1");
		if (!$stmt) {
			throw new Exception('Failed to prepare market query: ' . mysqli_error($con));
		}
		mysqli_stmt_bind_param($stmt, 'i', $scraped_market_id);
		mysqli_stmt_execute($stmt);
		$market_result = mysqli_stmt_get_result($stmt);
		$market = $market_result ? mysqli_fetch_assoc($market_result) : null;

		if (!$market) {
			throw new Exception('Market not found: id=' . $scraped_market_id);
		}

		$market_name = $market['market_name'] ?? '';
		$date = $market['date'] ?? date('Y-m-d');

		// Log settlement start
		settle_log_start($market_name, $date, $settlement_type);

		// Build result data for bet evaluation
		$result_data = [
			'open_panna' => $market['open_panna'] ?? '',
			'open_ank' => $market['open_ank'] ?? '',
			'close_panna' => $market['close_panna'] ?? '',
			'close_ank' => $market['close_ank'] ?? '',
			'jodi' => $market['jodi'] ?? '',
		];

		// Load game rates
		$rates = get_game_rates();

		// Fetch pending bets
		$pending_bets = get_pending_bets($scraped_market_id, $date, $settlement_type);

		// Process each bet
		foreach ($pending_bets as $bet) {
			$game_type = $bet['game_type'] ?? '';
			$session_type = $bet['session_type'] ?? '';

			// For open settlement: defer jodi, half_sangam, full_sangam (they need both results)
			if ($settlement_type === 'open' && in_array($game_type, ['jodi', 'half_sangam', 'full_sangam'], true)) {
				// These bets are deferred — they'll be settled during close settlement
				continue;
			}

			$result['bets_processed']++;

			// Evaluate the bet
			$eval = evaluate_bet($bet, $result_data);

			if ($eval['win']) {
				// Determine rate for this game type
				$rate = $rates[$game_type] ?? 0;
				$win_amount = round((float) $bet['amount'] * $rate, 2);

				// Credit the winner
				$credit_ok = credit_winner(
					(int) $bet['user_id'],
					$win_amount,
					(int) $bet['id'],
					$market_name
				);

				if ($credit_ok) {
					$result['winners']++;
					$result['total_credited'] += $win_amount;
				} else {
					throw new Exception('Failed to credit winner: user_id=' . $bet['user_id'] . ', bet_id=' . $bet['id']);
				}
			} else {
				// Mark as loss: set win column to '0'
				$loss_stmt = mysqli_prepare($con, "UPDATE user_transaction SET win = '0' WHERE id = ? LIMIT 1");
				if (!$loss_stmt) {
					throw new Exception('Failed to prepare loss update: ' . mysqli_error($con));
				}
				$bet_id = (int) $bet['id'];
				mysqli_stmt_bind_param($loss_stmt, 'i', $bet_id);
				if (!mysqli_stmt_execute($loss_stmt)) {
					throw new Exception('Failed to mark bet as loss: bet_id=' . $bet['id']);
				}
			}
		}

		// Commit transaction
		mysqli_commit($con);
		$result['success'] = true;

		// Log settlement completion
		settle_log_complete($scraped_market_id, $market_name, $settlement_type, $date, $result);

	} catch (Exception $e) {
		// Rollback all changes on failure
		mysqli_rollback($con);
		$result['error'] = $e->getMessage();
		settle_log_error($scraped_market_id, $market_name ?? '', $settlement_type, $result['error']);
	}

	return $result;
}

/**
 * get_pending_bets - Fetch unsettled bets with row-level locking
 * 
 * Retrieves bets where win IS NULL or empty for the given market, date,
 * and session type. Uses SELECT ... FOR UPDATE to prevent concurrent settlement.
 * For close settlement, also fetches deferred open-session bets (jodi, half_sangam, full_sangam).
 * 
 * @param int $market_id  The scraped_markets.id (stored as game_id in user_transaction)
 * @param string $date  The bet date (Y-m-d)
 * @param string $session_type  'open' or 'close'
 * @return array  Array of bet records
 */
function get_pending_bets($market_id, $date, $session_type) {
	global $con;

	$market_id = (int) $market_id;
	$date = (string) $date;
	$session_type = (string) $session_type;
	$bets = [];

	if ($session_type === 'close') {
		// For close settlement: get close-session bets + deferred open-session bets (jodi, half_sangam, full_sangam)
		$sql = "SELECT * FROM user_transaction 
				WHERE game_id = ? 
				AND date = ? 
				AND type = 'bid' 
				AND starline = 0 
				AND (win IS NULL OR win = '') 
				AND (
					session_type = 'close' 
					OR (session_type = 'open' AND game_type IN ('jodi', 'half_sangam', 'full_sangam'))
				)
				FOR UPDATE";
	} else {
		// For open settlement: get open-session bets only
		$sql = "SELECT * FROM user_transaction 
				WHERE game_id = ? 
				AND date = ? 
				AND type = 'bid' 
				AND starline = 0 
				AND session_type = ? 
				AND (win IS NULL OR win = '') 
				FOR UPDATE";
	}

	$stmt = mysqli_prepare($con, $sql);
	if (!$stmt) {
		error_log('[Settlement] Failed to prepare pending bets query: ' . mysqli_error($con));
		return $bets;
	}

	if ($session_type === 'close') {
		mysqli_stmt_bind_param($stmt, 'is', $market_id, $date);
	} else {
		mysqli_stmt_bind_param($stmt, 'iss', $market_id, $date, $session_type);
	}

	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

	if ($result) {
		while ($row = mysqli_fetch_assoc($result)) {
			$bets[] = $row;
		}
	}

	return $bets;
}

/**
 * evaluate_bet - Determine if a bet wins based on game_type and digit comparison
 * 
 * Compares the bet digit against the declared result based on game type:
 * - single: compare against open_ank or close_ank
 * - single_patti/double_patti/triple_patti: compare against open_panna or close_panna
 * - jodi: compare against concatenation of open_ank + close_ank
 * - half_sangam: check against open_panna-close_ank OR open_ank-close_panna
 * - full_sangam: compare against open_panna-close_panna
 * 
 * @param array $bet  The bet record from user_transaction
 * @param array $result_data  ['open_panna', 'open_ank', 'close_panna', 'close_ank', 'jodi']
 * @return array ['win' => bool, 'matched_value' => string]
 */
function evaluate_bet($bet, $result_data) {
	$game_type = $bet['game_type'] ?? '';
	$session_type = $bet['session_type'] ?? '';
	$digit = (string) ($bet['digit'] ?? '');

	$open_panna = (string) ($result_data['open_panna'] ?? '');
	$open_ank = (string) ($result_data['open_ank'] ?? '');
	$close_panna = (string) ($result_data['close_panna'] ?? '');
	$close_ank = (string) ($result_data['close_ank'] ?? '');
	$jodi = (string) ($result_data['jodi'] ?? '');

	$eval_result = ['win' => false, 'matched_value' => ''];

	switch ($game_type) {
		case 'single':
			// Compare bet digit against ank value for the session
			$compare_value = ($session_type === 'close') ? $close_ank : $open_ank;
			if ($digit !== '' && $compare_value !== '' && $digit === $compare_value) {
				$eval_result['win'] = true;
				$eval_result['matched_value'] = $compare_value;
			}
			break;

		case 'single_patti':
		case 'double_patti':
		case 'triple_patti':
			// Compare bet digit against panna value for the session
			$compare_value = ($session_type === 'close') ? $close_panna : $open_panna;
			if ($digit !== '' && $compare_value !== '' && $digit === $compare_value) {
				$eval_result['win'] = true;
				$eval_result['matched_value'] = $compare_value;
			}
			break;

		case 'jodi':
			// Compare bet digit against jodi (open_ank + close_ank concatenation)
			$jodi_value = $jodi !== '' ? $jodi : $open_ank . $close_ank;
			if ($digit !== '' && $jodi_value !== '' && $digit === $jodi_value) {
				$eval_result['win'] = true;
				$eval_result['matched_value'] = $jodi_value;
			}
			break;

		case 'half_sangam':
			// Check bet digit against open_panna-close_ank OR open_ank-close_panna
			$pattern1 = $open_panna . '-' . $close_ank; // e.g. "428-5"
			$pattern2 = $open_ank . '-' . $close_panna; // e.g. "8-578"
			if ($digit !== '') {
				if (($pattern1 !== '-' && $digit === $pattern1) || ($pattern2 !== '-' && $digit === $pattern2)) {
					$eval_result['win'] = true;
					$eval_result['matched_value'] = $digit;
				}
			}
			break;

		case 'full_sangam':
			// Compare bet digit against open_panna-close_panna
			$sangam_value = $open_panna . '-' . $close_panna;
			if ($digit !== '' && $sangam_value !== '-' && $digit === $sangam_value) {
				$eval_result['win'] = true;
				$eval_result['matched_value'] = $sangam_value;
			}
			break;

		default:
			// Unknown game type - log warning, treat as loss
			error_log('[Settlement] Unknown game_type: ' . $game_type . ' for bet_id=' . ($bet['id'] ?? 'unknown'));
			break;
	}

	return $eval_result;
}

/**
 * get_game_rates - Load payout rates from game_rate table with fallback defaults
 * 
 * Queries the game_rate table for all rate values. If the database connection
 * fails, returns hardcoded fallback rates. If the DB is available but a rate
 * record is missing, logs it as a requirement violation error.
 * 
 * @return array  Associative array mapping game_type => rate multiplier
 */
function get_game_rates() {
	global $con;

	// Fallback defaults (used only if DB connection fails)
	$defaults = [
		'single' => 9,
		'jodi' => 95,
		'single_patti' => 140,
		'double_patti' => 280,
		'triple_patti' => 600,
		'half_sangam' => 1000,
		'full_sangam' => 10000,
	];

	// Map game_rate table IDs to game_type names
	$id_to_type = [
		1 => 'single',
		2 => 'jodi',
		3 => 'single_patti',
		4 => 'double_patti',
		5 => 'triple_patti',
		6 => 'half_sangam',
		7 => 'full_sangam',
	];

	// Attempt to load from database
	$sql = "SELECT id, rate FROM game_rate WHERE id BETWEEN 1 AND 7";
	$result = mysqli_query($con, $sql);

	if (!$result) {
		// DB connection/query failure - use fallback defaults
		error_log('[Settlement] Failed to query game_rate table, using fallback rates: ' . mysqli_error($con));
		return $defaults;
	}

	$rates = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$id = (int) $row['id'];
		if (isset($id_to_type[$id])) {
			$rates[$id_to_type[$id]] = (float) $row['rate'];
		}
	}

	// Check for missing rate records (requirement violation if DB is available)
	foreach ($id_to_type as $id => $type) {
		if (!isset($rates[$type])) {
			error_log('[Settlement] REQUIREMENT VIOLATION: Missing game_rate record for id=' . $id . ' (' . $type . '). Using fallback rate.');
			$rates[$type] = $defaults[$type];
		}
	}

	return $rates;
}

/**
 * credit_winner - Credit user balance and record win transaction atomically
 * 
 * Acquires a row-level lock on the user balance, credits the win amount,
 * inserts a win transaction record, and updates the original bet's win column.
 * 
 * @param int $user_id  The user to credit
 * @param float $amount  The win amount (bet_amount × rate)
 * @param int $bet_id  The original bet's user_transaction.id
 * @param string $market_name  Market name for the transaction record
 * @return bool  True on success, false on failure
 */
function credit_winner($user_id, $amount, $bet_id, $market_name) {
	global $con;

	$user_id = (int) $user_id;
	$amount = round((float) $amount, 2);
	$bet_id = (int) $bet_id;
	$market_name = (string) $market_name;
	$date = date('Y-m-d');
	$time = date('h:i:s A');

	// Acquire row-level lock on user balance
	$stmt = mysqli_prepare($con, "SELECT balance FROM users WHERE id = ? LIMIT 1 FOR UPDATE");
	if (!$stmt) {
		error_log('[Settlement] Failed to prepare user lock query: ' . mysqli_error($con));
		return false;
	}
	mysqli_stmt_bind_param($stmt, 'i', $user_id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$user = $result ? mysqli_fetch_assoc($result) : null;

	if (!$user) {
		error_log('[Settlement] User not found for crediting: user_id=' . $user_id);
		return false;
	}

	$current_balance = (float) $user['balance'];
	$new_balance = round($current_balance + $amount, 2);

	// Update user balance
	$update_stmt = mysqli_prepare($con, "UPDATE users SET balance = ? WHERE id = ? LIMIT 1");
	if (!$update_stmt) {
		error_log('[Settlement] Failed to prepare balance update: ' . mysqli_error($con));
		return false;
	}
	mysqli_stmt_bind_param($update_stmt, 'di', $new_balance, $user_id);
	if (!mysqli_stmt_execute($update_stmt)) {
		error_log('[Settlement] Failed to update balance: user_id=' . $user_id . ', error=' . mysqli_error($con));
		return false;
	}

	// Insert win transaction record
	$api_response = 'Auto result settlement';
	$win_type = 'win';
	$debit_credit = 'credit';
	$starline = 0;

	$insert_stmt = mysqli_prepare($con, 
		"INSERT INTO user_transaction (user_id, game_id, game_type, session_type, digit, date, time, amount, type, debit_credit, balance, win, api_response, starline) 
		 SELECT user_id, game_id, game_type, session_type, digit, ?, ?, ?, ?, ?, ?, ?, ?, ?
		 FROM user_transaction WHERE id = ? LIMIT 1"
	);
	if (!$insert_stmt) {
		error_log('[Settlement] Failed to prepare win transaction insert: ' . mysqli_error($con));
		return false;
	}
	mysqli_stmt_bind_param($insert_stmt, 'ssdssdssii',
		$date, $time, $amount, $win_type, $debit_credit, $new_balance, $amount, $api_response, $starline, $bet_id
	);
	if (!mysqli_stmt_execute($insert_stmt)) {
		error_log('[Settlement] Failed to insert win transaction: bet_id=' . $bet_id . ', error=' . mysqli_error($con));
		return false;
	}

	// Update original bet record: set win column to calculated win amount
	$win_value = (string) $amount;
	$bet_update = mysqli_prepare($con, "UPDATE user_transaction SET win = ? WHERE id = ? LIMIT 1");
	if (!$bet_update) {
		error_log('[Settlement] Failed to prepare bet win update: ' . mysqli_error($con));
		return false;
	}
	mysqli_stmt_bind_param($bet_update, 'si', $win_value, $bet_id);
	if (!mysqli_stmt_execute($bet_update)) {
		error_log('[Settlement] Failed to update bet win column: bet_id=' . $bet_id . ', error=' . mysqli_error($con));
		return false;
	}

	return true;
}

// ============================================================
// Settlement Logging Helpers
// ============================================================

/**
 * Log the start of a settlement operation
 */
function settle_log_start($market_name, $date, $settlement_type) {
	error_log('[Settlement] START: market=' . $market_name . ', date=' . $date . ', type=' . $settlement_type);
}

/**
 * Log settlement completion and record to settlement_log table
 */
function settle_log_complete($market_id, $market_name, $settlement_type, $date, $result) {
	global $con;

	error_log('[Settlement] COMPLETE: market=' . $market_name . ', date=' . $date . ', type=' . $settlement_type 
		. ', bets_processed=' . $result['bets_processed'] 
		. ', winners=' . $result['winners'] 
		. ', total_credited=' . $result['total_credited']);

	// Record to settlement_log table
	$stmt = mysqli_prepare($con, 
		"INSERT INTO settlement_log (market_id, market_name, settlement_type, date, bets_processed, winners_found, total_credited, status, error_message) 
		 VALUES (?, ?, ?, ?, ?, ?, ?, 'success', '')"
	);
	if ($stmt) {
		$market_id = (int) $market_id;
		$bets_processed = (int) $result['bets_processed'];
		$winners = (int) $result['winners'];
		$total_credited = (float) $result['total_credited'];
		mysqli_stmt_bind_param($stmt, 'isssiid', $market_id, $market_name, $settlement_type, $date, $bets_processed, $winners, $total_credited);
		mysqli_stmt_execute($stmt);
	}
}

/**
 * Log settlement error and record to settlement_log table
 */
function settle_log_error($market_id, $market_name, $settlement_type, $error_message) {
	global $con;

	$date = date('Y-m-d');
	error_log('[Settlement] ERROR: market=' . $market_name . ', date=' . $date . ', type=' . $settlement_type . ', error=' . $error_message);

	// Record to settlement_log table
	$stmt = mysqli_prepare($con, 
		"INSERT INTO settlement_log (market_id, market_name, settlement_type, date, bets_processed, winners_found, total_credited, status, error_message) 
		 VALUES (?, ?, ?, ?, 0, 0, 0, 'failed', ?)"
	);
	if ($stmt) {
		$market_id = (int) $market_id;
		mysqli_stmt_bind_param($stmt, 'issss', $market_id, $market_name, $settlement_type, $date, $error_message);
		mysqli_stmt_execute($stmt);
	}
}

// ============================================================
// Deferral and Cascade Handlers
// ============================================================

/**
 * handle_jodi_deferral - During open settlement, jodi bets are deferred
 * 
 * Jodi bets require both open_ank and close_ank to evaluate.
 * During open settlement, these bets are simply skipped (left with win=NULL).
 * They will be picked up during close settlement via get_pending_bets().
 * 
 * @param array $bet  The jodi bet record
 * @return bool  True if deferral succeeded (bet left pending)
 */
function handle_jodi_deferral($bet) {
	// Jodi bets are deferred by simply not processing them during open settlement.
	// The get_pending_bets() function for close settlement will pick them up
	// because they still have win=NULL and game_type='jodi' with session_type='open'.
	error_log('[Settlement] Deferred jodi bet: bet_id=' . ($bet['id'] ?? 'unknown') . ', digit=' . ($bet['digit'] ?? ''));
	return true;
}

/**
 * handle_sangam_deferral - During open settlement, sangam bets are deferred
 * 
 * Half_sangam and full_sangam bets require both open and close results.
 * During open settlement, these bets are simply skipped (left with win=NULL).
 * They will be processed immediately during close settlement.
 * 
 * @param array $bet  The sangam bet record
 * @return bool  True if deferral succeeded (bet left pending)
 */
function handle_sangam_deferral($bet) {
	// Sangam bets are deferred by simply not processing them during open settlement.
	// When close result arrives, get_pending_bets() fetches them and they are evaluated immediately.
	error_log('[Settlement] Deferred sangam bet: bet_id=' . ($bet['id'] ?? 'unknown') . ', type=' . ($bet['game_type'] ?? '') . ', digit=' . ($bet['digit'] ?? ''));
	return true;
}

/**
 * evaluate_close_bet_cascade - Multi-step evaluation for close settlement
 * 
 * During close settlement, bets are evaluated in cascade order:
 * 1. Direct close result match (close_ank for single, close_panna for patti) → WIN
 * 2. Sangam match (half_sangam or full_sangam pattern) → WIN
 * 3. Jodi match (open_ank + close_ank) → WIN (preserves jodi winnings)
 * 4. None match → LOSS
 * 
 * This ensures jodi winnings are preserved even when a bet doesn't match
 * close/sangam patterns.
 * 
 * @param array $bet  The bet record
 * @param array $result_data  Market result data
 * @param array $rates  Game rates
 * @return array ['action' => 'win'|'loss', 'win_amount' => float, 'game_type_used' => string]
 */
function evaluate_close_bet_cascade($bet, $result_data, $rates) {
	// Standard evaluation first
	$eval = evaluate_bet($bet, $result_data);
	$game_type = $bet['game_type'] ?? '';
	$amount = (float) ($bet['amount'] ?? 0);

	if ($eval['win']) {
		$rate = $rates[$game_type] ?? 0;
		return [
			'action' => 'win',
			'win_amount' => round($amount * $rate, 2),
			'game_type_used' => $game_type,
		];
	}

	// For deferred jodi bets from open session - check jodi match
	if ($game_type === 'jodi') {
		$jodi_eval = evaluate_bet($bet, $result_data);
		if ($jodi_eval['win']) {
			$rate = $rates['jodi'] ?? 95;
			return [
				'action' => 'win',
				'win_amount' => round($amount * $rate, 2),
				'game_type_used' => 'jodi',
			];
		}
	}

	return [
		'action' => 'loss',
		'win_amount' => 0,
		'game_type_used' => $game_type,
	];
}

/**
 * escalate_error - Higher-level error handler for critical failures
 * 
 * Invoked when rollback OR error logging fails. Writes to emergency log file
 * and marks market as requiring manual intervention.
 * 
 * @param int $market_id  The scraped market ID
 * @param string $market_name  Market name
 * @param string $error  Error description
 */
function escalate_error($market_id, $market_name, $error) {
	// Write to emergency log file (filesystem-level, independent of DB)
	$emergency_log = '/tmp/settlement_emergency.log';
	$timestamp = date('Y-m-d H:i:s');
	$entry = "[{$timestamp}] CRITICAL: market_id={$market_id}, market={$market_name}, error={$error}\n";
	
	@file_put_contents($emergency_log, $entry, FILE_APPEND | LOCK_EX);
	
	// Also write to PHP error log as last resort
	error_log('[Settlement] CRITICAL ESCALATION: market_id=' . $market_id . ', market=' . $market_name . ', error=' . $error);
}

?>
