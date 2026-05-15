<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");
include("include/session.php");
include("include/functions.php");

$user_id = (int) $_SESSION['usr_id'];
$verified_until = isset($_SESSION['password_reset_verified_until']) ? (int) $_SESSION['password_reset_verified_until'] : 0;
$can_reset_password = $verified_until >= time();

if (isset($_POST['verify_current_password']) && isset($_SESSION['usr_id']) != "") {
    if (!app_validate_csrf()) {
        echo "<script>window.location = 'change-password.php?invalidrequest';</script>";
        exit;
    }

    $current_password = (string) ($_POST['current_password'] ?? '');

    $stmt = mysqli_prepare($con, "SELECT id, password FROM users WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = $result ? mysqli_fetch_assoc($result) : null;

    if ($row && app_password_verify($current_password, $row['password'] ?? '')) {
        if (app_password_needs_rehash($row['password'] ?? '')) {
            $newHash = app_password_hash($current_password);
            $update = mysqli_prepare($con, "UPDATE users SET password = ? WHERE id = ? LIMIT 1");
            mysqli_stmt_bind_param($update, 'si', $newHash, $user_id);
            mysqli_stmt_execute($update);
        }
        $_SESSION['password_reset_verified_until'] = time() + 300;
        echo "<script>window.location = 'change-password.php?passwordverified';</script>";
        exit;
    }

    unset($_SESSION['password_reset_verified_until']);
    echo "<script>window.location = 'change-password.php?notupdated';</script>";
    exit;
}

if (isset($_POST['change_password']) && isset($_SESSION['usr_id']) != "") {
    if (!app_validate_csrf()) {
        echo "<script>window.location = 'change-password.php?invalidrequest';</script>";
        exit;
    }

    if (!$can_reset_password) {
        echo "<script>window.location = 'change-password.php?invalidrequest';</script>";
        exit;
    }

    $new_password = (string) ($_POST['new_password'] ?? '');
    $confirm_new_password = (string) ($_POST['confirm_new_password'] ?? '');

    if (strlen($new_password) < 8 || strlen($new_password) > 72 || $new_password !== $confirm_new_password) {
        echo "<script>window.location = 'change-password.php?invalidrequest';</script>";
        exit;
    }

    $new_password_hash = app_password_hash($new_password);
    $stmt = mysqli_prepare($con, "UPDATE users SET password = ? WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'si', $new_password_hash, $user_id);
    $res = mysqli_stmt_execute($stmt);
    unset($_SESSION['password_reset_verified_until']);

    if ($res) {
        echo "<script>window.location = 'change-password.php?detailupdated';</script>";
        exit;
    }

    echo "<script>window.location = 'change-password.php?notupdated';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Update Password - <?php echo $site_title; ?></title>

    <?php include("include/head.php"); ?>
</head>

<body class="page-change-password">

    <div class="wrapper">

        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>

            <div class="container">
                <div class="container py-4">
                    <div class="card shadow-lg border-0" style="border-radius: 20px;">
                        <div class="text-center mb-4 mt-2">
                            <h3 class="font-weight-bold" style="color: var(--primary-color);">Change Password</h3>
                            <span class="text-muted" style="font-size: 14px;">
                                <?php echo $can_reset_password ? 'Enter a new password for your account' : 'Verify your present password first'; ?>
                            </span>
                        </div>

                        <?php if (!$can_reset_password) { ?>
                            <form action="" method="POST" autocomplete="off">
                                <?php echo app_csrf_input(); ?>
                                <div class="form-group mb-4">
                                    <label for="current_password" class="text-secondary font-weight-bold" style="font-size:12px;">Present Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0" style="border-radius: 12px 0 0 12px; border-color: #e2e8f0;"><i class="fa fa-unlock-alt text-muted" style="width:20px;"></i></span>
                                        </div>
                                        <input type="password" class="form-control border-left-0 pl-0 bg-light" name="current_password" minlength="4" maxlength="50" placeholder="Enter present password" id="current_password" autocomplete="off" required style="border-radius: 0 12px 12px 0;">
                                    </div>
                                </div>

                                <button type="submit" name="verify_current_password" class="btn btn-theme py-3 font-weight-bold w-100" style="font-size: 16px; border-radius: 12px;">Verify Password <i class="fa fa-arrow-right ml-2"></i></button>
                            </form>
                        <?php } else { ?>
                            <form action="" method="POST" autocomplete="off">
                                <?php echo app_csrf_input(); ?>
                                <div class="form-group mb-3">
                                    <label for="new_password" class="text-secondary font-weight-bold" style="font-size:12px;">New Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0" style="border-radius: 12px 0 0 12px; border-color: #e2e8f0;"><i class="fa fa-lock text-muted" style="width:20px;"></i></span>
                                        </div>
                                        <input type="password" class="form-control border-left-0 pl-0 bg-light" name="new_password" minlength="8" maxlength="72" placeholder="Enter new password" id="new_password" autocomplete="off" required style="border-radius: 0 12px 12px 0;">
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="confirm_new_password" class="text-secondary font-weight-bold" style="font-size:12px;">Confirm Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0" style="border-radius: 12px 0 0 12px; border-color: #e2e8f0;"><i class="fa fa-check-circle text-muted" style="width:20px;"></i></span>
                                        </div>
                                        <input type="password" class="form-control border-left-0 pl-0 bg-light" name="confirm_new_password" minlength="8" maxlength="72" placeholder="Confirm new password" id="confirm_new_password" autocomplete="off" required style="border-radius: 0 12px 12px 0;">
                                    </div>
                                </div>

                                <button type="submit" name="change_password" class="btn btn-theme py-3 font-weight-bold w-100" style="font-size: 16px; border-radius: 12px;">Reset Password <i class="fa fa-save ml-2"></i></button>
                            </form>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include("include/footer.php"); ?>

</body>

</html>
