<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");
    include("include/session.php");
	include("include/functions.php");
	
if (isset($_POST['update_bank_details']) && isset($_SESSION['usr_id'])!="") {
            if (!app_validate_csrf()) {
                echo "<script>window.location = 'update-bank-details.php?invalidrequest';</script>";
                exit;
            }

	        $user_id = (int) $_SESSION['usr_id'];
			
            $stmt21 = mysqli_prepare($con, "SELECT account_number FROM users WHERE id = ? LIMIT 1");
            mysqli_stmt_bind_param($stmt21, 'i', $user_id);
            mysqli_stmt_execute($stmt21);
			$result21 = mysqli_stmt_get_result($stmt21);

				if($row21 = mysqli_fetch_array($result21)){
					if($row21['account_number'] =='' || $row21['account_number'] ==NULL)
					{
						//do nothing
					}else{
						
						echo "<script>alert('Contact Admin to Update Details');</script>";
						echo "<script>window.location = 'update-bank-details.php?invalidrequest';</script>";
						exit;
					}
				}
				
				
				
			
            $account_number = mysqli_real_escape_string($con, $_POST['account_number']);
            $ifsc = mysqli_real_escape_string($con, $_POST['ifsc']);
            $bank_name = mysqli_real_escape_string($con, $_POST['bank_name']);
            $account_holder_name = mysqli_real_escape_string($con, $_POST['account_holder_name']);
			
			$account_number = htmlspecialchars($account_number, ENT_QUOTES, 'UTF-8');
			$ifsc = htmlspecialchars($ifsc, ENT_QUOTES, 'UTF-8');
			$bank_name = htmlspecialchars($bank_name, ENT_QUOTES, 'UTF-8');
			$account_holder_name = htmlspecialchars($account_holder_name, ENT_QUOTES, 'UTF-8');

           $stmt22 = mysqli_prepare($con, "SELECT id FROM users WHERE account_number = ? AND id != ? LIMIT 1");
           mysqli_stmt_bind_param($stmt22, 'si', $account_number, $user_id);
           mysqli_stmt_execute($stmt22);
           $result22 = mysqli_stmt_get_result($stmt22);
			if($row22 = mysqli_fetch_array($result22)){
				echo "<script>alert('Account Number Already Exist.');</script>";
				echo "<script>window.location = 'update-bank-details.php?invalidrequest';</script>";
				exit;
			
			}
			
            $stmt = mysqli_prepare($con, "UPDATE users SET account_number = ?, ifsc = ?, bank_name = ?, account_holder_name = ? WHERE id = ? LIMIT 1");
            mysqli_stmt_bind_param($stmt, 'ssssi', $account_number, $ifsc, $bank_name, $account_holder_name, $user_id);
		    $res = mysqli_stmt_execute($stmt);
            
            echo "<script>window.location = 'update-bank-details.php?detailupdated';</script>";
  
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Bank Details - <?php echo $site_title;?></title>
    
    <?php include("include/head.php"); ?>
</head>

<body class="page-bank-details">

    <div class="wrapper">
        
        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>
            
            <div class="container bank-details-page py-3">
            <div class="bank-details-card">
                <?php 
                $user_id = $_SESSION['usr_id'];
                $qry =  "SELECT * FROM users where id='".$user_id."'";
                $user_info = mysqli_query($con, $qry);
                while($row = mysqli_fetch_array($user_info)){
                $account_holder_name = $row['account_holder_name'];
                $account_number = $row['account_number'];
                $ifsc = $row['ifsc'];
                $bank_name = $row['bank_name'];
                }
                ?>
                <div class="bank-details-page__head">
                    <div class="bank-details-page__head-icon">
                        <img src="assets/icons/bank.png" width="28" height="28" alt="" decoding="async">
                    </div>
                    <div class="bank-details-page__head-text">
                        <h3 class="font-weight-bold">Bank Details</h3>
                        <span class="text-muted">Update your withdrawal info</span>
                    </div>
                </div>
                
                 <form action="" method="POST" autocomplete="off" class="mt-2">
                  <div class="form-group mb-3">
                    <label for="account_holder_name" class="text-secondary font-weight-bold">A/c Holder Name</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0"><i class="fa fa-user text-muted"></i></span>
                        </div>
                        <input type="text" class="form-control border-left-0 pl-0 bg-light" name="account_holder_name" value="<?php echo $account_holder_name;?>" maxlength="50" minlength="4" placeholder="Beneficiary name" id="account_holder_name" autocomplete="off" required>
                    </div>
                  </div>
                  
                  <div class="form-group mb-3">
                    <label for="account_number" class="text-secondary font-weight-bold">Bank Account Number</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0"><i class="fa fa-hashtag text-muted"></i></span>
                        </div>
                        <input type="text" class="form-control border-left-0 pl-0 bg-light" name="account_number" value="<?php echo $account_number;?>" maxlength="25" minlength="4" placeholder="950000124587" id="account_number" autocomplete="off" required>
                    </div>
                  </div>
                  
                  <div class="form-group mb-3">
                    <label for="ifsc" class="text-secondary font-weight-bold">IFSC Code</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0"><i class="fa fa-code text-muted"></i></span>
                        </div>
                        <input type="text" class="form-control border-left-0 pl-0 bg-light" name="ifsc" value="<?php echo $ifsc;?>" maxlength="11" minlength="11" placeholder="HDFC0000139" id="ifsc" autocomplete="off" required>
                    </div>
                  </div>
                  
                  <div class="form-group mb-4">
                    <label for="bank_name" class="text-secondary font-weight-bold">Bank Name</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0"><i class="fa fa-building text-muted"></i></span>
                        </div>
                        <input type="text" class="form-control border-left-0 pl-0 bg-light" name="bank_name" value="<?php echo $bank_name;?>" maxlength="25" minlength="3" placeholder="HDFC/SBI" id="bank_name" autocomplete="off" required>
                    </div>
                  </div>
                  
                  <?php echo app_csrf_input(); ?>
                  <?php 
                  if($account_holder_name =='' || $account_holder_name ==NULL){
                  ?>        
                  <button type="submit" name="update_bank_details" class="btn btn-theme py-3 font-weight-bold w-100 mb-3">Save Details <i class="fa fa-save ml-2"></i></button>
                  <?php }else{?>
                  <div class="alert alert-success text-center"><i class="fa fa-check-circle"></i> Bank Details Verified & Locked.</div>
                  <button type="button" name="bank_details" class="btn btn-secondary py-3 font-weight-bold w-100 mb-3" disabled>Already Updated</button>
                  <?php } ?>
                </form> 
                
                <div class="text-center mt-2">
                    <p class="text-muted mb-2">Need to change verified details?</p>
                    <a href="https://wa.me/<?php echo get_SettingValue('PWA_whatsapp2');?>" class="btn btn-outline w-100 font-weight-bold"><i class="fa fa-whatsapp text-success"></i> Contact Admin</a>
                </div>

            </div>
            </div>
            
        </div>
    </div>
    
    <?php include("include/footer.php"); ?>

</body>

</html>
