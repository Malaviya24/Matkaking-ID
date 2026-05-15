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

    <title>Bidding History Main Markets - <?php echo $site_title;?></title>
    
    <?php include("include/head.php"); ?>
</head>

<body class="page-bidding-history-main">

    <div class="wrapper">
        
        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>
            
            <div class="container" > 
            
            <div class="text-center tb-10">
                    <h3 class="gdash3">Bidding History</h3>
                    <span style="font-size:12px;">Main markets bidding records</span>
            </div>
            <div class="tb-10">
            <?php
            
            $limit = 10;
            $offset = ($page-1)*$limit;
            $user_id = $_SESSION['usr_id'];
            // Count of all records
            $query   = "SELECT COUNT(id) as rowNum FROM user_transaction where user_id='".$user_id."' and type='bid' and starline='0'";
            $res  = mysqli_query($con,$query); 
            $res1 = mysqli_fetch_assoc($res);
            $allRecrods= $res1['rowNum'];
            $totoalPages = ceil($allRecrods / $limit);
            
            
            
             $qry = "SELECT user_transaction.*, COALESCE(games.name, sm.market_name, CONCAT('Game #', user_transaction.game_id)) as game_name 
                     FROM user_transaction 
                     LEFT JOIN games ON user_transaction.game_id = games.id AND user_transaction.game_id REGEXP '^[0-9]+$'
                     LEFT JOIN scraped_markets sm ON user_transaction.game_id = sm.id AND sm.date = user_transaction.date
                     WHERE user_id='".$user_id."' AND user_transaction.type='bid' AND user_transaction.starline='0' 
                     ORDER BY user_transaction.id DESC LIMIT $offset,$limit";
             $result = mysqli_query($con,$qry);
             $data["records"]=array();
             if(mysqli_num_rows($result)>0){
                 
                 while ($row = mysqli_fetch_array($result)){
		            
		         
		         if($row['win']=='' || $row['win']=='NULL'){
		              $game_result = 'Pending';
		          }elseif($row['win']=='0')
		          {
		              $game_result = 'LOSE';
		          }else{
		              $game_result = $row['win'];
		          }
                
                
                ?>
                <div class="card shadow-sm mb-3 border-0 transition" style="border-radius: 16px;">
                    <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center" style="border-radius: 16px 16px 0 0;">
                        <h6 class="font-weight-bold mb-0 text-dark"><?php echo $row['game_name'];?> <span class="badge badge-light text-uppercase border" style="font-size:10px; color:var(--secondary-color);"><?php echo $row['game_type'];?></span>
                        <?php if (!empty($row['session_type'])) { ?>
                            <span class="badge" style="font-size:9px;background:<?php echo $row['session_type']=='open' ? 'rgba(104,211,145,.15);color:#68d391' : 'rgba(246,173,85,.15);color:#f6ad55'; ?>;padding:2px 6px;border-radius:4px;"><?php echo ucfirst($row['session_type']); ?></span>
                        <?php } ?>
                        </h6>
                        <span class="text-muted" style="font-size:11px;">#<?php echo $row['id'];?></span>
                    </div>
                    <div class="card-body p-3">
                        <div class="row text-center mb-3">
                            <div class="col-4 border-right">
                                <span class="text-muted d-block" style="font-size:11px;">Number</span>
                                <h4 class="font-weight-bold text-dark mb-0"><?php echo $row['digit'];?></h4>
                            </div>
                            <div class="col-4 border-right">
                                <span class="text-muted d-block" style="font-size:11px;">Bet Amount</span>
                                <h4 class="font-weight-bold mb-0" style="color:var(--primary-color);">₹<?php echo app_format_money($row['amount']);?></h4>
                            </div>
                            <div class="col-4">
                                <span class="text-muted d-block" style="font-size:11px;">Win Amount</span>
                                <?php
                                // Calculate potential/actual win
                                $bet_amt = (float)$row['amount'];
                                $game_type = $row['game_type'];
                                $rates = ['single'=>9,'jodi'=>95,'single_patti'=>140,'double_patti'=>280,'triple_patti'=>600,'half_sangam'=>1000,'full_sangam'=>10000];
                                $rate = $rates[$game_type] ?? 0;
                                $potential_win = $bet_amt * $rate;
                                
                                if($row['win']!='' && $row['win']!='NULL' && $row['win']!='0'){
                                    // Actually won
                                    echo '<h4 class="font-weight-bold mb-0" style="color:#38a169;">₹'.app_format_money($row['win']).'</h4>';
                                } elseif($row['win']=='0') {
                                    echo '<h4 class="font-weight-bold mb-0" style="color:#e53e3e;">₹0</h4>';
                                } else {
                                    // Pending - show potential
                                    echo '<h4 class="font-weight-bold mb-0" style="color:#68d391;">₹'.app_format_money($potential_win).'</h4>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2 align-items-center">
                            <small class="text-muted font-weight-bold" style="font-size:11px;"><i class="fa fa-calendar-o"></i> <?php echo date('d M Y',strtotime($row['date']));?> <?php echo $row['time'] ?? '';?></small>
                            <?php if($row['win']=='' || $row['win']=='NULL' || $row['win']===null){?>
                                <span style="background:rgba(232,184,74,.15);color:#f6ad55;font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;"><i class="fa fa-check-circle"></i> Bet Placed</span>
                            <?php }elseif($row['win']=='0'){ ?>
                                <span style="background:rgba(229,62,62,.12);color:#fc8181;font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;"><i class="fa fa-times-circle"></i> You Lost</span>
                            <?php }else{ ?>
                                <span style="background:rgba(56,161,105,.12);color:#68d391;font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;"><i class="fa fa-trophy"></i> You Won!</span>
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