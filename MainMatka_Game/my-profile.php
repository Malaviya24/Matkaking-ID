<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include 'include/connect.php';
include 'include/session.php';
include 'include/functions.php';

$profile_display_name = isset($_SESSION['usr_name']) ? (string) $_SESSION['usr_name'] : 'User';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>My profile - <?php echo htmlspecialchars($site_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <?php include 'include/head.php'; ?>
</head>

<body class="page-profile">

    <div class="wrapper">

        <?php include 'include/sidebar.php'; ?>
        <div id="content">
            <?php include 'include/nav.php'; ?>

            <div class="profile-page">
                <header class="profile-header">
                    <div class="profile-header__avatar" aria-hidden="true">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </div>
                    <div class="profile-header__meta">
                        <div class="profile-header__name"><?php echo htmlspecialchars($profile_display_name, ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                </header>

                <div class="profile-tiles">
                    <a href="update-bank-details.php" class="profile-tile">
                        <div class="profile-tile__visual">
                            <div class="profile-tile__inner">
                                <img src="assets/icons/bank.png" width="30" height="30" alt="" decoding="async">
                            </div>
                        </div>
                        <span class="profile-tile__label">Bank Details</span>
                    </a>
                    <a href="change-password.php" class="profile-tile">
                        <div class="profile-tile__visual">
                            <div class="profile-tile__inner">
                                <i class="fa fa-key" aria-hidden="true"></i>
                            </div>
                        </div>
                        <span class="profile-tile__label">Change password</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <?php include 'include/footer.php'; ?>

</body>

</html>
