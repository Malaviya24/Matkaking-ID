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

<title>Download Mobile application - <?php echo $site_title;?></title>
<meta name="description" content="Download Mobile application and stay connected with us.">
<?php include("include/head.php"); ?>

</head>

<body>

<div class="wrapper">
<?php include("include/sidebar.php"); ?>

<div id="content">
<?php include("include/nav.php"); ?>

<div class="container download-wrapper">

    <!-- HEADER -->
    <div class="download-header">
        <h3>Download App</h3>
        <span class="text-muted">Fast • Secure • Mobile Friendly</span>
    </div>

    <!-- CARD -->
    <div class="card shadow border-0 download-card">

        <div class="download-hero">
            <div class="download-hero__left">
                <img src="assets/img/app-home.webp?v1" alt="App Preview">
            </div>
            <div class="download-hero__right">
                <h2>MainMatka App</h2>
                <div class="feature-box">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fa fa-rocket"></i>
                        </div>
                        <div class="feature-text">
                            <h6>Fast Performance</h6>
                            <p class="text-muted">Works even on slow internet</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fa fa-shield"></i>
                        </div>
                        <div class="feature-text">
                            <h6>Secure System</h6>
                            <p class="text-muted">Safe & encrypted</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fa fa-bell"></i>
                        </div>
                        <div class="feature-text">
                            <h6>Instant Alerts</h6>
                            <p class="text-muted">Live updates & results</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="download-actions">
            <a href="https://mainmatka.app/apk/MainMatka_1011.apk" class="download-btn">
                <i class="fa fa-android"></i> Download App
            </a>
            <p class="download-tagline">Fast results, secure bidding & real-time updates.</p>
        </div>

    </div>

</div>

</div>
</div>

<?php include("include/footer.php"); ?>

</body>
</html>