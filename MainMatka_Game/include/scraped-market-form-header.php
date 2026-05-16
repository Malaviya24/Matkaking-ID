<?php
/**
 * Scraped Market Form Header
 *
 * Renders the Date / Market header row for a scraped-market betting page.
 *
 * Column order matches the regular (parent_games) betting pages:
 *   - col-6 first-child  → date  (CSS pseudo-label: "Date")
 *   - col-6 last-child   → market select  (CSS pseudo-label: "Market")
 * head.php's CSS reorders these visually (last-child renders first) and
 * adds the "MARKET" / "DATE" labels via ::before pseudo-elements. We must
 * keep this DOM order so the labels line up with their values.
 *
 * Set $sm_force_open_only = true BEFORE including this file to hide the
 * Close session option (used by jodi / half-sangam pages where a "close"
 * session bet has no meaningful settlement path).
 */
if ($is_scraped_market && $bidding_status) {
    $sm_force_open_only = isset($sm_force_open_only) && $sm_force_open_only;
    $show_open  = $sm_open_available;
    $show_close = $sm_close_available && !$sm_force_open_only;
?>
                <div class="row bidoptions-list tb-10">
                    <div class="col-6">
                        <a class="dateGameIDbox">
                            <p><?php echo date('d/m/Y'); ?></p>
                        </a>
                    </div>
                    <div class="col-6">
                        <select class="dateGameIDbox" name="game_id" id="scraped_session_select">
                            <?php if ($show_open) { ?>
                            <option value="<?php echo htmlspecialchars($game_id, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $default_bidding_game === 'open' ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($game_name, ENT_QUOTES, 'UTF-8'); ?> (Open)
                            </option>
                            <?php } ?>
                            <?php if ($show_close) { ?>
                            <option value="<?php echo htmlspecialchars($game_id, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $default_bidding_game === 'close' ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($game_name, ENT_QUOTES, 'UTF-8'); ?> (Close)
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
<?php
}
?>
