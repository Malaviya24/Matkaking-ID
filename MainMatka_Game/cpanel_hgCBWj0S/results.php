<?php
require_once __DIR__ . '/includes/layout.php';
admin_require_login();

function result_ank($panna)
{
    $sum = 0;
    $panna = (string) $panna;
    for ($i = 0; $i < strlen($panna); $i++) {
        $sum += (int) $panna[$i];
    }

    return (string) ($sum % 10);
}

function result_multiplier_value($rate, $fallback)
{
    $rate = (float) $rate;
    $fallback = (float) $fallback;
    if ($rate <= 0) {
        return $fallback;
    }

    return $rate > ($fallback * 2) ? $rate / 10 : $rate;
}

function result_multiplier($gameType, $starline)
{
    if ((int) $starline === 1) {
        $rates = [
            'single' => [get_StarlineSingle(), 10],
            'single_patti' => [get_StarlineSinglePatti(), 160],
            'double_patti' => [get_StarlineDoublePatti(), 300],
            'triple_patti' => [get_StarlineTriplePatti(), 1000],
        ];
    } else {
        $rates = [
            'single' => [get_RateSingle(), 9],
            'jodi' => [get_RateJodi(), 95],
            'single_patti' => [get_RateSinglePatti(), 140],
            'double_patti' => [get_RateDoublePatti(), 280],
            'triple_patti' => [get_RateTriplePatti(), 600],
            'half_sangam' => [get_RateHalfSangam(), 1000],
            'full_sangam' => [get_RateFullSangam(), 10000],
        ];
    }

    if (!isset($rates[$gameType])) {
        return 0;
    }

    return result_multiplier_value($rates[$gameType][0], $rates[$gameType][1]);
}

function result_existing_digit($table, $gameId, $date)
{
    $table = $table === 'starline_result' ? 'starline_result' : 'result';
    $gameId = (int) $gameId;
    $date = mysqli_real_escape_string($GLOBALS['con'], $date);
    $row = admin_fetch_one("SELECT digit FROM {$table} WHERE game_id = {$gameId} AND date = '{$date}' AND game_type = 'single_patti' ORDER BY id DESC LIMIT 1");
    return $row ? (string) $row['digit'] : '';
}

function result_save_digit($table, $gameId, $digit, $date)
{
    global $con;
    $table = $table === 'starline_result' ? 'starline_result' : 'result';
    $gameId = (int) $gameId;
    $dateSafe = mysqli_real_escape_string($con, $date);
    $digitSafe = mysqli_real_escape_string($con, $digit);

    mysqli_query($con, "DELETE FROM {$table} WHERE game_id = {$gameId} AND date = '{$dateSafe}' AND game_type = 'single_patti'");

    return admin_insert_row($table, [
        'game_id' => $gameId,
        'game_type' => 'single_patti',
        'digit' => $digitSafe,
        'date' => $date,
        'time' => date('h:i:s A'),
        'status' => 1,
    ]);
}

function result_lock_game($table, $gameId)
{
    $tableName = $table === 'starline_result' ? 'starline' : 'games';
    $gameId = (int) $gameId;
    return admin_fetch_one("SELECT id FROM {$tableName} WHERE id = {$gameId} LIMIT 1 FOR UPDATE");
}

function result_bid_wins(array $bid, array $ctx)
{
    $type = (string) $bid['game_type'];
    $digit = (string) $bid['digit'];

    if (!empty($ctx['starline'])) {
        if ($type === 'single') {
            return $digit === $ctx['ank'];
        }
        return in_array($type, ['single_patti', 'double_patti', 'triple_patti'], true) && $digit === $ctx['panna'];
    }

    if (in_array($type, ['single', 'single_patti', 'double_patti', 'triple_patti'], true)) {
        $side = (int) $bid['game_id'] === (int) $ctx['close_game_id'] ? 'close' : 'open';
        if ($type === 'single') {
            return $digit === $ctx[$side . '_ank'];
        }

        return $digit === $ctx[$side . '_panna'];
    }

    if (empty($ctx['open_panna']) || empty($ctx['close_panna'])) {
        return false;
    }

    if ($type === 'jodi') {
        return $digit === $ctx['open_ank'] . $ctx['close_ank'];
    }

    if ($type === 'half_sangam') {
        $parts = explode('-', $digit);
        if (count($parts) !== 2) {
            return false;
        }

        return ($parts[0] === $ctx['open_ank'] && $parts[1] === $ctx['close_panna'])
            || ($parts[0] === $ctx['open_panna'] && $parts[1] === $ctx['close_ank']);
    }

    if ($type === 'full_sangam') {
        return $digit === $ctx['open_panna'] . '-' . $ctx['close_panna'];
    }

    return false;
}

function result_credit_winner(array $bid, $winAmount, $title, $starline, $date)
{
    global $con;
    $userId = (int) $bid['user_id'];
    $user = admin_fetch_one("SELECT balance FROM users WHERE id = {$userId} LIMIT 1 FOR UPDATE");
    if (!$user) {
        return false;
    }

    $newBalance = round((float) $user['balance'] + (float) $winAmount, 2);
    $updatedUser = mysqli_query($con, "UPDATE users SET balance = '{$newBalance}' WHERE id = {$userId} LIMIT 1");
    if (!$updatedUser) {
        return false;
    }

    return admin_insert_row('user_transaction', [
        'user_id' => $userId,
        'game_id' => (int) $bid['game_id'],
        'game_type' => $bid['game_type'],
        'digit' => $bid['digit'],
        'date' => $date,
        'time' => date('h:i:s A'),
        'amount' => $winAmount,
        'type' => 'win',
        'debit_credit' => 'credit',
        'balance' => $newBalance,
        'status' => 2,
        'title' => $title,
        'api_response' => 'Auto result settlement',
        'starline' => (int) $starline,
    ]);
}

function result_settle_bids(array $ctx, $date, $starline)
{
    global $con;
    $dateSafe = mysqli_real_escape_string($con, $date);
    $gameIds = [(int) $ctx['game_id']];
    $types = ["'single'", "'single_patti'", "'double_patti'", "'triple_patti'"];

    if (empty($starline) && !empty($ctx['open_panna']) && !empty($ctx['close_panna'])) {
        $gameIds[] = (int) $ctx['open_game_id'];
        $types[] = "'jodi'";
        $types[] = "'half_sangam'";
        $types[] = "'full_sangam'";
    }

    $gameIds = array_values(array_unique(array_filter($gameIds)));
    if (!$gameIds) {
        return ['processed' => 0, 'winners' => 0, 'credited' => 0];
    }

    $gameSql = implode(',', $gameIds);
    $typeSql = implode(',', array_unique($types));
    $starlineSql = (int) $starline;

    $rows = admin_fetch_all("
        SELECT id, user_id, game_id, game_type, digit, amount
        FROM user_transaction
        WHERE date = '{$dateSafe}'
          AND type = 'bid'
          AND COALESCE(starline, 0) = {$starlineSql}
          AND game_id IN ({$gameSql})
          AND game_type IN ({$typeSql})
          AND (win IS NULL OR win = '' OR win = 'NULL')
        ORDER BY id ASC
        FOR UPDATE
    ");

    $summary = ['processed' => 0, 'winners' => 0, 'credited' => 0];
    foreach ($rows as $bid) {
        $summary['processed']++;
        $isWinner = result_bid_wins($bid, $ctx);
        if (!$isWinner) {
            mysqli_query($con, "UPDATE user_transaction SET win = '0' WHERE id = " . (int) $bid['id'] . " LIMIT 1");
            continue;
        }

        $multiplier = result_multiplier($bid['game_type'], $starline);
        $winAmount = round((float) $bid['amount'] * $multiplier, 2);
        if ($winAmount <= 0) {
            mysqli_query($con, "UPDATE user_transaction SET win = '0' WHERE id = " . (int) $bid['id'] . " LIMIT 1");
            continue;
        }

        if (!result_credit_winner($bid, $winAmount, $starline ? 'STARLINE RESULT WIN' : 'MAIN MARKET RESULT WIN', $starline, $date)) {
            return false;
        }

        mysqli_query($con, "UPDATE user_transaction SET win = '{$winAmount}' WHERE id = " . (int) $bid['id'] . " LIMIT 1");
        $summary['winners']++;
        $summary['credited'] += $winAmount;
    }

    return $summary;
}

function result_settle_main($gameId, $digit, $date)
{
    $gameId = (int) $gameId;
    $game = admin_fetch_one("
        SELECT g.id, g.type, g.parent_game, pg.child_open_id, pg.child_close_id
        FROM games g
        INNER JOIN parent_games pg ON pg.id = g.parent_game
        WHERE g.id = {$gameId}
        LIMIT 1
    ");
    if (!$game) {
        return false;
    }

    $openGameId = (int) $game['child_open_id'];
    $closeGameId = (int) $game['child_close_id'];
    $openPanna = result_existing_digit('result', $openGameId, $date);
    $closePanna = result_existing_digit('result', $closeGameId, $date);

    $ctx = [
        'starline' => 0,
        'game_id' => $gameId,
        'open_game_id' => $openGameId,
        'close_game_id' => $closeGameId,
        'open_panna' => $openPanna,
        'close_panna' => $closePanna,
        'open_ank' => $openPanna !== '' ? result_ank($openPanna) : '',
        'close_ank' => $closePanna !== '' ? result_ank($closePanna) : '',
    ];

    return result_settle_bids($ctx, $date, 0);
}

function result_settle_starline($gameId, $digit, $date)
{
    $ctx = [
        'starline' => 1,
        'game_id' => (int) $gameId,
        'panna' => (string) $digit,
        'ank' => result_ank($digit),
    ];

    return result_settle_bids($ctx, $date, 1);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_validate_csrf()) {
    $mode = $_POST['mode'] ?? '';
    $gameId = (int) ($_POST['game_id'] ?? 0);
    $digit = preg_replace('/\D+/', '', (string) ($_POST['digit'] ?? ''));
    $date = admin_date_param('unused', date('Y-m-d'));
    $date = preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) ($_POST['date'] ?? '')) ? $_POST['date'] : date('Y-m-d');

    if ($gameId <= 0 || !preg_match('/^\d{3}$/', $digit)) {
        admin_flash('error', 'Select a game and enter a 3 digit panna result.');
        admin_redirect('results.php');
    }

    $table = $mode === 'starline' ? 'starline_result' : 'result';
    mysqli_begin_transaction($con);

    if (!result_lock_game($table, $gameId)) {
        mysqli_rollback($con);
        admin_flash('error', 'Selected game was not found.');
        admin_redirect('results.php');
    }

    $existingDigit = result_existing_digit($table, $gameId, $date);
    if ($existingDigit !== '' && $existingDigit !== $digit) {
        mysqli_rollback($con);
        admin_flash('error', 'A different result already exists for this game/date. Changing settled results is blocked to prevent wallet mismatch.');
        admin_redirect('results.php');
    }

    if (!result_save_digit($table, $gameId, $digit, $date)) {
        mysqli_rollback($con);
        admin_flash('error', 'Result was not saved.');
        admin_redirect('results.php');
    }

    $summary = $mode === 'starline'
        ? result_settle_starline($gameId, $digit, $date)
        : result_settle_main($gameId, $digit, $date);

    if ($summary === false) {
        mysqli_rollback($con);
        admin_flash('error', 'Result saved failed while crediting winners. No changes were committed.');
        admin_redirect('results.php');
    }

    mysqli_commit($con);
    admin_flash('success', 'Result saved. Processed ' . $summary['processed'] . ' bids, winners ' . $summary['winners'] . ', credited Rs ' . admin_money($summary['credited']) . '.');
    admin_redirect('results.php');
}

$mainGames = admin_fetch_all("
    SELECT g.id, CONCAT(pg.name, ' ', UPPER(g.type)) AS name
    FROM games g
    INNER JOIN parent_games pg ON pg.id = g.parent_game
    WHERE COALESCE(g.status, 1) = 1 AND COALESCE(pg.status, 1) = 1
    ORDER BY pg.order_of_display, pg.name, g.type
");
$starlineGames = admin_fetch_all("SELECT id, name, time FROM starline WHERE COALESCE(status, 1) = 1 ORDER BY time, id");
$recentMain = admin_fetch_all("
    SELECT r.id, r.game_id, r.digit, r.date, r.time, g.name
    FROM result r
    LEFT JOIN games g ON g.id = r.game_id
    ORDER BY r.id DESC
    LIMIT 20
");
$recentStarline = admin_fetch_all("
    SELECT r.id, r.game_id, r.digit, r.date, r.time, s.name
    FROM starline_result r
    LEFT JOIN starline s ON s.id = r.game_id
    ORDER BY r.id DESC
    LIMIT 20
");

admin_render_header('Results');
?>
<div class="content-grid">
    <section class="panel">
        <h2>Main Market Result</h2>
        <form method="post" class="admin-stack-form">
            <input type="hidden" name="csrf_token" value="<?php echo e(admin_csrf_token()); ?>">
            <input type="hidden" name="mode" value="main">
            <label class="field">
                <span>Market</span>
                <select name="game_id" required>
                    <option value="">Select market</option>
                    <?php foreach ($mainGames as $game) { ?>
                        <option value="<?php echo (int) $game['id']; ?>"><?php echo e($game['name']); ?></option>
                    <?php } ?>
                </select>
            </label>
            <label class="field">
                <span>Date</span>
                <input type="date" name="date" value="<?php echo e(date('Y-m-d')); ?>" required>
            </label>
            <label class="field">
                <span>Panna Result</span>
                <input type="text" name="digit" inputmode="numeric" minlength="3" maxlength="3" pattern="\d{3}" required>
            </label>
            <button class="button primary" type="submit">Save & Settle Main</button>
        </form>
    </section>

    <section class="panel">
        <h2>Starline Result</h2>
        <form method="post" class="admin-stack-form">
            <input type="hidden" name="csrf_token" value="<?php echo e(admin_csrf_token()); ?>">
            <input type="hidden" name="mode" value="starline">
            <label class="field">
                <span>Slot</span>
                <select name="game_id" required>
                    <option value="">Select slot</option>
                    <?php foreach ($starlineGames as $game) { ?>
                        <option value="<?php echo (int) $game['id']; ?>"><?php echo e($game['name'] . ' ' . $game['time']); ?></option>
                    <?php } ?>
                </select>
            </label>
            <label class="field">
                <span>Date</span>
                <input type="date" name="date" value="<?php echo e(date('Y-m-d')); ?>" required>
            </label>
            <label class="field">
                <span>Panna Result</span>
                <input type="text" name="digit" inputmode="numeric" minlength="3" maxlength="3" pattern="\d{3}" required>
            </label>
            <button class="button primary" type="submit">Save & Settle Starline</button>
        </form>
    </section>
</div>

<div class="content-grid">
    <section class="panel">
        <h2>Recent Main Results</h2>
        <?php if ($recentMain) { ?>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>ID</th><th>Game</th><th>Result</th><th>Ank</th><th>Date</th></tr></thead>
                    <tbody>
                        <?php foreach ($recentMain as $row) { ?>
                            <tr>
                                <td>#<?php echo (int) $row['id']; ?></td>
                                <td><?php echo e($row['name'] ?: ('Game #' . $row['game_id'])); ?></td>
                                <td><strong><?php echo e($row['digit']); ?></strong></td>
                                <td><?php echo e(result_ank($row['digit'])); ?></td>
                                <td><?php echo e($row['date']); ?> <?php echo e($row['time']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { admin_empty_state('No main results found.'); } ?>
    </section>

    <section class="panel">
        <h2>Recent Starline Results</h2>
        <?php if ($recentStarline) { ?>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>ID</th><th>Slot</th><th>Result</th><th>Ank</th><th>Date</th></tr></thead>
                    <tbody>
                        <?php foreach ($recentStarline as $row) { ?>
                            <tr>
                                <td>#<?php echo (int) $row['id']; ?></td>
                                <td><?php echo e($row['name'] ?: ('Slot #' . $row['game_id'])); ?></td>
                                <td><strong><?php echo e($row['digit']); ?></strong></td>
                                <td><?php echo e(result_ank($row['digit'])); ?></td>
                                <td><?php echo e($row['date']); ?> <?php echo e($row['time']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { admin_empty_state('No starline results found.'); } ?>
    </section>
</div>
<?php admin_render_footer(); ?>
