<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");
    include("include/session.php");
	include("include/functions.php");
	

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Notifications - <?php echo $site_title;?></title>
    <meta name="description" content="Latest Notification list of <?php echo $site_title;?>">
    <?php include("include/head.php"); ?>
</head>

<body>

    <div class="wrapper">
        
        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>
            
            <div class="container" > 
            <div class="text-center tb-10 mt-3 mb-4">
                <div style="width: 60px; height: 60px; background: #ebf4ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                    <i class="fa fa-bell" style="font-size: 28px; color: var(--primary-color);"></i>
                </div>
                <h3 class="font-weight-bold" style="color: var(--primary-color);">Notifications</h3>
                <span class="text-muted" style="font-size:13px;">Latest updates and alerts</span>
            </div>
            
        <?php
        $today_date = date('Y-m-d');
        $result = mysqli_query($con,"SELECT * FROM notification order by id DESC limit 50");
		if(mysqli_num_rows($result)>0){
                 
            while ($row = mysqli_fetch_array($result)){
            ?> 
            <div class="card shadow-sm border-0 mb-3 transition notification-list-card" style="border-radius: 16px;">
                <div class="card-body p-3">
                    <div class="d-flex flex-row align-items-start notification-card-row notification-card__row">
                        <div class="notification-card__icon flex-shrink-0">
                            <img src="assets/icons/notification.png" alt="" class="notification-card__icon-img" width="40" height="40">
                        </div>
                        <div class="notification-card__main flex-grow-1 pl-0" style="min-width:0;">
                            <div class="d-flex justify-content-between align-items-start notification-card__headline">
                                <h6 class="mb-0 pr-2 font-weight-bold text-dark notification-card__title"><?php echo $row['title'];?></h6>
                                <small class="notification-card__date flex-shrink-0 text-nowrap"><i class="fa fa-clock-o"></i> <?php echo date('d M Y, h:i A',strtotime($row['date'].' '.$row['time']));?></small>
                            </div>
                            <p class="mb-0 mt-2 notification-card__desc"><?php echo $row['description'];?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            
        <?php }else{ ?>
             
            <div class="tbmar-40 text-center">
                <p>No record found</p>
            </div>
        <?php } ?>    

            </div>
            </div>
      
        <br><br><br>  
        </div>
    </div>
    
    <?php include("include/footer.php"); ?>

</body>

</html>