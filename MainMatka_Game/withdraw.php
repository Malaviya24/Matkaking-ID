<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");
    include("include/session.php");
	include("include/functions.php");


if (isset($_POST['withdraw']) && isset($_SESSION['usr_id'])!="") {
    if (!app_validate_csrf()) {
        echo "<script>window.location = 'withdraw.php?invalidrequest';</script>";
        exit;
    }
	

		$user_id= (int) $_SESSION['usr_id'];
            $amount = app_money_value($_POST['amount'] ?? 0);
            $date = date('Y-m-d');
    		$time = date('h:i:s A');

            if((int) date('w') === 0){
                echo "<script>window.location = 'withdraw.php?invalidrequest';</script>";
            }elseif($amount < 1000 || $amount > 10000){
                echo "<script>window.location = 'withdraw.php?invalidrequest';</script>";
            }else{
                mysqli_begin_transaction($con);
                $stmt = mysqli_prepare($con, "SELECT balance, account_number FROM users WHERE id = ? LIMIT 1 FOR UPDATE");
                mysqli_stmt_bind_param($stmt, 'i', $user_id);
                mysqli_stmt_execute($stmt);
                $userResult = mysqli_stmt_get_result($stmt);
                $userRow = $userResult ? mysqli_fetch_assoc($userResult) : null;

                $withdrawable = app_user_withdrawable_amount($user_id);

                if (!$userRow || empty($userRow['account_number'])) {
                    mysqli_rollback($con);
                    echo "<script>window.location = 'withdraw.php?invalidrequest';</script>";
                    exit;
                }

                $balance = app_money_value($userRow['balance']);
                if ($amount > $balance || $amount > $withdrawable) {
                    mysqli_rollback($con);
                    echo "<script>window.location = 'withdraw.php?insufficientbalance';</script>";
                    exit;
                }

                $new_balance = round($balance - $amount, 2);
                $update = mysqli_prepare($con, "UPDATE users SET balance = ?, total_withdrawal = (COALESCE(total_withdrawal, 0) + ?) WHERE id = ? LIMIT 1");
                mysqli_stmt_bind_param($update, 'ddi', $new_balance, $amount, $user_id);
                $updated = mysqli_stmt_execute($update);

                $insert = mysqli_prepare($con, "INSERT INTO user_transaction(user_id,game_id,game_type,digit,date,time,amount,type,debit_credit,balance,status,api_response) VALUES(?,'','withdraw','',?,?,?,'withdraw','debit',?,1,'from Website')");
                mysqli_stmt_bind_param($insert, 'issdd', $user_id, $date, $time, $amount, $new_balance);
                $res = $updated && mysqli_stmt_execute($insert);
    
                if($res){
                mysqli_commit($con);
                echo "<script>window.location = 'withdraw.php?detailupdated';</script>";
                }else{
                    mysqli_rollback($con);
                    echo "<script>window.location = 'withdraw.php?notupdated';</script>";
                }
            }
    mysqli_close($con);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Withdraw Fund - <?php echo $site_title;?></title>
    
    <?php include("include/head.php"); ?>
</head>

<body class="page-withdraw">

    <div class="wrapper">
        
        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>
            
            <div class="container">
            <div class="text-center tb-10">
                    <h3 class="gdash3">Withdraw Fund</h3>
                    <span style="font-size:12px;">Instant transfer to your bank</span>
            </div>
            <div class="tb-10">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                <?php 
                $user_id = $_SESSION['usr_id'];
                $qry =  "SELECT * FROM users where id='".$user_id."'";
                $user_info = mysqli_query($con, $qry);
                while($row = mysqli_fetch_array($user_info)){
                $account_holder_name = $row['account_holder_name'];
                $account_number = $row['account_number'];
                $ifsc = $row['ifsc'];
                $bank_name = $row['bank_name'];
				
					if($row['status'] ==0){
							// User is blocked, log them out
							session_unset();
							session_destroy();
							app_clear_auth_cookies();

							// Redirect to the login page
							$return_url = "logout.php";
							header("Location: " . $return_url);
							exit;
					}
                }
                $current_balance = get_lastBalance($user_id);
                $withdrawable_balance = min($current_balance, app_user_withdrawable_amount($user_id));
                ?>
                        <div class="withdraw-total-summary text-center mb-3 pb-3 border-bottom">
                            <small class="text-muted d-block mb-1">Available for Withdraw</small>
                            <div class="withdraw-total-amt">₹ <?php echo app_format_money($withdrawable_balance); ?></div>
                        </div>
                        <div class="text-center mb-3">
                            <div class="withdraw-page-icon">
                                <i class="fa fa-university"></i>
                            </div>
                        </div>
                
                <div class="alert alert-warning text-center withdraw-alert-info">
                    <i class="fa fa-clock-o"></i> Turnaround 24 hrs. Safely credited to your account.
                    <br><strong class="text-danger mt-1 d-block">Sunday withdrawals are off.</strong>
                </div>
                
                 <form action="" method="POST" autocomplete="off" class="withdraw-main-form">
                  
                  <?php if($account_number =='' || $account_number == NULL){?>
                   <div class="alert alert-danger text-center withdraw-alert-bank">
                       <i class="fa fa-exclamation-triangle"></i> Kindly update your bank details first to withdraw amount.
                   </div>
                   <a href="update-bank-details.php" class="btn btn-outline-danger w-100 font-weight-bold withdraw-btn-outline">Update Bank Details</a>
                  <?php }else{ ?>
                  <div class="form-group mb-4">
                    <label class="font-weight-bold text-secondary text-center w-100 withdraw-label-amt">Withdrawal Amount (<i class="fa fa-inr"></i>)</label>
                    <input type="number" class="form-control text-center withdraw-input-amt" name="amount" value="" min="1000" max="10000" placeholder="Minimum ₹1000" autocomplete="off" required>
                  </div>
                 
                  
                  <?php echo app_csrf_input(); ?>
                  <button type="submit" name="withdraw" class="btn btn-theme py-3 font-weight-bold w-100 mb-3 withdraw-submit-btn">Withdraw Funds <i class="fa fa-check ml-2"></i></button>
                  <?php } ?>
                </form> 
                
                <div class="text-center withdraw-support">
                    <p class="text-muted mb-2 withdraw-help-text">Having Issues?</p>
                    <a href="https://wa.me/<?php echo get_SettingValue('PWA_whatsapp2');?>" class="btn btn-outline w-100 font-weight-bold withdraw-btn-wa"><i class="fa fa-whatsapp text-success"></i> Contact Admin</a>
                </div>

                    </div>
                </div>
            </div>
            </div>
      
            
        </div>
    </div>
    
    <?php include("include/footer.php"); ?>

</body>

</html>
