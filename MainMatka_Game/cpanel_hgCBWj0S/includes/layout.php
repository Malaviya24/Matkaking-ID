<?php
require_once __DIR__ . '/bootstrap.php';

function admin_active_class($page)
{
    $current = strtolower(basename($_SERVER['SCRIPT_NAME'] ?? ''));
    return $current === $page ? ' class="active"' : '';
}

function admin_render_header($title)
{
    global $site_title;
    $flash = admin_take_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title); ?> - <?php echo e($site_title); ?> Admin</title>
    <link rel="stylesheet" href="assets/admin.css?v=1.0.0">
</head>
<body>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a class="admin-brand" href="index.php">
                <span class="admin-brand__mark">M</span>
                <span>
                    <strong><?php echo e($site_title); ?></strong>
                    <small>Admin Panel</small>
                </span>
            </a>
            <nav class="admin-menu">
                <a<?php echo admin_active_class('index.php'); ?> href="index.php">Dashboard</a>
                <a<?php echo admin_active_class('bets.php'); ?> href="bets.php">Bets</a>
                <a<?php echo admin_active_class('results.php'); ?> href="results.php">Results</a>
                <a<?php echo admin_active_class('deposits.php'); ?> href="deposits.php">Deposit Requests</a>
                <a<?php echo admin_active_class('test-market.php'); ?> href="test-market.php">Test Market</a>
                <a<?php echo admin_active_class('users.php'); ?> href="users.php">Users</a>
                <a<?php echo admin_active_class('notifications.php'); ?> href="notifications.php">Notifications</a>
            </nav>
            <div class="admin-sidebar__foot">
                <span><?php echo e($_SESSION['admin_name'] ?? 'Admin'); ?></span>
                <a href="logout.php">Logout</a>
            </div>
        </aside>
        <main class="admin-main">
            <header class="admin-topbar">
                <div>
                    <p class="admin-kicker">Control room</p>
                    <h1><?php echo e($title); ?></h1>
                </div>
                <a class="ghost-button" href="../index.php" target="_blank" rel="noopener">Open App</a>
            </header>
            <?php if ($flash) { ?>
                <div class="alert alert-<?php echo e($flash['type']); ?>"><?php echo e($flash['message']); ?></div>
            <?php } ?>
<?php
}

function admin_render_footer()
{
?>
        </main>
    </div>
</body>
</html>
<?php
}

function admin_stat_card($label, $value, $sub = '')
{
?>
    <article class="stat-card">
        <span><?php echo e($label); ?></span>
        <strong><?php echo e($value); ?></strong>
        <?php if ($sub !== '') { ?><small><?php echo e($sub); ?></small><?php } ?>
    </article>
<?php
}

function admin_empty_state($message)
{
?>
    <div class="empty-state"><?php echo e($message); ?></div>
<?php
}
?>
