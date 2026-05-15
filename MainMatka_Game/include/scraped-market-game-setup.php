<?php
/**
 * Scraped Market Game Setup
 * 
 * BETTING LOGIC:
 * - Betting opens at market open_time
 * - Before open result: bets go to "Open" session
 * - After open result declared: bets go to "Close" session  
 * - Betting closes 10 min before close_time
 * - User can also manually select Open/Close session
 */

$is_scraped_market = false;
$_scraped_pgid = app_is_scraped_market_gid($parent_game_id);

if ($_scraped_pgid) {
    $is_scraped_market = true;
    
    $today = date('Y-m-d');
    $_sm_stmt = mysqli_prepare($con, "SELECT * FROM scraped_markets WHERE id = ? AND date = ? LIMIT 1");
    mysqli_stmt_bind_param($_sm_stmt, 'is', $_scraped_pgid, $today);
    mysqli_stmt_execute($_sm_stmt);
    $_sm_result = mysqli_stmt_get_result($_sm_stmt);
    $_sm_data = $_sm_result ? mysqli_fetch_assoc($_sm_result) : null;
    
    if (!$_sm_data) {
        echo "<script>alert('Market not available today.')</script>";
        echo "<script>window.location = 'index.php';</script>";
        exit;
    }
    
    // Set game variables
    $game_name = $_sm_data['market_name'];
    $game_id = 'scraped_' . $_scraped_pgid;
    $child_game_id = 'scraped_' . $_scraped_pgid;
    $child_open = 'scraped_' . $_scraped_pgid;
    $child_close = 'scraped_' . $_scraped_pgid;
    
    // Time calculations
    $now = time();
    $open_ts = strtotime(date('Y-m-d') . ' ' . $_sm_data['open_time']);
    $close_ts = strtotime(date('Y-m-d') . ' ' . $_sm_data['close_time']);
    if ($close_ts < $open_ts) {
        $close_ts = strtotime(date('Y-m-d', strtotime('+1 day')) . ' ' . $_sm_data['close_time']);
    }
    
    // 10 min before close = cutoff, 2 min after close = result done
    $close_cutoff = $close_ts - (10 * 60);
    $result_done_time = $close_ts + (2 * 60);
    
    // Check if open result is already declared
    $open_result_declared = ($_sm_data['result_status'] === 'open_declared' || $_sm_data['result_status'] === 'closed');
    
    // Determine betting status - only closed during the 12-min window
    if ($now >= $close_cutoff && $now < $result_done_time) {
        $bidding_status = 0;
        $msg = 'Betting is Closed for Today';
        $default_bidding_game = '';
    } else {
        // Betting is open
        $bidding_status = 1;
        
        if ($open_result_declared && $now < $close_cutoff) {
            $default_bidding_game = 'close';
            $msg = 'Close Betting is Running';
        } else {
            $default_bidding_game = 'open';
            $msg = 'Open Betting is Running';
        }
        
        // Allow user to override session
        if ($default_game === 'close') {
            $default_bidding_game = 'close';
            $msg = 'Close Betting is Running';
        } elseif ($default_game === 'open') {
            $default_bidding_game = 'open';
            $msg = 'Open Betting is Running';
        }
    }
    
    $default_bidding_date = 'today';
    $open_time = $_sm_data['open_time'];
    $close_time = $_sm_data['close_time'];
    $status = 1;
    
    // For session selector in form - both always available until cutoff
    $sm_open_available = ($now < $close_cutoff);
    $sm_close_available = ($now < $close_cutoff);
}
?>
