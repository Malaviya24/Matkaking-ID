<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");
    include("include/session.php");
	include("include/functions.php");
	

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>My profile - <?php echo $site_title;?></title>
    
    <?php include("include/head.php"); ?>
</head>

<body>

    <div class="wrapper">
        
        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>
            
            <div class="container" > 
            <div class="text-center tb-10 mt-3 mb-4">
                <div style="width: 60px; height: 60px; background: #f3e8ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                    <i class="fa fa-history" style="font-size: 28px; color: var(--secondary-color);"></i>
                </div>
                <h3 class="font-weight-bold" style="color: var(--primary-color);">History & Activity</h3>
                <span class="text-muted" style="font-size:13px;">View all your past records</span>
            </div>
            
            <div class="pb-10">
                
                <a href="fund-history.php" class="text-decoration-none d-block mb-3">
                    <div class="card shadow-sm border-0 transition" style="border-radius: 16px;">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #e6fffa; color: #38a169; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-right: 15px;">
                                <i class="fa fa-money"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 font-weight-bold text-dark">Fund History</h6>
                                <small class="text-muted">Deposit & withdraw records</small>
                            </div>
                            <i class="fa fa-chevron-right text-muted" style="font-size:12px;"></i>
                        </div>
                    </div>
                </a>

                <a href="bidding-history.php" class="text-decoration-none d-block mb-3">
                    <div class="card shadow-sm border-0 transition" style="border-radius: 16px;">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #ebf4ff; color: #3182ce; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-right: 15px;">
                                <i class="fa fa-gamepad"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 font-weight-bold text-dark">Main Bidding</h6>
                                <small class="text-muted">Normal market bets</small>
                            </div>
                            <i class="fa fa-chevron-right text-muted" style="font-size:12px;"></i>
                        </div>
                    </div>
                </a>
                
                <a href="bidding-history-starline.php" class="text-decoration-none d-block mb-3">
                    <div class="card shadow-sm border-0 transition" style="border-radius: 16px;">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #faf5ff; color: #805ad5; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-right: 15px;">
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 font-weight-bold text-dark">Starline Bidding</h6>
                                <small class="text-muted">Starline market bets</small>
                            </div>
                            <i class="fa fa-chevron-right text-muted" style="font-size:12px;"></i>
                        </div>
                    </div>
                </a>
                
                <a href="transaction-history.php" class="text-decoration-none d-block mb-3">
                    <div class="card shadow-sm border-0 transition" style="border-radius: 16px;">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #fff5f5; color: #e53e3e; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-right: 15px;">
                                <i class="fa fa-exchange"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 font-weight-bold text-dark">Passbook</h6>
                                <small class="text-muted">All debits and credits</small>
                            </div>
                            <i class="fa fa-chevron-right text-muted" style="font-size:12px;"></i>
                        </div>
                    </div>
                </a>
                        
            </div>
            </div>
      
            
        </div>
    </div>
    
    <?php include("include/footer.php"); ?>

</body>

</html>