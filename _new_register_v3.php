<?php
session_start();
$db = new mysqli('localhost', 'root', '', 'login');
if ($db->connect_error) {
    die('Database connection failed: ' . $db->connect_error);
}
$conn = $db;

$ok = 'no';
$msg = '';
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $user = trim($_POST['username'] ?? '');
    $pass = (string) ($_POST['password'] ?? '');
    $rem = isset($_POST['remember']);

    $user = mysqli_real_escape_string($conn, $user);
    $stmt = mysqli_prepare($conn, 'SELECT id, namefull, password FROM user WHERE username = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 's', $user);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if ($row) {
        if ($row['password'] === $pass) {
            $_SESSION['userid'] = (int) $row['id'];
            $_SESSION['namefull'] = $row['namefull'];
            $_SESSION['username'] = $user;
            $ok = 'yes';
            $msg = 'ورود موفقیت‌آمیز بود';

            if ($rem) {
                $token = bin2hex(random_bytes(32));
                $tokek = mysqli_real_escape_string($conn, $token);
                mysqli_query($conn, "UPDATE user SET remember_token = '$tokek' WHERE id = " . (int) $row['id']);
                setcookie('remember_me', $token, time() + 60 * 60 * 24 * 30, '/');
            }
        } else {
            $msg = 'اطلاعات صحیح نیست';
        }
    } else {
        $msg = 'اطلاعات صحیح نیست';
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ورود</title>
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="random.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="auth-v2">
    <div class="auth-card-wrapper">
        <div class="card mb-3">
            <div class="card-body py-4 px-4">
                <div class="auth-logo">
                    <i class="fa-solid fa-right-to-bracket fa-3x"></i>
                </div>
                <h5 class="auth-title">
                    <i class="fa-solid fa-circle-check text-success"></i> اطلاعات صحیح را وارد کنید
                </h5>
                <form method="post" action="index.php">
                    <input type="hidden" name="action" value="login">
                    <div class="mb-3">
                        <label for="username" class="form-label"><i class="fa-solid fa-user text-primary"></i> نام
                            کاربری</label>
                        <input name="username" class="form-control" id="username" required minlength="3" maxlength="20"
                            placeholder="نام کاربری">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label"><i class="fa-solid fa-lock text-primary"></i> رمز
                            عبور</label>
                        <input name="password" type="password" class="form-control" id="password" required minlength="6"
                            maxlength="11" placeholder="رمز عبور">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember">
                        <label class="form-check-label" for="remember"><i class="fa-solid fa-clock-rotate-left"></i> مرا
                            به خاطر بسپار</label>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" id="btn" class="btn btn-primary w-100">
                            <i class="fa-solid fa-sign-in-alt"></i> ورود
                        </button>
                        <a class="btn btn-outline-secondary" href="bazyabi.php">
                            <i class="fa-solid fa-key"></i> فراموشی رمز عبور
                        </a>
                    </div>
                </form>

                <table class="table table-bordered table-striped mt-4 mb-0">
                    <thead>
                        <tr>
                            <th>کاربر</th>
                            <th>رمز</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>meysam1</td>
                            <td>123456789</td>
                        </tr>
                        <tr>
                            <td>amirhossein</td>
                            <td>112233445</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p class="auth-footer">
            <a href="login.php"><i class="fa-solid fa-user-plus"></i> ثبت نام جدید</a>
        </p>
    </div>

    <script src="ajax.js"></script>
    <script>
        document.getElementById('btn').addEventListener('click', function (e) {
            e.preventDefault();

            var form = document.querySelector('form');
            fetch(form.action || window.location.href, { method: 'POST', body: new FormData(form) })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.success) {
                        showTopAlert(data.message, 'error');
                        return;
                    }
                    showTopAlert('ورود موفق بود، در حال انتقال...', 'success');
                    setTimeout(function () { window.location.href = 'index.php'; }, 1200);
                })
                .catch(function () { showTopAlert('خطا در ارتباط با سرور', 'error'); });
        });
    </script>
</body>

</html>