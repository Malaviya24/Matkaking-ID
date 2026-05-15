<?php
require_once __DIR__ . '/includes/bootstrap.php';

if (admin_is_logged_in()) {
    admin_redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (admin_validate_csrf()) {
        $username = trim($_POST['username'] ?? '');
        $password = (string) ($_POST['password'] ?? '');

        if (admin_login_with_password($username, $password)) {
            admin_redirect('index.php');
        }

        admin_flash('error', 'Invalid admin username or password.');
        admin_redirect('login.php');
    }
}

$flash = admin_take_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="assets/admin.css?v=1.0.0">
</head>
<body class="auth-page">
    <section class="auth-card">
        <h1>Admin Login</h1>
        <p>Manage users, balances, bets, and notifications.</p>
        <?php if ($flash) { ?>
            <div class="alert alert-<?php echo e($flash['type']); ?>"><?php echo e($flash['message']); ?></div>
        <?php } ?>
        <form method="post" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?php echo e(admin_csrf_token()); ?>">
            <label class="field">
                <span>Username</span>
                <input type="text" name="username" required autofocus>
            </label>
            <label class="field">
                <span>Password</span>
                <input type="password" name="password" required>
            </label>
            <button class="button primary" type="submit">Login</button>
        </form>
    </section>
</body>
</html>
