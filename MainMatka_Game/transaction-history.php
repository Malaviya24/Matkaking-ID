<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");
    include("include/session.php");
	include("include/functions.php");


if($_GET['page'] >0)
    {
        $page = mysqli_real_escape_string($con, $_GET['page']);
    }else{
        $page =1;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Transaction History - <?php echo $site_title;?></title>
    
    <?php include("include/head.php"); ?>
</head>

<body class="page-transaction-history">

    <div class="wrapper">
        
        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>
            
            <div class="container" > 
            
            <div class="text-center tb-10">
                    <h3 class="gdash3">Transaction History</h3>
                    <span style="font-size:12px;">Passbook View Transaction History</span>
            </div>
            <div class="tb-10">
            <?php
            
            $limit = 10;
            $offset = ($page-1)*$limit;
            $user_id = $_SESSION['usr_id'];
            // Count of all records
            $query   = "SELECT COUNT(id) as rowNum FROM user_transaction where user_id='".$user_id."'";
            $res  = mysqli_query($con,$query); 
            $res1 = mysqli_fetch_assoc($res);
            $allRecrods= $res1['rowNum'];
            $totoalPages = ceil($allRecrods / $limit);
            
            
            
             //$qry = "SELECT user_transaction.*, games.name as game_name FROM user_transaction INNER JOIN games ON user_transaction.game_id = games.id WHERE user_id='".$user_id."' order by id DESC LIMIT $offset,$limit";
             
             $qry = "SELECT ut.*, 
                       IF(ut.starline = 0, g.name, g2.name) AS game_name
                        FROM user_transaction ut
                        LEFT JOIN games g ON ut.game_id = g.id AND ut.starline = 0
                        LEFT JOIN starline g2 ON ut.game_id = g2.id AND ut.starline = 1
                        WHERE ut.user_id='".$user_id."' order by ut.id DESC LIMIT $offset,$limit";
             $result = mysqli_query($con,$qry);
             $data["records"]=array();
             if(mysqli_num_rows($result)>0){
                 
                 while ($row = mysqli_fetch_array($result)){
                     
		        if($row['type'] =='bid'){
		                if($row['starline']==1){
		                    $narration = 'STARLINE ('.get_Starlinetime($row['game_id']).')';
		                }elseif($row['starline']==2){
		                $narration =  "MJ ".get_Jackpottime($row['game_id']);
		                }else{
		                $narration = 'Bid - '.get_gameNameById($row['game_id']).' - '.$row['game_type'];
		                }
		            }
		            
                if(($row['type'] =='deposit' || $row['type'] =='deposit_request') && ($row['title'] =='manual_deposit' || $row['title'] =='manual_deposit_rejected')){
                    if($row['status'] ==1){
                        $narration = 'Manual UPI Deposit Pending';
                    }elseif($row['status'] ==2){
                        $narration = 'Manual UPI Deposit Approved';
                    }else{
                        $narration = 'Manual UPI Deposit Rejected';
                    }
                    }
		            
		            if($row['type'] =='deposit' && $row['title'] =='Debited By Admin'){
                    $narration = 'Debited By Admin';
                    }
                    		            
                    if($row['type'] =='deposit' && $row['title'] =='upi'){
                    $narration = 'Credited By UPI';
                    }
                    		            
                    if($row['type'] =='deposit' && $row['title'] ==''){
                    $narration = 'Credited By Admin';
                    }
		            
		            if($row['type'] =='withdraw'){
		            $narration = 'Withdraw';
		            }
		            
		            if($row['type'] =='withdraw_rejected'){
		            $narration = 'Withdraw Rejected By Admin';
		            }
					
					if($row['game_type'] =='withdraw_cancelled'){ 
		            $narration = 'Withdraw Cancelled By User';
		            }
					
					
		            
		            if($row['type'] =='win'){
		                if($row['starline']==1){
		                    $narration = 'STARLINE ('.get_Starlinetime($row['game_id']).')';
		                }else{
		                $narration = 'Win - '.get_gameNameById($row['game_id']).' - '.$row['game_type'];
		                }
		            }
		            
		            if($row['type'] =='revert'){
		            $narration = 'Revert Back ';
		            }
		            
             
                $data_item=array(
                    "id" => $row['id'],
                    "narration" => $narration,
                    "digit" => $row['digit'],
                    "game_type" => $row['game_type'],
					"digit_type" => $row['game_type'],
                    "trans_type" => ucfirst($row['debit_credit']),
                    "amount" => number_format($row['amount'],2),
                    "result" => $game_result,
                    "bid_time" => date('d/m/Y h:i A',strtotime($row['timestamp'])),
                    "bid_on" => date('d/m/Y',strtotime($row['timestamp'])).'('.date('l', strtotime($row['timestamp'])).')',
                    "play_for" => date('d/m/Y',strtotime($row['date'])).'('.date('l', strtotime($row['date'])).')'
                );
                array_push($data["records"], $data_item);
                
                
                ?>
                <div class="card shadow-sm mb-3 border-0 transition" style="border-radius: 16px;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <?php if($row['debit_credit']=='debit'){ ?>
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #fff5f5; color: #e53e3e; display: flex; align-items: center; justify-content: center; font-size: 16px; margin-right: 12px; flex-shrink: 0;">
                                        <i class="fa fa-arrow-up"></i>
                                    </div>
                                <?php }else{ ?>
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #e6fffa; color: #38a169; display: flex; align-items: center; justify-content: center; font-size: 16px; margin-right: 12px; flex-shrink: 0;">
                                        <i class="fa fa-arrow-down"></i>
                                    </div>
                                <?php } ?>
                                <div>
                                    <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 14px; line-height: 1.2; padding-bottom: 2px;"><?php echo $narration;?></h6>
                                    <small class="text-muted" style="font-size: 11px;"><i class="fa fa-clock-o"></i> <?php echo date('d M Y, h:i A',strtotime($row['timestamp']));?></small>
                                </div>
                            </div>
                            <div class="text-right ml-2">
                                <?php if($row['debit_credit']=='debit'){ ?>
                                    <h6 class="mb-0 font-weight-bold" style="color: #e53e3e; font-size: 15px;">- ₹<?php echo app_format_money($row['amount']);?></h6>
                                <?php }else{ ?>
                                    <h6 class="mb-0 font-weight-bold" style="color: #38a169; font-size: 15px;">+ ₹<?php echo app_format_money($row['amount']);?></h6>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2 mt-2">
                            <span class="text-muted" style="font-size: 12px;">Ref No: #<?php echo $row['id'];?></span>
                            <span class="font-weight-bold text-secondary" style="font-size: 12px;">Closing Bal: ₹<?php echo $row['balance'];?></span>
                        </div>
                    </div>
                </div>
                    
                    
            <?php  } ?>
            
            <?php if($page == 1){?>
            <a href="?page=<?php echo $page-1;?>" class="btn btn-theme disabled" style="float: left;"><< Previous</a> 
            <?php }else{?> 
            <a href="?page=<?php echo $page-1;?>" class="btn btn-theme" style="float: left;"><< Previous</a> 
            <?php } ?>
           
            
            <?php if($page == $totoalPages){?>
            <a href="?page=<?php echo $page+1;?>" class="btn btn-theme disabled" style="float: right;">Next >></a>
            <?php }else{?>
            
            <a href="?page=<?php echo $page+1;?>" class="btn btn-theme" style="float: right;">Next >></a>
            <?php } ?>
            
            <br><br>
            <?php }else{?>
             
                <div class="tbmar-40 text-center">
                    <p>No Record Found.</p>
                </div>
                
             <?php } ?>
         
            </div>
            
            <br><br><br>
            </div>
      
            
        </div>
    </div>
    
    <?php include("include/footer.php"); ?>

</body>

</html>
