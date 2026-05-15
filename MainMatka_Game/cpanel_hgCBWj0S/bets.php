<?php
require_once __DIR__ . '/includes/layout.php';
admin_require_login();

$filters = admin_bet_filters(false);
$sort = admin_text_param('sort', 20);
$page = admin_page_param();
$limit = 50;
$offset = ($page - 1) * $limit;
$where = admin_bet_where_sql($filters);
$gameNameSql = admin_game_name_sql('ut');

$orderBy = 'ut.id DESC';
if ($sort === 'amount_high') {
    $orderBy = 'CAST(ut.amount AS DECIMAL(12,2)) DESC, ut.id DESC';
} elseif ($sort === 'number_high') {
    $orderBy = 'digit_total DESC, ut.id DESC';
}

$totalRows = (int) admin_count_value("
    SELECT COUNT(ut.id)
    FROM user_transaction ut
    LEFT JOIN users u ON u.id = ut.user_id
    WHERE {$where}
");
$totalPages = max(1, (int) ceil($totalRows / $limit));

$summary = admin_fetch_one("
    SELECT
        COUNT(ut.id) AS bet_count,
        COUNT(DISTINCT ut.user_id) AS user_count,
        COALESCE(SUM(CAST(ut.amount AS DECIMAL(12,2))), 0) AS total_amount,
        COALESCE(AVG(CAST(ut.amount AS DECIMAL(12,2))), 0) AS avg_amount
    FROM user_transaction ut
    LEFT JOIN users u ON u.id = ut.user_id
    WHERE {$where}
") ?: ['bet_count' => 0, 'user_count' => 0, 'total_amount' => 0, 'avg_amount' => 0];

$topNumbers = admin_fetch_all("
    SELECT
        ut.digit,
        ut.game_type,
        COUNT(*) AS bet_count,
        COALESCE(SUM(CAST(ut.amount AS DECIMAL(12,2))), 0) AS total_amount,
        COALESCE(MAX(CAST(ut.amount AS DECIMAL(12,2))), 0) AS max_amount
    FROM user_transaction ut
    LEFT JOIN users u ON u.id = ut.user_id
    WHERE {$where}
    GROUP BY ut.digit, ut.game_type
    ORDER BY total_amount DESC, bet_count DESC
    LIMIT 20
");

$typeSummary = admin_fetch_all("
    SELECT
        ut.game_type,
        COUNT(*) AS bet_count,
        COALESCE(SUM(CAST(ut.amount AS DECIMAL(12,2))), 0) AS total_amount
    FROM user_transaction ut
    LEFT JOIN users u ON u.id = ut.user_id
    WHERE {$where}
    GROUP BY ut.game_type
    ORDER BY total_amount DESC
    LIMIT 10
");

$bets = admin_fetch_all("
    SELECT
        ut.id,
        ut.user_id,
        ut.game_id,
        ut.game_type,
        ut.digit,
        ut.date,
        ut.time,
        ut.amount,
        ut.balance,
        ut.win,
        COALESCE(ut.starline, 0) AS starline,
        {$gameNameSql} AS game_name,
        u.name,
        u.username,
        u.mobile,
        digit_rollup.total_amount AS digit_total
    FROM user_transaction ut
    LEFT JOIN users u ON u.id = ut.user_id
    LEFT JOIN games g ON g.id = ut.game_id AND COALESCE(ut.starline, 0) = 0
    LEFT JOIN starline sl ON sl.id = ut.game_id AND COALESCE(ut.starline, 0) = 1
    LEFT JOIN scraped_markets sm ON sm.id = ut.game_id AND sm.date = ut.date
    LEFT JOIN (
        SELECT digit, game_type, COALESCE(SUM(CAST(amount AS DECIMAL(12,2))), 0) AS total_amount
        FROM user_transaction
        WHERE type = 'bid'
        GROUP BY digit, game_type
    ) digit_rollup ON digit_rollup.digit = ut.digit AND digit_rollup.game_type = ut.game_type
    WHERE {$where}
    ORDER BY {$orderBy}
    LIMIT {$offset}, {$limit}
");

$query = $_GET;
unset($query['page']);
$baseQuery = http_build_query($query);
$pageUrl = function ($targetPage) use ($baseQuery) {
    $query = $baseQuery ? $baseQuery . '&' : '';
    return '?' . $query . 'page=' . (int) $targetPage;
};

admin_render_header('Bet Reports');
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
            <span>Min Amount</span>
            <input type="number" name="min_amount" min="0" value="<?php echo e($filters['min_amount']); ?>">
        </label>
        <label class="field">
            <span>Max Amount</span>
            <input type="number" name="max_amount" min="0" value="<?php echo e($filters['max_amount']); ?>">
        </label>
        <label class="field">
            <span>Number</span>
            <input type="text" name="digit" value="<?php echo e($filters['digit']); ?>">
        </label>
        <label class="field">
            <span>Bet Type</span>
            <input type="text" name="game_type" value="<?php echo e($filters['game_type']); ?>" placeholder="single, jodi">
        </label>
        <label class="field">
            <span>User</span>
            <input type="text" name="user_query" value="<?php echo e($filters['user_query']); ?>" placeholder="Name or mobile">
        </label>
        <label class="field">
            <span>Market</span>
            <select name="market">
                <option value="">All</option>
                <option value="main" <?php echo $filters['market'] === 'main' ? 'selected' : ''; ?>>Main</option>
                <option value="starline" <?php echo $filters['market'] === 'starline' ? 'selected' : ''; ?>>Starline</option>
            </select>
        </label>
        <label class="field">
            <span>Sort</span>
            <select name="sort">
                <option value="" <?php echo $sort === '' ? 'selected' : ''; ?>>Latest</option>
                <option value="amount_high" <?php echo $sort === 'amount_high' ? 'selected' : ''; ?>>High Amount</option>
                <option value="number_high" <?php echo $sort === 'number_high' ? 'selected' : ''; ?>>High Number Total</option>
            </select>
        </label>
        <button class="button primary" type="submit">Apply</button>
    </form>
</section>

<section class="stats-grid">
    <?php admin_stat_card('Total Bet Amount', 'Rs ' . admin_money($summary['total_amount']), (int) $summary['bet_count'] . ' bets'); ?>
    <?php admin_stat_card('Users Betting', (int) $summary['user_count'], 'Unique users'); ?>
    <?php admin_stat_card('Average Bet', 'Rs ' . admin_money($summary['avg_amount'])); ?>
    <?php
    $top = $topNumbers[0] ?? null;
    admin_stat_card('High Bet Number', $top ? $top['digit'] : '-', $top ? 'Rs ' . admin_money($top['total_amount']) . ' total' : 'No data');
    ?>
</section>

<div class="content-grid">
    <section class="panel">
        <h2>All Bets</h2>
        <?php if ($bets) { ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Game</th>
                            <th>Market</th>
                            <th>Type</th>
                            <th>Number</th>
                            <th>Amount</th>
                            <th>Result</th>
                            <th>Date</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bets as $row) { ?>
                            <tr>
                                <td>#<?php echo (int) $row['id']; ?></td>
                                <td>
                                    <a href="user.php?id=<?php echo (int) $row['user_id']; ?>">
                                        <?php echo e($row['username'] ?: $row['mobile'] ?: 'User #' . $row['user_id']); ?>
                                    </a>
                                </td>
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
                                <td>Rs <?php echo admin_money($row['balance']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="pagination">
                <?php if ($page > 1) { ?><a class="ghost-button" href="<?php echo e($pageUrl($page - 1)); ?>">Previous</a><?php } else { ?><span></span><?php } ?>
                <span class="muted">Page <?php echo (int) $page; ?> of <?php echo (int) $totalPages; ?>, <?php echo (int) $totalRows; ?> records</span>
                <?php if ($page < $totalPages) { ?><a class="ghost-button" href="<?php echo e($pageUrl($page + 1)); ?>">Next</a><?php } else { ?><span></span><?php } ?>
            </div>
        <?php } else { admin_empty_state('No bets match this filter.'); } ?>
    </section>

    <section>
        <div class="panel">
            <h2>High Number Summary</h2>
            <?php if ($topNumbers) { ?>
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
                            <?php foreach ($topNumbers as $row) { ?>
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
            <?php } else { admin_empty_state('No number totals found.'); } ?>
        </div>

        <div class="panel">
            <h2>Bet Type Summary</h2>
            <?php if ($typeSummary) { ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Bets</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($typeSummary as $row) { ?>
                                <tr>
                                    <td><?php echo e($row['game_type']); ?></td>
                                    <td><?php echo (int) $row['bet_count']; ?></td>
                                    <td>Rs <?php echo admin_money($row['total_amount']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { admin_empty_state('No type summary found.'); } ?>
        </div>
    </section>
</div>
<?php admin_render_footer(); ?>
