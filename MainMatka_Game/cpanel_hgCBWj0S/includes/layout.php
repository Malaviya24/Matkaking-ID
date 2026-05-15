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
    <style>
    /* Mobile responsive overrides */
    .admin-hamburger{display:none;background:none;border:none;color:#fff;font-size:24px;cursor:pointer;padding:8px;line-height:1;}
    @media(max-width:768px){
        .admin-hamburger{display:block;}
        .admin-sidebar{position:fixed !important;left:0;top:0;bottom:0;transform:translateX(-100%);transition:transform .3s ease;z-index:1000;width:260px;}
        .admin-sidebar.open{transform:translateX(0) !important;}
        .admin-shell{display:block !important;}
        .admin-main{margin-left:0 !important;width:100% !important;min-width:0 !important;}
        .admin-topbar{flex-wrap:wrap;gap:8px;padding:12px 16px !important;}
        .admin-topbar h1{font-size:18px !important;}
        .admin-kicker{display:none;}
        .stats-grid{grid-template-columns:1fr 1fr !important;gap:8px !important;}
        .content-grid{grid-template-columns:1fr !important;}
        .filter-form{grid-template-columns:1fr 1fr !important;gap:8px !important;}
        .table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;}
        .table-wrap table{min-width:500px;}
        .stat-card{padding:12px !important;}
        .stat-card strong{font-size:16px !important;}
        .panel{padding:12px !important;margin-bottom:12px !important;}
        .form-grid{grid-template-columns:1fr !important;}
        .ghost-button{font-size:12px;padding:6px 10px !important;}
    }
    @media(max-width:480px){
        .stats-grid{grid-template-columns:1fr !important;}
        .filter-form{grid-template-columns:1fr !important;}
        .admin-topbar h1{font-size:16px !important;}
    }
    .admin-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;}
    .admin-overlay.open{display:block;}
    </style>
</head>
<body>
    <div class="admin-overlay" id="adminOverlay" onclick="toggleAdminMenu()"></div>
    <div class="admin-shell">
        <aside class="admin-sidebar" id="adminSidebar">
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
                <div style="display:flex;align-items:center;gap:10px;">
                    <button class="admin-hamburger" onclick="toggleAdminMenu()" aria-label="Menu">&#9776;</button>
                    <div>
                        <p class="admin-kicker">Control room</p>
                        <h1><?php echo e($title); ?></h1>
                    </div>
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
    <script>
    function toggleAdminMenu(){
        var s=document.getElementById('adminSidebar');
        var o=document.getElementById('adminOverlay');
        s.classList.toggle('open');
        o.classList.toggle('open');
    }
    </script>
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
