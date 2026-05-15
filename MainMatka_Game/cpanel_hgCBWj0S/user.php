<?php
require_once __DIR__ . '/includes/layout.php';
admin_require_login();

$userId = (int) ($_GET['id'] ?? 0);
if ($userId <= 0) {
    admin_redirect('users.php');
}

$user = admin_fetch_one("SELECT * FROM users WHERE id = {$userId} LIMIT 1");
if (!$user) {
    admin_flash('error', 'User account not found.');
    admin_redirect('users.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_validate_csrf()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'adjust_balance') {
        $direction = $_POST['direction'] === 'debit' ? 'debit' : 'credit';
        $amount = isset($_POST['amount']) && is_numeric($_POST['amount']) ? (float) $_POST['amount'] : 0;
        $note = trim(substr((string) ($_POST['note'] ?? ''), 0, 180));

        if ($amount <= 0) {
            admin_flash('error', 'Enter a valid amount.');
            admin_redirect('user.php?id=' . $userId);
        }

        mysqli_begin_transaction($con);
        $current = admin_fetch_one("SELECT id, balance FROM users WHERE id = {$userId} LIMIT 1 FOR UPDATE");
        if (!$current) {
            mysqli_rollback($con);
            admin_flash('error', 'User account not found.');
            admin_redirect('users.php');
        }

        $currentBalance = (float) $current['balance'];
        if ($direction === 'debit' && $amount > $currentBalance) {
            mysqli_rollback($con);
            admin_flash('error', 'Remove amount cannot be greater than current balance.');
            admin_redirect('user.php?id=' . $userId);
        }

        $newBalance = $direction === 'credit' ? $currentBalance + $amount : $currentBalance - $amount;
        $stmt = mysqli_prepare($con, 'UPDATE users SET balance = ? WHERE id = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 'di', $newBalance, $userId);
        $updated = mysqli_stmt_execute($stmt);
        $logged = $updated ? admin_record_wallet_transaction($userId, $amount, $direction, $newBalance, $note) : false;

        if ($updated && $logged) {
            mysqli_commit($con);
            admin_flash('success', $direction === 'credit' ? 'Balance added successfully.' : 'Balance removed successfully.');
        } else {
            mysqli_rollback($con);
            admin_flash('error', 'Balance was not updated. Please check transaction table columns.');
        }

        admin_redirect('user.php?id=' . $userId);
    }

    if ($action === 'toggle_status') {
        $newStatus = (int) ($_POST['status'] ?? 1);
        $newStatus = $newStatus === 0 ? 0 : 1;
        $stmt = mysqli_prepare($con, 'UPDATE users SET status = ? WHERE id = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 'ii', $newStatus, $userId);

        if (mysqli_stmt_execute($stmt)) {
            admin_flash('success', $newStatus === 0 ? 'User blocked successfully.' : 'User unblocked successfully.');
        } else {
            admin_flash('error', 'User status was not updated.');
        }

        admin_redirect('user.php?id=' . $userId);
    }

    if ($action === 'reset_password') {
        $newPassword = (string) ($_POST['new_password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

        if (strlen($newPassword) < 8 || strlen($newPassword) > 72) {
            admin_flash('error', 'Password must be between 8 and 72 characters.');
            admin_redirect('user.php?id=' . $userId);
        }

        if ($newPassword !== $confirmPassword) {
            admin_flash('error', 'Password confirmation does not match.');
            admin_redirect('user.php?id=' . $userId);
        }

        $passwordHash = app_password_hash($newPassword);
        $stmt = mysqli_prepare($con, 'UPDATE users SET password = ? WHERE id = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 'si', $passwordHash, $userId);

        if (mysqli_stmt_execute($stmt)) {
            admin_flash('success', 'User password reset successfully.');
        } else {
            admin_flash('error', 'Password was not reset.');
        }

        admin_redirect('user.php?id=' . $userId);
    }
}

$user = admin_fetch_one("SELECT * FROM users WHERE id = {$userId} LIMIT 1");
$gameNameSql = admin_game_name_sql('ut');

$userTotals = admin_fetch_one("
    SELECT
        COUNT(CASE WHEN type = 'bid' THEN 1 END) AS bet_count,
        COALESCE(SUM(CASE WHEN type = 'bid' THEN CAST(amount AS DECIMAL(12,2)) ELSE 0 END), 0) AS bet_amount,
        COALESCE(SUM(CASE WHEN debit_credit = 'credit' AND type != 'deposit_request' THEN CAST(amount AS DECIMAL(12,2)) ELSE 0 END), 0) AS credit_amount,
        COALESCE(SUM(CASE WHEN debit_credit = 'debit' THEN CAST(amount AS DECIMAL(12,2)) ELSE 0 END), 0) AS debit_amount,
        COALESCE(SUM(CASE WHEN type = 'win' THEN CAST(amount AS DECIMAL(12,2)) ELSE 0 END), 0) AS win_amount
    FROM user_transaction
    WHERE user_id = {$userId}
") ?: ['bet_count' => 0, 'bet_amount' => 0, 'credit_amount' => 0, 'debit_amount' => 0, 'win_amount' => 0];

$numberSummary = admin_fetch_all("
    SELECT
        digit,
        game_type,
        COUNT(*) AS bet_count,
        COALESCE(SUM(CAST(amount AS DECIMAL(12,2))), 0) AS total_amount
    FROM user_transaction
    WHERE user_id = {$userId} AND type = 'bid'
    GROUP BY digit, game_type
    ORDER BY total_amount DESC, bet_count DESC
    LIMIT 20
");

$recentBets = admin_fetch_all("
    SELECT
        ut.id,
        ut.game_type,
        ut.digit,
        ut.date,
        ut.time,
        ut.amount,
        ut.win,
        COALESCE(ut.starline, 0) AS starline,
        {$gameNameSql} AS game_name
    FROM user_transaction ut
    LEFT JOIN games g ON g.id = ut.game_id AND COALESCE(ut.starline, 0) = 0
    LEFT JOIN starline sl ON sl.id = ut.game_id AND COALESCE(ut.starline, 0) = 1
    WHERE ut.user_id = {$userId} AND ut.type = 'bid'
    ORDER BY ut.id DESC
    LIMIT 30
");

$transactions = admin_fetch_all("
    SELECT id, type, title, game_type, amount, debit_credit, balance, date, time
    FROM user_transaction
    WHERE user_id = {$userId}
    ORDER BY id DESC
    LIMIT 30
");

admin_render_header('User Account');
?>
<section class="stats-grid">
    <?php admin_stat_card('Current Balance', 'Rs ' . admin_money($user['balance'] ?? 0), (string) ($user['username'] ?? '')); ?>
    <?php admin_stat_card('Total Bet Amount', 'Rs ' . admin_money($userTotals['bet_amount']), (int) $userTotals['bet_count'] . ' bets'); ?>
    <?php admin_stat_card('Total Credit', 'Rs ' . admin_money($userTotals['credit_amount'])); ?>
    <?php admin_stat_card('Total Debit', 'Rs ' . admin_money($userTotals['debit_amount'])); ?>
</section>

<div class="content-grid">
    <section>
        <div class="panel">
            <h2>Account Details</h2>
            <div class="detail-list">
                <div><span>ID</span><strong>#<?php echo (int) $user['id']; ?></strong></div>
                <div><span>Name</span><strong><?php echo e($user['name'] ?? '-'); ?></strong></div>
                <div><span>Username</span><strong><?php echo e($user['username'] ?? '-'); ?></strong></div>
                <div><span>Mobile</span><strong><?php echo e($user['mobile'] ?? '-'); ?></strong></div>
                <div><span>Status</span><strong><?php echo (string) ($user['status'] ?? '1') === '0' ? 'Blocked' : 'Active'; ?></strong></div>
                <div><span>Joined</span><strong><?php echo e($user['date_created'] ?? '-'); ?></strong></div>
                <div><span>Last Bid</span><strong><?php echo e($user['last_bid_placed_on'] ?? '-'); ?></strong></div>
            </div>
        </div>

        <div class="panel">
            <h2>Bet Number Summary</h2>
            <?php if ($numberSummary) { ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Number</th>
                                <th>Type</th>
                                <th>Bets</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($numberSummary as $row) { ?>
                                <tr>
                                    <td><strong><?php echo e($row['digit']); ?></strong></td>
                                    <td><?php echo e($row['game_type']); ?></td>
                                    <td><?php echo (int) $row['bet_count']; ?></td>
                                    <td>Rs <?php echo admin_money($row['total_amount']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { admin_empty_state('No bet numbers found for this user.'); } ?>
        </div>
    </section>

    <section>
        <div class="panel">
            <h2>Manage Balance</h2>
            <form method="post" class="admin-stack-form">
                <input type="hidden" name="csrf_token" value="<?php echo e(admin_csrf_token()); ?>">
                <input type="hidden" name="action" value="adjust_balance">
                <label class="field">
                    <span>Action</span>
                    <select name="direction">
                        <option value="credit">Add Balance</option>
                        <option value="debit">Remove Balance</option>
                    </select>
                </label>
                <label class="field">
                    <span>Amount</span>
                    <input type="number" name="amount" min="1" step="0.01" required>
                </label>
                <label class="field">
                    <span>Note</span>
                    <input type="text" name="note" placeholder="Optional">
                </label>
                <button class="button primary" type="submit">Save Balance</button>
            </form>
        </div>

        <div class="panel">
            <h2>Reset Password</h2>
            <form method="post" class="admin-stack-form">
                <input type="hidden" name="csrf_token" value="<?php echo e(admin_csrf_token()); ?>">
                <input type="hidden" name="action" value="reset_password">
                <label class="field">
                    <span>New Password</span>
                    <input type="password" name="new_password" minlength="8" maxlength="72" required>
                </label>
                <label class="field">
                    <span>Confirm Password</span>
                    <input type="password" name="confirm_password" minlength="8" maxlength="72" required>
                </label>
                <button class="button primary" type="submit">Reset User Password</button>
            </form>
        </div>

        <div class="panel">
            <h2>Block Control</h2>
            <form method="post" class="actions">
                <input type="hidden" name="csrf_token" value="<?php echo e(admin_csrf_token()); ?>">
                <input type="hidden" name="action" value="toggle_status">
                <?php if ((string) ($user['status'] ?? '1') === '0') { ?>
                    <input type="hidden" name="status" value="1">
                    <button class="button success" type="submit">Unblock User</button>
                <?php } else { ?>
                    <input type="hidden" name="status" value="0">
                    <button class="button danger" type="submit">Block User</button>
                <?php } ?>
                <span class="muted">Blocked users cannot continue using logged-in pages.</span>
            </form>
        </div>
    </section>
</div>

<section class="panel">
    <h2>Recent Bets</h2>
    <?php if ($recentBets) { ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Game</th>
                        <th>Market</th>
                        <th>Type</th>
                        <th>Number</th>
                        <th>Amount</th>
                        <th>Result</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentBets as $row) { ?>
                        <tr>
                            <td>#<?php echo (int) $row['id']; ?></td>
                            <td><?php echo e($row['game_name']); ?></td>
                            <td><?php echo (int) $row['starline'] === 1 ? '<span class="badge gold">Starline</span>' : '<span class="badge">Main</span>'; ?></td>
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
    <?php } else { admin_empty_state('No recent bets found.'); } ?>
</section>

<section class="panel">
    <h2>Recent Transactions</h2>
    <?php if ($transactions) { ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Narration</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Balance</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $row) { ?>
                        <tr>
                            <td>#<?php echo (int) $row['id']; ?></td>
                            <td><?php echo e($row['title'] ?: $row['game_type'] ?: $row['type']); ?></td>
                            <td><?php echo e(ucfirst($row['debit_credit'])); ?></td>
                            <td class="<?php echo $row['debit_credit'] === 'credit' ? 'text-green' : 'text-red'; ?>">
                                <?php echo $row['debit_credit'] === 'credit' ? '+' : '-'; ?> Rs <?php echo admin_money($row['amount']); ?>
                            </td>
                            <td>Rs <?php echo admin_money($row['balance']); ?></td>
                            <td><?php echo e($row['date']); ?> <?php echo e($row['time']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { admin_empty_state('No transactions found.'); } ?>
</section>
<?php admin_render_footer(); ?>
