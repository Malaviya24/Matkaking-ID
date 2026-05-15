<?php
require_once __DIR__ . '/includes/layout.php';
admin_require_login();

$search = admin_text_param('search', 80);
$status = admin_text_param('status', 20);
$sort = admin_text_param('sort', 20);
$page = admin_page_param();
$limit = 50;
$offset = ($page - 1) * $limit;

$conditions = ['1=1'];
if ($search !== '') {
    $safe = admin_escape_like($search);
    $idMatch = ctype_digit($search) ? " OR id = " . (int) $search : '';
    $conditions[] = "(name LIKE '%{$safe}%' OR username LIKE '%{$safe}%' OR mobile LIKE '%{$safe}%'{$idMatch})";
}
if ($status === 'active') {
    $conditions[] = 'status = 1';
} elseif ($status === 'blocked') {
    $conditions[] = 'status = 0';
}
$where = implode(' AND ', $conditions);

$orderBy = 'id DESC';
if ($sort === 'balance_high') {
    $orderBy = 'CAST(balance AS DECIMAL(12,2)) DESC, id DESC';
} elseif ($sort === 'balance_low') {
    $orderBy = 'CAST(balance AS DECIMAL(12,2)) ASC, id DESC';
} elseif ($sort === 'name') {
    $orderBy = 'username ASC, id DESC';
}

$totalRows = (int) admin_count_value("SELECT COUNT(*) FROM users WHERE {$where}");
$totalPages = max(1, (int) ceil($totalRows / $limit));
$columns = admin_existing_user_columns();
$columnSql = $columns ? implode(', ', array_map(function ($column) {
    return "`{$column}`";
}, $columns)) : '*';

$users = admin_fetch_all("
    SELECT {$columnSql}
    FROM users
    WHERE {$where}
    ORDER BY {$orderBy}
    LIMIT {$offset}, {$limit}
");

$totals = admin_fetch_one("
    SELECT
        COUNT(*) AS total_users,
        COALESCE(SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END), 0) AS active_users,
        COALESCE(SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END), 0) AS blocked_users,
        COALESCE(SUM(balance), 0) AS total_balance
    FROM users
    WHERE {$where}
") ?: ['total_users' => 0, 'active_users' => 0, 'blocked_users' => 0, 'total_balance' => 0];

$query = $_GET;
unset($query['page']);
$baseQuery = http_build_query($query);
$pageUrl = function ($targetPage) use ($baseQuery) {
    $query = $baseQuery ? $baseQuery . '&' : '';
    return '?' . $query . 'page=' . (int) $targetPage;
};

admin_render_header('Users');
?>
<section class="panel">
    <form class="filter-form" method="get">
        <label class="field">
            <span>Search User</span>
            <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Name, mobile, ID">
        </label>
        <label class="field">
            <span>Status</span>
            <select name="status">
                <option value="">All</option>
                <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="blocked" <?php echo $status === 'blocked' ? 'selected' : ''; ?>>Blocked</option>
            </select>
        </label>
        <label class="field">
            <span>Sort</span>
            <select name="sort">
                <option value="" <?php echo $sort === '' ? 'selected' : ''; ?>>Newest</option>
                <option value="balance_high" <?php echo $sort === 'balance_high' ? 'selected' : ''; ?>>Balance High</option>
                <option value="balance_low" <?php echo $sort === 'balance_low' ? 'selected' : ''; ?>>Balance Low</option>
                <option value="name" <?php echo $sort === 'name' ? 'selected' : ''; ?>>Name</option>
            </select>
        </label>
        <button class="button primary" type="submit">Filter</button>
    </form>
</section>

<section class="stats-grid">
    <?php admin_stat_card('Users Found', (int) $totals['total_users']); ?>
    <?php admin_stat_card('Active', (int) $totals['active_users']); ?>
    <?php admin_stat_card('Blocked', (int) $totals['blocked_users']); ?>
    <?php admin_stat_card('Wallet Balance', 'Rs ' . admin_money($totals['total_balance'])); ?>
</section>

<section class="panel">
    <h2>All Login Users</h2>
    <?php if ($users) { ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Mobile</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Last Bet</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $row) { ?>
                        <tr>
                            <td>#<?php echo (int) ($row['id'] ?? 0); ?></td>
                            <td><?php echo e($row['name'] ?? '-'); ?></td>
                            <td><?php echo e($row['username'] ?? '-'); ?></td>
                            <td><?php echo e($row['mobile'] ?? '-'); ?></td>
                            <td>Rs <?php echo admin_money($row['balance'] ?? 0); ?></td>
                            <td>
                                <?php if ((string) ($row['status'] ?? '1') === '0') { ?>
                                    <span class="badge danger">Blocked</span>
                                <?php } else { ?>
                                    <span class="badge success">Active</span>
                                <?php } ?>
                            </td>
                            <td><?php echo e($row['date_created'] ?? '-'); ?></td>
                            <td><?php echo e($row['last_bid_placed_on'] ?? '-'); ?></td>
                            <td><a class="ghost-button" href="user.php?id=<?php echo (int) ($row['id'] ?? 0); ?>">View Account</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="pagination">
            <?php if ($page > 1) { ?><a class="ghost-button" href="<?php echo e($pageUrl($page - 1)); ?>">Previous</a><?php } else { ?><span></span><?php } ?>
            <span class="muted">Page <?php echo (int) $page; ?> of <?php echo (int) $totalPages; ?>, <?php echo (int) $totalRows; ?> users</span>
            <?php if ($page < $totalPages) { ?><a class="ghost-button" href="<?php echo e($pageUrl($page + 1)); ?>">Next</a><?php } else { ?><span></span><?php } ?>
        </div>
    <?php } else { admin_empty_state('No users match this filter.'); } ?>
</section>
<?php admin_render_footer(); ?>
