<?php
error_reporting(E_ALL);
ini_set('display_errors', getenv('MAINMATKA_DISPLAY_ERRORS') === '1' ? '1' : '0');
ini_set('log_errors', '1');

$local_config_path = __DIR__ . '/local-config.php';
if (is_file($local_config_path)) {
    require_once $local_config_path;
}

function env_or_default($name, $default) {
    $value = getenv($name);
    if (($value === false || $value === '') && defined($name)) {
        $value = constant($name);
    }
    return ($value === false || $value === '') ? $default : $value;
}

function required_env($name) {
    $value = env_or_default($name, '');
    if ($value === '') {
        http_response_code(500);
        error_log("Missing required configuration: {$name}");
        die('Application configuration is incomplete.');
    }

    return $value;
}

$site_title = 'MainMatka';
$site_url = env_or_default('MAINMATKA_BACKEND_URL', '');
$admin_folder_name = 'cpanel_hgCBWj0S';

$package_name = 'com.MainMatka.web';

/**
 * Format number with Indian comma style: 1,00,00,000
 * For amounts: 10,000 / 1,00,000 / 1,00,00,000
 */
function app_format_money($amount) {
    $amount = (float) $amount;
    if ($amount < 0) {
        return '-' . app_format_money(abs($amount));
    }
    $amount = floor($amount);
    return number_format($amount);
}

$db_host = required_env('MAINMATKA_DB_HOST');
$db_user = required_env('MAINMATKA_DB_USER');
$db_pass = env_or_default('MAINMATKA_DB_PASS', '');
$db_name = required_env('MAINMATKA_DB_NAME');
$db_port = (int) env_or_default('MAINMATKA_DB_PORT', '3306');

$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port) or die("Error " . mysqli_error($con));

/**
 * Ensure required schema migrations exist. Idempotent and cheap —
 * runs once per process via a static flag. Keeps the PHP side from
 * crashing on a fresh deploy where the scraper hasn't yet bootstrapped
 * new columns it added later in the project's life.
 */
function app_ensure_schema_migrations()
{
    global $con;
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;

    // Add is_live column to scraped_markets if missing.
    // PHP 8.1+ mysqli throws mysqli_sql_exception by default; @ does not
    // catch exceptions, only warnings, so we use a real try/catch here.
    // Errno 1060 = duplicate column (already there) — expected, ignore.
    // Errno 1146 = table doesn't exist (scraper hasn't run) — also fine.
    try {
        mysqli_query($con, "ALTER TABLE scraped_markets ADD COLUMN is_live TINYINT(1) NOT NULL DEFAULT 0");
    } catch (mysqli_sql_exception $e) {
        $errno = (int) $e->getCode();
        if ($errno !== 1060 && $errno !== 1146) {
            error_log("schema migration warning ({$errno}): " . $e->getMessage());
        }
    }
}

app_ensure_schema_migrations();

// Public website base. Keep the default relative so local/admin navigation never jumps to a live domain.
define('SITEURL', env_or_default('MAINMATKA_SITE_URL', './'));
$home_url = SITEURL;
define('SITEDOMAIN', env_or_default('MAINMATKA_SITE_DOMAIN', $_SERVER['HTTP_HOST'] ?? 'localhost'));
define('SMS_AUTH_KEY', env_or_default('MAINMATKA_SMS_AUTH_KEY', ''));
define('SMS_SENDER_ID', env_or_default('MAINMATKA_SMS_SENDER_ID', ''));

$service_json = realpath(env_or_default('MAINMATKA_SERVICE_JSON_PATH', '')) ?: '';
define('SERVICE_ACCOUNT_KEY_FCM', $service_json);

//push notificaiton API ACCESS KEY
define('API_ACCESS_KEY', env_or_default('MAINMATKA_API_ACCESS_KEY', ''));

date_default_timezone_set(env_or_default('TZ', 'Asia/Kolkata'));

function app_is_secure_request()
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}

function app_cookie_options($expires)
{
    return [
        'expires' => $expires,
        'path' => '/',
        'secure' => app_is_secure_request(),
        'httponly' => true,
        'samesite' => 'Lax',
    ];
}

function app_set_cookie($name, $value, $expires)
{
    setcookie($name, (string) $value, app_cookie_options($expires));
}

function app_clear_auth_cookies()
{
    $expires = time() - 3600;
    foreach (['usr_id', 'usr_name', 'usr_mobile', 'api_access_token'] as $name) {
        app_set_cookie($name, '', $expires);
    }
}

function app_clear_user_session()
{
    unset(
        $_SESSION['usr_id'],
        $_SESSION['status'],
        $_SESSION['usr_name'],
        $_SESSION['usr_mobile'],
        $_SESSION['api_access_token']
    );
}

function app_password_hash($password)
{
    return password_hash((string) $password, PASSWORD_DEFAULT);
}

function app_password_verify($password, $storedHash)
{
    $storedHash = (string) $storedHash;
    if (preg_match('/^[a-f0-9]{32}$/i', $storedHash)) {
        return hash_equals(strtolower($storedHash), md5((string) $password));
    }

    return password_verify((string) $password, $storedHash);
}

function app_password_needs_rehash($storedHash)
{
    $storedHash = (string) $storedHash;
    return preg_match('/^[a-f0-9]{32}$/i', $storedHash)
        || password_needs_rehash($storedHash, PASSWORD_DEFAULT);
}

function app_random_token($bytes = 32)
{
    return bin2hex(random_bytes($bytes));
}

function app_set_user_session(array $user)
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }

    $_SESSION['usr_id'] = (int) $user['id'];
    $_SESSION['status'] = $user['status'] ?? 1;
    $_SESSION['usr_name'] = $user['username'] ?? ($user['name'] ?? '');
    $_SESSION['usr_mobile'] = $user['mobile'] ?? '';
    $_SESSION['api_access_token'] = $user['api_access_token'] ?? '';
}

function app_set_auth_cookies(array $user)
{
    $expires = time() + 30 * 24 * 60 * 60;
    app_set_cookie('usr_id', $user['id'] ?? '', $expires);
    app_set_cookie('usr_name', $user['username'] ?? ($user['name'] ?? ''), $expires);
    app_set_cookie('usr_mobile', $user['mobile'] ?? '', $expires);
    app_set_cookie('api_access_token', $user['api_access_token'] ?? '', $expires);
}

function app_login_user(array $user)
{
    app_set_user_session($user);
    app_set_auth_cookies($user);
}

function app_find_user_by_token($userId, $token)
{
    global $con;
    $userId = (int) $userId;
    $token = (string) $token;
    if ($userId <= 0 || $token === '') {
        return null;
    }

    $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE id = ? AND api_access_token = ? LIMIT 1");
    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, 'is', $userId, $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result ? mysqli_fetch_assoc($result) : null;
}

function app_restore_session_from_cookies()
{
    $sessionUserId = $_SESSION['usr_id'] ?? null;
    $sessionToken = $_SESSION['api_access_token'] ?? null;
    $cookieUserId = $_COOKIE['usr_id'] ?? null;
    $cookieToken = $_COOKIE['api_access_token'] ?? null;

    $userId = $sessionUserId ?: $cookieUserId;
    $token = $sessionToken ?: $cookieToken;

    if (empty($userId) || empty($token)) {
        return false;
    }

    $user = app_find_user_by_token((int) $userId, (string) $token);
    if (!$user || (string) ($user['status'] ?? '1') === '0') {
        app_clear_auth_cookies();
        app_clear_user_session();
        return false;
    }

    app_set_user_session($user);
    return true;
}

function app_logout_and_redirect($target = 'logout.php')
{
    app_clear_auth_cookies();
    $_SESSION = [];
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    header('Location: ' . $target);
    exit;
}

function app_require_current_user()
{
    if (!app_restore_session_from_cookies()) {
        $actual_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        header('Location: login.php?return_url=' . base64_encode($actual_url));
        exit;
    }

    $user = app_find_user_by_token((int) $_SESSION['usr_id'], (string) ($_SESSION['api_access_token'] ?? ''));
    if (!$user || (string) ($user['status'] ?? '1') === '0') {
        app_logout_and_redirect('logout.php');
    }

    return $user;
}

function app_csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function app_csrf_input()
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(app_csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function app_validate_csrf()
{
    $token = $_POST['csrf_token'] ?? '';
    return is_string($token) && hash_equals(app_csrf_token(), $token);
}

function app_money_value($value)
{
    if (!is_numeric($value)) {
        return 0.0;
    }

    return round((float) $value, 2);
}

function app_user_withdrawable_amount($userId)
{
    global $con;

    $userId = (int) $userId;
    $stmt = mysqli_prepare($con, "
        SELECT
            COALESCE(SUM(CASE WHEN type = 'win' AND debit_credit = 'credit' THEN CAST(amount AS DECIMAL(12,2)) ELSE 0 END), 0) -
            COALESCE(SUM(CASE WHEN type = 'withdraw' AND debit_credit = 'debit' AND status IN (1,2) THEN CAST(amount AS DECIMAL(12,2)) ELSE 0 END), 0) AS withdrawable
        FROM user_transaction
        WHERE user_id = ?
    ");
    if (!$stmt) {
        return 0.0;
    }

    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = $result ? mysqli_fetch_assoc($result) : null;

    return max(0.0, app_money_value($row['withdrawable'] ?? 0));
}

function app_place_bets($userId, $gameId, $gameType, array $bets, $starline = 0, $sessionType = '', $betDate = '')
{
    global $con;

    $userId = (int) $userId;
    $gameId = (int) $gameId;
    $gameType = (string) $gameType;
    $starline = (int) $starline;
    $date = ($betDate !== '') ? $betDate : date('Y-m-d');
    $time = date('h:i:s A');
    $rows = [];
    $total = 0.0;

    foreach ($bets as $digit => $amount) {
        $amount = app_money_value($amount);
        $digit = trim((string) $digit);
        if ($digit === '' || $amount < 100) {
            continue;
        }

        $rows[] = [
            'digit' => $digit,
            'amount' => $amount,
        ];
        $total += $amount;
    }

    if (!$rows || $total <= 0) {
        return ['ok' => false, 'reason' => 'empty'];
    }

    mysqli_begin_transaction($con);

    $stmt = mysqli_prepare($con, "SELECT balance FROM users WHERE id = ? LIMIT 1 FOR UPDATE");
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = $result ? mysqli_fetch_assoc($result) : null;

    if (!$user) {
        mysqli_rollback($con);
        return ['ok' => false, 'reason' => 'user'];
    }

    $balance = app_money_value($user['balance']);
    if ($balance < $total) {
        mysqli_rollback($con);
        return ['ok' => false, 'reason' => 'insufficient'];
    }

    $insert = mysqli_prepare($con, "INSERT INTO user_transaction(user_id,game_id,game_type,session_type,digit,date,time,amount,type,debit_credit,balance,starline) VALUES(?,?,?,?,?,?,?,?,'bid','debit',?,?)");
    if (!$insert) {
        mysqli_rollback($con);
        return ['ok' => false, 'reason' => 'insert'];
    }

    foreach ($rows as $row) {
        $balance = round($balance - $row['amount'], 2);
        mysqli_stmt_bind_param(
            $insert,
            'iisssssddi',
            $userId,
            $gameId,
            $gameType,
            $sessionType,
            $row['digit'],
            $date,
            $time,
            $row['amount'],
            $balance,
            $starline
        );

        if (!mysqli_stmt_execute($insert)) {
            mysqli_rollback($con);
            return ['ok' => false, 'reason' => 'insert'];
        }
    }

    $update = mysqli_prepare($con, "UPDATE users SET balance = ?, last_bid_placed_on = ? WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($update, 'dsi', $balance, $date, $userId);
    if (!mysqli_stmt_execute($update)) {
        mysqli_rollback($con);
        return ['ok' => false, 'reason' => 'balance'];
    }

    mysqli_commit($con);
    return ['ok' => true, 'count' => count($rows), 'total' => $total, 'balance' => $balance];
}

function app_safe_return_path($encodedReturnUrl)
{
    $decoded = base64_decode((string) $encodedReturnUrl, true);
    if (!is_string($decoded) || $decoded === '') {
        return 'index.php';
    }

    $parts = parse_url($decoded);
    $currentHost = $_SERVER['HTTP_HOST'] ?? '';
    if (!empty($parts['host']) && !hash_equals(strtolower($currentHost), strtolower($parts['host']))) {
        return 'index.php';
    }

    $path = $parts['path'] ?? '';
    if ($path === '' || preg_match('/[\r\n]/', $path)) {
        return 'index.php';
    }

    $query = isset($parts['query']) && $parts['query'] !== '' ? '?' . $parts['query'] : '';
    return ltrim($path, '/') . $query;
}

/**
 * Check if a scraped market is currently accepting bets.
 *
 * ALWAYS-OPEN MODEL:
 * Betting is ONLY blocked when the market is in the source's LIVE RESULT
 * block (is_live=1). All other states allow betting — if today's result
 * is already declared, the bet is placed for tomorrow (handled by the
 * calling code via $sm_bet_date).
 *
 * @param int $scraped_market_id  The scraped_markets.id
 * @param string $bet_side  'open' or 'close'
 * @return array ['allowed' => bool, 'reason' => string, 'market' => array (when allowed)]
 */
function app_scraped_market_bet_allowed($scraped_market_id, $bet_side = 'open')
{
    global $con;

    $id = (int) $scraped_market_id;
    $today = date('Y-m-d');

    // Get the most recent row for this market (today or latest)
    $stmt = mysqli_prepare($con, "SELECT * FROM scraped_markets WHERE id = ? LIMIT 1");
    if (!$stmt) {
        return ['allowed' => false, 'reason' => 'Betting is not available right now.'];
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $market = $result ? mysqli_fetch_assoc($result) : null;

    if (!$market) {
        return ['allowed' => false, 'reason' => 'Market not found.'];
    }

    // is_live may not exist on older DB schemas — default to 0 (not live)
    $is_live = (int) ($market['is_live'] ?? 0);

    // ONLY block when market is in LIVE RESULT section
    if ($is_live === 1) {
        return ['allowed' => false, 'reason' => 'Result is being declared, betting paused'];
    }

    // All other states: betting is allowed
    return ['allowed' => true, 'reason' => '', 'market' => $market];
}

/**
 * Check if a scraped market game_id (from URL like "scraped_123") is valid.
 * Returns the scraped market ID or false.
 */
function app_is_scraped_market_gid($gid)
{
    if (is_string($gid) && strpos($gid, 'scraped_') === 0) {
        return (int) substr($gid, 8);
    }
    return false;
}

?>
