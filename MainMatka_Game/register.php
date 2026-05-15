<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");
include("include/functions.php");
app_restore_session_from_cookies();
	
	
	if(isset($_SESSION['usr_id'])!=""){
	echo "<script>window.location = 'index.php';</script>";
	exit;
    }
    
    if (isset($_POST['signup'])) {
        if (!app_validate_csrf()) {
            echo "<script>window.location = 'register.php?invalidrequest';</script>";
            exit;
        }
		
		
		if (0) {
    echo "<script>alert('Opps... Kindly contact Admin')</script>";
    echo "<script>window.location = 'register.php';</script>";
    exit;
  }
		// Allow 5 attempts per minute
		$timeFrame = 60;
		$maxAttempts =5;
		
			$ip = $_SERVER['REMOTE_ADDR'];
			$key = 'rate_limit_' . $ip;

			if (!isset($_SESSION[$key])) {
				$_SESSION[$key] = ['attempts' => 1, 'time' => time()];
			} else {
				$elapsedTime = time() - $_SESSION[$key]['time'];

				if ($elapsedTime < $timeFrame) {
					$_SESSION[$key]['attempts']++;

					if ($_SESSION[$key]['attempts'] > $maxAttempts) {
						// Implement your action, like blocking the IP or introducing a delay.
						echo 'Invalid Activity Found';
						exit;
					}
				} else {
					$_SESSION[$key] = ['attempts' => 1, 'time' => time()];
				}
			}
			
			
           
            $name = mysqli_real_escape_string($con, $_POST['name']);
			$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
			
            $username = mysqli_real_escape_string($con, $_POST['username']);
			$username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
			
            $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
			$mobile = htmlspecialchars($mobile, ENT_QUOTES, 'UTF-8');
			$mobile = '+91'.$mobile;
			
            $password = (string) ($_POST['password'] ?? '');
			$password_hash = app_password_hash($password);
			
            $confirmpassword = (string) ($_POST['confirmpassword'] ?? '');


			// Allow only letters and spaces, and require at least 2 characters
    if (!preg_match('/^[a-zA-Z ]{2,}$/', $name)) {
        echo "<script>alert('Invalid name. Only letters and spaces allowed')</script>";
        echo "<script>window.location = 'register.php';</script>";
        exit;
    }
	
			
			if (preg_match('/^\d{10}$/', $_POST['mobile'])) {
				//Valid mobile number!
			} else {
				echo "<script>alert('Invalid mobile number. Please enter a 10-digit number')</script>";
				echo "<script>window.location = 'register.php';</script>";
				exit;
			}


            
            if($password != $confirmpassword)
            {
                echo "<script>alert('Password Does not match')</script>";
				echo "<script>window.location = 'register.php';</script>";
				exit;
                
            }
            
			$refer_by = '';
			$api_access_token = app_random_token();
            
            $date_created = date("Y-m-d");
            $username_check = 0;
			$phone_check = 0;
            $err_msg ='';

			$sql = "SELECT * FROM users WHERE mobile='$mobile'";
            $result = mysqli_query($con, $sql);
            $phone_check = mysqli_num_rows($result);
            
			
			if($phone_check >0)
            {
                echo "<script>alert('Mobile Number Already Exist.')</script>";
				echo "<script>window.location = 'register.php';</script>";
				exit;
                
            }else{$phone_check =0; }
            	  
                
                $refer_id = strtoupper(substr($username,0,2)).random_int(1000,9999);
                $sql = "INSERT INTO users(name,username,mobile,password,status,date_created,role,refer_id,refer_by,package_name,api_access_token)
                VALUES('$name','$username', '$mobile','$password_hash','1','$date_created','user','$refer_id','$refer_by','website','$api_access_token')";
                $res = mysqli_query($con, $sql);
                //echo mysqli_error($con);
                $last_id = mysqli_insert_id($con);
				
                if($last_id !='' && $last_id > 0){
				$result = mysqli_query($con, "SELECT * FROM users WHERE mobile = '" . $mobile. "' and password = '" . $password_hash . "'");

				if ($row = mysqli_fetch_array($result)){
					
					if($row['status'] ==0){
						echo '<center><br><br><br><br>Contact admin</center>';
						exit;
							
					}else{
						
					app_login_user($row);
					}
				}


                echo "<script>window.location = 'index.php?success_alert1';</script>";
                }else{
                    echo "<script>alert('Sorry! Try Again')</script>";
				    echo "<script>window.location = 'register.php';</script>";
				    exit;
                }
            

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Signup - <?php echo $site_title;?></title>
    <meta name="description" content="Create account by register with online matka play. india's largest and trusted satta matka play application, Fastest withdrawal and full rate">
    <?php include("include/head.php"); ?>
</head>

<body class="auth-screen">

    <div class="wrapper">
        
        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>
            
            <div class="auth-wrap">
            <div class="auth-panel">
                <div class="auth-head">
                    <!-- <img src="assets/img/logo-fill.png" class="auth-logo" alt="Logo"> -->
                    <p class="auth-kicker">Welcome to</p>
                    <h3 class="auth-brand">Online <span>Matka</span></h3>
                </div>
                
                 <form action="" method="POST" autocomplete="off">
                  <?php echo app_csrf_input(); ?>
                  <div class="form-group mb-3">
                    <label for="name" class="auth-label">Full Name</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" name="name" maxlength="50" minlength="4" placeholder="Full name" id="name" autocomplete="off" required>
                    </div>
                  </div>
                  
                  <div class="form-group mb-3">
                    <label for="username" class="auth-label">Username</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-id-badge"></i></span>
                        </div>
                        <input type="text" class="form-control" name="username" maxlength="25" minlength="4" placeholder="username" id="username" autocomplete="off" required>
                    </div>
                  </div>
                  
                  <div class="form-group mb-3">
                    <label for="mobile" class="auth-label">Mobile Number</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-phone"></i></span>
                        </div>
                        <input type="text" class="form-control" name="mobile" maxlength="10" minlength="10" placeholder="10 digit number" id="mobile" autocomplete="off" required>
                    </div>
                  </div>
                  
                  <div class="form-group mb-3">
                    <label for="pwd" class="auth-label">Password</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        </div>
                        <input type="password" class="form-control" name="password" placeholder="••••••••" id="pwd" autocomplete="off" required>
                    </div>
                  </div>
                  
                  <div class="form-group mb-4">
                    <label for="cnpwd" class="auth-label">Confirm Password</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-check-circle"></i></span>
                        </div>
                        <input type="password" class="form-control" name="confirmpassword" placeholder="••••••••" id="cnpwd" autocomplete="off" required>
                    </div>
                  </div>
                  
                  <button type="submit" name="signup" class="btn auth-btn w-100">Signup</button>
                </form> 
                
                <div class="auth-foot">
                    Already have an account? <a href="login.php">Login</a>
                </div>

            </div>
            </div>
            
            
        </div>
    </div>
    
    <?php include("include/footer.php"); ?>

</body>

</html>
