<?php
require_once __DIR__ . '/../../include/session-bootstrap.php';

require_once __DIR__ . '/../../include/connect.php';
require_once __DIR__ . '/../../include/functions.php';

function admin_env($name, $default = '')
{
    $value = getenv($name);
    if (($value === false || $value === '') && defined($name)) {
        $value = constant($name);
    }
    return ($value === false || $value === '') ? $default : $value;
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function admin_money($value)
{
    return number_format((float) $value, 2);
}

function admin_redirect($path)
{
    header('Location: ' . $path);
    exit;
}

function admin_is_logged_in()
{
    return !empty($_SESSION['admin_logged_in']);
}

function admin_require_login()
{
    if (!admin_is_logged_in()) {
        admin_redirect('login.php');
    }
}

function admin_csrf_token()
{
    if (empty($_SESSION['admin_csrf_token'])) {
        $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['admin_csrf_token'];
}

function admin_validate_csrf()
{
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(admin_csrf_token(), $token)) {
        admin_flash('error', 'Invalid request token. Please try again.');
        return false;
    }

    return true;
}

function admin_flash($type, $message)
{
    $_SESSION['admin_flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function admin_take_flash()
{
    $flash = $_SESSION['admin_flash'] ?? null;
    unset($_SESSION['admin_flash']);
    return $flash;
}

function admin_table_exists($table)
{
    global $con;
    static $cache = [];

    if (!preg_match('/^[A-Za-z0-9_]+$/', $table)) {
        return false;
    }

    if (array_key_exists($table, $cache)) {
        return $cache[$table];
    }

    $safeTable = mysqli_real_escape_string($con, $table);
    $result = mysqli_query($con, "SHOW TABLES LIKE '{$safeTable}'");
    $cache[$table] = $result && mysqli_num_rows($result) > 0;

    return $cache[$table];
}

function admin_column_exists($table, $column)
{
    global $con;
    static $cache = [];

    if (!preg_match('/^[A-Za-z0-9_]+$/', $table) || !preg_match('/^[A-Za-z0-9_]+$/', $column)) {
        return false;
    }

    $key = $table . '.' . $column;
    if (array_key_exists($key, $cache)) {
        return $cache[$key];
    }

    $safeTable = mysqli_real_escape_string($con, $table);
    $safeColumn = mysqli_real_escape_string($con, $column);
    $result = mysqli_query($con, "SHOW COLUMNS FROM `{$safeTable}` LIKE '{$safeColumn}'");
    $cache[$key] = $result && mysqli_num_rows($result) > 0;

    return $cache[$key];
}

function admin_fetch_one($sql)
{
    global $con;
    $result = mysqli_query($con, $sql);
    if (!$result) {
        return null;
    }

    return mysqli_fetch_assoc($result) ?: null;
}

function admin_fetch_all($sql)
{
    global $con;
    $result = mysqli_query($con, $sql);
    if (!$result) {
        return [];
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function admin_count_value($sql)
{
    $row = admin_fetch_one($sql);
    if (!$row) {
        return 0;
    }

    $value = reset($row);
    return $value === null ? 0 : (float) $value;
}

function admin_date_param($name, $default)
{
    $value = $_GET[$name] ?? $default;
    if (!is_string($value) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
        return $default;
    }

    return $value;
}

function admin_decimal_param($name)
{
    $value = $_GET[$name] ?? '';
    if ($value === '' || $value === null || !is_numeric($value)) {
        return '';
    }

    return max(0, (float) $value);
}

function admin_text_param($name, $maxLength = 100)
{
    $value = $_GET[$name] ?? '';
    if (!is_string($value)) {
        return '';
    }

    return trim(substr($value, 0, $maxLength));
}

function admin_page_param()
{
    $page = (int) ($_GET['page'] ?? 1);
    return max(1, $page);
}

function admin_escape_like($value)
{
    global $con;
    return mysqli_real_escape_string($con, str_replace(['%', '_'], ['\%', '\_'], $value));
}

function admin_bet_filters($defaultToday = false)
{
    $today = date('Y-m-d');
    return [
        'date_from' => admin_date_param('date_from', $defaultToday ? $today : date('Y-m-d', strtotime('-7 days'))),
        'date_to' => admin_date_param('date_to', $today),
        'min_amount' => admin_decimal_param('min_amount'),
        'max_amount' => admin_decimal_param('max_amount'),
        'digit' => admin_text_param('digit', 20),
        'game_type' => admin_text_param('game_type', 40),
        'market' => admin_text_param('market', 20),
        'user_query' => admin_text_param('user_query', 80),
    ];
}

function admin_bet_where_sql(array $filters, $transactionAlias = 'ut', $userAlias = 'u')
{
    global $con;

    $ta = preg_replace('/[^A-Za-z0-9_]/', '', $transactionAlias);
    $ua = preg_replace('/[^A-Za-z0-9_]/', '', $userAlias);
    $conditions = ["{$ta}.type = 'bid'"];

    if (!empty($filters['date_from'])) {
        $from = mysqli_real_escape_string($con, $filters['date_from']);
        $conditions[] = "{$ta}.date >= '{$from}'";
    }

    if (!empty($filters['date_to'])) {
        $to = mysqli_real_escape_string($con, $filters['date_to']);
        $conditions[] = "{$ta}.date <= '{$to}'";
    }

    if ($filters['min_amount'] !== '') {
        $min = (float) $filters['min_amount'];
        $conditions[] = "CAST({$ta}.amount AS DECIMAL(12,2)) >= {$min}";
    }

    if ($filters['max_amount'] !== '') {
        $max = (float) $filters['max_amount'];
        $conditions[] = "CAST({$ta}.amount AS DECIMAL(12,2)) <= {$max}";
    }

    if ($filters['digit'] !== '') {
        $digit = mysqli_real_escape_string($con, $filters['digit']);
        $conditions[] = "{$ta}.digit = '{$digit}'";
    }

    if ($filters['game_type'] !== '') {
        $gameType = mysqli_real_escape_string($con, $filters['game_type']);
        $conditions[] = "{$ta}.game_type = '{$gameType}'";
    }

    if ($filters['market'] === 'main') {
        $conditions[] = "COALESCE({$ta}.starline, 0) = 0";
    } elseif ($filters['market'] === 'starline') {
        $conditions[] = "COALESCE({$ta}.starline, 0) = 1";
    }

    if ($filters['user_query'] !== '') {
        $query = admin_escape_like($filters['user_query']);
        $conditions[] = "({$ua}.name LIKE '%{$query}%' OR {$ua}.username LIKE '%{$query}%' OR {$ua}.mobile LIKE '%{$query}%')";
    }

    return implode(' AND ', $conditions);
}

function admin_game_name_sql($transactionAlias = 'ut')
{
    $ta = preg_replace('/[^A-Za-z0-9_]/', '', $transactionAlias);
    return "COALESCE(g.name, sl.name, sm.market_name, CONCAT('Game #', {$ta}.game_id))";
}

function admin_bind_and_execute($stmt, array $values)
{
    if (!$stmt) {
        return false;
    }

    if ($values) {
        $types = str_repeat('s', count($values));
        $refs = [$types];
        foreach ($values as $key => $value) {
            $values[$key] = (string) $value;
            $refs[] = &$values[$key];
        }
        call_user_func_array([$stmt, 'bind_param'], $refs);
    }

    return mysqli_stmt_execute($stmt);
}

function admin_insert_row($table, array $data)
{
    global $con;

    $columns = [];
    $values = [];
    foreach ($data as $column => $value) {
        if (admin_column_exists($table, $column)) {
            $columns[] = $column;
            $values[] = $value;
        }
    }

    if (!$columns) {
        return false;
    }

    $columnSql = '`' . implode('`,`', $columns) . '`';
    $placeholders = implode(',', array_fill(0, count($columns), '?'));
    $stmt = mysqli_prepare($con, "INSERT INTO `{$table}` ({$columnSql}) VALUES ({$placeholders})");

    return admin_bind_and_execute($stmt, $values);
}

function admin_record_wallet_transaction($userId, $amount, $direction, $balanceAfter, $note)
{
    $date = date('Y-m-d');
    $time = date('h:i:s A');
    $title = $direction === 'credit' ? 'Credited By Admin' : 'Debited By Admin';

    return admin_insert_row('user_transaction', [
        'user_id' => $userId,
        'game_id' => '',
        'game_type' => '',
        'digit' => '',
        'date' => $date,
        'time' => $time,
        'amount' => $amount,
        'type' => 'deposit',
        'debit_credit' => $direction,
        'balance' => $balanceAfter,
        'status' => 1,
        'title' => $title,
        'api_response' => $note ?: 'from Admin Panel',
        'starline' => 0,
    ]);
}

function admin_login_with_password($username, $password)
{
    global $con;

    $username = trim($username);
    $envUser = admin_env('MAINMATKA_ADMIN_USERNAME', '');
    $envPasswordHash = admin_env('MAINMATKA_ADMIN_PASSWORD_HASH', '');

    if ($envUser !== '' && $envPasswordHash !== '' && hash_equals($envUser, $username) && password_verify($password, $envPasswordHash)) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = 'Admin';
        $_SESSION['admin_source'] = 'env';
        return true;
    }

    if (!admin_column_exists('users', 'role')) {
        return false;
    }

    $mobile = preg_match('/^\d{10}$/', $username) ? '+91' . $username : $username;
    $stmt = mysqli_prepare($con, "SELECT id, name, username, mobile, password FROM users WHERE (username = ? OR mobile = ?) AND status = 1 AND role IN ('admin', 'super_admin') LIMIT 1");
    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, 'ss', $username, $mobile);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = $result ? mysqli_fetch_assoc($result) : null;

    if (!$row || !app_password_verify($password, $row['password'] ?? '')) {
        return false;
    }

    if (app_password_needs_rehash($row['password'] ?? '')) {
        $newHash = app_password_hash($password);
        $update = mysqli_prepare($con, "UPDATE users SET password = ? WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($update, 'si', $newHash, $row['id']);
        mysqli_stmt_execute($update);
    }

    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_id'] = $row['id'];
    $_SESSION['admin_name'] = $row['username'] ?: $row['name'];
    $_SESSION['admin_source'] = 'users';
    return true;
}

function admin_existing_user_columns()
{
    $columns = ['id', 'name', 'username', 'mobile', 'balance', 'status', 'date_created', 'role', 'last_bid_placed_on', 'total_deposit', 'total_withdrawal'];
    return array_values(array_filter($columns, function ($column) {
        return admin_column_exists('users', $column);
    }));
}

function admin_get_setting($name, $default = '')
{
    global $con;
    $safeName = mysqli_real_escape_string($con, $name);
    $row = admin_fetch_one("SELECT value FROM settings WHERE name = '{$safeName}' LIMIT 1");
    return $row && isset($row['value']) ? $row['value'] : $default;
}

function admin_save_setting($name, $value)
{
    global $con;
    if (!admin_table_exists('settings') || !admin_column_exists('settings', 'name') || !admin_column_exists('settings', 'value')) {
        return false;
    }

    $safeName = mysqli_real_escape_string($con, $name);
    $exists = admin_fetch_one("SELECT name FROM settings WHERE name = '{$safeName}' LIMIT 1");

    if ($exists) {
        $stmt = mysqli_prepare($con, "UPDATE settings SET value = ? WHERE name = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'ss', $value, $name);
        return mysqli_stmt_execute($stmt);
    }

    $stmt = mysqli_prepare($con, "INSERT INTO settings(name, value) VALUES(?, ?)");
    mysqli_stmt_bind_param($stmt, 'ss', $name, $value);
    return mysqli_stmt_execute($stmt);
}
?>
