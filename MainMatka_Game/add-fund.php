<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");
    include("include/session.php");
include("include/functions.php");

function manual_deposit_setting($name, $default = '') {
    global $con;
    $value = getenv(strtoupper('MAINMATKA_' . $name));
    if ($value !== false && $value !== '') {
        return $value;
    }

    $safe_name = mysqli_real_escape_string($con, strtolower($name));
    $result = mysqli_query($con, "SELECT value FROM settings WHERE name='{$safe_name}' LIMIT 1");
    if ($result && ($row = mysqli_fetch_assoc($result)) && $row['value'] !== '') {
        return $row['value'];
    }

    return $default;
}

if(isset($_POST['manual_deposit']) && isset($_SESSION['usr_id'])!="") {
    if (!app_validate_csrf()) {
        echo "<script>window.location = 'add-fund.php?invalidrequest';</script>";
        exit;
    }

    $user_id = (int) $_SESSION['usr_id'];
    $amount = isset($_POST['amount']) ? (float) $_POST['amount'] : 0;
    $utr_no = strtoupper(trim(mysqli_real_escape_string($con, $_POST['utr_no'] ?? '')));
    $date = date('Y-m-d');
    $time = date('h:i:s A');
    $balance = get_lastBalance($user_id);

    if ($amount < 500 || $amount > 50000) {
        echo "<script>window.location = 'add-fund.php?invalidrequest';</script>";
        exit;
    }

    if (!preg_match('/^[A-Z0-9-]{6,40}$/', $utr_no)) {
        echo "<script>window.location = 'add-fund.php?invalidutr';</script>";
        exit;
    }

    $utr_check = mysqli_query($con, "SELECT id FROM user_transaction WHERE api_response LIKE 'Manual Deposit UTR: {$utr_no}%' LIMIT 1");
    if ($utr_check && mysqli_num_rows($utr_check) > 0) {
        echo "<script>window.location = 'add-fund.php?duplicateutr';</script>";
        exit;
    }

    $api_response = 'Manual Deposit UTR: ' . $utr_no;
    $sql = "INSERT INTO user_transaction(user_id,game_id,game_type,digit,date,time,amount,type,debit_credit,balance,status,title,api_response)
            VALUES('$user_id','','manual_deposit','','$date','$time','$amount','deposit_request','credit','$balance','1','manual_deposit','$api_response')";
    $res = mysqli_query($con, $sql);

    if ($res) {
        echo "<script>window.location = 'add-fund.php?depositrequested';</script>";
        exit;
    }

    echo "<script>window.location = 'add-fund.php?notupdated';</script>";
    exit;
}

$manual_upi_id = manual_deposit_setting('deposit_upi_id', '');
$manual_qr_url = manual_deposit_setting('deposit_qr_url', '');
$manual_payee = manual_deposit_setting('deposit_payee_name', 'MainMatka');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Add Fund - <?php echo $site_title;?></title>
    
    <?php include("include/head.php"); ?>
</head>

<body class="page-add-fund">

    <div class="wrapper">

        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>

            <div class="add-fund-page">
                <div class="add-fund-container">
                    <?php
                    $user_id = $_SESSION['usr_id'];
                    ?>
                    
                    <div class="add-fund-glass-card">
                        <div class="add-fund-header">
                            <div class="add-fund-icon-wrap">
                                <img src="assets/icons/fundhistory.png" alt="Wallet" class="add-fund-hero-img">
                            </div>
                            <h2 class="add-fund-title">Deposit Funds</h2>
                            <p class="add-fund-tagline">Load points securely to your wallet</p>
                        </div>

                        <div class="add-fund-info-bar">
                            <span class="info-bubble"><i class="fa fa-clock-o"></i> Faster Processing (5m)</span>
                            <span class="info-bubble"><i class="fa fa-shield"></i> 100% Secured</span>
                        </div>

                        <form action="" method="POST" autocomplete="off" class="add-fund-form">
                            <?php echo app_csrf_input(); ?>
                            <div class="amount-entry-section">
                                <label class="amount-label">Enter Deposit Amount (<i class="fa fa-inr"></i>)</label>
                                <div class="amount-input-wrapper">
                                    <input type="number" id="add_fund_amount" class="add-fund-main-input" name="amount" value="" min="500" max="50000" placeholder="0" autocomplete="off" required>
                                    <div class="input-focus-glow"></div>
                                </div>
                            </div>

                            <div class="quick-chip-grid">
                                <div class="chip-item">
                                    <a class="addFundamtbox amount-chip" data="500">
                                        <span class="chip-sign">+</span><span class="chip-val">500</span>
                                    </a>
                                </div>
                                <div class="chip-item">
                                    <a class="addFundamtbox amount-chip" data="1000">
                                        <span class="chip-sign">+</span><span class="chip-val">1000</span>
                                    </a>
                                </div>
                                <div class="chip-item">
                                    <a class="addFundamtbox amount-chip" data="5000">
                                        <span class="chip-sign">+</span><span class="chip-val">5000</span>
                                    </a>
                                </div>
                                <div class="chip-item">
                                    <a class="addFundamtbox amount-chip" data="10000">
                                        <span class="chip-sign">+</span><span class="chip-val">10000</span>
                                    </a>
                                </div>
                            </div>

                            <div class="method-section">
                                <label class="method-label">Scan QR & Pay</label>
                                <div style="border:1px solid rgba(202,166,74,.28);border-radius:16px;background:rgba(255,255,255,.04);padding:16px;text-align:center;">
                                    <?php if ($manual_qr_url != '') { ?>
                                        <img src="<?php echo htmlspecialchars($manual_qr_url, ENT_QUOTES, 'UTF-8'); ?>" alt="Deposit QR" style="width:210px;max-width:100%;border-radius:14px;background:#fff;padding:10px;margin-bottom:12px;">
                                    <?php } else { ?>
                                        <div style="width:210px;max-width:100%;min-height:210px;border-radius:14px;background:#fff;color:#111;display:flex;align-items:center;justify-content:center;padding:18px;margin:0 auto 12px;font-weight:800;line-height:1.35;">
                                            QR not set<br>Contact admin
                                        </div>
                                    <?php } ?>
                                    <?php if ($manual_upi_id != '') { ?>
                                        <div style="color:#fff;font-weight:800;margin-bottom:6px;">UPI ID: <?php echo htmlspecialchars($manual_upi_id, ENT_QUOTES, 'UTF-8'); ?></div>
                                        <a id="manual_upi_link" class="minimal-wa-link" href="upi://pay?pa=<?php echo rawurlencode($manual_upi_id); ?>&pn=<?php echo rawurlencode($manual_payee); ?>&cu=INR" style="display:inline-flex;margin-top:6px;">Open UPI App</a>
                                    <?php } else { ?>
                                        <div style="color:#fca5a5;font-weight:700;">Payment UPI ID is not configured yet.</div>
                                    <?php } ?>
                                    <p style="margin:12px 0 0;color:rgba(255,255,255,.62);font-size:12px;">After payment, enter your UTR / transaction number below. Admin will verify and approve your wallet balance.</p>
                                </div>
                            </div>

                            <div class="method-section">
                                <label class="method-label">UTR / Transaction Number</label>
                                <div class="amount-input-wrapper">
                                    <input type="text" class="add-fund-main-input" name="utr_no" maxlength="40" minlength="6" placeholder="Enter UTR after payment" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="add-fund-action-wrap">
                                <button type="submit" name="manual_deposit" class="btn premium-add-btn">
                                    <span>Submit Deposit Request</span>
                                    <i class="fa fa-check"></i>
                                </button>
                            </div>
                        </form>

                        <div class="add-fund-footer">
                            <p class="help-text">Need assistance with deposit?</p>
                            <a href="https://wa.me/<?php echo get_SettingValue('PWA_whatsapp1'); ?>" class="minimal-wa-link">
                                <i class="fa fa-whatsapp"></i> Chat with Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php if ($manual_upi_id != '') { ?>
    <script>
        (function() {
            var amountInput = document.getElementById('add_fund_amount');
            var upiLink = document.getElementById('manual_upi_link');
            var baseLink = 'upi://pay?pa=<?php echo rawurlencode($manual_upi_id); ?>&pn=<?php echo rawurlencode($manual_payee); ?>&cu=INR';
            function updateUpiLink() {
                var amount = amountInput ? amountInput.value : '';
                upiLink.href = amount ? baseLink + '&am=' + encodeURIComponent(amount) : baseLink;
            }
            if (amountInput && upiLink) {
                amountInput.addEventListener('input', updateUpiLink);
                document.querySelectorAll('.addFundamtbox').forEach(function(button) {
                    button.addEventListener('click', function() {
                        window.setTimeout(updateUpiLink, 0);
                    });
                });
                updateUpiLink();
            }
        })();
    </script>
    <?php } ?>

    <?php include("include/footer.php"); ?>

</body>

</html>
