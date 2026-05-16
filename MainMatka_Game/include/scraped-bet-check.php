<?php
/**
 * Scraped Market Bet Validation (Always-Open Model)
 * Include this in betting pages AFTER $date/$time are set and BEFORE $game_time check.
 * 
 * For scraped markets:
 * - Validates betting is allowed (only blocked when is_live=1)
 * - Sets $game_id to numeric scraped market ID (for DB storage)
 * - Sets $_scraped_bet_validated = true to skip the normal time check
 * - Sets $_scraped_bet_date to the effective resolution date
 *   (today if result not yet closed, tomorrow if today's result is done)
 */
$_scraped_bet_id = app_is_scraped_market_gid($_POST['pgid'] ?? '');
$_scraped_bet_validated = false;
$_scraped_bet_date = date('Y-m-d');

if ($_scraped_bet_id) {
    $_bet_side = ($default_game === 'close') ? 'close' : 'open';
    $_scraped_check = app_scraped_market_bet_allowed($_scraped_bet_id, $_bet_side);
    if (!$_scraped_check['allowed']) {
        echo "<script>alert('No Bets Taken! " . addslashes($_scraped_check['reason']) . "')</script>";
        echo "<script>window.location = 'index.php';</script>";
        exit;
    }
    // Use the numeric scraped market ID as game_id for database
    $game_id = (int) $_scraped_bet_id;
    $_scraped_bet_validated = true;
    
    // Determine bet resolution date
    $market_row = $_scraped_check['market'] ?? null;
    if ($market_row) {
        $rs = $market_row['result_status'] ?? 'waiting';
        if ($rs === 'closed') {
            // Today's result is done → bet resolves tomorrow
            $_scraped_bet_date = date('Y-m-d', strtotime('+1 day'));
        } else {
            $_scraped_bet_date = date('Y-m-d');
        }
    }
}
?>
