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
        echo "<script>window.location = 'single-patti.php?invalidrequest';</script>";
        exit;
    }
	
	if(get_SettingValue('pause_main_market_bidding_website')){
    	echo "<script>alert('Bidding are Stopped Temprary !!!')</script>";
        echo "<script>window.location = 'index.php';</script>";
    	exit;	
    	}
	
		$user_id= $_SESSION['usr_id'];
		
            $game_id = mysqli_real_escape_string($con, $_POST['game_id']);
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
                echo "<script>window.location = 'single-patti.php?invalid_date&".$get_parameters."';</script>";
                
            }else{
			
                $all_single_patti = array(127, 136, 145, 190, 235, 280, 370, 389, 460, 479, 569, 578, 128, 137, 146, 236, 245, 290, 380, 470, 489, 560, 579, 678, 129, 138, 147, 156, 237, 246, 345, 390, 480, 570, 589, 679, 120, 139, 148, 157, 238, 247, 256, 346, 490, 580, 670, 689, 130, 149, 158, 167, 239, 248, 257, 347, 356, 590, 680, 789, 140, 159, 168, 230, 249, 258, 267, 348, 357, 456, 690, 780, 123, 150, 169, 178, 240, 259, 268, 349, 358, 367, 457, 790, 124, 160, 179, 250, 269, 278, 340, 359, 368, 458, 467, 890, 125, 134, 170, 189, 260, 279, 350, 369, 378, 459, 468, 567, 126, 135, 180, 234, 270, 289, 360, 379, 450, 469, 478, 568);
                    
                $bets = [];
                foreach($all_single_patti as $digit){
                    $bets[(string) $digit] = $_POST['single_patti'.$digit] ?? 0;
                }
                $placed = app_place_bets($user_id, $game_id, 'single_patti', $bets, 0);
    
                if($placed['ok']){
                    //echo "<script>alert('Bidding Successfully Submited')</script>";
                    echo "<script>window.location = 'single-patti.php?bidplacedsuccessfully&".$get_parameters."';</script>";
                    exit;
                }elseif(($placed['reason'] ?? '') === 'insufficient'){
                    echo "<script>window.location = 'single-patti.php?insufficientbalance&".$get_parameters."';</script>";
                    exit;
                }else{
                    //echo "<script>alert('Something Wrong! Try Again')</script>";
                    echo "<script>window.location = 'single-patti.php?bidfailed&".$get_parameters."';</script>";
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

    <title>Single Patti Matka Play Dashboard</title>
    
    <?php include("include/head.php"); ?>
</head>

<body class="page-single-patti">

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
                <form action="" method="POST" class="myform">
                <div class="text-center mb-4 mt-2">
                    <h3 class="font-weight-bold text-uppercase" style="color: var(--primary-color); font-size: 20px;">
                        <?php echo isset($game_name) ? $game_name : (isset($game_title) ? $game_title : "Play Game"); ?>
                    </h3>
                    <span class="text-muted" style="font-size: 13px;"><i class="fa fa-clock-o"></i> Select Market & Place Bid</span>
                </div>
                <?php if($is_scraped_market && $bidding_status){ include(__DIR__ . '/include/scraped-market-form-header.php'); } elseif($default_bidding_game =='open'){?>
                <div class="row bidoptions-list tb-10">
                                <div class="col-6">
                                  <a class="dateGameIDbox">
                                      <p><?php echo date('d/m/Y');?></p>
                                  </a>
                                </div>
                                
                                <div class="col-6">
                                    <select class="dateGameIDbox" name="game_id">
                                        <option value="<?php echo $child_open;?>"> <?php echo get_gameNameById($child_open);?></option>
                                        <option value="<?php echo $child_close;?>"> <?php echo get_gameNameById($child_close);?></option>
                                    </select>
                                </div>
                                
                </div>
                
                 <?php }elseif($default_bidding_game =='close'){ ?>
                
                <div class="row bidoptions-list tb-10">
                                <div class="col-6">
                                  <a class="dateGameIDbox">
                                      <p><?php echo date('d/m/Y');?></p>
                                  </a>
                                </div>
                                
                                <div class="col-6">
                                    <select class="dateGameIDbox" name="game_id">
                                        <option value="<?php echo $child_close;?>"> <?php echo get_gameNameById($child_close);?></option>
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
                
                <h3 class="subheading">Select Amount</h3>
                <div class="row bidoptions-list tb-10">
                                <div class="col-3">
                                  <a href="#" class="bidamtbox" id="amount_5" data="5" onclick="return selectAmt(this,5)">
                                      <p><i class="fa fa-inr" aria-hidden="true"></i> 5</p>
                                  </a>
                                </div>
                                
                                <div class="col-3">
                                  <a href="#" class="bidamtbox" id="amount_10" data="10" onclick="return selectAmt(this,10)">
                                      <p><i class="fa fa-inr" aria-hidden="true"></i> 10</p>
                                  </a>
                                </div>
                                
                                <div class="col-3">
                                  <a href="#" class="bidamtbox" id="amount_50" data="50" onclick="return selectAmt(this,50)">
                                      <p><i class="fa fa-inr" aria-hidden="true"></i> 50</p>
                                  </a>
                                </div>
                                <div class="col-3">
                                  <a href="#" class="bidamtbox" id="amount_100" data="100" onclick="return selectAmt(this,100)">
                                      <p><i class="fa fa-inr" aria-hidden="true"></i> 100</p>
                                  </a>
                                </div>
                </div>
                
                
               
                
                <div class="row bidoptions-list tb-10">
                                <div class="col-3">
                                  <a href="#" class="bidamtbox" id="amount_200" data="200" onclick="return selectAmt(this,200)">
                                      <p><i class="fa fa-inr" aria-hidden="true"></i> 200</p>
                                  </a>
                                </div>
                                
                                <div class="col-3">
                                  <a href="#" class="bidamtbox" id="amount_500" data="500" onclick="return selectAmt(this,500)">
                                      <p><i class="fa fa-inr" aria-hidden="true"></i> 500</p>
                                  </a>
                                </div>
                                
                                <div class="col-3">
                                  <a href="#" class="bidamtbox" id="amount_1000" data="1000" onclick="return selectAmt(this,1000)">
                                      <p><i class="fa fa-inr" aria-hidden="true"></i> 1000</p>
                                  </a>
                                </div>
                                <div class="col-3">
                                  <a href="#" class="bidamtbox" id="amount_5000" data="5000" onclick="return selectAmt(this,5000)">
                                      <p><i class="fa fa-inr" aria-hidden="true"></i> 5000</p>
                                  </a>
                                </div>
                </div>
                
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Select Panna Digits</h3>
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 0</h3>
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $single_patti_0 = array(127, 136, 145, 190, 235, 280, 370, 389, 460, 479, 569, 578);
                    
                    foreach($single_patti_0 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="single_patti<?php echo $digit;?>" name="single_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 1</h3>
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $single_patti_1 = array(128, 137, 146, 236, 245, 290, 380, 470, 489, 560, 579, 678);
                    
                    foreach($single_patti_1 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="single_patti<?php echo $digit;?>" name="single_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 2</h3>
                
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $single_patti_2 = array(129, 138, 147, 156, 237, 246, 345, 390, 480, 570, 589, 679);
                    
                    foreach($single_patti_2 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="single_patti<?php echo $digit;?>" name="single_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 3</h3>
                
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $single_patti_3 = array(120, 139, 148, 157, 238, 247, 256, 346, 490, 580, 670, 689);
                    
                    foreach($single_patti_3 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="single_patti<?php echo $digit;?>" name="single_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 4</h3>
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $single_patti_4 = array(130, 149, 158, 167, 239, 248, 257, 347, 356, 590, 680, 789);
                    
                    foreach($single_patti_4 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="single_patti<?php echo $digit;?>" name="single_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 5</h3>
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $single_patti_5 = array(140, 159, 168, 230, 249, 258, 267, 348, 357, 456, 690, 780);
                    
                    foreach($single_patti_5 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="single_patti<?php echo $digit;?>" name="single_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 6</h3>
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $single_patti_6 = array(123, 150, 169, 178, 240, 259, 268, 349, 358, 367, 457, 790);
                    
                    foreach($single_patti_6 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="single_patti<?php echo $digit;?>" name="single_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 7</h3>
                
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $single_patti_7 = array(124, 160, 179, 250, 269, 278, 340, 359, 368, 458, 467, 890);
                    
                    foreach($single_patti_7 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="single_patti<?php echo $digit;?>" name="single_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 8</h3>
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $single_patti_8 = array(125, 134, 170, 189, 260, 279, 350, 369, 378, 459, 468, 567);
                    
                    foreach($single_patti_8 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="single_patti<?php echo $digit;?>" name="single_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 9</h3>
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $single_patti_9 = array(126, 135, 180, 234, 270, 289, 360, 379, 450, 469, 478, 568);
                    
                    foreach($single_patti_9 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="single_patti<?php echo $digit;?>" name="single_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                
                
                
                <input type="hidden" id="total_point" name="total_point" value="">
                <input type="hidden" id="selected_amount" value="">
                <?php echo app_csrf_input(); ?>
                
                <input type="hidden" name="gid" value="<?php echo $child_game_id;?>">
                <input type="hidden" name="pgid" value="<?php echo $parent_game_id;?>">
                <input type="hidden" name="dgame" value="<?php echo $default_game;?>">
                
                
                
                
                <div class="tbmar-20 text-center">
                    <p>Total Points : <a id="total_point2">0</a></p>
                </div>
                
                <div class="row bidoptions-list tb-10">
                                <div class="col-6"> 
                                  <button class="btn btn-light btn-streched" onclick = "resetjsvar();" type="reset">Reset</button>
                                </div>
                                
                                <div class="col-6">
                                <button class="btn btn-theme btn-streched" type="submit" name="single_submit">Submit</button>
                                </div>
                                
                </div>
                
                
                <?php } ?>
                
                </form>
                        
            <br><br><br><br><br><br>
            </div> 
            </div>
            
            
        </div><br><br><br>
    </div>
    
    <?php include("include/footer.php"); ?>

</body>

</html>
