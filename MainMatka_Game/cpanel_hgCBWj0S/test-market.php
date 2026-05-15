<?php
require_once __DIR__ . '/includes/layout.php';
admin_require_login();

function tm_columns($table)
{
    global $con;
    $rows = [];
    $result = mysqli_query($con, "SHOW COLUMNS FROM `{$table}`");
    if (!$result) {
        return $rows;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function tm_default_value(array $column)
{
    $type = strtolower($column['Type'] ?? '');
    if ($column['Default'] !== null) {
        return $column['Default'];
    }
    if (($column['Null'] ?? '') === 'YES') {
        return null;
    }
    if (strpos($type, 'int') !== false || strpos($type, 'decimal') !== false || strpos($type, 'float') !== false || strpos($type, 'double') !== false) {
        return 0;
    }
    if (strpos($type, 'datetime') !== false || strpos($type, 'timestamp') !== false) {
        return date('Y-m-d H:i:s');
    }
    if (strpos($type, 'date') !== false) {
        return date('Y-m-d');
    }
    if (strpos($type, 'time') !== false) {
        return date('H:i:s');
    }

    return '';
}

function tm_insert_dynamic($table, array $values)
{
    global $con;
    $columns = tm_columns($table);
    $insertColumns = [];
    $insertValues = [];

    foreach ($columns as $column) {
        $field = $column['Field'];
        if (stripos($column['Extra'] ?? '', 'auto_increment') !== false) {
            continue;
        }

        $insertColumns[] = $field;
        $insertValues[] = array_key_exists($field, $values) ? $values[$field] : tm_default_value($column);
    }

    if (!$insertColumns) {
        return 0;
    }

    $columnSql = '`' . implode('`,`', $insertColumns) . '`';
    $placeholders = implode(',', array_fill(0, count($insertColumns), '?'));
    $stmt = mysqli_prepare($con, "INSERT INTO `{$table}` ({$columnSql}) VALUES ({$placeholders})");
    if (!$stmt) {
        return 0;
    }

    $types = str_repeat('s', count($insertValues));
    $refs = [$types];
    foreach ($insertValues as $key => $value) {
        $insertValues[$key] = $value === null ? null : (string) $value;
        $refs[] = &$insertValues[$key];
    }
    call_user_func_array([$stmt, 'bind_param'], $refs);

    if (!mysqli_stmt_execute($stmt)) {
        return 0;
    }

    return mysqli_insert_id($con);
}

function tm_update_if_column($table, $column, $value, $id)
{
    global $con;
    if (!admin_column_exists($table, $column)) {
        return true;
    }

    $safeValue = mysqli_real_escape_string($con, (string) $value);
    return mysqli_query($con, "UPDATE `{$table}` SET `{$column}` = '{$safeValue}' WHERE id = " . (int) $id . " LIMIT 1");
}

function tm_test_market()
{
    return admin_fetch_one("SELECT * FROM parent_games WHERE name = 'TEMP TEST MARKET' ORDER BY id DESC LIMIT 1");
}

function tm_child_game($parentId, $type)
{
    global $con;
    $parentId = (int) $parentId;
    $type = mysqli_real_escape_string($con, $type);
    return admin_fetch_one("SELECT * FROM games WHERE parent_game = {$parentId} AND type = '{$type}' ORDER BY id ASC LIMIT 1");
}

function tm_panna_ank($panna)
{
    $sum = 0;
    for ($i = 0; $i < strlen($panna); $i++) {
        $sum += (int) $panna[$i];
    }

    return (string) ($sum % 10);
}

function tm_rate_multiplier($gameType)
{
    if ($gameType === 'single') {
        $rate = (float) get_RateSingle();
        return tm_normalized_rate_multiplier($rate, 9);
    }

    if ($gameType === 'triple_patti') {
        $rate = (float) get_RateTriplePatti();
        return tm_normalized_rate_multiplier($rate, 600);
    }

    return 0;
}

function tm_normalized_rate_multiplier($rate, $fallbackMultiplier)
{
    $rate = (float) $rate;
    $fallbackMultiplier = (float) $fallbackMultiplier;

    if ($rate <= 0) {
        return $fallbackMultiplier;
    }

    return $rate > ($fallbackMultiplier * 2) ? $rate / 10 : $rate;
}

function tm_upsert_result($gameId, $digit)
{
    global $con;
    $gameId = (int) $gameId;
    $date = date('Y-m-d');
    $safeDigit = mysqli_real_escape_string($con, $digit);

    mysqli_query($con, "DELETE FROM result WHERE game_id = {$gameId} AND date = '{$date}' AND game_type = 'single_patti'");

    return tm_insert_dynamic('result', [
        'game_id' => $gameId,
        'game_type' => 'single_patti',
        'digit' => $safeDigit,
        'date' => $date,
        'time' => date('h:i:s A'),
        'status' => 1,
    ]);
}

function tm_process_result_wins($gameId, $digit)
{
    global $con;
    $gameId = (int) $gameId;
    $date = date('Y-m-d');
    $ank = tm_panna_ank($digit);
    $safeDigit = mysqli_real_escape_string($con, $digit);
    $safeAnk = mysqli_real_escape_string($con, $ank);
    $processed = 0;
    $winners = 0;
    $credited = 0;

    $result = mysqli_query($con, "
        SELECT id, user_id, game_type, digit, amount
        FROM user_transaction
        WHERE game_id = {$gameId}
          AND date = '{$date}'
          AND type = 'bid'
          AND game_type IN ('single', 'triple_patti')
          AND (win IS NULL OR win = '' OR win = 'NULL')
        ORDER BY id ASC
    ");

    if (!$result) {
        return ['processed' => 0, 'winners' => 0, 'credited' => 0];
    }

    while ($bid = mysqli_fetch_assoc($result)) {
        $processed++;
        $isWinner = false;
        if ($bid['game_type'] === 'single' && (string) $bid['digit'] === $safeAnk) {
            $isWinner = true;
        }
        if ($bid['game_type'] === 'triple_patti' && (string) $bid['digit'] === $safeDigit) {
            $isWinner = true;
        }

        if (!$isWinner) {
            mysqli_query($con, "UPDATE user_transaction SET win = '0' WHERE id = " . (int) $bid['id'] . " LIMIT 1");
            continue;
        }

        $multiplier = tm_rate_multiplier($bid['game_type']);
        $winAmount = round((float) $bid['amount'] * $multiplier, 2);
        $userId = (int) $bid['user_id'];
        $user = admin_fetch_one("SELECT balance FROM users WHERE id = {$userId} LIMIT 1");
        $oldBalance = $user ? (float) $user['balance'] : 0;
        $newBalance = $oldBalance + $winAmount;

        mysqli_query($con, "UPDATE users SET balance = '{$newBalance}' WHERE id = {$userId} LIMIT 1");
        mysqli_query($con, "UPDATE user_transaction SET win = '{$winAmount}' WHERE id = " . (int) $bid['id'] . " LIMIT 1");
        admin_insert_row('user_transaction', [
            'user_id' => $userId,
            'game_id' => $gameId,
            'game_type' => $bid['game_type'],
            'digit' => $bid['digit'],
            'date' => $date,
            'time' => date('h:i:s A'),
            'amount' => $winAmount,
            'type' => 'win',
            'debit_credit' => 'credit',
            'balance' => $newBalance,
            'status' => 2,
            'title' => 'TEMP TEST MARKET RESULT ' . $digit,
            'api_response' => 'Auto test result',
            'starline' => 0,
        ]);

        $winners++;
        $credited += $winAmount;
    }

    return ['processed' => $processed, 'winners' => $winners, 'credited' => $credited];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_validate_csrf()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'create_test_market') {
        $openTime = '00:00:00';
        $closeTime = '23:59:59';
        $resultTime = date('H:i:s');
        $openDays = 'mon,tue,wed,thu,fri,sat,sun';

        $existing = tm_test_market();
        if ($existing) {
            $existingId = (int) $existing['id'];
            if (admin_column_exists('parent_games', 'status')) {
                mysqli_query($con, "UPDATE parent_games SET status = 1 WHERE id = {$existingId} LIMIT 1");
            }
            mysqli_query($con, "UPDATE parent_games SET open_time = '{$openTime}', close_time = '{$closeTime}', result_open_time = '{$resultTime}', result_close_time = '{$resultTime}', open_days = '{$openDays}' WHERE id = {$existingId} LIMIT 1");
            mysqli_query($con, "UPDATE games SET lottery_time = '{$closeTime}', result_time = '{$resultTime}', open_days = '{$openDays}' WHERE parent_game = {$existingId}");
            if (admin_column_exists('games', 'status')) {
                mysqli_query($con, "UPDATE games SET status = 1 WHERE parent_game = {$existingId}");
            }
            admin_flash('success', 'TEMP TEST MARKET already exists and is active.');
            admin_redirect('test-market.php');
        }

        mysqli_begin_transaction($con);

        $parentId = tm_insert_dynamic('parent_games', [
            'name' => 'TEMP TEST MARKET',
            'open_time' => $openTime,
            'close_time' => $closeTime,
            'result_open_time' => $resultTime,
            'result_close_time' => $resultTime,
            'open_days' => $openDays,
            'status' => 1,
            'order_of_display' => 0,
        ]);

        if (!$parentId) {
            mysqli_rollback($con);
            admin_flash('error', 'Could not create parent test market.');
            admin_redirect('test-market.php');
        }

        $openId = tm_insert_dynamic('games', [
            'name' => 'TEMP TEST MARKET OPEN',
            'parent_game' => $parentId,
            'type' => 'open',
            'lottery_time' => $openTime,
            'result_time' => $resultTime,
            'open_days' => $openDays,
            'status' => 1,
            'order_of_display' => 0,
        ]);

        $closeId = tm_insert_dynamic('games', [
            'name' => 'TEMP TEST MARKET CLOSE',
            'parent_game' => $parentId,
            'type' => 'close',
            'lottery_time' => $closeTime,
            'result_time' => $resultTime,
            'open_days' => $openDays,
            'status' => 1,
            'order_of_display' => 0,
        ]);

        if (!$openId || !$closeId) {
            mysqli_rollback($con);
            admin_flash('error', 'Could not create child test games.');
            admin_redirect('test-market.php');
        }

        tm_update_if_column('parent_games', 'child_open_id', $openId, $parentId);
        tm_update_if_column('parent_games', 'child_close_id', $closeId, $parentId);

        mysqli_commit($con);
        admin_flash('success', 'TEMP TEST MARKET created and activated.');
        admin_redirect('test-market.php');
    }

    if ($action === 'disable_test_market') {
        $market = tm_test_market();
        if ($market) {
            $parentId = (int) $market['id'];
            if (admin_column_exists('parent_games', 'status')) {
                mysqli_query($con, "UPDATE parent_games SET status = 0 WHERE id = {$parentId} LIMIT 1");
            }
            if (admin_column_exists('games', 'status')) {
                mysqli_query($con, "UPDATE games SET status = 0 WHERE parent_game = {$parentId}");
            }
            admin_flash('success', 'TEMP TEST MARKET disabled.');
        } else {
            admin_flash('error', 'No TEMP TEST MARKET found.');
        }
        admin_redirect('test-market.php');
    }

    if ($action === 'set_open_555' || $action === 'set_close_555') {
        $market = tm_test_market();
        if (!$market) {
            admin_flash('error', 'Create TEMP TEST MARKET first.');
            admin_redirect('test-market.php');
        }

        $childType = $action === 'set_open_555' ? 'open' : 'close';
        $child = tm_child_game((int) $market['id'], $childType);
        if (!$child) {
            admin_flash('error', 'Test market child game was not found.');
            admin_redirect('test-market.php');
        }

        mysqli_begin_transaction($con);
        $resultId = tm_upsert_result((int) $child['id'], '555');
        if (!$resultId) {
            mysqli_rollback($con);
            admin_flash('error', 'Could not save result 555.');
            admin_redirect('test-market.php');
        }

        $summary = tm_process_result_wins((int) $child['id'], '555');
        mysqli_commit($con);
        admin_flash('success', strtoupper($childType) . ' result 555 saved. Processed ' . $summary['processed'] . ' bids, winners ' . $summary['winners'] . ', credited Rs ' . admin_money($summary['credited']) . '.');
        admin_redirect('test-market.php');
    }
}

$market = tm_test_market();
$children = $market ? admin_fetch_all("SELECT * FROM games WHERE parent_game = " . (int) $market['id'] . " ORDER BY id ASC") : [];
$today = date('Y-m-d');
$todayResults = $market ? admin_fetch_all("
    SELECT r.game_id, g.name AS game_name, r.digit, r.date, r.time
    FROM result r
    INNER JOIN games g ON g.id = r.game_id
    WHERE g.parent_game = " . (int) $market['id'] . "
      AND r.date = '{$today}'
      AND r.game_type = 'single_patti'
    ORDER BY r.id DESC
    LIMIT 10
") : [];
$todayBids = $market ? admin_fetch_all("
    SELECT ut.id, ut.game_type, ut.digit, ut.amount, ut.win, ut.date, ut.time, u.username, u.mobile, g.name AS game_name
    FROM user_transaction ut
    INNER JOIN games g ON g.id = ut.game_id
    LEFT JOIN users u ON u.id = ut.user_id
    WHERE g.parent_game = " . (int) $market['id'] . "
      AND ut.date = '{$today}'
      AND ut.type = 'bid'
    ORDER BY ut.id DESC
    LIMIT 20
") : [];

admin_render_header('Test Market');
?>
<section class="stats-grid">
    <?php admin_stat_card('Market', $market ? 'Created' : 'Not created', $market ? 'ID #' . (int) $market['id'] : 'Use button below'); ?>
    <?php admin_stat_card('Status', $market && (string) ($market['status'] ?? '1') === '1' ? 'Active' : 'Inactive'); ?>
    <?php admin_stat_card('Open Time', $market['open_time'] ?? '00:00:00'); ?>
    <?php admin_stat_card('Child Games', count($children)); ?>
</section>

<div class="content-grid">
    <section class="panel">
        <h2>Create Temporary Market</h2>
        <p class="muted">This adds TEMP TEST MARKET to the normal app market list with open and close child games. Use it to test bid, wallet deduction, history, admin bet reports, and user bet summaries.</p>
        <form method="post" class="actions">
            <input type="hidden" name="csrf_token" value="<?php echo e(admin_csrf_token()); ?>">
            <button class="button primary" type="submit" name="action" value="create_test_market">Create / Activate Test Market</button>
            <?php if ($market) { ?>
                <button class="button danger" type="submit" name="action" value="disable_test_market">Disable Test Market</button>
            <?php } ?>
            <a class="ghost-button" href="../index.php" target="_blank" rel="noopener">Open App Home</a>
        </form>
    </section>

    <section class="panel">
        <h2>Test Steps</h2>
        <div class="detail-list">
            <div><span>1</span><strong>Login with test user</strong></div>
            <div><span>2</span><strong>Add balance from admin user page</strong></div>
            <div><span>3</span><strong>Open TEMP TEST MARKET and place bid</strong></div>
            <div><span>4</span><strong>Check admin Bets and user history</strong></div>
        </div>
    </section>
</div>

<section class="panel">
    <h2>Result Test</h2>
    <p class="muted">Use this after placing a test bid. Result 555 gives ank 5, so single ank 5 and triple patti 555 bids can win. The action saves the result and credits winning users once for pending bids.</p>
    <form method="post" class="actions">
        <input type="hidden" name="csrf_token" value="<?php echo e(admin_csrf_token()); ?>">
        <button class="button success" type="submit" name="action" value="set_open_555">Set Open Result 555 + Process Wins</button>
        <button class="button success" type="submit" name="action" value="set_close_555">Set Close Result 555 + Process Wins</button>
    </form>
</section>

<section class="panel">
    <h2>Today Test Results</h2>
    <?php if ($todayResults) { ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Game</th>
                        <th>Result</th>
                        <th>Ank</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($todayResults as $row) { ?>
                        <tr>
                            <td><?php echo e($row['game_name']); ?></td>
                            <td><strong><?php echo e($row['digit']); ?></strong></td>
                            <td><?php echo e(tm_panna_ank($row['digit'])); ?></td>
                            <td><?php echo e($row['date']); ?> <?php echo e($row['time']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { admin_empty_state('No 555 test result saved for today yet.'); } ?>
</section>

<section class="panel">
    <h2>Today Test Bids</h2>
    <?php if ($todayBids) { ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Game</th>
                        <th>Type</th>
                        <th>Number</th>
                        <th>Amount</th>
                        <th>Result</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($todayBids as $row) { ?>
                        <tr>
                            <td>#<?php echo (int) $row['id']; ?></td>
                            <td><?php echo e(($row['username'] ?: '-') . ' ' . ($row['mobile'] ?: '')); ?></td>
                            <td><?php echo e($row['game_name']); ?></td>
                            <td><?php echo e($row['game_type']); ?></td>
                            <td><strong><?php echo e($row['digit']); ?></strong></td>
                            <td>Rs <?php echo admin_money($row['amount']); ?></td>
                            <td>
                                <?php if ($row['win'] === '' || $row['win'] === null || $row['win'] === 'NULL') { ?>
                                    <span class="badge">Pending</span>
                                <?php } elseif ((string) $row['win'] === '0') { ?>
                                    <span class="badge danger">Loss</span>
                                <?php } else { ?>
                                    <span class="badge success">Win <?php echo e($row['win']); ?></span>
                                <?php } ?>
                            </td>
                            <td><?php echo e($row['date']); ?> <?php echo e($row['time']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { admin_empty_state('No TEMP TEST MARKET bids for today yet.'); } ?>
</section>

<section class="panel">
    <h2>Current Test Market</h2>
    <?php if ($market) { ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Open</th>
                        <th>Close</th>
                        <th>Days</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#<?php echo (int) $market['id']; ?></td>
                        <td><?php echo e($market['name']); ?></td>
                        <td><?php echo e($market['open_time'] ?? '-'); ?></td>
                        <td><?php echo e($market['close_time'] ?? '-'); ?></td>
                        <td><?php echo e($market['open_days'] ?? '-'); ?></td>
                        <td><?php echo (string) ($market['status'] ?? '1') === '1' ? '<span class="badge success">Active</span>' : '<span class="badge danger">Inactive</span>'; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php } else { admin_empty_state('No temporary test market exists yet.'); } ?>
</section>

<section class="panel">
    <h2>Child Games</h2>
    <?php if ($children) { ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Lottery Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($children as $child) { ?>
                        <tr>
                            <td>#<?php echo (int) $child['id']; ?></td>
                            <td><?php echo e($child['name'] ?? '-'); ?></td>
                            <td><?php echo e($child['type'] ?? '-'); ?></td>
                            <td><?php echo e($child['lottery_time'] ?? '-'); ?></td>
                            <td><?php echo (string) ($child['status'] ?? '1') === '1' ? '<span class="badge success">Active</span>' : '<span class="badge danger">Inactive</span>'; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { admin_empty_state('Child games will appear after creating the test market.'); } ?>
</section>
<?php admin_render_footer(); ?>
