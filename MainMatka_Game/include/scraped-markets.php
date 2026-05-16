<?php
/**
 * Scraped Markets Display Component (Optimized)
 * Uses a single shared modal instead of one per market.
 */

function get_scraped_markets() {
    global $con;
    $today = date('Y-m-d');
    $sql = "SELECT id, market_name, market_slug, open_time, close_time, open_panna, open_ank, close_panna, close_ank, jodi, result_status, display_order FROM scraped_markets WHERE date = '$today' AND open_time != '' AND close_time != '' AND display_order > 0 ORDER BY display_order ASC";
    $result = mysqli_query($con, $sql);
    $markets = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $markets[] = $row;
        }
    }
    return $markets;
}

function format_scraped_result($row) {
    $status = $row['result_status'] ?? 'waiting';

    // Strictly enforce waiting format — no other format allowed while waiting
    if ($status === 'waiting') {
        return '***-**-***';
    }

    $open_panna = $row['open_panna'] ?? '';
    $open_ank = $row['open_ank'] ?? '';
    $close_panna = $row['close_panna'] ?? '';
    $jodi = $row['jodi'] ?? '';

    // Force immediate update on status transition to open_declared
    if ($status === 'open_declared') {
        $panna = $open_panna !== '' ? $open_panna : '***';
        $ank = $open_ank !== '' ? $open_ank : '*';
        return $panna . '-' . $ank . '*-***';
    }

    // Force immediate update on status transition to closed
    if ($status === 'closed') {
        $panna = $open_panna !== '' ? $open_panna : '***';
        $j = $jodi !== '' ? $jodi : '**';
        $cpanna = $close_panna !== '' ? $close_panna : '***';
        return $panna . '-' . $j . '-' . $cpanna;
    }

    // Fallback for any unknown status
    return '***-**-***';
}

function render_scraped_markets() {
    $markets = get_scraped_markets();
    if (empty($markets)) return;

    foreach ($markets as $row) {
        $market_name = htmlspecialchars($row['market_name'], ENT_QUOTES, 'UTF-8');
        $open_time = htmlspecialchars($row['open_time'], ENT_QUOTES, 'UTF-8');
        $close_time = htmlspecialchars($row['close_time'], ENT_QUOTES, 'UTF-8');
        $result_display = format_scraped_result($row);
        $market_slug = $row['market_slug'];

        // Betting logic: open all day, close only 10 min before close_time
        $now = time();
        $open_ts = strtotime(date('Y-m-d') . ' ' . $row['open_time']);
        $close_ts = strtotime(date('Y-m-d') . ' ' . $row['close_time']);
        if ($close_ts < $open_ts) {
            $close_ts = strtotime(date('Y-m-d', strtotime('+1 day')) . ' ' . $row['close_time']);
        }
        $close_cutoff = $close_ts - (10 * 60);
        $result_done_time = $close_ts + (2 * 60);

        if ($now >= $close_cutoff && $now < $result_done_time) {
            $bidding_status = 0;
            $msg = 'Betting is Closed';
        } else {
            $bidding_status = 1;
            $msg = ($row['result_status'] === 'open_declared') ? 'Betting For Close' : 'Betting Running';
        }
?>
        <div class="game-card-new">
            <button type="button" class="game-card__clock" onclick="showTimeModal('<?php echo $market_name; ?>','<?php echo $open_time; ?>','<?php echo $close_time; ?>')" aria-label="Timings">
                <img src="assets/img/clock.png" width="30" height="30" alt="" loading="lazy">
            </button>
            <div class="game-card__main">
                <div class="game-card__title-row">
                    <span class="game-title"><?php echo $market_name; ?></span>
                </div>
                <div class="game-result"><?php echo $result_display; ?></div>
                <div class="game-card__meta"><?php echo $open_time; ?> | <?php echo $close_time; ?></div>
                <div class="status-badge <?php echo $bidding_status ? 'running' : 'closed'; ?>"><?php echo $msg; ?></div>
            </div>
            <?php if ($bidding_status) { ?>
                <a href="game-dashboard.php?game=<?php echo $market_slug; ?>&gid=<?php echo $row['id']; ?>&src=live" class="game-card__play">
                    <img src="assets/img/play.png" width="22" height="22" alt="" loading="lazy"><span>Play</span>
                </a>
            <?php } else { ?>
                <span class="game-card__play game-card__play--disabled"><img src="assets/img/play.png" width="22" height="22" alt="" loading="lazy"><span>Play</span></span>
            <?php } ?>
        </div>
<?php
    }

    // Single shared modal for all markets
?>
    <div class="modal fade game-time-modal" id="sharedTimeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header game-time-modal__header">
                    <h5 class="modal-title game-time-modal__title" id="stm_title"></h5>
                    <button type="button" class="close game-time-modal__close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body game-time-modal__body">
                    <div class="game-time-modal__row">
                        <span class="game-time-modal__ico"><img src="assets/img/clock.png" width="40" height="40" alt=""></span>
                        <span class="game-time-modal__label">Open Bid Ends</span>
                        <span class="game-time-modal__value" id="stm_open"></span>
                    </div>
                    <div class="game-time-modal__row">
                        <span class="game-time-modal__ico"><img src="assets/img/clock1.png" width="40" height="40" alt=""></span>
                        <span class="game-time-modal__label">Close Bid Ends</span>
                        <span class="game-time-modal__value" id="stm_close"></span>
                    </div>
                </div>
                <div class="modal-footer game-time-modal__footer">
                    <button type="button" class="game-time-modal__ok" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    function showTimeModal(name, open, close) {
        document.getElementById('stm_title').textContent = name;
        document.getElementById('stm_open').textContent = open;
        document.getElementById('stm_close').textContent = close;
        $('#sharedTimeModal').modal('show');
    }
    </script>
<?php
}
?>
