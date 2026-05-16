<?php

/**
 * #footer-bar: first include prints it once (nav.php calls early so it parses with header).
 * Page-end include: skips bar, outputs <br> + scripts only.
 */
if (!defined('MAINMATKA_FOOTER_BAR_PRINTED')) {
    define('MAINMATKA_FOOTER_BAR_PRINTED', true);
    $fb_script = strtolower(basename($_SERVER['SCRIPT_NAME'] ?? ''));
    $fb_history_active = in_array($fb_script, ['history.php', 'bidding-history.php', 'bidding-history-starline.php', 'fund-history.php'], true);
?>
    <div id="footer-bar" class="footer-bar-1">
        <a href="index.php" class="<?php echo $fb_script === 'index.php' ? 'active-nav' : ''; ?>"><img class="footer-bar__icon-img" src="assets/icons/home.png" alt="" width="22" height="22" loading="lazy" decoding="async"><span>Home</span></a>
        <!-- <a href="transaction-history.php" class="<?php echo $fb_script === 'transaction-history.php' ? 'active-nav' : ''; ?>"><i class="fa fa-book"></i><span>Passbook</span></a> -->
        <!-- <a href="add-fund.php"><strong><i class="fa fa-plus"></i></strong><span>Add Fund</span></a> -->
        <a href="withdraw.php" class="<?php echo $fb_script === 'withdraw.php' ? 'active-nav' : ''; ?>"><img class="footer-bar__icon-img" src="assets/icons/widraw.png" alt="" width="22" height="22" loading="lazy" decoding="async"><span>Withdraw</span></a>
        <a href="history.php" class="<?php echo $fb_history_active ? 'active-nav' : ''; ?>"><img class="footer-bar__icon-img" src="assets/icons/history.png" alt="" width="22" height="22" loading="lazy" decoding="async"><span>History</span></a>
        <a href="my-profile.php" class="footer-bar__profile<?php echo $fb_script === 'my-profile.php' ? ' active-nav' : ''; ?>"><img class="footer-bar__icon-img" src="assets/icons/user.png" alt="" width="22" height="22" loading="lazy" decoding="async"><span>Profile</span></a>
    </div>
<?php
}
if (!empty($GLOBALS['footer_bar_early_include'])) {
    return;
}
?>
<br><br><br>


<?php if (0) { ?>
    <div class="overlay"></div>
    <div id="loading-bg"></div>
    <div id="mloader" class="lds-ripple">
        <div></div>
        <div></div>
    </div>

<?php } ?>


<!-- jQuery CDN - Slim version (=without AJAX) -->
<script src="assets/js/jquery-3.3.1.slim.min.js"></script>
<!-- Popper.JS -->
<script src="assets/js/popper.min.js"></script>
<!-- Bootstrap JS -->
<script src="assets/js/bootstrap.min.js"></script>

<script>
// Global betting functions - inline, no dependency on jQuery ready
var selectedBidAmount = 0;

function selectAmt(el, amt) {
    selectedBidAmount = amt;
    // Clear all buttons style
    var all = document.querySelectorAll('.bidamtbox');
    for (var i = 0; i < all.length; i++) {
        all[i].removeAttribute('style');
        all[i].classList.remove('active','selected');
        var p = all[i].querySelector('p');
        if (p) p.removeAttribute('style');
    }
    // Highlight selected
    el.classList.add('active','selected');
    el.setAttribute('style', 'background:linear-gradient(145deg,#f0d27a,#caa64a) !important; border-color:rgba(255,255,255,.25) !important; transform:scale(1.05) !important; box-shadow:0 6px 18px rgba(202,166,74,.5) !important;');
    var p = el.querySelector('p');
    if (p) p.setAttribute('style', 'color:#1a1200 !important; font-weight:900 !important;');
    // Set hidden field
    var h = document.getElementById('selected_amount');
    if (h) h.value = amt;
    return false;
}

function clickPanna(el) {
    if (!selectedBidAmount || selectedBidAmount <= 0) {
        // Highlight the amount section instead of showing an alert
        var amtBoxes = document.querySelectorAll('.bidamtbox');
        amtBoxes.forEach(function(box) {
            box.style.transition = 'box-shadow 0.3s';
            box.style.boxShadow = '0 0 12px rgba(252,129,129,0.7)';
            setTimeout(function(){ box.style.boxShadow = ''; }, 1200);
        });
        // Scroll to amount section
        var heading = document.querySelector('.subheading');
        if (heading) heading.scrollIntoView({behavior:'smooth', block:'center'});
        return;
    }
    var existing = parseInt(el.value) || 0;
    el.value = existing + selectedBidAmount;
    // Recalc total
    var total = 0;
    var inputs = document.querySelectorAll('.pointinputbox');
    for (var i = 0; i < inputs.length; i++) {
        var v = parseInt(inputs[i].value) || 0;
        if (v > 0) total += v;
    }
    var td = document.getElementById('total_point2');
    var ti = document.getElementById('total_point');
    if (td) td.textContent = total.toLocaleString('en-IN');
    if (ti) ti.value = total;
}
</script>


<script type="text/javascript">
    $(function() {
        $('#loading-bg').hide();
        $('#mloader').hide();

        $(window).on('beforeunload', function() {
            $('#loading-bg').show();
            $('#mloader').show();
        });
    });


    var grantotal = 0;

    function resetjsvar() {
        grantotal = 0;
        $('#total_point2').html(0);
        $('#total_point').val('');
        $('.pointinputbox').val('');
        $('.bidamtbox').removeClass('active');
        $('#selected_amount').val('');
    }

    function recalcTotal() {
        var total = 0;
        $('.pointinputbox').each(function() {
            var v = parseInt($(this).val()) || 0;
            if (v > 0) total += v;
        });
        grantotal = total;
        $('#total_point2').html(total.toLocaleString());
        $('#total_point').val(total);
    }

    $(document).ready(function() {

        $('.addFundamtbox').on('click', function() {
            var addFund_amount = $(this).attr('data');
            $('#add_fund_amount').val(addFund_amount);
        });

        // Amount button selection - DISABLED (using inline onclick="selectAmt()" instead)
        // $('.bidamtbox').on('click', ...);

        // Panna click - DISABLED (using inline onclick="clickPanna()" instead)
        // $(document).on('click', '.pointinputbox', ...);



        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').addClass('active');
            $('.overlay').addClass('active');
            $('.collapse.in').toggleClass('in');
            $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        });

        $('#dismiss, .overlay').on('click', function() {
            $('#sidebar').removeClass('active');
            $('.overlay').removeClass('active');
        });


    });


    <?php
    if (isset($_GET['bidplacedsuccessfully'])) { ?>
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Bid Placed Successfully',
            showConfirmButton: false,
            timer: 1000
        })
    <?php } ?>

    <?php
    if (isset($_GET['invalid_date'])) { ?>
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Invalid Date',
            showConfirmButton: false,
            timer: 3000
        })
    <?php } ?>

    <?php
    if (isset($_GET['bidfailed'])) { ?>
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Something Went Wrong, Try Again',
            showConfirmButton: false,
            timer: 3000
        })
    <?php } ?>

    <?php
    if (isset($_GET['insufficientbalance'])) { ?>
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Insufficient Balance',
            showConfirmButton: true,
            timer: 3000
        })
    <?php } ?>


    <?php
    if (isset($_GET['detailupdated'])) { ?>
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Detail Updated',
            showConfirmButton: false,
            timer: 1000
        })
    <?php } ?>

    <?php
    if (isset($_GET['invalidrequest'])) { ?>
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Invalid Request',
            showConfirmButton: true,
            timer: 3000
        })
    <?php } ?>

    <?php
    if (isset($_GET['notupdated'])) { ?>
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Sorry, Data Not Updated.',
            showConfirmButton: true,
            timer: 3000
        })
    <?php } ?>

    <?php
    if (isset($_GET['depositrequested'])) { ?>
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Deposit Request Submitted',
            text: 'Admin will verify your payment and add balance.',
            showConfirmButton: true
        })
    <?php } ?>

    <?php
    if (isset($_GET['invalidutr'])) { ?>
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Invalid UTR Number',
            showConfirmButton: true
        })
    <?php } ?>

    <?php
    if (isset($_GET['duplicateutr'])) { ?>
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'UTR Already Submitted',
            text: 'Please contact admin if this is a mistake.',
            showConfirmButton: true
        })
    <?php } ?>

    <?php
    if (isset($_GET['passwordverified'])) { ?>
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Password Verified',
            text: 'Now enter your new password.',
            showConfirmButton: false,
            timer: 1200
        })
    <?php } ?>

    <?php
    if (isset($_SESSION['usr_id'])) {
        $notice_result = mysqli_query($con, "SELECT id, title, description FROM notification ORDER BY id DESC LIMIT 1");
        $notice = $notice_result ? mysqli_fetch_assoc($notice_result) : null;
        if ($notice && !empty($notice['id'])) {
    ?>
        (function() {
            var noticeId = <?php echo json_encode('mainmatka_notice_' . $notice['id']); ?>;
            if (window.localStorage && localStorage.getItem(noticeId)) {
                return;
            }
            Swal.fire({
                icon: 'info',
                title: <?php echo json_encode($notice['title']); ?>,
                text: <?php echo json_encode($notice['description']); ?>,
                confirmButtonText: 'OK',
                confirmButtonColor: '#d5aa45',
                background: '#0a1b36',
                color: '#ffffff'
            }).then(function() {
                if (window.localStorage) {
                    localStorage.setItem(noticeId, '1');
                }
            });
        })();
    <?php
        }
    }
    ?>
</script>

<!-- Fallback vanilla JS for betting (in case jQuery handlers don't bind) -->
<script>
(function(){
    function setupBetting(){
        var amtButtons = document.querySelectorAll('.bidamtbox');
        var pannaInputs = document.querySelectorAll('.pointinputbox');
        var selectedAmountInput = document.getElementById('selected_amount');
        var totalDisplay = document.getElementById('total_point2');
        var totalInput = document.getElementById('total_point');
        
        if (!selectedAmountInput) return; // Not a betting page
        
        function recalcTotal(){
            var total = 0;
            pannaInputs.forEach(function(inp){
                var v = parseInt(inp.value) || 0;
                if (v > 0) total += v;
            });
            if (totalDisplay) totalDisplay.textContent = total.toLocaleString('en-IN');
            if (totalInput) totalInput.value = total;
        }
        
        // Amount button click
        amtButtons.forEach(function(btn){
            btn.addEventListener('click', function(e){
                e.preventDefault();
                e.stopPropagation();
                var amt = btn.getAttribute('data') || btn.getAttribute('data-amt');
                // Clear all
                amtButtons.forEach(function(b){
                    b.classList.remove('active','selected');
                    b.removeAttribute('style');
                    var p = b.querySelector('p');
                    if (p) p.removeAttribute('style');
                });
                // Highlight clicked
                btn.classList.add('active','selected');
                btn.setAttribute('style', 'background:linear-gradient(145deg,#f0d27a,#caa64a) !important; border-color:rgba(255,255,255,.25) !important; transform:scale(1.05) !important; box-shadow:0 8px 22px rgba(202,166,74,.55) !important;');
                var p = btn.querySelector('p');
                if (p) p.setAttribute('style', 'color:#1a1200 !important; font-weight:900 !important;');
                selectedAmountInput.value = amt;
                return false;
            }, true);
        });
        
        // Panna input click - add selected amount
        pannaInputs.forEach(function(inp){
            inp.addEventListener('click', function(e){
                var selectedAmt = selectedAmountInput.value;
                if (!selectedAmt || selectedAmt === '0'){
                    // Highlight amount buttons instead of alert
                    amountBtns.forEach(function(box) {
                        box.style.transition = 'box-shadow 0.3s';
                        box.style.boxShadow = '0 0 12px rgba(252,129,129,0.7)';
                        setTimeout(function(){ box.style.boxShadow = ''; }, 1200);
                    });
                    return;
                }
                // If empty, fill in. If has value, add to it.
                var existing = parseInt(inp.value) || 0;
                inp.value = existing + parseInt(selectedAmt);
                recalcTotal();
            });
            
            // Allow manual typing
            inp.addEventListener('input', function(){
                inp.value = inp.value.replace(/[^0-9]/g, '');
                recalcTotal();
            });
        });
        
        // Make sure inputs are not readonly
        pannaInputs.forEach(function(inp){
            inp.removeAttribute('readonly');
        });
    }
    
    if (document.readyState === 'loading'){
        document.addEventListener('DOMContentLoaded', setupBetting);
    } else {
        setupBetting();
    }
})();
</script>

<?php if (0) { ?>
    <script>
        if ('serviceWorker' in navigator) {
            console.log("Will the service worker register?");
            navigator.serviceWorker.register('service-worker.js')
                .then(function(reg) {
                    console.log("Yes, it did.");
                }).catch(function(err) {
                    console.log("No it didn't. This happened:", err)
                });
        }
    </script>

<?php } ?>
