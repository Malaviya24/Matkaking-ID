<?php
require_once __DIR__ . '/includes/layout.php';
admin_require_login();

$filters = admin_bet_filters(true);
$where = admin_bet_where_sql($filters);
$gameNameSql = admin_game_name_sql('ut');

$userTotals = admin_fetch_one("
    SELECT
        COUNT(*) AS total_users,
        COALESCE(SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END), 0) AS active_users,
        COALESCE(SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END), 0) AS blocked_users,
        COALESCE(SUM(balance), 0) AS total_balance
    FROM users
") ?: ['total_users' => 0, 'active_users' => 0, 'blocked_users' => 0, 'total_balance' => 0];

$betTotals = admin_fetch_one("
    SELECT
        COUNT(ut.id) AS total_bets,
        COALESCE(SUM(CAST(ut.amount AS DECIMAL(12,2))), 0) AS total_amount,
        COUNT(DISTINCT ut.user_id) AS users_played,
        COALESCE(MAX(CAST(ut.amount AS DECIMAL(12,2))), 0) AS highest_single_bet
    FROM user_transaction ut
    LEFT JOIN users u ON u.id = ut.user_id
    WHERE {$where}
") ?: ['total_bets' => 0, 'total_amount' => 0, 'users_played' => 0, 'highest_single_bet' => 0];

$marketTotals = admin_fetch_one("
    SELECT
        COALESCE(SUM(CASE WHEN COALESCE(ut.starline, 0) = 0 THEN CAST(ut.amount AS DECIMAL(12,2)) ELSE 0 END), 0) AS main_amount,
        COALESCE(SUM(CASE WHEN COALESCE(ut.starline, 0) = 1 THEN CAST(ut.amount AS DECIMAL(12,2)) ELSE 0 END), 0) AS starline_amount
    FROM user_transaction ut
    LEFT JOIN users u ON u.id = ut.user_id
    WHERE {$where}
") ?: ['main_amount' => 0, 'starline_amount' => 0];

$pendingDeposits = admin_fetch_one("
    SELECT COUNT(id) AS request_count, COALESCE(SUM(CAST(amount AS DECIMAL(12,2))), 0) AS request_amount
    FROM user_transaction
    WHERE type = 'deposit_request' AND status = 1 AND title = 'manual_deposit'
") ?: ['request_count' => 0, 'request_amount' => 0];

$topDigits = admin_fetch_all("
    SELECT
        ut.digit,
        ut.game_type,
        COUNT(*) AS bet_count,
        COALESCE(SUM(CAST(ut.amount AS DECIMAL(12,2))), 0) AS total_amount
    FROM user_transaction ut
    LEFT JOIN users u ON u.id = ut.user_id
    WHERE {$where}
    GROUP BY ut.digit, ut.game_type
    ORDER BY total_amount DESC, bet_count DESC
    LIMIT 10
");

$topUsers = admin_fetch_all("
    SELECT
        u.id,
        u.username,
        u.mobile,
        COUNT(ut.id) AS bet_count,
        COALESCE(SUM(CAST(ut.amount AS DECIMAL(12,2))), 0) AS total_amount
    FROM user_transaction ut
    LEFT JOIN users u ON u.id = ut.user_id
    WHERE {$where}
    GROUP BY u.id, u.username, u.mobile
    ORDER BY total_amount DESC
    LIMIT 10
");

$recentBets = admin_fetch_all("
    SELECT
        ut.id,
        ut.user_id,
        ut.game_type,
        ut.digit,
        ut.date,
        ut.time,
        ut.amount,
        COALESCE(ut.starline, 0) AS starline,
        {$gameNameSql} AS game_name,
        u.username,
        u.mobile
    FROM user_transaction ut
    LEFT JOIN users u ON u.id = ut.user_id
    LEFT JOIN games g ON g.id = ut.game_id AND COALESCE(ut.starline, 0) = 0
    LEFT JOIN starline sl ON sl.id = ut.game_id AND COALESCE(ut.starline, 0) = 1
    LEFT JOIN scraped_markets sm ON sm.id = ut.game_id AND sm.date = ut.date
    WHERE {$where}
    ORDER BY ut.id DESC
    LIMIT 20
");

admin_render_header('Dashboard');
?>
<section class="panel">
    <form class="filter-form" method="get">
        <label class="field">
            <span>From</span>
            <input type="date" name="date_from" value="<?php echo e($filters['date_from']); ?>">
        </label>
        <label class="field">
            <span>To</span>
            <input type="date" name="date_to" value="<?php echo e($filters['date_to']); ?>">
        </label>
        <label class="field">
            <span>Min Bet</span>
            <input type="number" name="min_amount" min="0" value="<?php echo e($filters['min_amount']); ?>">
        </label>
        <label class="field">
            <span>Number</span>
            <input type="text" name="digit" value="<?php echo e($filters['digit']); ?>" placeholder="Digit">
        </label>
        <label class="field">
            <span>Market</span>
            <select name="market">
                <option value="">All</option>
                <option value="main" <?php echo $filters['market'] === 'main' ? 'selected' : ''; ?>>Main</option>
                <option value="starline" <?php echo $filters['market'] === 'starline' ? 'selected' : ''; ?>>Starline</option>
            </select>
        </label>
        <button class="button primary" type="submit">Filter</button>
    </form>
</section>

<section class="stats-grid">
    <?php admin_stat_card('Total Users', (int) $userTotals['total_users'], (int) $userTotals['blocked_users'] . ' blocked'); ?>
    <?php admin_stat_card('All User Balance', 'Rs ' . admin_money($userTotals['total_balance']), 'Wallet total'); ?>
    <?php admin_stat_card('Bet Amount', 'Rs ' . admin_money($betTotals['total_amount']), (int) $betTotals['total_bets'] . ' bets'); ?>
    <?php admin_stat_card('Users Played', (int) $betTotals['users_played'], 'Highest single Rs ' . admin_money($betTotals['highest_single_bet'])); ?>
</section>

<section class="stats-grid">
    <?php admin_stat_card('Main Market Bets', 'Rs ' . admin_money($marketTotals['main_amount'])); ?>
    <?php admin_stat_card('Starline Bets', 'Rs ' . admin_money($marketTotals['starline_amount'])); ?>
    <?php
    $topDigit = $topDigits[0] ?? null;
    admin_stat_card('Highest Bet Number', $topDigit ? $topDigit['digit'] : '-', $topDigit ? 'Rs ' . admin_money($topDigit['total_amount']) . ' in ' . $topDigit['game_type'] : 'No bets found');
    ?>
    <?php admin_stat_card('Pending Deposits', (int) $pendingDeposits['request_count'], 'Rs ' . admin_money($pendingDeposits['request_amount'])); ?>
</section>

<div class="content-grid">
    <section class="panel">
        <h2>Recent Bets</h2>
        <?php if ($recentBets) { ?>
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
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentBets as $row) { ?>
                            <tr>
                                <td>#<?php echo (int) $row['id']; ?></td>
                                <td><a href="user.php?id=<?php echo (int) $row['user_id']; ?>"><?php echo e($row['username'] ?: $row['mobile']); ?></a></td>
                                <td><?php echo e($row['game_name']); ?></td>
                                <td><span class="badge <?php echo (int) $row['starline'] === 1 ? 'gold' : ''; ?>"><?php echo e($row['game_type']); ?></span></td>
                                <td><strong><?php echo e($row['digit']); ?></strong></td>
                                <td>Rs <?php echo admin_money($row['amount']); ?></td>
                                <td><?php echo e($row['date']); ?> <?php echo e($row['time']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { admin_empty_state('No bet records found for this filter.'); } ?>
    </section>

    <section>
        <div class="panel">
            <h2>High Bet Numbers</h2>
            <?php if ($topDigits) { ?>
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
                            <?php foreach ($topDigits as $row) { ?>
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
            <?php } else { admin_empty_state('No number summary yet.'); } ?>
        </div>

        <div class="panel">
            <h2>Top Users By Bet Amount</h2>
            <?php if ($topUsers) { ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Bets</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topUsers as $row) { ?>
                                <tr>
                                    <td><a href="user.php?id=<?php echo (int) $row['id']; ?>"><?php echo e($row['username'] ?: $row['mobile']); ?></a></td>
                                    <td><?php echo (int) $row['bet_count']; ?></td>
                                    <td>Rs <?php echo admin_money($row['total_amount']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { admin_empty_state('No user betting summary yet.'); } ?>
        </div>
    </section>
</div>
<?php admin_render_footer(); ?>
