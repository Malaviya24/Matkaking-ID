<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");
include("include/functions.php");
app_restore_session_from_cookies();
	
	
	if(isset($_SESSION['usr_id'])!=""){
	echo "<script>window.location = 'index.php';</script>";
	exit;
    }

if(isset($_POST['login'])){
    if (!app_validate_csrf()) {
        echo "<script>window.location = 'login.php?invalidrequest';</script>";
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
			
			
	$return_url = (string) ($_POST['return_url'] ?? '');
	$mobileInput = preg_replace('/\D+/', '', (string) ($_POST['mobile'] ?? ''));
	$password = (string) ($_POST['password'] ?? '');
	$mobile = '+91'.$mobileInput;

	$stmt = mysqli_prepare($con, "SELECT * FROM users WHERE mobile = ? LIMIT 1");
	mysqli_stmt_bind_param($stmt, 's', $mobile);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

	if (($row = mysqli_fetch_array($result)) && app_password_verify($password, $row['password'] ?? '')){
	    
	    if($row['status'] ==0){
	        echo '<center><br><br><br><br>Contact admin</center>';
			exit;
				
	    }else{
			
        if (app_password_needs_rehash($row['password'] ?? '')) {
            $newHash = app_password_hash($password);
            $update = mysqli_prepare($con, "UPDATE users SET password = ? WHERE id = ? LIMIT 1");
            mysqli_stmt_bind_param($update, 'si', $newHash, $row['id']);
            mysqli_stmt_execute($update);
        }

		app_login_user($row);
    		if($return_url =='')
    		{
    		    echo "<script>window.location = 'index.php';</script>";
    		    exit;
    		}else{
    		    echo "<script>window.location = '".htmlspecialchars(app_safe_return_path($return_url), ENT_QUOTES, 'UTF-8')."';</script>";
    		    exit;
    		}
	    }
	}else{
		$show_error_msg = 1;
		echo "<script>alert('Invalid Mobile No or Password')</script>";
        echo "<script>window.location = 'login.php?success_alert1';</script>";
	}

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Login - <?php echo $site_title;?></title>
    <meta name="description" content="login and access play option for satta matka online and win big money, india's largest and trusted satta matka play application, Fastest withdrawal and full rate">
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
                    
                    <form action="" method="POST">
                      <?php echo app_csrf_input(); ?>
                      <div class="form-group mb-3">
                        <label for="mobile" class="auth-label">Mobile Number</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-phone" ></i></span>
                            </div>
                            <input type="text" class="form-control" name="mobile" maxlength="10" minlength="10" placeholder="Enter 10 Digit Phone Number" id="mobile" autocomplete="off" required>
                        </div>
                      </div>
                      
                      <div class="form-group mb-2">
                        <label for="pwd" class="auth-label">Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" name="password" placeholder="••••••••" id="pwd" autocomplete="off" required>
                        </div>
                      </div>
                      
                      <!-- <div class="auth-row">
                        <a class="auth-link" href="javascript:void(0)">Forgot password?</a>
                      </div> -->
                      
                      <button type="submit" class="btn auth-btn w-100" name="login">Login</button>
                      <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($_GET['return_url'] ?? '', ENT_QUOTES, 'UTF-8');?>">
                    </form> 
                    
                    <div class="auth-foot">
                        Don't Have An Account? <a href="register.php">Signup</a>
                    </div>
                </div>
            </div>
            
            
        </div>
    </div>
    
    <?php include("include/footer.php"); ?>

</body>

</html>
