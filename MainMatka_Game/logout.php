<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

app_clear_auth_cookies();

// Redirect to the login page or any other desired page
header("Location: login.php");
exit;
?>
