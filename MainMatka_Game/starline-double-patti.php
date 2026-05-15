<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");
    include("include/session.php");
	include("include/functions.php");
	
	$game_id = intval(mysqli_real_escape_string($con, $_GET['gid']));
	
if (isset($_POST['single_submit']) && isset($_SESSION['usr_id'])!="") {
    if (!app_validate_csrf()) {
        echo "<script>window.location = 'starline-double-patti.php?invalidrequest';</script>";
        exit;
    }
	
	if(get_SettingValue('pause_starline_market_bidding')){
    	echo "<script>alert('Bidding are Stopped Temprary !!!')</script>";
        echo "<script>window.location = 'index.php';</script>";
    	exit;	
    	}
	
		$user_id= $_SESSION['usr_id'];
		
            $game_id = mysqli_real_escape_string($con, $_POST['game_id']);
            $get_parameters = "gid=$game_id";
            
            
            $date = date('Y-m-d');
    		$time = date('h:i:s A');
    		
            $game_time = get_Starlinetime($game_id);
            $date_time = $date." ".$game_time;
            $market_time = strtotime($date_time);
            
    
            if(time() >= $market_time){
                
                //echo "<script>alert('Invalid Date and Time')</script>";
                echo "<script>window.location = 'starline-double-patti.php?invalid_date&".$get_parameters."';</script>";
                
            }else{
			
                $all_double_patti = array(118,226,244,299,334,488,550,668,677,100,119,155,227,335,344,399,588,669,110,200,228,255,336,499,660,688,778,166,229,300,337,355,445,599,779,788,112,220,266,338,400,446,455,699,770,113,122,177,339,366,447,500,799,889,114,277,330,448,466,556,600,880,899,115,133,188,223,377,449,557,566,700,116,224,233,288,440,477,558,800,990,117,144,199,225,388,559,577,667,900);  
                 
                $bets = [];
    			foreach($all_double_patti as $digit)
    			{
                    $bets[(string) $digit] = $_POST['double_patti'.$digit] ?? 0;
    			}
                $placed = app_place_bets($user_id, $game_id, 'double_patti', $bets, 1);
    
                if($placed['ok']){
                    //echo "<script>alert('Bidding Successfully Submited')</script>";
                    echo "<script>window.location = 'starline-double-patti.php?bidplacedsuccessfully&".$get_parameters."';</script>";
                    exit;
                }elseif(($placed['reason'] ?? '') === 'insufficient'){
                    echo "<script>window.location = 'starline-double-patti.php?insufficientbalance&".$get_parameters."';</script>";
                    exit;
                }else{
                    //echo "<script>alert('Something Wrong! Try Again')</script>";
                    echo "<script>window.location = 'starline-double-patti.php?bidfailed&".$get_parameters."';</script>";
                    exit;
                }
            
            }
  
    
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Starling Double Patti Play Dashboard</title>
    
    <?php include("include/head.php"); ?>
</head>

<body>

    <div class="wrapper">
        
        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>
            
            
            <div class="container" >  
            <div class="card-full-page tb-10">
                
                <?php
                $games_list_qry =  "SELECT * FROM `starline` WHERE id='$game_id' and status=1";
				$games = mysqli_query($con, $games_list_qry);
                         while ($row = mysqli_fetch_array($games)){
                             
                            $startline_time = strtotime(date('Y-m-d').' '.$row['time']);
                            $game_id = $row['id'];
                            $game_name = $row['name']; 
                    ?>
                <form action="" method="POST" class="myform">
                <div class="text-center mb-4 mt-2">
                    <h3 class="font-weight-bold text-uppercase" style="color: var(--primary-color); font-size: 20px;">
                        <?php echo isset($game_name) ? $game_name : (isset($game_title) ? $game_title : "Play Game"); ?>
                    </h3>
                    <span class="text-muted" style="font-size: 13px;"><i class="fa fa-clock-o"></i> Select Market & Place Bid</span>
                </div>
                <div class="row bidoptions-list tb-10">
                                <div class="col-6">
                                  <a class="dateGameIDbox">
                                      <p><?php echo date('d/m/Y');?></p>
                                  </a>
                                </div>
                                
                                <div class="col-6">
                                    <a class="dateGameIDbox">
                                      <p><?php echo $game_name;?></p>
                                  </a>
                                </div>
                                
                </div>

                
                <?php if(time() < $startline_time){?>
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
                <h3 class="subheading">Select Digits</h3>
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 0</h3>
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $double_patti_0 = array(118,226,244,299,334,488,550,668,677);
                    
                    foreach($double_patti_0 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="double_patti<?php echo $digit;?>" name="double_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 1</h3>
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $double_patti_1 = array(100,119,155,227,335,344,399,588,669);
                    
                    foreach($double_patti_1 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="double_patti<?php echo $digit;?>" name="double_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 2</h3>
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $double_patti_2 = array(110,200,228,255,336,499,660,688,778);
                    
                    foreach($double_patti_2 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="double_patti<?php echo $digit;?>" name="double_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 3</h3>
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $double_patti_3 = array(166,229,300,337,355,445,599,779,788);
                    
                    foreach($double_patti_3 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="double_patti<?php echo $digit;?>" name="double_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 4</h3>
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                   $double_patti_4 = array(112,220,266,338,400,446,455,699,770);
                    
                    foreach($double_patti_4 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="double_patti<?php echo $digit;?>" name="double_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 5</h3>
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $double_patti_5 = array(113,122,177,339,366,447,500,799,889);
                    
                    foreach($double_patti_5 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="double_patti<?php echo $digit;?>" name="double_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 6</h3>
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $double_patti_6 = array(114,277,330,448,466,556,600,880,899);
                    
                    foreach($double_patti_6 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="double_patti<?php echo $digit;?>" name="double_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 7</h3>
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $double_patti_7 = array(115,133,188,223,377,449,557,566,700);
                    
                    foreach($double_patti_7 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="double_patti<?php echo $digit;?>" name="double_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 8</h3>
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $double_patti_8 = array(116,224,233,288,440,477,558,800,990);
                    
                    foreach($double_patti_8 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="double_patti<?php echo $digit;?>" name="double_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                
                <div class="tb-10"><hr class="devider"></div>
                <h3 class="subheading">Panna of ank 9</h3>
                <div class="row bidoptions-list tb-10">
                    
                    <?php 
                    $double_patti_9 = array(117,144,199,225,388,559,577,667,900);
                    
                    foreach($double_patti_9 as $digit){?>
                        
                        <div class="col-3">
                                    <div class="bidinputdiv">
                                        <lable><?php echo $digit;?></lable>
                                        <input type="text" value="" class="pointinputbox" onclick="clickPanna(this)" id="double_patti<?php echo $digit;?>" name="double_patti<?php echo $digit;?>" readonly>
                                    </div>
                        </div>
                                
                    <?php } ?>

                </div>
                
                
                <input type="hidden" id="total_point" name="total_point" value="">
                <input type="hidden" id="selected_amount" value="">
                <?php echo app_csrf_input(); ?>
                
                <input type="hidden" name="game_id" value="<?php echo $game_id;?>">
                
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
                
                <?php }else{ ?>
                <div class="tbmar-40 text-center">
                    <span>Sorry! Bidding is Close for <?php echo $game_name;?>. <br> Play for Next One.</span>
                </div>
                <?php } ?>
                
                </form>
                <?php } ?>
                        
            <br><br><br><br><br><br>
            </div>
            </div>
            
            
        </div>
    </div>
    
    <?php include("include/footer.php"); ?>

</body>

</html>
