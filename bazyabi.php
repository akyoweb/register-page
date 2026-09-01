<?php
require_once __DIR__ . '/auth.php';

// پردازش درخواست Ajax
if (isset($_POST['ajax']) && $_POST['ajax'] == 'check_phone') {
    $phone = mysqli_real_escape_string($db, trim($_POST['phone']));

    // اعتبارسنجی شماره
    if (!preg_match('/^09[0-9]{9}$/', $phone)) {
        echo json_encode(['success' => false, 'message' => 'شماره تلفن معتبر نیست']);
        exit();
    }

    $stmt = mysqli_prepare($db, 'SELECT namefull FROM user WHERE phone = ? LIMIT 1');
    if (!$stmt) {
        json_response(false, 'خطا در بررسی شماره تلفن');
    }
    mysqli_stmt_bind_param($stmt, 's', $phone);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['recovery_phone'] = $phone;
        unset($_SESSION['recovery_verified_phone']);
        echo json_encode([
            'success' => true,
            'name' => $row['namefull'],
            'message' => 'شماره تلفن یافت شد'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'شماره تلفن وارد شده در سیستم ثبت نشده است'
        ]);
    }
    mysqli_stmt_close($stmt);
    exit();
}

if (isset($_POST['ajax']) && $_POST['ajax'] === 'verify_code') {
    $phone = trim($_POST['phone'] ?? '');
    $code = trim($_POST['code'] ?? '');

    if ($phone !== ($_SESSION['recovery_phone'] ?? '') || $code !== '123456') {
        json_response(false, 'کد تایید نامعتبر است');
    }
    $_SESSION['recovery_verified_phone'] = $phone;
    json_response(true, 'کد تایید شد');
}

if (isset($_POST['ajax']) && $_POST['ajax'] === 'reset_password') {
    $phone = trim($_POST['phone'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if ($phone !== ($_SESSION['recovery_verified_phone'] ?? '')) {
        json_response(false, 'ابتدا کد بازیابی را تایید کنید');
    }
    if (!preg_match('/^09[0-9]{9}$/', $phone)) {
        json_response(false, 'شماره تلفن معتبر نیست');
    }
    if (strlen($password) < 6 || strlen($password) > 11) {
        json_response(false, 'رمز عبور باید بین 6 تا 11 کاراکتر باشد');
    }

    $stmt = mysqli_prepare($db, 'UPDATE user SET password = ? WHERE phone = ?');
    if (!$stmt) {
        json_response(false, 'خطا در آماده‌سازی تغییر رمز');
    }
    mysqli_stmt_bind_param($stmt, 'ss', $password, $phone);
    $saved = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (!$saved) {
        json_response(false, 'تغییر رمز انجام نشد. لطفاً دوباره تلاش کنید');
    }
    unset($_SESSION['recovery_phone'], $_SESSION['recovery_verified_phone']);
    json_response(true, 'رمز عبور با موفقیت تغییر کرد. اکنون وارد حساب شوید');
}

// نمایش خطا در صورت وجود (برای بار اول)
if (isset($_GET['error'])) {
    echo '<div class="alert-error-center" id="errorAlert">شماره تلفن وارد شده در سیستم ثبت نشده است</div>';
    echo '<script>
        setTimeout(function() {
            var alert = document.getElementById("errorAlert");
            if (alert) {
                alert.style.display = "none";
            }
        }, 5000);
    </script>';
}
?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <link rel="stylesheet" href="random.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap.min.css" rel="stylesheet">
    <link integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        .alert-error-center {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 10px auto;
            border-right: 4px solid #f5c6cb;
            text-align: center;
            max-width: 500px;
            animation: slideDown 0.5s ease;
            direction: rtl;
        }

        .alert-slide-pro {
            background: #d4edda;
            color: #155724;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 10px auto;
            border-right: 4px solid #c3e6cb;
            text-align: center;
            max-width: 500px;
            animation: slideDown 0.5s ease;
            direction: rtl;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f5f5f5;
        }

        .box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .din {
            margin: 15px 0;
        }

        .in1 {
            border-radius: 10px;
            border: solid 1px lightslategray;
            width: 100%;
            height: 45px;
            outline: none;
            padding: 9px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .in1:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .btn-success {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 5px;
        }

        .btn-success:disabled {
            opacity: 0.6;
        }

        h5 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .reset-modal .modal-content {
            border: 0;
            border-radius: 10px;
        }

        .reset-modal .modal-header,
        .reset-modal .modal-footer {
            border: 0;
        }
    </style>
</head>

<body class="body">
    <div id="alertbox" class="d-none alert alert-success d-flex align-items-center" role="alert">
    </div>

    <div class="modal fade reset-modal" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordTitle"
        aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="resetPasswordForm">
                    <div class="modal-header">
                        <h5 class="modal-title mb-0" id="resetPasswordTitle">تعیین رمز عبور جدید</h5>
                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="بستن"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">رمز عبور جدید</label>
                            <input type="password" id="newPassword" class="form-control" minlength="6" maxlength="11"
                                required autocomplete="new-password">
                        </div>
                        <div>
                            <label for="confirmPassword" class="form-label">تکرار رمز عبور جدید</label>
                            <input type="password" id="confirmPassword" class="form-control" minlength="6"
                                maxlength="11" required autocomplete="new-password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="savePasswordBtn" class="btn btn-success">ذخیره رمز جدید</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="box">
            <h5>بازیابی حساب کاربری</h5>
            <form id="recoveryForm">
                <div class="din">
                    <input type="tel" name="phone" required id="phone" class="in1" maxlength="11"
                        placeholder="شماره تلفن خود را وارد کنید">
                </div>

                <div id="divcode" class="din">
                    <input
                        style="border-radius: 10px; border: solid 1px lightslategray; width: 100%; height: 45px; outline: none; padding: 9px; box-sizing: border-box; font-size: 16px;"
                        maxlength="6" required class="d-none" id="code" type="tel"
                        placeholder="کد شش رقمی را وارد کنید">
                </div>

                <div>
                    <button style="margin-top: 9px; display: none;" type="button" id="btn2"
                        class="btn btn-success">تایید کد</button>
                    <button type="button" id="btn" class="btn btn-success">ارسال کد</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let phone = document.getElementById("phone");
            let code = document.getElementById("code");
            let btn = document.getElementById("btn");
            let btn2 = document.getElementById("btn2");
            let alertbox = document.getElementById("alertbox");
            let resetPasswordForm = document.getElementById("resetPasswordForm");
            let newPassword = document.getElementById("newPassword");
            let confirmPassword = document.getElementById("confirmPassword");
            let savePasswordBtn = document.getElementById("savePasswordBtn");
            let resetPasswordModal = new bootstrap.Modal(document.getElementById("resetPasswordModal"), {
                backdrop: 'static',
                keyboard: false
            });

            let timer = null;
            let time = 60;
            let isPhoneVerified = false;

            // تابع نمایش پیام
            function showAlert(message, type) {
                alertbox.classList.remove("d-none", "alert-success", "alert-danger");
                alertbox.style.display = "block";

                if (type === 'success') {
                    alertbox.className = "alert-slide-pro";
                    alertbox.style.color = "#155724";
                    alertbox.style.backgroundColor = "#d4edda";
                    alertbox.style.borderRight = "4px solid #c3e6cb";
                } else {
                    alertbox.className = "alert-error-center";
                    alertbox.style.color = "#721c24";
                    alertbox.style.backgroundColor = "#f8d7da";
                    alertbox.style.borderRight = "4px solid #f5c6cb";
                }

                alertbox.innerHTML = message;
                alertbox.style.direction = "rtl";
                alertbox.style.textAlign = "center";
                alertbox.style.width = "100%";
                alertbox.style.maxWidth = "400px";
                alertbox.style.margin = "10px auto";
                alertbox.style.padding = "12px 20px";
                alertbox.style.borderRadius = "8px";
                alertbox.style.animation = "slideDown 0.5s ease";

                // حذف خودکار پیام بعد از 5 ثانیه
                clearTimeout(window.alertTimeout);
                window.alertTimeout = setTimeout(function () {
                    alertbox.style.display = "none";
                    alertbox.classList.add("d-none");
                }, 5000);
            }

            // تابع بررسی شماره در دیتابیس
            function checkPhoneInDatabase(phoneNumber) {
                return new Promise((resolve, reject) => {
                    var formData = new FormData();
                    formData.append('phone', phoneNumber);
                    formData.append('ajax', 'check_phone');

                    fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            resolve(data);
                        })
                        .catch(error => {
                            reject(error);
                        });
                });
            }

            // دکمه ارسال کد
            btn.onclick = async function () {
                if (!phone.value || phone.value.length < 11) {
                    showAlert("لطفاً شماره تلفن معتبر وارد کنید (مثال: 09123456789)", "error");
                    return;
                }

                // اعتبارسنجی شماره
                if (!/^09[0-9]{9}$/.test(phone.value)) {
                    showAlert("شماره تلفن باید با 09 شروع شود و 11 رقم باشد", "error");
                    return;
                }

                // بررسی شماره در دیتابیس
                btn.disabled = true;
                btn.innerText = "در حال بررسی...";

                try {
                    const result = await checkPhoneInDatabase(phone.value);

                    if (result.success) {
                        isPhoneVerified = true;
                        showAlert("✅ شماره تلفن شما یافت شد. کد بازیابی ارسال گردید.", "success");

                        // نمایش کد ورودی
                        code.classList.remove("d-none");
                        btn2.style.display = "block";
                        code.disabled = false;
                        code.value = "";
                        code.focus();

                        // شروع تایمر
                        if (timer !== null) {
                            clearInterval(timer);
                            timer = null;
                        }

                        btn.disabled = true;
                        time = 60;
                        btn.innerText = `${time} ثانیه`;

                        timer = setInterval(function () {
                            time--;
                            btn.innerText = `${time} ثانیه`;

                            if (time <= 0) {
                                clearInterval(timer);
                                timer = null;
                                btn.disabled = false;
                                btn.innerText = "ارسال مجدد کد";
                            }
                        }, 1000);

                    } else {
                        showAlert("❌ " + result.message, "error");
                        btn.disabled = false;
                        btn.innerText = "ارسال کد";
                    }
                } catch (error) {
                    showAlert("❌ خطا در ارتباط با سرور", "error");
                    btn.disabled = false;
                    btn.innerText = "ارسال کد";
                }
            };

            // دکمه تایید کد
            btn2.onclick = async function () {
                if (!isPhoneVerified) {
                    showAlert("ابتدا شماره تلفن را تایید کنید", "error");
                    return;
                }
                if (!code.value || code.value.length !== 6) {
                    showAlert("لطفاً کد 6 رقمی را وارد کنید", "error");
                    return;
                }

                btn2.disabled = true;
                btn2.innerText = "در حال تایید...";
                var formData = new FormData();
                formData.append('ajax', 'verify_code');
                formData.append('phone', phone.value);
                formData.append('code', code.value);

                try {
                    var response = await fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                        method: 'POST',
                        body: formData
                    });
                    var data = await response.json();
                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'کد وارد شده اشتباه است');
                    }
                    clearInterval(timer);
                    timer = null;
                    newPassword.value = "";
                    confirmPassword.value = "";
                    resetPasswordModal.show();
                    setTimeout(function () { newPassword.focus(); }, 300);
                } catch (error) {
                    showAlert(error.message || "خطا در ارتباط با سرور", "error");
                    code.value = "";
                    code.focus();
                } finally {
                    btn2.disabled = false;
                    btn2.innerText = "تایید کد";
                }
            };

            resetPasswordForm.addEventListener('submit', async function (event) {
                event.preventDefault();
                var password = newPassword.value;

                if (password.length < 6 || password.length > 11) {
                    showAlert("رمز عبور باید بین 6 تا 11 کاراکتر باشد", "error");
                    return;
                }
                if (password !== confirmPassword.value) {
                    showAlert("تکرار رمز عبور با رمز جدید یکسان نیست", "error");
                    confirmPassword.focus();
                    return;
                }

                savePasswordBtn.disabled = true;
                savePasswordBtn.innerText = "در حال ذخیره...";
                var formData = new FormData();
                formData.append('ajax', 'reset_password');
                formData.append('phone', phone.value);
                formData.append('password', password);

                try {
                    var response = await fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                        method: 'POST',
                        body: formData
                    });
                    var data = await response.json();
                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'تغییر رمز انجام نشد');
                    }

                    resetPasswordModal.hide();
                    showAlert("رمز عبور با موفقیت تغییر کرد. در حال انتقال به صفحه ورود...", "success");
                    setTimeout(function () {
                        window.location.href = "register.php";
                    }, 1800);
                } catch (error) {
                    showAlert(error.message || "خطا در ارتباط با سرور", "error");
                    savePasswordBtn.disabled = false;
                    savePasswordBtn.innerText = "ذخیره رمز جدید";
                }
            });

            // محدود کردن ورودی شماره تلفن به عدد
            phone.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // محدود کردن ورودی کد به عدد
            code.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // ارسال با Enter
            phone.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    btn.click();
                }
            });

            code.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    btn2.click();
                }
            });
        });
    </script>
</body>

</html>