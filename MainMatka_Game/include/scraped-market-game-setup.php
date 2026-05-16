<?php
/**
 * Scraped Market Game Setup
 * 
 * NEW BETTING MODEL (always-open):
 * - Betting is ALWAYS open unless market is in LIVE RESULT block (is_live=1)
 * - If today's result is fully declared (closed), bets are placed for TOMORROW
 * - If today's open result is declared, close-session bets are for today,
 *   but jodi/half-sangam/full-sangam bets roll to tomorrow (they need both halves)
 * - The bet's effective date ($sm_bet_date) is passed to the form and used
 *   by app_place_bets() to store the correct resolution date
 */

$is_scraped_market = false;
$_scraped_pgid = app_is_scraped_market_gid($parent_game_id);

if ($_scraped_pgid) {
    $is_scraped_market = true;
    
    $today = date('Y-m-d');
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    
    $_sm_stmt = mysqli_prepare($con, "SELECT * FROM scraped_markets WHERE id = ? AND date = ? LIMIT 1");
    mysqli_stmt_bind_param($_sm_stmt, 'is', $_scraped_pgid, $today);
    mysqli_stmt_execute($_sm_stmt);
    $_sm_result = mysqli_stmt_get_result($_sm_stmt);
    $_sm_data = $_sm_result ? mysqli_fetch_assoc($_sm_result) : null;
    
    if (!$_sm_data) {
        // Try to find by slug (market might not have today's row yet)
        $_sm_stmt2 = mysqli_prepare($con, "SELECT * FROM scraped_markets WHERE id = ? ORDER BY date DESC LIMIT 1");
        mysqli_stmt_bind_param($_sm_stmt2, 'i', $_scraped_pgid);
        mysqli_stmt_execute($_sm_stmt2);
        $_sm_result2 = mysqli_stmt_get_result($_sm_stmt2);
        $_sm_data = $_sm_result2 ? mysqli_fetch_assoc($_sm_result2) : null;
        
        if (!$_sm_data) {
            echo "<script>alert('Market not available.')</script>";
            echo "<script>window.location = 'index.php';</script>";
            exit;
        }
    }
    
    // Set game variables
    $game_name = $_sm_data['market_name'];
    $game_id = 'scraped_' . $_scraped_pgid;
    $child_game_id = 'scraped_' . $_scraped_pgid;
    $child_open = 'scraped_' . $_scraped_pgid;
    $child_close = 'scraped_' . $_scraped_pgid;
    $sm_market_slug = $_sm_data['market_slug'];
    
    // Check current state
    $is_live = (int) ($_sm_data['is_live'] ?? 0);
    $result_status = $_sm_data['result_status'] ?? 'waiting';
    $open_result_declared = ($result_status === 'open_declared' || $result_status === 'closed');
    $market_closed_today = ($result_status === 'closed');
    
    // ─── BETTING STATUS ─────────────────────────────────────────────
    // Only closed when market is in LIVE RESULT block
    if ($is_live === 1) {
        $bidding_status = 0;
        $msg = 'Result is being declared';
        $default_bidding_game = '';
    } else {
        // Betting is ALWAYS open
        $bidding_status = 1;
        
        if ($market_closed_today) {
            // Today's full result is done → bets are for tomorrow
            $default_bidding_game = 'open';
            $msg = 'Betting for Tomorrow';
        } elseif ($open_result_declared) {
            // Open declared, close not yet → close session for today
            $default_bidding_game = 'close';
            $msg = 'Close Betting Running';
        } else {
            $default_bidding_game = 'open';
            $msg = 'Open Betting Running';
        }
        
        // Allow user to override session (only if not closed for today)
        if (!$market_closed_today) {
            if ($default_game === 'close') {
                $default_bidding_game = 'close';
            } elseif ($default_game === 'open') {
                $default_bidding_game = 'open';
            }
        }
    }
    
    // ─── BET DATE CALCULATION ───────────────────────────────────────
    // This is the date the bet resolves on (used by settlement engine)
    if ($market_closed_today) {
        // Today done → all bets are for tomorrow
        $sm_bet_date = $tomorrow;
    } else {
        // Today still in progress → bets are for today
        $sm_bet_date = $today;
    }
    
    $default_bidding_date = ($sm_bet_date === $tomorrow) ? 'tomorrow' : 'today';
    $open_time = $_sm_data['open_time'];
    $close_time = $_sm_data['close_time'];
    $status = 1;
    
    // For session selector in form — both available unless market is live
    $sm_open_available = ($bidding_status === 1);
    $sm_close_available = ($bidding_status === 1 && !$market_closed_today);
}
?>
