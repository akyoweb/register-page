<?php
require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $namefull = trim($_POST['namefull'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if (mb_strlen($namefull) < 3 || mb_strlen($namefull) > 20) {
        json_response(false, 'نام کاربری باید بین ۳ تا ۲۰ کاراکتر باشد');
    }
    if (strlen($password) < 6) {
        json_response(false, 'رمز عبور باید حداقل ۶ کاراکتر باشد');
    }

  
    $stmt = mysqli_prepare($db, 'SELECT id, namefull FROM user WHERE namefull = ? AND password = ? LIMIT 1');
    if (!$stmt) {
        json_response(false, 'خطا در آماده‌سازی کوئری');
    }
    mysqli_stmt_bind_param($stmt, 'ss', $namefull, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $remember = !empty($_POST['remember']);
        login_user((int) $row['id'], $row['namefull'], $remember);
        mysqli_stmt_close($stmt);
        json_response(true, 'خوش آمدی ' . $row['namefull'] . '! در حال انتقال به خانه...');
    } else {
        mysqli_stmt_close($stmt);
        json_response(false, 'نام کاربری یا رمز عبور اشتباه است');
    }
}
?>
<!DOCTYPE html>
<html lang="fa">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <title>ورود به حساب</title>
    <link rel="stylesheet" href="random.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="body">

    <div class="main">
        <div class="box">
            <h5>اطلاعات صحیح را وارد کنید</h5>

            <form method="post" action="register.php" data-ajax data-redirect="index.php">
                <div class="din">
                    <input maxlength="20" required name="namefull" id="namefull" class="in1" type="text"
                        placeholder=" شماره یا نام کاربری خود را وارد کنید">
                    <div id="nameError" class="error_div d-none">
                        <span class="error">نام خود را به درستی وارد کنید (حداقل ۳ حرف)</span>
                    </div>
                </div>

                <div class="din">
                    <input required name="password" id="password" maxlength="11" class="in1" type="password"
                        placeholder="رمز خود را وارد کنید">
                    <div id="passwordError" class="error_div d-none">
                        <span class="error">رمز عبور خود را به درستی وارد کنید (حداقل ۶ کاراکتر)</span>
                    </div>
                </div>

                <div>
                    <button type="submit" id="btn" class="btn btn-success">ورود</button>
                </div>

                <div class="check">
                    <label class="checktext" for="remember">
                        <input class="checktik" style="direction: rtl;" type="checkbox" name="remember" id="remember">
                        مرا به خاطر بسپار
                    </label>
                </div>
            </form>

            <div style="font-size: 14px; padding: 0px; margin-top: -15px;">
                رمز خود را فراموش کرده اید؟
                <a class="a" href="bazyabi.php">بازیابی</a> حساب
            </div>

            <div style="font-size: 14px;">
                آیا حسابی ندارید؟
                <a class="a" href="login.php">اینجا</a> ثبت نام کنید
            </div>
        </div>
    </div>

    <script src="ajax.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var namefull = document.getElementById("namefull");
            var password = document.getElementById("password");
            var btn = document.getElementById("btn");
            var nameError = document.getElementById("nameError");
            var passwordError = document.getElementById("passwordError");

            btn.disabled = true;

            function checkAllFields() {
                btn.disabled = !(namefull.value.length >= 3 && password.value.length >= 6);
            }

            namefull.addEventListener('input', function () {
                var ok = this.value.length >= 3;
                nameError.classList.toggle('d-none', ok);
                this.classList.toggle('error-input', !ok);
                this.classList.toggle('success-input', ok);
                checkAllFields();
            });

            password.addEventListener('input', function () {
                var ok = this.value.length >= 6;
                passwordError.classList.toggle('d-none', ok);
                this.classList.toggle('error-input', !ok);
                this.classList.toggle('success-input', ok);
                checkAllFields();
            });
        });
    </script>
</body>

</html>