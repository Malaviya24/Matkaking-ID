<?php
/**
 * API endpoint: Returns current scraped market status for live polling.
 * Called by AJAX on the index page to update betting status in real-time.
 * 
 * Returns JSON: { markets: [ { slug, is_live, result_status, result_display } ] }
 */
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');

require_once __DIR__ . '/include/connect.php';

$today = date('Y-m-d');

$sql = "SELECT market_slug, is_live, result_status, open_panna, open_ank, close_panna, close_ank, jodi 
        FROM scraped_markets 
        WHERE date = '$today' AND open_time != '' AND close_time != '' AND display_order > 0 
        ORDER BY display_order ASC";

$result = null;
try {
    $result = mysqli_query($con, $sql);
} catch (mysqli_sql_exception $e) {
    echo json_encode(['error' => 'db_error']);
    exit;
}

$markets = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $is_live = (int)($row['is_live'] ?? 0);
        $status = $row['result_status'] ?? 'waiting';
        
        // Determine betting status and message
        if ($is_live === 1) {
            $bidding_status = 0;
            $msg = 'Result Declaring';
        } else {
            $bidding_status = 1;
            if ($status === 'closed') {
                $msg = 'Bet for Tomorrow';
            } elseif ($status === 'open_declared') {
                $msg = 'Betting For Close';
            } else {
                $msg = 'Betting Running';
            }
        }
        
        // Format result display
        if ($status === 'waiting') {
            $result_display = '***-**-***';
        } elseif ($status === 'open_declared') {
            $panna = $row['open_panna'] !== '' ? $row['open_panna'] : '***';
            $ank = $row['open_ank'] !== '' ? $row['open_ank'] : '*';
            $result_display = $panna . '-' . $ank . '*-***';
        } elseif ($status === 'closed') {
            $panna = $row['open_panna'] !== '' ? $row['open_panna'] : '***';
            $j = $row['jodi'] !== '' ? $row['jodi'] : '**';
            $cpanna = $row['close_panna'] !== '' ? $row['close_panna'] : '***';
            $result_display = $panna . '-' . $j . '-' . $cpanna;
        } else {
            $result_display = '***-**-***';
        }
        
        $markets[] = [
            'slug' => $row['market_slug'],
            'is_live' => $is_live,
            'status' => $status,
            'bidding' => $bidding_status,
            'msg' => $msg,
            'result' => $result_display,
        ];
    }
}

echo json_encode(['markets' => $markets]);
