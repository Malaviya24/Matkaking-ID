<?php
/**
 * Scraped Market Form Header
 * Shows market name with Open/Close session selector.
 */
if ($is_scraped_market && $bidding_status) {
?>
                <div class="row bidoptions-list tb-10">
                    <div class="col-6">
                        <select class="dateGameIDbox" name="game_id" id="scraped_session_select">
                            <?php if ($sm_open_available) { ?>
                            <option value="<?php echo htmlspecialchars($game_id, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $default_bidding_game === 'open' ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($game_name, ENT_QUOTES, 'UTF-8'); ?> (Open)
                            </option>
                            <?php } ?>
                            <?php if ($sm_close_available) { ?>
                            <option value="<?php echo htmlspecialchars($game_id, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $default_bidding_game === 'close' ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($game_name, ENT_QUOTES, 'UTF-8'); ?> (Close)
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <a class="dateGameIDbox">
                            <p><?php echo date('d/m/Y'); ?></p>
                        </a>
                    </div>
                </div>
<?php
}
?>
