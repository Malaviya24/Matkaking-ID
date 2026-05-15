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

    <title>Fund History - <?php echo $site_title;?></title>
    
    <?php include("include/head.php"); ?>
</head>

<body class="page-fund-history">

    <div class="wrapper">
        
        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>
            
            <div class="container" > 
            
            <div class="text-center tb-10">
                    <h3 class="gdash3">Fund History</h3>
                    <span style="font-size:12px;">Deposit and Withdraw History</span>
            </div>
            <div class="tb-10">
            <?php
			
			$limit = 10;
            $offset = ($page-1)*$limit;
            $user_id = $_SESSION['usr_id'];
			
			// Count of all records
            $query   = "SELECT COUNT(id) as rowNum FROM user_transaction where user_id='".$user_id."' and (type='deposit' or type='deposit_request' or type='withdraw')";
            $res  = mysqli_query($con,$query); 
            $res1 = mysqli_fetch_assoc($res);
            $allRecrods= $res1['rowNum'];
            $totoalPages = ceil($allRecrods / $limit);
			

             $qry = "SELECT * from user_transaction WHERE (type='deposit' or type='deposit_request' or type='withdraw') and user_id='".$user_id."' order by id DESC LIMIT $offset,$limit";
             $result = mysqli_query($con,$qry);
             $data["records"]=array();
             if(mysqli_num_rows($result)>0){
                 
                 while ($row = mysqli_fetch_array($result)){
                     
		            
                    $narration = 'Transaction';
                    $status = 'Success';

                    if(($row['type'] =='deposit' || $row['type'] =='deposit_request') && ($row['title'] =='manual_deposit' || $row['title'] =='manual_deposit_rejected')){
                    $narration = 'Manual UPI Deposit';
                        if($row['status'] ==1)
                         {$status = 'Pending';
                         }elseif($row['status'] ==2)
                         {$status = 'Approved';
                         }else{$status = 'Rejected';}
                    }elseif($row['type'] =='deposit' && $row['title'] =='Debited By Admin'){
                    $narration = 'Credited By Admin';
                    $status = 'Success';
                    }elseif($row['type'] =='deposit' && $row['title'] =='upi'){
                    $narration = 'Credited By UPI';
                    $status = 'Success';
                    }elseif($row['type'] =='deposit' && $row['title'] ==''){
                    $narration = 'Credited By Other';
                    $status = 'Success';
                    }
		            
		            if($row['type'] =='withdraw'){
		            $narration = 'Withdraw';
		                if($row['status'] ==1)
                         {$status = 'Pending';
                         }elseif($row['status'] ==2)
                         {$status = 'Approved';
                         }else{$status = 'Canceled';}
    		        }
		            
					
             
                $data_item=array(
                    "id" => $row['id'],
                    "narration" => $narration,
                    "trans_type" => ucfirst($row['debit_credit']),
                    "amount" => number_format($row['amount'],2)
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
                                    <h6 class="mb-0 font-weight-bold" style="color: #e53e3e; font-size: 15px;">- ₹<?php echo number_format($row['amount'],2);?></h6>
                                <?php }else{ ?>
                                    <h6 class="mb-0 font-weight-bold" style="color: #38a169; font-size: 15px;">+ ₹<?php echo number_format($row['amount'],2);?></h6>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2 mt-2">
                            <span class="text-muted" style="font-size: 12px;">Ref No: #<?php echo $row['id'];?></span>
                            <?php if($status == 'Pending'){ ?>
                                <span class="font-weight-bold text-warning" style="font-size: 12px;"><i class="fa fa-clock-o"></i> <?php echo $status;?></span>
                            <?php }elseif($status == 'Approved' || $status == 'Success'){ ?>
                                <span class="font-weight-bold text-success" style="font-size: 12px;"><i class="fa fa-check-circle"></i> <?php echo $status;?></span>
                            <?php }else{ ?>
                                <span class="font-weight-bold text-danger" style="font-size: 12px;"><i class="fa fa-times-circle"></i> <?php echo $status;?></span>
                            <?php } ?>
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
