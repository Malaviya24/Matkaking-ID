<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");
include("include/session.php");
include("include/functions.php");
app_restore_session_from_cookies();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - <?php echo $site_title; ?></title>
    <?php include("include/head.php"); ?>
</head>
<body class="page-notifications">
    <div class="wrapper">
        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>

            <div class="container">
                <div class="text-center tb-10">
                    <h3 class="gdash3"><i class="fa fa-bell"></i> Notifications</h3>
                </div>

                <div class="tb-10">
                <?php
                $notif_result = mysqli_query($con, "SELECT * FROM notification ORDER BY id DESC LIMIT 50");
                if ($notif_result && mysqli_num_rows($notif_result) > 0) {
                    while ($notif = mysqli_fetch_assoc($notif_result)) {
                ?>
                    <div class="card shadow-sm border-0 mb-3" style="background:rgba(10,27,54,.95);border:1px solid rgba(232,184,74,.15) !important;border-radius:12px;">
                        <div class="card-body p-3">
                            <div style="display:flex;align-items:flex-start;gap:12px;">
                                <div style="flex:0 0 36px;width:36px;height:36px;border-radius:50%;background:linear-gradient(145deg,#f0d27a,#caa64a);display:flex;align-items:center;justify-content:center;">
                                    <i class="fa fa-bell" style="color:#1a1200;font-size:16px;"></i>
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <h6 style="color:#f6ad55;font-weight:700;margin:0 0 4px;font-size:14px;"><?php echo htmlspecialchars($notif['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h6>
                                    <p style="color:rgba(255,255,255,.75);font-size:13px;margin:0 0 6px;line-height:1.4;"><?php echo htmlspecialchars($notif['description'] ?? $notif['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                                    <small style="color:rgba(255,255,255,.35);font-size:11px;">
                                        <i class="fa fa-clock-o"></i> <?php echo htmlspecialchars(($notif['date'] ?? '') . ' ' . ($notif['time'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                } else {
                ?>
                    <div class="text-center" style="padding:40px 20px;color:rgba(255,255,255,.5);">
                        <i class="fa fa-bell-slash" style="font-size:40px;display:block;margin-bottom:12px;opacity:.4;"></i>
                        <p>No notifications yet</p>
                    </div>
                <?php } ?>
                </div>
            </div>

        </div>
    </div>
    <?php include("include/footer.php"); ?>
</body>
</html>
