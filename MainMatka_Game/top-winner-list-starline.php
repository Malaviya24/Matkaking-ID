<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");
include("include/functions.php");
app_restore_session_from_cookies();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Top Starline Winners List - <?php echo $site_title;?></title>
    <meta name="description" content="Check out the list of todays top starline winners. online satta matka starline top winner list will give you clear idea that how many people wins and you will get more confidance to play and win big money.">
    <?php include("include/head.php"); ?>
</head>

<body class="page-top-winner-list">

    <div class="wrapper">
        
        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>
            
            <div class="container">
            <div class="top-winners-page">
                <header class="top-winners-hero text-center">
                    <div class="top-winners-hero__icon-wrap">
                        <img src="assets/icons/top_winners.png" alt="" class="top-winners-hero__icon" width="80" height="80">
                    </div>
                    <h1 class="top-winners-hero__title">Top Starline Winner List</h1>
                    <p class="top-winners-hero__sub">List of Today's top winners with amount</p>
                </header>

                <div class="top-winners-list">
        <?php
        $today_date = date('Y-m-d');
        $qry =  "SELECT * FROM user_transaction where date='$today_date' and type='win' and starline='1' order by amount+0 DESC limit 10";
		$upi_transactions = mysqli_query($con, $qry);
		while($row = mysqli_fetch_array($upi_transactions)){
		    $user_name= get_userNameById($row['user_id']);
			$length = strlen($user_name);

			$visibleLength = 2;
			$hiddenLength = $length - $visibleLength * 2; // Calculate total hidden characters

			$visibleChars = substr($user_name, 0, $visibleLength);
			$hiddenChars = str_repeat('*', $hiddenLength);
			$visibleCharsEnd = substr($user_name, -$visibleLength);

			$masked_username = $visibleChars . $hiddenChars . $visibleCharsEnd; 
		?>
                <div class="card top-winner-card shadow-sm border-0 mb-3">
                    <div class="card-body top-winner-card__inner">

                        <!-- Row 1: Trophy icon — top center -->
                        <div class="top-winner-card__icon-row">
                            <div class="top-winner-card__avatar">
                                <i class="fa fa-trophy top-winner-card__trophy-ico"></i>
                            </div>
                        </div>

                        <!-- Row 2: Masked username & Digit — left/right -->
                        <div class="top-winner-card__name-row">
                            <h6 class="top-winner-card__name"><?php echo $masked_username;?></h6>
                            <span class="top-winner-card__digit-badge">Digit: <?php echo $row['digit'];?></span>
                        </div>

                        <!-- Row 3: Game name (left) | Date+Time (right) -->
                        <div class="top-winner-card__meta-row">
                            <div class="top-winner-card__meta-left">
                                <i class="fa fa-star top-winner-card__ico"></i>
                                <small class="top-winner-card__meta"><?php echo get_Starlinetime($row['game_id']);?></small>
                            </div>
                            <div class="top-winner-card__meta-right">
                                <i class="fa fa-clock-o top-winner-card__ico"></i>
                                <small class="top-winner-card__time"><?php echo date('d M Y, h:i A',strtotime($row['date'].' '.$row['time']));?></small>
                            </div>
                        </div>

                        <!-- Row 4: Prize — center bottom -->
                        <div class="top-winner-card__prize-block">
                            <span class="top-winner-card__prize-label">PRIZE</span>
                            <span class="top-winner-card__prize-amt">?<?php echo number_format($row['amount']*40,2);?></span>
                        </div>

                    </div>
                </div>
        <?php } ?>
                </div>
            </div>
            </div>
      
         <br><br><br>   
        </div>
    </div>
    
    <?php include("include/footer.php"); ?>

</body>

</html>
