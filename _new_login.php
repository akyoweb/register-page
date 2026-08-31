

<?php
require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ---------- اعتبارسنجی سمت سرور ----------
    $namefull = trim($_POST['namefull'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if (mb_strlen($namefull) < 3 || mb_strlen($namefull) > 20) {
        json_response(false, 'نام کاربری باید بین ۳ تا ۲۰ کاراکتر باشد');
    }
    if (!preg_match('/^09[0-9]{9}$/', $phone)) {
        json_response(false, 'شماره تلفن معتبر نیست (مثال: 09123456789)');
    }
    if (strlen($password) < 6 || strlen($password) > 11) {
        json_response(false, 'رمز عبور باید بین ۶ تا ۱۱ کاراکتر باشد');
    }

    // ---------- جلوگیری از ثبت نام تکراری ----------
    $check = mysqli_prepare($db, 'SELECT id FROM user WHERE namefull = ? OR phone = ? LIMIT 1');
    if ($check) {
        mysqli_stmt_bind_param($check, 'ss', $namefull, $phone);
        mysqli_stmt_execute($check);
        if (mysqli_fetch_assoc(mysqli_stmt_get_result($check))) {
            mysqli_stmt_close($check);
            json_response(false, 'این نام کاربری یا شماره تلفن قبلاً ثبت شده است');
        }
        mysqli_stmt_close($check);
    }

    // ---------- ثبت نام با prepared statement ----------
    $stmt = mysqli_prepare($db, 'INSERT INTO user(namefull, phone, password) VALUES (?, ?, ?)');
    if (!$stmt) {
        json_response(false, 'خطا در آماده‌سازی کوئری');
    }
    mysqli_stmt_bind_param($stmt, 'sss', $namefull, $phone, $password);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        json_response(false, 'ثبت نام انجام نشد. لطفاً دوباره تلاش کنید');
    }
    $newId = mysqli_insert_id($db);
    mysqli_stmt_close($stmt);

    // ---------- ورود خودکار بعد از ثبت نام ----------
    login_user($newId, $namefull, !empty($_POST['remember']));
    json_response(true, 'ثبت نام با موفقیت انجام شد. خوش آمدی ' . $namefull . '!');
}
?>
<title>ثبت نام</title>
<link rel="stylesheet" href="random.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="body">
    <p class="header">صفحه ثبت نام</p>

    <div class="main">
        <div class="box">
            <h5>اطلاعات مورد نیاز را وارد کنید</h5>

            <form action="login.php" method="POST" data-ajax data-redirect="index.php">

                <div class="din">
                    <input required maxlength="20" name="namefull" id="namefull" class="in1" type="text"
                        placeholder="نام کاربری خود را وارد کنید">
                    <div id="nameError" class="error_div d-none">
                        <span class="error">نام خود را به درستی وارد کنید (حداقل ۳ حرف)</span>
                    </div>
                </div>

                <div class="din">
                    <input required maxlength="11" name="phone" id="phone" class="in1" type="tel"
                        placeholder="شماره خود را وارد کنید">
                    <div id="phoneError" class="error_div d-none">
                        <span class="error">شماره خود را به درستی وارد کنید (11 رقم و با 09)</span>
                    </div>
                </div>

                <div class="din">
                    <input required maxlength="11" name="password" id="password" class="in1" type="password"
                        placeholder="رمز خود را وارد کنید (حداقل ۶ کاراکتر)">
                    <div id="passwordError" class="error_div d-none">
                        <span class="error">رمز عبور خود را به درستی وارد کنید (حداقل ۶ کاراکتر)</span>
                    </div>
                </div>

                <div>
                    <button type="submit" id="btn" class="btn btn-success">ثبت</button>
                </div>
            </form>

            <div>
                آیا از قبل حساب دارید؟
                <a class="a" href="register.php">وارد</a> شوید
            </div>
        </div>
    </div>

    <script src="ajax.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var namefull = document.getElementById("namefull");
            var phone = document.getElementById("phone");
            var password = document.getElementById("password");
            var nameError = document.getElementById("nameError");
            var phoneError = document.getElementById("phoneError");
            var passwordError = document.getElementById("passwordError");
            var btn = document.getElementById("btn");

            btn.disabled = true;

            function checkAll() {
                var okName = namefull.value.length >= 3;
                var okPhone = /^09[0-9]{9}$/.test(phone.value);
                var okPass = password.value.length >= 6;
                btn.disabled = !(okName && okPhone && okPass);
            }

            namefull.addEventListener('input', function () {
                var ok = this.value.length >= 3;
                nameError.classList.toggle('d-none', ok);
                this.classList.toggle('error-input', !ok);
                this.classList.toggle('success-input', ok);
                checkAll();
            });

            phone.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
                var ok = /^09[0-9]{9}$/.test(this.value);
                phoneError.classList.toggle('d-none', ok || this.value.length === 0);
                this.classList.toggle('error-input', !ok && this.value.length > 0);
                this.classList.toggle('success-input', ok);
                checkAll();
            });

            password.addEventListener('input', function () {
                var ok = this.value.length >= 6;
                passwordError.classList.toggle('d-none', ok);
                this.classList.toggle('error-input', !ok);
                this.classList.toggle('success-input', ok);
                checkAll();
            });
        });
    </script>
</body>

</html>