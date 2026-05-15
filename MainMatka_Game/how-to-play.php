<?php
//ini_set('session.cookie_lifetime', 2592000);  // 30 days in seconds
//session_start(); 
//include("include/connect.php");
//include("include/functions.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How to Play Matka Online - <?php echo $site_title;?></title>
    <?php include("include/head.php"); ?>
    
    <style>
    .step-badge {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: #fff;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 700;
        display: inline-block;
        margin-bottom: 10px;
    }
    .htp-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.04);
        margin-bottom: 15px;
        padding: 15px;
        background: #fff;
    }
    .htp-card ul {
        padding-left: 20px;
        margin-bottom: 0;
        font-size: 14px;
        color: #4a5568;
    }
    .htp-card ul li {
        margin-bottom: 5px;
    }
    </style>
</head>

<body>

    <div class="wrapper">
        <?php //include("include/sidebar.php"); ?>
        <div id="content">
            <?php //include("include/nav.php"); ?>
            
            <div class="container" > 
                <div class="text-center tb-10 mt-3 mb-4">
                    <div style="width: 60px; height: 60px; background: #ebf4ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                        <i class="fa fa-book" style="font-size: 28px; color: var(--primary-color);"></i>
                    </div>
                    <h3 class="font-weight-bold" style="color: var(--primary-color);">How to Play</h3>
                    <span class="text-muted" style="font-size:13px;">Beginner's guide to start winning</span>
                </div>
                
                <div class="pb-4">
                    <!-- Intro -->
                    <div class="htp-card" style="background: linear-gradient(135deg, #ebf4ff, #e6fffa);">
                        <p class="mb-0 text-dark" style="font-size:14px; line-height:1.6; font-weight:500;">
                            <i class="fa fa-info-circle text-primary"></i> <strong>MainMatka</strong> is a premium number-based prediction game. This guide will help you understand how to play step-by-step.
                        </p>
                    </div>

                    <!-- Step 1 -->
                    <div class="htp-card">
                        <span class="step-badge">Step 1</span>
                        <h6 class="font-weight-bold text-dark mb-2">Create ID & Add Balance</h6>
                        <ul>
                            <li>First deposit money into your account wallet.</li>
                            <li>Minimum deposit: <strong>₹500</strong></li>
                            <li>1 Rupee = <strong>1 Point</strong></li>
                        </ul>
                    </div>

                    <!-- Step 2 -->
                    <div class="htp-card">
                        <span class="step-badge">Step 2</span>
                        <h6 class="font-weight-bold text-dark mb-2">Choose Game Type</h6>
                        <ul>
                            <li><strong>Single Digit:</strong> Choose a number from 0 to 9</li>
                            <li><strong>Jodi:</strong> Choose a 2-digit number (00–99)</li>
                            <li><strong>Single/Double/Triple Patti:</strong> 3-digit combinations based on open/close results.</li>
                        </ul>
                    </div>

                    <!-- Step 3 -->
                    <div class="htp-card">
                        <span class="step-badge">Step 3</span>
                        <h6 class="font-weight-bold text-dark mb-2">Place Your Bet</h6>
                        <ul>
                            <li>Select your desired number in the dashboard.</li>
                            <li>Enter points to wager.</li>
                            <li>Minimum bet allowed: <strong>10 Points</strong></li>
                        </ul>
                    </div>

                    <!-- Step 4 -->
                    <div class="htp-card">
                        <span class="step-badge">Step 4</span>
                        <h6 class="font-weight-bold text-dark mb-2">Check Game Timing</h6>
                        <ul>
                            <li>Each market has fixed open & close times.</li>
                            <li>You must place your bets before the market's closing time.</li>
                        </ul>
                    </div>

                    <!-- Step 5 -->
                    <div class="htp-card">
                        <span class="step-badge">Step 5</span>
                        <h6 class="font-weight-bold text-dark mb-2">Check Result & Win Prizes</h6>
                        <ul>
                            <li>Results are declared daily at scheduled times.</li>
                            <li>If your number matches the result → <strong>You Win!</strong> 🎉</li>
                            <li>Winning points are added automatically to your wallet.</li>
                        </ul>
                    </div>

                    <!-- Step 6 -->
                    <div class="htp-card">
                        <span class="step-badge">Step 6</span>
                        <h6 class="font-weight-bold text-dark mb-2">Withdraw Money</h6>
                        <ul>
                            <li>Request a withdrawal anytime from the Wallet.</li>
                            <li>Funds are credited securely to your verifed Bank Account.</li>
                        </ul>
                    </div>
                    
                    <!-- Important Tips -->
                    <div class="htp-card" style="border-left: 4px solid var(--primary-color);">
                        <h6 class="font-weight-bold text-dark mb-2"><i class="fa fa-lightbulb-o text-warning"></i> Essential Tips</h6>
                        <ul>
                            <li>Start with small bets to learn the game.</li>
                            <li>Play responsibly; set a daily budget.</li>
                            <li>Do not rely strictly on rumors or "sure-shot" tricks.</li>
                        </ul>
                    </div>

                </div>
            </div>
            
            <br><br>
        </div>
    </div>
    
    <?php //include("include/footer.php"); ?>

</body>
</html>