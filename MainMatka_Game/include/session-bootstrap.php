<?php
// Enable gzip compression for faster page loads
if (!ob_get_level() && !headers_sent() && extension_loaded('zlib')) {
    ini_set('zlib.output_compression', '1');
    ini_set('zlib.output_compression_level', '6');
}

$isSecureRequest = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

ini_set('session.cookie_lifetime', '2592000');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.cookie_secure', $isSecureRequest ? '1' : '0');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 2592000,
        'path' => '/',
        'secure' => $isSecureRequest,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
