<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include 'include/connect.php';
include 'include/session.php';
include 'include/functions.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>History — <?php echo htmlspecialchars($site_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <?php include 'include/head.php'; ?>
</head>

<body class="page-history">

    <div class="wrapper">

        <?php include 'include/sidebar.php'; ?>
        <div id="content">
            <?php include 'include/nav.php'; ?>

            <div class="history-page">
                <header class="history-page__head">
                    <h1 class="history-page__title">History</h1>
                    <p class="history-page__sub">Bids, funds &amp; transactions</p>
                </header>

                <ul class="history-menu" role="list">
                    <li>
                        <a href="bidding-history.php" class="history-menu__link">
                            <div class="history-menu__card">
                                <span class="history-menu__ico" aria-hidden="true"><img src="assets/icons/bidhistory.png" width="26" height="26" alt=""></span>
                                <span class="history-menu__text">Bid History</span>
                                <span class="history-menu__chev" aria-hidden="true"><i class="fa fa-chevron-right"></i></span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="bidding-history-starline.php" class="history-menu__link">
                            <div class="history-menu__card">
                                <span class="history-menu__ico" aria-hidden="true"><i class="fa fa-star"></i></span>
                                <span class="history-menu__text">Starline Bid History</span>
                                <span class="history-menu__chev" aria-hidden="true"><i class="fa fa-chevron-right"></i></span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="fund-history.php" class="history-menu__link">
                            <div class="history-menu__card">
                                <span class="history-menu__ico" aria-hidden="true"><img src="assets/icons/fundhistory.png" width="26" height="26" alt=""></span>
                                <span class="history-menu__text">Fund History</span>
                                <span class="history-menu__chev" aria-hidden="true"><i class="fa fa-chevron-right"></i></span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="transaction-history.php" class="history-menu__link">
                            <div class="history-menu__card">
                                <span class="history-menu__ico" aria-hidden="true"><img src="assets/icons/transaction.png" width="26" height="26" alt=""></span>
                                <span class="history-menu__text">Transaction History</span>
                                <span class="history-menu__chev" aria-hidden="true"><i class="fa fa-chevron-right"></i></span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <?php include 'include/footer.php'; ?>

</body>

</html>
