<?php
// logout.php — خروج از حساب: حذف سشن و کوکی‌ها
require_once __DIR__ . '/auth.php';

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
setcookie('remember_me', '', time() - 42000, '/');
session_destroy();

header('Location: register.php');
exit;
