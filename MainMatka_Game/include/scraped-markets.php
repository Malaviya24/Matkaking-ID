<?php
/**
 * Scraped Markets Display Component (Optimized)
 * Uses a single shared modal instead of one per market.
 */

function get_scraped_markets() {
    global $con;
    $today = date('Y-m-d');
    $sql_with_live = "SELECT id, market_name, market_slug, open_time, close_time, open_panna, open_ank, close_panna, close_ank, jodi, result_status, is_live, display_order FROM scraped_markets WHERE date = '$today' AND open_time != '' AND close_time != '' AND display_order > 0 ORDER BY display_order ASC";
    $sql_legacy = "SELECT id, market_name, market_slug, open_time, close_time, open_panna, open_ank, close_panna, close_ank, jodi, result_status, display_order FROM scraped_markets WHERE date = '$today' AND open_time != '' AND close_time != '' AND display_order > 0 ORDER BY display_order ASC";

    // PHP 8.1+ mysqli throws mysqli_sql_exception on errors. The first
    // SELECT may fail with errno 1054 (Unknown column 'is_live') on a
    // fresh deploy where the column hasn't been added yet — fall back
    // to the legacy SELECT and default is_live to 0 in PHP.
    $result = null;
    try {
        $result = mysqli_query($con, $sql_with_live);
    } catch (mysqli_sql_exception $e) {
        try {
            $result = mysqli_query($con, $sql_legacy);
        } catch (mysqli_sql_exception $e2) {
            error_log("get_scraped_markets fallback failed: " . $e2->getMessage());
            return [];
        }
    }
    $markets = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Default is_live to 0 when column is absent
            if (!array_key_exists('is_live', $row)) {
                $row['is_live'] = 0;
            }
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

        // Betting flow (always-open model):
        //   - Market in source's LIVE RESULT block → betting OFF
        //   - Otherwise → betting ON (if today closed, bets go to tomorrow)
        $is_live = (int) ($row['is_live'] ?? 0);
        if ($is_live === 1) {
            $bidding_status = 0;
            $msg = 'Result Declaring';
        } else {
            $bidding_status = 1;
            if ($row['result_status'] === 'closed') {
                $msg = 'Bet for Tomorrow';
            } elseif ($row['result_status'] === 'open_declared') {
                $msg = 'Betting For Close';
            } else {
                $msg = 'Betting Running';
            }
        }
?>
        <div class="game-card-new" data-market-slug="<?php echo $market_slug; ?>" data-market-gid="<?php echo $row['id']; ?>">
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

    // Live polling: update market betting status every 5 seconds
    (function(){
        function updateMarketStatus() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'api_market_status.php?t=' + Date.now(), true);
            xhr.timeout = 8000;
            xhr.onload = function() {
                if (xhr.status !== 200) return;
                try {
                    var data = JSON.parse(xhr.responseText);
                    if (!data.markets) return;
                    data.markets.forEach(function(m) {
                        var card = document.querySelector('[data-market-slug="' + m.slug + '"]');
                        if (!card) return;

                        // Update result display
                        var resultEl = card.querySelector('.game-result');
                        if (resultEl && resultEl.textContent !== m.result) {
                            resultEl.textContent = m.result;
                        }

                        // Update status badge
                        var badge = card.querySelector('.status-badge');
                        if (badge) {
                            badge.textContent = m.msg;
                            badge.className = 'status-badge ' + (m.bidding ? 'running' : 'closed');
                        }

                        // Update play button (enable/disable)
                        var playEl = card.querySelector('.game-card__play');
                        if (playEl) {
                            var gid = card.getAttribute('data-market-gid');
                            if (m.bidding && playEl.tagName === 'SPAN') {
                                // Was disabled, now should be enabled — convert to link
                                var link = document.createElement('a');
                                link.href = 'game-dashboard.php?game=' + m.slug + '&gid=' + gid + '&src=live';
                                link.className = 'game-card__play';
                                link.innerHTML = '<img src="assets/img/play.png" width="22" height="22" alt="" loading="lazy"><span>Play</span>';
                                playEl.parentNode.replaceChild(link, playEl);
                            } else if (!m.bidding && playEl.tagName === 'A') {
                                // Was enabled, now should be disabled — convert to span
                                var span = document.createElement('span');
                                span.className = 'game-card__play game-card__play--disabled';
                                span.innerHTML = '<img src="assets/img/play.png" width="22" height="22" alt="" loading="lazy"><span>Play</span>';
                                playEl.parentNode.replaceChild(span, playEl);
                            }
                        }
                    });
                } catch(e) {}
            };
            xhr.send();
        }

        // Poll every 5 seconds
        setInterval(updateMarketStatus, 5000);
    })();
    </script>
<?php
}
?>
