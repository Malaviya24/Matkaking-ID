<?php
/**
 * Scraped Market Bet Validation
 * Include this in betting pages AFTER $date/$time are set and BEFORE $game_time check.
 * 
 * For scraped markets:
 * - Validates betting is allowed
 * - Sets $game_id to numeric scraped market ID (for DB storage)
 * - Sets $_scraped_bet_validated = true to skip the normal time check
 */
$_scraped_bet_id = app_is_scraped_market_gid($_POST['pgid'] ?? '');
$_scraped_bet_validated = false;

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
}
?>
