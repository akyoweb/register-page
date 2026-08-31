<?php
require_once __DIR__ . '/auth.php';
restore_from_cookie();

// اگر لاگین نیستیم: برای AJAX پاسخ JSON تمیز، برای GET ریدایرکت
if (!current_user()) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        json_response(false, 'نشست شما منقضی شده است. لطفاً دوباره وارد شوید');
    }
    header('Location: register.php');
    exit;
}

$user = current_user();
$db = $GLOBALS['db'];

// ---------- پردازش تنظیمات (AJAX) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // تغییر نام کاربری
    if ($action === 'update_name') {
        $newname = trim($_POST['namefull'] ?? '');
        if (mb_strlen($newname) < 3 || mb_strlen($newname) > 20) {
            json_response(false, 'نام کاربری باید بین ۳ تا ۲۰ کاراکتر باشد');
        }
        $stmt = mysqli_prepare($db, 'SELECT id FROM user WHERE namefull = ? AND id <> ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 'si', $newname, $user['id']);
        mysqli_stmt_execute($stmt);
        if (mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))) {
            mysqli_stmt_close($stmt);
            json_response(false, 'این نام کاربری قبلاً گرفته شده است');
        }
        mysqli_stmt_close($stmt);

        $up = mysqli_prepare($db, 'UPDATE user SET namefull = ? WHERE id = ?');
        mysqli_stmt_bind_param($up, 'si', $newname, $user['id']);
        if (mysqli_stmt_execute($up)) {
            $_SESSION['namefull'] = $newname;
            mysqli_stmt_close($up);
            json_response(true, 'نام کاربری با موفقیت تغییر کرد');
        }
        mysqli_stmt_close($up);
        json_response(false, 'تغییر نام انجام نشد');
    }

    // تغییر رمز عبور
    if ($action === 'update_pass') {
        $current = (string) ($_POST['current_pass'] ?? '');
        $newpass = (string) ($_POST['new_pass'] ?? '');
        if (strlen($newpass) < 6 || strlen($newpass) > 11) {
            json_response(false, 'رمز جدید باید بین ۶ تا ۱۱ کاراکتر باشد');
        }
        $stmt = mysqli_prepare($db, 'SELECT password FROM user WHERE id = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 'i', $user['id']);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        if (!$row || $row['password'] !== $current) {
            json_response(false, 'رمز فعلی اشتباه است');
        }

        $up = mysqli_prepare($db, 'UPDATE user SET password = ? WHERE id = ?');
        mysqli_stmt_bind_param($up, 'si', $newpass, $user['id']);
        mysqli_stmt_execute($up);
        mysqli_stmt_close($up);
        json_response(true, 'رمز عبور با موفقیت تغییر کرد');
    }

    // آپلود عکس پروفایل
    if ($action === 'upload_avatar') {
        if (empty($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            json_response(false, 'لطفاً یک تصویر انتخاب کنید');
        }
        $f = $_FILES['avatar'];
        if ($f['size'] > 2 * 1024 * 1024) {
            json_response(false, 'حجم تصویر حداکثر ۲ مگابایت است');
        }
        $extMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($f['tmp_name']);
        $ext = $extMap[$mime] ?? null;
        if (!$ext) {
            json_response(false, 'فقط فرمت JPG، PNG، WebP یا GIF مجاز است');
        }

        $dir = __DIR__ . '/uploads';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        foreach ((glob($dir . '/avatar_' . $user['id'] . '.*') ?: []) as $old) {
            @unlink($old);
        }
        $dest = $dir . '/avatar_' . $user['id'] . '.' . $ext;
        if (move_uploaded_file($f['tmp_name'], $dest)) {
            json_response(true, 'عکس پروفایل با موفقیت آپلود شد');
        }
        json_response(false, 'آپلود عکس ناموفق بود');
    }

    json_response(false, 'عملیات نامعتبر');
}

// ---------- توابع کمکی عکس پروفایل ----------
function avatar_url($id)
{
    foreach ((glob(__DIR__ . '/uploads/avatar_' . $id . '.*') ?: []) as $f) {
        return 'uploads/' . basename($f);
    }
    return null;
}

function default_avatar($name)
{
    $initial = mb_substr($name, 0, 1);
    $colors = ['#6366f1', '#0ea5e9', '#10b981', '#f59e0b', '#ef4444', '#06b6d4'];
    $color = $colors[abs(crc32($name)) % count($colors)];
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200">'
        . '<rect width="200" height="200" fill="' . $color . '"/>'
        . '<text x="100" y="132" font-size="96" fill="#ffffff" text-anchor="middle" font-family="Tahoma,Arial,sans-serif">'
        . htmlspecialchars($initial) . '</text></svg>';
    return 'data:image/svg+xml;charset=utf-8,' . rawurlencode($svg);
}

$avatar = avatar_url($user['id']);
$avatarSrc = $avatar ? $avatar : default_avatar($user['namefull']);
$hasAvatar = $avatar ? 'دارد' : 'ندارد';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>پنل شخصی</title>
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="random.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;700;900&display=swap" rel="stylesheet">
</head>

<body class="panel-body">

    <!-- ناوبار -->
    <nav class="panel-navbar">
        <div class="navbar-brand">
            <i class="fa-solid fa-user-gear"></i>
            <span>پنل شخصی</span>
        </div>
        <div class="navbar-user">
            <img src="<?php echo $avatarSrc; ?>" alt="آواتار">
            <span class="nav-name"><?php echo htmlspecialchars($user['namefull']); ?></span>
            <a class="logout-btn" href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> خروج</a>
        </div>
    </nav>

    <main class="panel-container">

        <!-- هیرو / خوش‌آمد -->
        <section class="hero-card">
            <img class="hero-avatar" src="<?php echo $avatarSrc; ?>" alt="عکس پروفایل">
            <div class="hero-info">
                <h1>سلام، <?php echo htmlspecialchars($user['namefull']); ?> 👋</h1>
                <p>همه‌چیز حساب تو اینجاست.</p>
                <span class="badge-active"><i class="fa-solid fa-circle-check"></i> حساب فعال</span>
            </div>
            <div class="hero-stats">
                <div class="stat"><i class="fa-solid fa-hashtag"></i> شناسه: <?php echo (int) $user['id']; ?></div>
                <div class="stat"><i class="fa-solid fa-image"></i> عکس پروفایل: <?php echo $hasAvatar; ?></div>
            </div>
        </section>

        <!-- اسلایدر -->
        <div class="slider my-4" id="slider">
            <div class="slide active" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                <i class="fa-solid fa-wand-magic-sparkles slide-icon"></i>
                <h3>به پنل شخصی خوش آمدید</h3>
                <p>پروفایل خود را مدیریت کنید</p>
            </div>
            <div class="slide" style="background: linear-gradient(135deg, #0ea5e9, #3b82f6);">
                <i class="fa-solid fa-image slide-icon"></i>
                <h3>عکس پروفایل</h3>
                <p>صورت خود را آپلود کنید</p>
            </div>
            <div class="slide" style="background: linear-gradient(135deg, #10b981, #06b6d4);">
                <i class="fa-solid fa-gear slide-icon"></i>
                <h3>تنظیمات حساب</h3>
                <p>نام کاربری و رمز را تغییر دهید</p>
            </div>
            <button class="slider-btn prev" type="button" aria-label="قبلی">&#10094;</button>
            <button class="slider-btn next" type="button" aria-label="بعدی">&#10095;</button>
            <div class="slider-dots"></div>
        </div>

        <!-- تنظیمات -->
        <h2 class="section-title"><i class="fa-solid fa-sliders"></i> تنظیمات حساب</h2>
        <div class="settings-grid">

            <div class="setting-card">
                <div class="setting-head g1">
                    <i class="fa-solid fa-camera"></i>
                    <h5>عکس پروفایل</h5>
                </div>
                <div class="setting-body">
                    <form method="post" data-ajax data-redirect="index.php" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="upload_avatar">
                        <div class="mb-3">
                            <label class="form-label">عکس جدید</label>
                            <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp,image/gif"
                                class="form-control" required>
                            <small class="text-muted">حداکثر ۲ مگابایت (JPG, PNG, WebP, GIF)</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-upload"></i>
                            آپلود</button>
                    </form>
                </div>
            </div>

            <div class="setting-card">
                <div class="setting-head g2">
                    <i class="fa-solid fa-user-pen"></i>
                    <h5>نام کاربری</h5>
                </div>
                <div class="setting-body">
                    <form method="post" data-ajax data-redirect="index.php">
                        <input type="hidden" name="action" value="update_name">
                        <div class="mb-3">
                            <label class="form-label">نام جدید</label>
                            <input type="text" name="namefull" class="form-control" maxlength="20"
                                placeholder="حداقل ۳ حرف" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-floppy-disk"></i>
                            ذخیره</button>
                    </form>
                </div>
            </div>

            <div class="setting-card">
                <div class="setting-head g3">
                    <i class="fa-solid fa-key"></i>
                    <h5>رمز عبور</h5>
                </div>
                <div class="setting-body">
                    <form method="post" data-ajax>
                        <input type="hidden" name="action" value="update_pass">
                        <div class="mb-3">
                            <label class="form-label">رمز فعلی</label>
                            <input type="password" name="current_pass" class="form-control" maxlength="11" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">رمز جدید</label>
                            <input type="password" name="new_pass" class="form-control" maxlength="11"
                                placeholder="حداقل ۶ کاراکتر" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-floppy-disk"></i>
                            ذخیره</button>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <footer class="panel-footer">
        <i class="fa-regular fa-copyright"></i> پنل شخصی — ساخته شده با <!-- --> و PHP
    </footer>

    <script src="ajax.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var slider = document.getElementById('slider');
            var slides = slider.querySelectorAll('.slide');
            var dotsWrap = slider.querySelector('.slider-dots');
            var current = 0;

            slides.forEach(function (_, i) {
                var dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'dot' + (i === 0 ? ' active' : '');
                dot.addEventListener('click', function () { goTo(i); restart(); });
                dotsWrap.appendChild(dot);
            });
            var dots = dotsWrap.querySelectorAll('.dot');

            function goTo(i) {
                slides[current].classList.remove('active');
                dots[current].classList.remove('active');
                current = (i + slides.length) % slides.length;
                slides[current].classList.add('active');
                dots[current].classList.add('active');
            }

            var timer = setInterval(function () { goTo(current + 1); }, 4000);
            function restart() { clearInterval(timer); timer = setInterval(function () { goTo(current + 1); }, 4000); }

            slider.querySelector('.prev').addEventListener('click', function () { goTo(current - 1); restart(); });
            slider.querySelector('.next').addEventListener('click', function () { goTo(current + 1); restart(); });
        });
    </script>
</body>

</html>