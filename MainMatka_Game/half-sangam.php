<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");
    include("include/session.php");
	include("include/functions.php");
	
	$child_game_id = mysqli_real_escape_string($con, $_GET['gid']);
	$parent_game_id = mysqli_real_escape_string($con, $_GET['pgid']);
	$default_game = mysqli_real_escape_string($con, $_GET['dgame']);
	
if (isset($_POST['single_submit']) && isset($_SESSION['usr_id'])!="") {
    if (!app_validate_csrf()) {
        echo "<script>window.location = 'half-sangam.php?invalidrequest';</script>";
        exit;
    }
	
	if(get_SettingValue('pause_main_market_bidding_website')){
    	echo "<script>alert('Bidding are Stopped Temprary !!!')</script>";
        echo "<script>window.location = 'index.php';</script>";
    	exit;	
    	}
	
		$user_id= $_SESSION['usr_id'];
		
            $game_id = mysqli_real_escape_string($con, $_POST['game_id']);
            
            $open_digit1 = mysqli_real_escape_string($con,$_POST['open_digit1']);
            $close_digit1 = mysqli_real_escape_string($con,$_POST['close_digit1']);
            $amount1 = mysqli_real_escape_string($con,$_POST['amount1']);
            
            $open_digit2 = mysqli_real_escape_string($con,$_POST['open_digit2']);
            $close_digit2 = mysqli_real_escape_string($con,$_POST['close_digit2']);
            $amount2 = mysqli_real_escape_string($con,$_POST['amount2']);
            
            $total_point = $amount1+$amount2;
            
            $child_game_id = mysqli_real_escape_string($con, $_POST['gid']);
            $parent_game_id = mysqli_real_escape_string($con, $_POST['pgid']);
            $default_game = mysqli_real_escape_string($con, $_POST['dgame']);
            
            $get_parameters = "gid=$child_game_id&pgid=$parent_game_id&dgame=$default_game";
            
            
            $date = date('Y-m-d');
    		$time = date('h:i:s A');
    		
    		    include_once(__DIR__ . '/include/scraped-bet-check.php');
    		
            if ($_scraped_bet_validated) {
                // Scraped market - already validated, skip time check
            } elseif (true) {
            $game_time = get_gameTimeById($game_id);
            $date_time = $date." ".$game_time;
            $market_time = strtotime($date_time);
            }

            if(!$_scraped_bet_validated && !IsTempTestMarketGame($game_id) && time() >= $market_time){
                
                //echo "<script>alert('Invalid Date and Time')</script>";
                echo "<script>window.location = 'half-sangam.php?invalid_date&".$get_parameters."';</script>";
                
            }else{
			        $bets = [];
                    $hs_digit1 = $open_digit1.'-'.$close_digit1;
                    $digit_length1 = strlen($hs_digit1);
    				if($amount1 >=5 && $digit_length1 == 5)
    				{
                        $bets[$hs_digit1] = $amount1;
    				}
    				
    				$hs_digit2 = $open_digit2.'-'.$close_digit2;
                    $digit_length2 = strlen($hs_digit2);
    				if($amount2 >=5 && $digit_length2 == 5)
    				{
                        $bets[$hs_digit2] = $amount2;
    				}
                    $placed = app_place_bets($user_id, $game_id, 'half_sangam', $bets, 0);
    			
    
                if($placed['ok']){
                    //echo "<script>alert('Bidding Successfully Submited')</script>";
                    echo "<script>window.location = 'half-sangam.php?bidplacedsuccessfully&".$get_parameters."';</script>";
                    exit;
                }elseif(($placed['reason'] ?? '') === 'insufficient'){
                    echo "<script>window.location = 'half-sangam.php?insufficientbalance&".$get_parameters."';</script>";
                    exit;
                }else{
                    //echo "<script>alert('Something Wrong! Try Again')</script>";
                    echo "<script>window.location = 'half-sangam.php?bidfailed&".$get_parameters."';</script>";
                    exit;
                }
            
            }
  
    
}


	
	
	if($child_game_id =='' || $parent_game_id =='' || $default_game ==''){
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

    <title>Half Sangam Matka Play Dashboard</title>
    
    <?php include("include/head.php"); ?>
</head>

<body class="page-half-sangam">

    <div class="wrapper">
        
        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>
            
            
            <div class="container" >  
            <div class="card-full-page tb-10">
                
                <?php
                include_once(__DIR__ . '/include/scraped-market-game-setup.php');
                if (!$is_scraped_market) {
                $games_list_qry =  "SELECT * FROM `parent_games` WHERE id=$parent_game_id and status=1";
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
                             if(IsTempTestMarketParent($parent_game_id)){
							   $bidding_status = 1;
                               $msg = 'Test Market is Running Now';
                               $default_bidding_date ='today';
                               $default_bidding_game = $default_game == 'close' ? 'close' : 'open';
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
                             //$open_result = GetOpneResultByid($child_open);
                             //$close_result = GetCloseResultByid($child_close);
                             
                            $game_id = $row['id'];
                            $game_name = $row['name'];
                            $open_time = $open_time;
                            $close_time = $close_time;
                            $result_open_time = $result_open_time;
                            $result_close_time = $result_close_time;
                            //$result = $open_result.''.$close_result;
							$bidding_status = $bidding_status;
                            $msg =  $msg;
                            $default_bidding_date = $default_bidding_date;
                            $default_bidding_game = $default_bidding_game;
                            $status = $row['status'];
                            //$game_title = strtolower(str_replace(" ","-",$game_name));

                    } // end while
                } // end if (!$is_scraped_market)
                ?>
                <form action="" method="POST" class="myform" autocomplete="off">
                <div class="text-center mb-4 mt-2">
                    <h3 class="font-weight-bold text-uppercase" style="color: var(--primary-color); font-size: 20px;">
                        <?php echo isset($game_name) ? $game_name : (isset($game_title) ? $game_title : "Play Game"); ?>
                    </h3>
                    <span class="text-muted" style="font-size: 13px;"><i class="fa fa-clock-o"></i> Select Market & Place Bid</span>
                </div>
                <?php if($is_scraped_market && $bidding_status){ include(__DIR__ . '/include/scraped-market-form-header.php'); } elseif($default_bidding_game =='open'){ ?>
                
                <div class="row bidoptions-list tb-10">
                                <div class="col-6">
                                  <a class="dateGameIDbox">
                                      <p><?php echo date('d/m/Y');?></p>
                                  </a>
                                </div>
                                
                                <div class="col-6">
                                    <select class="dateGameIDbox" name="game_id">
                                        <option value="<?php echo $child_open;?>"> <?php echo get_gameNameById($child_open);?></option>
                                    </select>
                                </div>
                                
                </div>
                
                <?php }else{ ?>
                
                <div class="tbmar-40 text-center">
                    <p>Sorry! Bidding is Close for <?php echo $game_name;?>. <br> Try again Tomorrow.</p>
                </div>
                
                <?php } ?>
                
                
                <?php if($bidding_status){?>
                <div class="tb-10"><hr class="devider"></div>

                <h3 class="subheading">Play Half Sangam</h3>
                
                <div class="row bidoptions-list tb-10">
                    
                <div class="col-4" style="padding-right: 5px;padding-left: 5px;">
                                    <div class="bidinputdiv">
                                        <lable>Open ank</lable>
                                        <input type="number" min="0" max="9" placeholder="0-9" name="open_digit1" style="padding: 10px 5px;cursor: text;" required>
                                    </div>
                </div>
                <div class="col-4" style="padding-right: 5px;padding-left: 5px;">
                                    <div class="bidinputdiv">
                                        <lable>Close Patti</lable>
                                        <input type="number" min="000" max="999" placeholder="000-999" name="close_digit1" style="padding: 10px 5px;cursor: text;" required>
                                    </div>
                </div>
                
                <div class="col-4" style="padding-right: 5px;padding-left: 5px;">
                                    <div class="bidinputdiv">
                                        <lable>Amount</lable>
                                        <input type="number"  min="5" name="amount1" style="padding: 10px 5px;cursor: text;" required>
                                    </div>
                </div>

                </div>
                
                <div class="row bidoptions-list tb-10">
                    
                <div class="col-4" style="padding-right: 5px;padding-left: 5px;">
                                    <div class="bidinputdiv">
                                        <lable>Open Patti</lable>
                                        <input type="number" min="000" max="999" placeholder="000-999" name="open_digit2" style="padding: 10px 5px;cursor: text;">
                                    </div>
                </div>
                
                <div class="col-4" style="padding-right: 5px;padding-left: 5px;">
                                    <div class="bidinputdiv">
                                        <lable>Close ank</lable>
                                        <input type="number" min="0" max="9" placeholder="0-9" name="close_digit2" style="padding: 10px 5px;cursor: text;">
                                    </div>
                </div>
                
                
                <div class="col-4" style="padding-right: 5px;padding-left: 5px;">
                                    <div class="bidinputdiv">
                                        <lable>Amount</lable>
                                        <input type="number" min="5" name="amount2" style="padding: 10px 5px;cursor: text;">
                                    </div>
                </div>

                </div>
                
                
                <input type="hidden" name="gid" value="<?php echo $child_game_id;?>">
                <input type="hidden" name="pgid" value="<?php echo $parent_game_id;?>">
                <input type="hidden" name="dgame" value="<?php echo $default_game;?>">
                <?php echo app_csrf_input(); ?>
                
                
                <div class="row bidoptions-list tb-10 sangam-form-actions">
                                <div class="col-6"> 
                                  <button class="btn btn-light btn-streched" onclick = "resetjsvar();" type="reset">Reset</button>
                                </div>
                                
                                <div class="col-6">
                                <button class="btn btn-theme btn-streched" type="submit" name="single_submit">Submit</button>
                                </div>
                                
                </div>
                
                <?php } ?>
                
                </form>
                        

            </div>
            </div>
            
            
        </div>
    </div>
    
    <?php include("include/footer.php"); ?>

</body>

</html>
