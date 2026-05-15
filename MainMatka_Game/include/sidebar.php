<!-- Sidebar  -->
<?php
$sb_script = strtolower(basename($_SERVER['SCRIPT_NAME'] ?? ''));
$sb_profile_section = in_array($sb_script, ['my-profile.php', 'update-bank-details.php', 'change-password.php'], true);
?>
<nav id="sidebar">
    <div class="sidebar-header">
        <button type="button" id="dismiss" class="sidebar-dismiss" aria-label="Close menu" title="Close">
            <i class="fa fa-chevron-left" aria-hidden="true"></i>
        </button>
        <div class="user-avatar" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
            <i class="fa fa-user" style="font-size: 24px; color: white;"></i>
        </div>
        <?php
        if (isset($_SESSION['usr_id']) != "") {
        ?>
            <span class="Uname"><?php echo $_SESSION['usr_name']; ?></span>
            <span class="Umobile"><?php echo str_replace("+91", "", $_SESSION['usr_mobile']); ?></span>
        <?php } else { ?>
            <span class="Uname">Hello User</span>
            <span class="Umobile">Welcome Back</span>
        <?php } ?>
    </div>

    <ul class="list-unstyled components sideMenu">
        <li<?php echo $sb_script === 'index.php' ? ' class="active"' : ''; ?>><a href="index.php"><span class="sidebar-nav-icon-wrap" aria-hidden="true"><img src="assets/icons/home.png" alt="" width="22" height="22" loading="lazy" decoding="async"></span><span>Home</span></a></li>
            <li<?php echo $sb_script === 'support.php' ? ' class="active"' : ''; ?>><a href="support.php"><span class="sidebar-nav-icon-wrap" aria-hidden="true"><img src="assets/icons/support.png" alt="" width="22" height="22" loading="lazy" decoding="async"></span><span>Support</span></a></li>
                <!-- <li><a href="transaction-history.php"> <i class="fa fa-list-alt"></i> <span>Transaction History</span></a></li>
                <li><a href="bidding-history.php"> <i class="fa fa-history"></i> <span>Bidding History</span></a></li>
                <li><a href="bidding-history-starline.php"> <i class="fa fa-star"></i> <span>Starline Bid History</span></a></li>
                <li><a href="fund-history.php"> <i class="fa fa-money"></i> <span>Fund History</span></a></li> -->
                <li<?php echo $sb_script === 'notifications.php' ? ' class="active"' : ''; ?>><a href="notifications.php"><span class="sidebar-nav-icon-wrap" aria-hidden="true"><img src="assets/icons/notification.png" alt="" width="22" height="22" loading="lazy" decoding="async"></span><span>Notifications</span></a></li>
                    <li<?php echo $sb_script === 'top-winner-list.php' ? ' class="active"' : ''; ?>><a href="top-winner-list.php"><span class="sidebar-nav-icon-wrap" aria-hidden="true"><img src="assets/icons/top_winners.png" alt="" width="22" height="22" loading="lazy" decoding="async"></span><span>Top Winners</span></a></li>
                        <li<?php echo $sb_script === 'top-winner-list-starline.php' ? ' class="active"' : ''; ?>><a href="top-winner-list-starline.php"> <i class="fa fa-star-o"></i> <span>Starline Winners</span></a></li>
                            <li<?php echo $sb_script === 'game-rates.php' ? ' class="active"' : ''; ?>><a href="game-rates.php"> <i class="fa fa-line-chart"></i> <span>Game Rates</span></a></li>
                                <li style="margin-bottom: 1px;" <?php echo $sb_script === 'download-app.php' ? 'class="active"' : ''; ?>>
                                    <a href="download-app.php">
                                        <span class="sidebar-nav-icon-wrap" aria-hidden="true">
                                            <img src="assets/icons/download.png" alt="" width="22" height="22" loading="lazy" decoding="async">
                                        </span>
                                        <span>Download App</span>
                                    </a>
                                </li>
    </ul>

    <ul class="list-unstyled CTAs">
        <!-- <li<?php echo $sb_profile_section ? ' class="active"' : ''; ?>>
                    <a href="my-profile.php" class="download">My Profile</a>
                </li> -->
        <li>
            <a href="logout.php" class="article">Logout</a>
        </li>
    </ul>
</nav>
