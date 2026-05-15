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

    <title>Whatsapp Support, Live Support, Telegram Support - <?php echo $site_title;?></title>
    
    <?php include("include/head.php"); ?>
</head>

<body class="page-support">

    <div class="wrapper">
        
        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>
            
            <div class="container" > 
            <div class="card shadow-sm border-0 support-page-head mb-4">
                <div class="card-body text-center py-4 px-3">
                    <div class="support-page-head__icon mb-3">
                        <img src="assets/icons/support.png" alt="" class="support-page-head__img" width="72" height="72">
                    </div>
                    <h3 class="font-weight-bold mb-2 support-page-head__title">Help & Support</h3>
                    <span class="support-page-head__sub">Need help? Contact us 24/7</span>
                </div>
            </div>
            
            <div class="pb-4">
                
                <a href="https://wa.me/<?php echo get_SettingValue('PWA_whatsapp1');?>" class="text-decoration-none d-block mb-3">
                    <div class="card shadow-sm border-0 transition" style="border-radius: 16px;">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #e6fffa; color: #25d366; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-right: 15px;">
                                <i class="fa fa-whatsapp"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 font-weight-bold text-dark">WhatsApp Support</h6>
                                <small class="text-muted">For Deposit/Add Funds Issues</small>
                            </div>
                            <i class="fa fa-chevron-right text-muted" style="font-size:12px;"></i>
                        </div>
                    </div>
                </a>

                <a href="https://wa.me/<?php echo get_SettingValue('PWA_whatsapp2');?>" class="text-decoration-none d-block mb-3">
                    <div class="card shadow-sm border-0 transition" style="border-radius: 16px;">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #e6fffa; color: #25d366; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-right: 15px;">
                                <i class="fa fa-whatsapp"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 font-weight-bold text-dark">WhatsApp Chat</h6>
                                <small class="text-muted">For General App Issues</small>
                            </div>
                            <i class="fa fa-chevron-right text-muted" style="font-size:12px;"></i>
                        </div>
                    </div>
                </a>
                
                <a href="https://t.me/Ritikdp99" class="text-decoration-none d-block mb-3">
                    <div class="card shadow-sm border-0 transition" style="border-radius: 16px;">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #ebf4ff; color: #0088cc; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-right: 15px;">
                                <i class="fa fa-telegram"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 font-weight-bold text-dark">Telegram Support</h6>
                                <small class="text-muted">Fast & Secure Chat Support</small>
                            </div>
                            <i class="fa fa-chevron-right text-muted" style="font-size:12px;"></i>
                        </div>
                    </div>
                </a>
                        
            </div>
            
            <div class="text-center mb-4">
                <span class="badge badge-light text-muted border py-2 px-3" style="border-radius:12px;"><i class="fa fa-shield text-success"></i> Please use Telegram for a secure experience.</span>
            </div>
            </div> 
      
            
        </div>
    </div>
    
    <?php include("include/footer.php"); ?>

<?php if(0){?>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/64d1d76acc26a871b02dee5c/1h79r2uj8';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
<?php } ?>

</body>

</html>