<?php
// auth.php — helpers مشترک برای سشن، کوکی و پاسخ‌های AJAX
require_once __DIR__ . '/database.php';

// اطمینان از شروع سشن
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// پاسخ JSON و توقف اجرا
function json_response($success, $message, $extra = [])
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

// ورود کاربر: ساخت سشن + کوکی «مرا به خاطر بسپار»
function login_user($userId, $namefull, $remember = false)
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $userId;
    $_SESSION['namefull'] = $namefull;
    if ($remember) {
        setcookie('remember_me', (string) (int) $userId, time() + 60 * 60 * 24 * 30, '/', '', false, true);
    }
}

// کاربر فعلی از سشن
function current_user()
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    return ['id' => (int) $_SESSION['user_id'], 'namefull' => $_SESSION['namefull'] ?? ''];
}

// اگر سشن نبود، از کوکی یادآوری سشن را بازسازی کن
function restore_from_cookie()
{
    if (current_user() || empty($_COOKIE['remember_me'])) {
        return;
    }
    $id = (int) $_COOKIE['remember_me'];
    $db = $GLOBALS['db'];
    $stmt = mysqli_prepare($db, 'SELECT id, namefull FROM user WHERE id = ? LIMIT 1');
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($res)) {
            login_user((int) $row['id'], $row['namefull']);
        }
        mysqli_stmt_close($stmt);
    }
}

// محافظ صفحات: اگر لاگین نبود به صفحه ورود برو
function require_login($redirect = 'register.php')
{
    restore_from_cookie();
    if (!current_user()) {
        header('Location: ' . $redirect);
        exit;
    }
}
