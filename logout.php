<?php
// logout.php
session_start();

// 1) Clear all session variables
$_SESSION = [];

// 2) Delete the PHP session cookie
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

// 3) (Optional) Clear any custom cookies you might set
foreach (['user_id', 'user_name', 'user_type'] as $c) {
    setcookie($c, '', time() - 3600, '/');
}

// 4) Destroy the session
session_destroy();

// 5) Redirect to login page
header("Location: login.php");
exit;
