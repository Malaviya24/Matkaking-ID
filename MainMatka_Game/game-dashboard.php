<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");
    include("include/session.php");
	include("include/functions.php");
	
	$game_title = mysqli_real_escape_string($con, $_GET['game']);
	$game_id_raw = $_GET['gid'] ?? '';
	$game_title = ucfirst(str_replace("-"," ",$game_title));
	
	// Check if this is a scraped market (src=live or gid starts with scraped_)
	$is_live_market = ($_GET['src'] ?? '') === 'live';
	if ($is_live_market && is_numeric($game_id_raw)) {
	    $scraped_market_id = (int) $game_id_raw;
	} else {
	    $scraped_market_id = app_is_scraped_market_gid($game_id_raw);
	}
	
	if($scraped_market_id) {
	    // Scraped market - check if betting is allowed
	    $open_check = app_scraped_market_bet_allowed($scraped_market_id, 'open');
	    $close_check = app_scraped_market_bet_allowed($scraped_market_id, 'close');
	    
	    if (!$open_check['allowed'] && !$close_check['allowed']) {
	        // Both open and close betting are closed
	        $betting_blocked = true;
	        $block_reason = $open_check['reason'] ?: $close_check['reason'];
	    } else {
	        $betting_blocked = false;
	        $block_reason = '';
	    }
	    
	    $game_id = 0; // No parent_game record for scraped markets
	} else {
	    $game_id = intval(mysqli_real_escape_string($con, $game_id_raw));
	    $betting_blocked = false;
	    $block_reason = '';
	}
	
	if($game_id_raw ==''){
	    echo "<script>window.location = '404.php';</script>";
	    exit;
	}
	
	
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Game Dashboard - <?php echo $game_title ;?></title>
    
    <?php include("include/head.php"); ?>
</head>

<body class="page-game-dashboard">

    <div class="wrapper">
        
        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>
            
            
            <div class="container" >  
            <div class="card-full-page tb-10">
                
                <div class="text-center tb-10 game-dashboard-header">
                    <h3 class="gdash3"><?php echo $game_title;?> Dashboard</h3>
                    <div class="bidding-subtitle-wrap"><span class="bidding-subtitle">Select Bidding Option</span></div>
                </div>
                
                <div class="tb-10">&nbsp;</div>
                
                <?php
                // If this is a scraped market and betting is blocked
                if ($scraped_market_id && $betting_blocked) { ?>
                
                <div class="tbmar-40 text-center">
                    <div class="game-status-note game-status-closed" style="background:rgba(255,0,0,0.08);border:1px solid rgba(255,0,0,0.2);border-radius:12px;padding:20px;margin:20px 0;">
                        <i class="fa fa-ban" style="font-size:32px;color:#fc8181;display:block;margin-bottom:10px;"></i>
                        <strong style="color:#fc8181;font-size:16px;">No Bets Taken!</strong><br>
                        <span style="color:rgba(255,255,255,0.7);font-size:13px;margin-top:8px;display:block;"><?php echo htmlspecialchars($block_reason, ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    <a href="index.php" class="btn btn-theme mt-3" style="margin-top:15px;">← Back to Home</a>
                </div>
                
                <?php } elseif ($scraped_market_id && !$betting_blocked) {
                    // Scraped market with betting open - show same options as parent_games
                    $market_data = $open_check['allowed'] ? $open_check['market'] : $close_check['market'];
                    $default_bidding_game = $open_check['allowed'] ? 'open' : 'close';
                    $scraped_gid = 'scraped_' . $scraped_market_id;
                    $msg = $default_bidding_game === 'open' ? 'Open Betting is Running' : 'Close Betting is Running';
                ?>
                
                <?php if($default_bidding_game == 'open'){ ?>
                <div class="row bidoptions-list tb-10">
                                <div class="col-4">
                                  <a href="single.php?gid=<?php echo $scraped_gid;?>&pgid=<?php echo $scraped_gid;?>&dgame=open" class="bidtypebox">
                                      <img src="assets/img/single_ank.png">
                                      <p>Single Ank</p>
                                  </a>
                                </div>
                                
                                <div class="col-4">
                                  <a href="jodi.php?gid=<?php echo $scraped_gid;?>&pgid=<?php echo $scraped_gid;?>&dgame=open" class="bidtypebox">
                                      <img src="assets/img/group.png">
                                      <p>Jodi</p>
                                  </a>
                                </div>
                                
                                <div class="col-4">
                                  <a href="single-patti.php?gid=<?php echo $scraped_gid;?>&pgid=<?php echo $scraped_gid;?>&dgame=open" class="bidtypebox">
                                      <img src="assets/img/single.patti.png">
                                      <p>Single Patti</p>
                                  </a>
                                </div>
                </div>
                
                <div class="row bidoptions-list tb-10">
                                <div class="col-4">
                                  <a href="double-patti.php?gid=<?php echo $scraped_gid;?>&pgid=<?php echo $scraped_gid;?>&dgame=open" class="bidtypebox">
                                      <img src="assets/img/double.png">
                                      <p>Double Patti</p>
                                  </a>
                                </div>
                                
                                <div class="col-4">
                                  <a href="triple-patti.php?gid=<?php echo $scraped_gid;?>&pgid=<?php echo $scraped_gid;?>&dgame=open" class="bidtypebox">
                                      <img src="assets/img/tripple_patti.png">
                                      <p>Triple Patti</p>
                                  </a>
                                </div>
                                
                                <div class="col-4">
                                  <a href="half-sangam.php?gid=<?php echo $scraped_gid;?>&pgid=<?php echo $scraped_gid;?>&dgame=open" class="bidtypebox">
                                      <img src="assets/img/half.png">
                                      <p>Half Sangam</p>
                                  </a>
                                </div>
                </div>
                
                <div class="row bidoptions-list tb-10">
                                <div class="col-4"></div>
                                <div class="col-4">
                                  <a href="full-sangam.php?gid=<?php echo $scraped_gid;?>&pgid=<?php echo $scraped_gid;?>&dgame=open" class="bidtypebox">
                                      <img src="assets/img/full_singum.png">
                                      <p>Full Sangam</p>
                                  </a>
                                </div>
                                <div class="col-4"></div>
                </div>
                
                <?php } else { ?>
                
                <div class="row bidoptions-list tb-10">
                                <div class="col-4">
                                  <a href="single.php?gid=<?php echo $scraped_gid;?>&pgid=<?php echo $scraped_gid;?>&dgame=close" class="bidtypebox">
                                      <img src="assets/img/single_ank.png">
                                      <p>Single Ank</p>
                                  </a>
                                </div>

                                <div class="col-4">
                                  <a href="single-patti.php?gid=<?php echo $scraped_gid;?>&pgid=<?php echo $scraped_gid;?>&dgame=close" class="bidtypebox">
                                      <img src="assets/img/single.patti.png">
                                      <p>Single Patti</p>
                                  </a>
                                </div>
                                <div class="col-4">
                                  <a href="double-patti.php?gid=<?php echo $scraped_gid;?>&pgid=<?php echo $scraped_gid;?>&dgame=close" class="bidtypebox">
                                      <img src="assets/img/double.png">
                                      <p>Double Patti</p>
                                  </a>
                                </div>
                </div>
                
                <div class="row bidoptions-list tb-10">
                                <div class="col-4"></div>
                                <div class="col-4">
                                  <a href="triple-patti.php?gid=<?php echo $scraped_gid;?>&pgid=<?php echo $scraped_gid;?>&dgame=close" class="bidtypebox">
                                      <img src="assets/img/tripple_patti.png">
                                      <p>Triple Patti</p>
                                  </a>
                                </div>
                                <div class="col-4"></div>
                </div>
                
                <?php } ?>
                
                <div class="tbmar-40 text-center">
                    <div class="game-status-note">
                        <i class="fa fa-info-circle"></i> Note: <?php echo $msg;?>
                    </div>
                </div>
                
                <?php } else {
                // Original parent_games logic
                ?>
                <?php
                $games_list_qry =  "SELECT * FROM `parent_games` WHERE id=$game_id and status=1";
				$games = mysqli_query($con, $games_list_qry);
                         while ($row = mysqli_fetch_array($games)){
                            $open_time =  $row['open_time'];
                            $close_time = $row['close_time'];
                            $result_open_time = $row['result_open_time'];
                            $result_close_time = $row['result_close_time'];
                            $open_days = $row['open_days'];
                            $game_days = explode(",", $open_days);
                            
                            $day = strtolower(date('D', strtotime(date('Y-m-d'))));
                             
                             $betting_open_time =strtotime(date('Y-m-d').' '.$open_time);
                             $betting_close_time =strtotime(date('Y-m-d').' '.$close_time);
                             if(IsTempTestMarketParent($game_id)){
							   $bidding_status = 1;
                               $msg = 'Test Market is Running Now';
                               $default_bidding_date ='today';
                               $default_bidding_game ='open';
                             }elseif(in_array($day, $game_days) && time() < $betting_open_time){
							   $bidding_status = 1;
                               $msg = 'Betting is Running Now';
                               $default_bidding_date ='today';
                               $default_bidding_game ='open';
                             }elseif(in_array($day, $game_days) && time() < $betting_close_time){
							   $bidding_status = 1;
                               $msg = 'Betting is Running For Close';
                               $default_bidding_date ='today';
                               $default_bidding_game ='close';
                             }else{
							   $bidding_status = 0;
                               $msg = 'Betting is Closed for Today';
                               $default_bidding_date ='next_date';
                               $default_bidding_game ='';
                             }
                             
                             
                             $child_open = $row['child_open_id'];
                             $child_close = $row['child_close_id'];

                             
                            $game_id = $row['id'];
                            $game_name = $row['name'];
                            $open_time = $open_time;
                            $close_time = $close_time;
 
							$bidding_status = $bidding_status;
                            $msg =  $msg;
                            $default_bidding_date = $default_bidding_date;
                            $default_bidding_game = $default_bidding_game;
                            $status = $row['status'];
                            //$game_title = strtolower(str_replace(" ","-",$game_name));

                    ?>
                
                <?php if($default_bidding_game =='open'){ ?>
                <div class="row bidoptions-list tb-10">
                                <div class="col-4">
                                  <a href="single.php?gid=<?php echo $child_open;?>&pgid=<?php echo $game_id;?>&dgame=open" class="bidtypebox">
                                      <img src="assets/img/single_ank.png">
                                      <p>Single Ank</p>
                                  </a>
                                </div>
                                
                                <div class="col-4">
                                  <a href="jodi.php?gid=<?php echo $child_open;?>&pgid=<?php echo $game_id;?>&dgame=open" class="bidtypebox">
                                      <img src="assets/img/group.png">
                                      <p>Jodi</p>
                                  </a>
                                </div>
                                
                                <div class="col-4">
                                  <a href="single-patti.php?gid=<?php echo $child_open;?>&pgid=<?php echo $game_id;?>&dgame=open" class="bidtypebox">
                                      <img src="assets/img/single.patti.png">
                                      <p>Single Patti</p>
                                  </a>
                                </div>

        
                </div>
                
                <div class="row bidoptions-list tb-10">
                                <div class="col-4">
                                  <a href="double-patti.php?gid=<?php echo $child_open;?>&pgid=<?php echo $game_id;?>&dgame=open" class="bidtypebox">
                                      <img src="assets/img/double.png">
                                      <p>Double Patti</p>
                                  </a>
                                </div>
                                
                                <div class="col-4">
                                  <a href="triple-patti.php?gid=<?php echo $child_open;?>&pgid=<?php echo $game_id;?>&dgame=open" class="bidtypebox">
                                      <img src="assets/img/tripple_patti.png">
                                      <p>Triple Patti</p>
                                  </a>
                                </div>
                                
                                <div class="col-4">
                                  <a href="half-sangam.php?gid=<?php echo $child_open;?>&pgid=<?php echo $game_id;?>&dgame=open" class="bidtypebox">
                                      <img src="assets/img/half.png">
                                      <p>Half Sangam</p>
                                  </a>
                                </div>

        
                </div>
                
                <div class="row bidoptions-list tb-10">
                                <div class="col-4">
                                  
                                </div>
                                
                                <div class="col-4">
                                  <a href="full-sangam.php?gid=<?php echo $child_open;?>&pgid=<?php echo $game_id;?>&dgame=open" class="bidtypebox">
                                      <img src="assets/img/full_singum.png">
                                      <p>Full Sangam</p>
                                  </a>
                                </div>
                                
                                <div class="col-4">
                                  
                                </div>

        
                </div>
                
                <div class="tbmar-40 text-center">
                    <div class="game-status-note">
                        <i class="fa fa-info-circle"></i> Note: <?php echo $msg;?>
                    </div>
                </div>
                
                <?php }elseif($default_bidding_game =='close'){ ?>
                
                <div class="row bidoptions-list tb-10">
                                <div class="col-4">
                                  <a href="single.php?gid=<?php echo $child_close;?>&pgid=<?php echo $game_id;?>&dgame=close" class="bidtypebox">
                                      <img src="assets/img/single_ank.png">
                                      <p>Single Ank</p>
                                  </a>
                                </div>

                                <div class="col-4">
                                  <a href="single-patti.php?gid=<?php echo $child_close;?>&pgid=<?php echo $game_id;?>&dgame=close" class="bidtypebox">
                                      <img src="assets/img/single.patti.png">
                                      <p>Single Patti</p>
                                  </a>
                                </div>
                                <div class="col-4">
                                  <a href="double-patti.php?gid=<?php echo $child_close;?>&pgid=<?php echo $game_id;?>&dgame=close" class="bidtypebox">
                                      <img src="assets/img/double.png">
                                      <p>Double Patti</p>
                                  </a>
                                </div>

        
                </div>
                
                <div class="row bidoptions-list tb-10">
                                <div class="col-4">

                                </div>
                                
                                <div class="col-4">
                                  <a href="triple-patti.php?gid=<?php echo $child_close;?>&pgid=<?php echo $game_id;?>&dgame=close" class="bidtypebox">
                                      <img src="assets/img/tripple_patti.png">
                                      <p>Triple Patti</p>
                                  </a>
                                </div>
                                
                                <div class="col-4">

                                </div>

        
                </div>
                
                <div class="tbmar-40 text-center">
                    <div class="game-status-note">
                        <i class="fa fa-info-circle"></i> Note: <?php echo $msg;?>
                    </div>
                </div>
                
                <?php }else{ ?>
                
                <div class="tbmar-40 text-center">
                    <div class="game-status-note game-status-closed">
                        <i class="fa fa-ban"></i> Sorry! Bidding is Close for <?php echo $game_title;?>. <br> Try again Tomorrow.
                    </div>
                </div>
                
                <?php } ?>
                
                
                <?php } ?>
                
                <?php } // end else (original parent_games logic) ?>


            </div>
            </div>
            
            
        </div>
    </div>
    
    <?php include("include/footer.php"); ?>

</body>

</html>
