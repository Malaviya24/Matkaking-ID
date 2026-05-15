<?php
require_once __DIR__ . '/includes/layout.php';
admin_require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_validate_csrf()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'save_payment_settings') {
        $upiId = trim(substr((string) ($_POST['deposit_upi_id'] ?? ''), 0, 120));
        $qrUrl = trim(substr((string) ($_POST['deposit_qr_url'] ?? ''), 0, 500));
        $payeeName = trim(substr((string) ($_POST['deposit_payee_name'] ?? ''), 0, 120));

        $ok = admin_save_setting('deposit_upi_id', $upiId)
            && admin_save_setting('deposit_qr_url', $qrUrl)
            && admin_save_setting('deposit_payee_name', $payeeName ?: 'MainMatka');

        admin_flash($ok ? 'success' : 'error', $ok ? 'Payment settings saved.' : 'Payment settings were not saved.');
        admin_redirect('deposits.php');
    }

    $requestId = (int) ($_POST['request_id'] ?? 0);
    if ($requestId <= 0) {
        admin_flash('error', 'Invalid deposit request.');
        admin_redirect('deposits.php');
    }

    if ($action === 'approve_deposit') {
        mysqli_begin_transaction($con);

        $request = admin_fetch_one("
            SELECT id, user_id, amount, status, type, title
            FROM user_transaction
            WHERE id = {$requestId}
              AND title = 'manual_deposit'
              AND type = 'deposit_request'
              AND status = 1
            LIMIT 1 FOR UPDATE
        ");

        if (!$request) {
            mysqli_rollback($con);
            admin_flash('error', 'Deposit request is not pending or was already handled.');
            admin_redirect('deposits.php');
        }

        $userId = (int) $request['user_id'];
        $amount = (float) $request['amount'];
        $user = admin_fetch_one("SELECT id, balance FROM users WHERE id = {$userId} LIMIT 1 FOR UPDATE");

        if (!$user) {
            mysqli_rollback($con);
            admin_flash('error', 'User account was not found.');
            admin_redirect('deposits.php');
        }

        $newBalance = (float) $user['balance'] + $amount;
        $stmt = mysqli_prepare($con, 'UPDATE users SET balance = ? WHERE id = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 'di', $newBalance, $userId);
        $updatedUser = mysqli_stmt_execute($stmt);

        if ($updatedUser && admin_column_exists('users', 'total_deposit')) {
            mysqli_query($con, "UPDATE users SET total_deposit = (COALESCE(total_deposit, 0) + {$amount}) WHERE id = {$userId} LIMIT 1");
        }

        $adminNote = trim(substr((string) ($_POST['admin_note'] ?? ''), 0, 180));
        $apiSuffix = $adminNote !== '' ? ' | Approved: ' . mysqli_real_escape_string($con, $adminNote) : ' | Approved by admin';
        $updatedRequest = mysqli_query($con, "
            UPDATE user_transaction
            SET type = 'deposit',
                debit_credit = 'credit',
                status = 2,
                balance = '{$newBalance}',
                api_response = CONCAT(COALESCE(api_response, ''), '{$apiSuffix}')
            WHERE id = {$requestId}
            LIMIT 1
        ");

        if ($updatedUser && $updatedRequest) {
            mysqli_commit($con);
            admin_flash('success', 'Deposit approved and user balance updated.');
        } else {
            mysqli_rollback($con);
            admin_flash('error', 'Deposit approval failed.');
        }

        admin_redirect('deposits.php');
    }

    if ($action === 'reject_deposit') {
        $adminNote = trim(substr((string) ($_POST['admin_note'] ?? ''), 0, 180));
        $safeNote = mysqli_real_escape_string($con, $adminNote !== '' ? $adminNote : 'Rejected by admin');
        $updated = mysqli_query($con, "
            UPDATE user_transaction
            SET status = 0,
                title = 'manual_deposit_rejected',
                api_response = CONCAT(COALESCE(api_response, ''), ' | Rejected: {$safeNote}')
            WHERE id = {$requestId}
              AND type = 'deposit_request'
              AND status = 1
              AND title = 'manual_deposit'
            LIMIT 1
        ");

        if ($updated && mysqli_affected_rows($con) > 0) {
            admin_flash('success', 'Deposit request rejected.');
        } else {
            admin_flash('error', 'Deposit request is not pending or was already handled.');
        }

        admin_redirect('deposits.php');
    }
}

$status = admin_text_param('status', 20);
$search = admin_text_param('search', 80);
$page = admin_page_param();
$limit = 40;
$offset = ($page - 1) * $limit;

$conditions = ["(ut.title IN ('manual_deposit', 'manual_deposit_rejected') OR ut.api_response LIKE 'Manual Deposit UTR:%')"];
if ($status === 'pending') {
    $conditions[] = "ut.type = 'deposit_request' AND ut.status = 1";
} elseif ($status === 'approved') {
    $conditions[] = "ut.type = 'deposit' AND ut.status = 2";
} elseif ($status === 'rejected') {
    $conditions[] = "ut.status = 0";
}
if ($search !== '') {
    $safe = admin_escape_like($search);
    $conditions[] = "(u.name LIKE '%{$safe}%' OR u.username LIKE '%{$safe}%' OR u.mobile LIKE '%{$safe}%' OR ut.api_response LIKE '%{$safe}%')";
}
$where = implode(' AND ', $conditions);

$totalRows = (int) admin_count_value("
    SELECT COUNT(ut.id)
    FROM user_transaction ut
    LEFT JOIN users u ON u.id = ut.user_id
    WHERE {$where}
");
$totalPages = max(1, (int) ceil($totalRows / $limit));

$summary = admin_fetch_one("
    SELECT
        COUNT(CASE WHEN ut.type = 'deposit_request' AND ut.status = 1 THEN 1 END) AS pending_count,
        COALESCE(SUM(CASE WHEN ut.type = 'deposit_request' AND ut.status = 1 THEN CAST(ut.amount AS DECIMAL(12,2)) ELSE 0 END), 0) AS pending_amount,
        COUNT(CASE WHEN ut.type = 'deposit' AND ut.status = 2 THEN 1 END) AS approved_count,
        COALESCE(SUM(CASE WHEN ut.type = 'deposit' AND ut.status = 2 THEN CAST(ut.amount AS DECIMAL(12,2)) ELSE 0 END), 0) AS approved_amount
    FROM user_transaction ut
    WHERE (ut.title IN ('manual_deposit', 'manual_deposit_rejected') OR ut.api_response LIKE 'Manual Deposit UTR:%')
") ?: ['pending_count' => 0, 'pending_amount' => 0, 'approved_count' => 0, 'approved_amount' => 0];

$requests = admin_fetch_all("
    SELECT
        ut.id,
        ut.user_id,
        ut.amount,
        ut.balance,
        ut.status,
        ut.type,
        ut.title,
        ut.api_response,
        ut.date,
        ut.time,
        ut.timestamp,
        u.name,
        u.username,
        u.mobile,
        u.balance AS current_balance
    FROM user_transaction ut
    LEFT JOIN users u ON u.id = ut.user_id
    WHERE {$where}
    ORDER BY ut.id DESC
    LIMIT {$offset}, {$limit}
");

$query = $_GET;
unset($query['page']);
$baseQuery = http_build_query($query);
$pageUrl = function ($targetPage) use ($baseQuery) {
    $query = $baseQuery ? $baseQuery . '&' : '';
    return '?' . $query . 'page=' . (int) $targetPage;
};

$upiId = getenv('MAINMATKA_DEPOSIT_UPI_ID') ?: admin_get_setting('deposit_upi_id', '');
$qrUrl = getenv('MAINMATKA_DEPOSIT_QR_URL') ?: admin_get_setting('deposit_qr_url', '');
$payeeName = getenv('MAINMATKA_DEPOSIT_PAYEE_NAME') ?: admin_get_setting('deposit_payee_name', 'MainMatka');

admin_render_header('Deposit Requests');
?>
<section class="stats-grid">
    <?php admin_stat_card('Pending Requests', (int) $summary['pending_count'], 'Rs ' . admin_money($summary['pending_amount'])); ?>
    <?php admin_stat_card('Approved Manual Deposits', (int) $summary['approved_count'], 'Rs ' . admin_money($summary['approved_amount'])); ?>
    <?php admin_stat_card('Payment UPI', $upiId ?: 'Not set', 'Shown on user deposit page'); ?>
    <?php admin_stat_card('QR Image', $qrUrl ? 'Configured' : 'Not set', 'Use image URL or env var'); ?>
</section>

<div class="content-grid">
    <section class="panel">
        <h2>Requests</h2>
        <form class="filter-form" method="get">
            <label class="field">
                <span>Status</span>
                <select name="status">
                    <option value="">All</option>
                    <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="approved" <?php echo $status === 'approved' ? 'selected' : ''; ?>>Approved</option>
                    <option value="rejected" <?php echo $status === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                </select>
            </label>
            <label class="field">
                <span>Search</span>
                <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="User, mobile, UTR">
            </label>
            <button class="button primary" type="submit">Filter</button>
        </form>
    </section>

    <section class="panel">
        <h2>Payment Settings</h2>
        <form method="post" class="form-grid">
            <input type="hidden" name="csrf_token" value="<?php echo e(admin_csrf_token()); ?>">
            <input type="hidden" name="action" value="save_payment_settings">
            <label class="field">
                <span>UPI ID</span>
                <input type="text" name="deposit_upi_id" value="<?php echo e($upiId); ?>" placeholder="name@upi">
            </label>
            <label class="field">
                <span>Payee Name</span>
                <input type="text" name="deposit_payee_name" value="<?php echo e($payeeName); ?>">
            </label>
            <label class="field" style="grid-column:1 / -1;">
                <span>QR Image URL</span>
                <input type="text" name="deposit_qr_url" value="<?php echo e($qrUrl); ?>" placeholder="https://.../qr.png">
            </label>
            <button class="button primary" type="submit">Save QR Settings</button>
        </form>
    </section>
</div>

<section class="panel">
    <h2>User Deposit Requests</h2>
    <?php if ($requests) { ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>UTR / Response</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Current Balance</th>
                        <th>Admin Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $row) {
                        $isPending = $row['type'] === 'deposit_request' && (int) $row['status'] === 1 && $row['title'] === 'manual_deposit';
                    ?>
                        <tr>
                            <td>#<?php echo (int) $row['id']; ?></td>
                            <td>
                                <a href="user.php?id=<?php echo (int) $row['user_id']; ?>"><?php echo e($row['username'] ?: $row['mobile'] ?: 'User #' . $row['user_id']); ?></a>
                                <div class="muted"><?php echo e($row['mobile'] ?? ''); ?></div>
                            </td>
                            <td><strong>Rs <?php echo admin_money($row['amount']); ?></strong></td>
                            <td class="wrap"><?php echo e($row['api_response']); ?></td>
                            <td>
                                <?php if ($isPending) { ?>
                                    <span class="badge gold">Pending</span>
                                <?php } elseif ($row['type'] === 'deposit' && (int) $row['status'] === 2) { ?>
                                    <span class="badge success">Approved</span>
                                <?php } else { ?>
                                    <span class="badge danger">Rejected</span>
                                <?php } ?>
                            </td>
                            <td><?php echo e($row['date']); ?> <?php echo e($row['time']); ?></td>
                            <td>Rs <?php echo admin_money($row['current_balance']); ?></td>
                            <td>
                                <?php if ($isPending) { ?>
                                    <form method="post" class="actions">
                                        <input type="hidden" name="csrf_token" value="<?php echo e(admin_csrf_token()); ?>">
                                        <input type="hidden" name="request_id" value="<?php echo (int) $row['id']; ?>">
                                        <input type="text" name="admin_note" placeholder="Admin note" style="min-height:40px;border-radius:8px;border:1px solid var(--line);background:var(--ink);color:var(--text);padding:9px 11px;">
                                        <button class="button success" type="submit" name="action" value="approve_deposit">Approve</button>
                                        <button class="button danger" type="submit" name="action" value="reject_deposit">Reject</button>
                                    </form>
                                <?php } else { ?>
                                    <span class="muted">Handled</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="pagination">
            <?php if ($page > 1) { ?><a class="ghost-button" href="<?php echo e($pageUrl($page - 1)); ?>">Previous</a><?php } else { ?><span></span><?php } ?>
            <span class="muted">Page <?php echo (int) $page; ?> of <?php echo (int) $totalPages; ?>, <?php echo (int) $totalRows; ?> requests</span>
            <?php if ($page < $totalPages) { ?><a class="ghost-button" href="<?php echo e($pageUrl($page + 1)); ?>">Next</a><?php } else { ?><span></span><?php } ?>
        </div>
    <?php } else { admin_empty_state('No deposit requests found.'); } ?>
</section>
<?php admin_render_footer(); ?>
