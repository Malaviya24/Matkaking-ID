<?php
/**
 * Settlement API Endpoint
 * 
 * Called by the Python scraper when a result status transition is detected.
 * Accepts POST with: market_id, settlement_type, secret_key
 * Returns JSON response with settlement results.
 * 
 * Task 6.1 - Requirements: 2.1, 2.2, 5.1
 */
header('Content-Type: application/json');

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Load dependencies
require_once __DIR__ . '/../include/connect.php';
require_once __DIR__ . '/../include/functions.php';
require_once __DIR__ . '/../include/settle-engine.php';

// Validate secret key
$secret_key = $_POST['secret_key'] ?? $_SERVER['HTTP_X_SETTLEMENT_KEY'] ?? '';
$configured_secret = env_or_default('MAINMATKA_SETTLEMENT_SECRET', 'mainmatka_settle_2024');

if ($secret_key === '' || !hash_equals($configured_secret, $secret_key)) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Validate parameters
$market_id = isset($_POST['market_id']) ? (int) $_POST['market_id'] : 0;
$settlement_type = $_POST['settlement_type'] ?? '';

if ($market_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid market_id']);
    exit;
}

if (!in_array($settlement_type, ['open', 'close'], true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid settlement_type. Must be "open" or "close"']);
    exit;
}

// Execute settlement
$result = settle_market($market_id, $settlement_type);

// Return result
http_response_code($result['success'] ? 200 : 500);
echo json_encode($result);
