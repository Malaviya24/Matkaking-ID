<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");
	include("include/functions.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>404 - <?php echo $site_title;?></title> 
    
    <?php include("include/head.php"); ?>
</head>

<body>

    <div class="wrapper">
        
        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>
            
            
            <div class="container" >  
            <div class="card tbmr-40">
                
                <img src="assets/img/logo-fill.png" style="min-height: 100px;height: 100px;margin: auto;margin-bottom: auto;border-radius: 10px;margin-bottom: 10px;" >
                
                <div class="text-center tb-10">
                    <h3>404</h3>
                    <span>Page Not Found</span>
                    <br><br><br>
                    
                    <a href="index.php" type="submit" class="btn btn-theme btn-login" name="login">Back to Home</a>
                </div>

                  


            </div>
            </div>
            
            
        </div>
    </div>
    
    <?php include("include/footer.php"); ?>

</body>

</html>