<?php
require_once __DIR__ . '/includes/bootstrap.php';

unset($_SESSION['admin_logged_in'], $_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_source']);
admin_flash('success', 'Logged out successfully.');
admin_redirect('login.php');
