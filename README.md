

<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<style>
  @import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;700&display=swap');
  
  body {
    margin: 0;
    width: 1000px;
    height: 600px;
    background: #0d1117;
    font-family: 'Vazirmatn', sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }
  
  .card {
    position: relative;
    width: 850px;
    background: #161b22;
    border-radius: 20px;
    border: 1px solid #30363d;
    padding: 40px 50px;
    box-shadow: 0 0 40px rgba(88,166,255,0.1);
    overflow: hidden;
  }
  
  /* افکت انیمهای */
  .card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(88,166,255,0.05), transparent);
    animation: rotate 8s linear infinite;
  }
  
  @keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }
  
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
  }
  
  .name {
    font-size: 42px;
    font-weight: 700;
    color: #c9d1d9;
    letter-spacing: 2px;
  }
  
  .name span {
    color: #58a6ff;
  }
  
  .anime-icon {
    font-size: 40px;
    animation: float 3s ease-in-out infinite;
  }
  
  @keyframes float {
    0%,100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
  }
  
  .bio {
    color: #8b949e;
    font-size: 18px;
    margin-bottom: 30px;
    border-right: 3px solid #58a6ff;
    padding-right: 15px;
  }
  
  .tech {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 15px;
    margin-bottom: 35px;
  }
  
  .tech-item {
    background: #21262d;
    border-radius: 10px;
    padding: 15px;
    text-align: center;
    color: #c9d1d9;
    font-size: 14px;
    transition: 0.3s;
    border: 1px solid transparent;
  }
  
  .tech-item:hover {
    border-color: #58a6ff;
    background: #2d333b;
    transform: translateY(-3px);
  }
  
  .tech-item .icon {
    font-size: 24px;
    display: block;
    margin-bottom: 5px;
  }
  
  .footer {
    display: flex;
    justify-content: space-between;
    color: #8b949e;
    font-size: 14px;
    padding-top: 15px;
    border-top: 1px solid #30363d;
  }
  
  .footer .status {
    color: #3fb950;
  }
  
  .anime-cat {
    position: absolute;
    bottom: 20px;
    left: 30px;
    font-size: 35px;
    opacity: 0.3;
    animation: tail 4s ease-in-out infinite;
  }
  
  @keyframes tail {
    0%,100% { transform: rotate(-5deg); }
    50% { transform: rotate(5deg); }
  }
</style>
</head>
<body>
  <div class="card">
    <div class="header">
      <div class="name">Akyo<span>web</span></div>
      <div class="anime-icon">js,php,sql</div>
    </div>
    
    <div class="bio">
      Full-Stack Developer <br>
      Building cool stuff with code & coffee
    </div>
    
    <div class="tech">
      <div class="tech-item"><span class="icon"></span>PHP</div>
      <div class="tech-item"><span class="icon"></span>JavaScript</div>
      <div class="tech-item"><span class="icon"></span>SQL</div>
      <div class="tech-item"><span class="icon"></span>HTML</div>
      <div class="tech-item"><span class="icon"></span>CSS</div>
    </div>
    
    <div class="footer">
      <span class="status">● Online</span>
      <span>github.com/Akyoweb</span>
    </div>
    
    <div class="anime-cat"></div>
  </div>
</body>
</html>

























# 🧑‍💻 Personal Panel (پنل شخصی)
A lightweight PHP user-account system: registration, login, password recovery (SMS-code style with a recovery code), and a personal panel where users can change their username, change their password, and upload a profile picture.

A RTL, Persian-first interface built with plain **PHP + MySQLi (procedural)** and vanilla **JavaScript/fetch** — no framework required beyond **Bootstrap 5** for styling.

---

## ✨ Features

- **Registration** (`login.php`) — create an account with username, phone number, and password
- **Login** (`register.php`) — sign in with username + password, optional "remember me" cookie
- **Account recovery** (`bazyabi.php`) — verify the phone number, confirm the 6-digit code (`123456` in the current demo), then set and confirm a new password
- **Personal panel** (`index.php`):
  - Change username
  - Change password (requires current password)
  - Upload profile picture (JPG, PNG, WebP, GIF — max 2 MB)
- **Session-based authentication** with a "remember me" cookie (`auth.php`)
- **AJAX** forms (`data-ajax` + `ajax.js`) with JSON responses and toast-style top alerts
- Auto-generated SVG avatar when no profile picture is set

> ⚠️ **Demo note:** the recovery code is hard-coded as `123456` and passwords are stored as plain text (the `password` column is `varchar(11)`). For production, use real SMS/OTP delivery and `password_hash()` / `password_verify()`.

---

## 📁 Project Structure

| File           | Purpose                                                                                        |
| -------------- | ---------------------------------------------------------------------------------------------- |
| `index.php`    | Personal panel: AJAX handlers for name/password/avatar + UI                                    |
| `register.php` | Login page (username + password)                                                               |
| `login.php`    | Registration page                                                                              |
| `bazyabi.php`  | Account recovery: phone check → code confirm → new password modal                              |
| `logout.php`   | Log out: clears session and cookies                                                            |
| `auth.php`     | Session/cookie helpers: `json_response()`, `login_user()`, `current_user()`, `require_login()` |
| `database.php` | MySQLi connection (DB `login`, root, no password)                                              |
| `ajax.js`      | Generic AJAX submit handler for every `form[data-ajax]`                                        |
| `random.css`   | Auth pages stylesheet                                                                          |
| `panel.css`    | Panel stylesheet                                                                               |
| `uploads/`     | Uploaded profile pictures (auto-created)                                                       |

---

## 🚀 Getting Started

### Requirements

- PHP 7.4+ (with `mysqli` and `fileinfo` extensions)
- MySQL / MariaDB
- A web server — [XAMPP](https://www.apachefriends.org/) works great

### Setup

1. **Copy** the project folder into your web root, e.g. `C:\xampp\htdocs\random\`
2. **Start** Apache and MySQL (XAMPP Control Panel)
3. **Create the database** (default connection settings in `database.php`: host `localhost`, user `root`, no password, database `login`):

   ```sql
   CREATE DATABASE IF NOT EXISTS login CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   USE login;

   CREATE TABLE IF NOT EXISTS `user` (
     `id`       INT(20)     NOT NULL AUTO_INCREMENT,
     `namefull` VARCHAR(20) NOT NULL,
     `password` VARCHAR(11) NOT NULL,
     `phone`    VARCHAR(11) NOT NULL,
     PRIMARY KEY (`id`)
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;
   ```

4. **Open the app:** `http://localhost/random/register.php`

---

## 🔐 Usage Flow

| Action          | Page           | Notes                                                     |
| --------------- | -------------- | --------------------------------------------------------- |
| Create account  | `login.php`    | Username (3–20 chars), phone `09xxxxxxxxx`, password 6–11 |
| Login           | `register.php` | Username + password                                       |
| Recover account | `bazyabi.php`  | Phone → code `123456` → new password (6–11)               |
| Manage profile  | `index.php`    | Change name/password, upload avatar                       |

---

## ⚙️ Configuration

All database settings live in the top of `database.php`:

```php
$db = mysqli_connect('localhost', 'root', "", 'login');
```

Adjust host/user/password/database to match your environment.

---

## 🛠️ Troubleshooting

- **"خطا در ارتباط با سرور" / session-expired errors on the panel** — make sure `ajax.js` is loaded fresh (hard-refresh with `Ctrl + F5`) and that you are logged in.
- **Upload fails** — verify the `uploads/` folder is writable and the file is a valid JPG/PNG/WebP/GIF under 2 MB.
- **Blank page / DB error** — confirm MySQL is running and the `login` database + `user` table exist, and your PHP has `mysqli` enabled.

---

## 📄 License

Free to use and modify for learning and personal projects.

---

# 🧑‍💻 پنل شخصی

یک سیستم حساب کاربری سبک و ساده با PHP: ثبت‌نام، ورود، بازیابی رمز عبور (به‌سبک کد تایید) و پنل شخصی که کاربر می‌تواند نام کاربری، رمز عبور و عکس پروفایل خود را تغییر دهد.

رابط کاربری راست‌چین (RTL) و فارسی است؛ با **PHP خالص + MySQLi (رویه‌ای)** و **جاوااسکریپت خالص (fetch)** نوشته شده و برای ظاهر فقط از **Bootstrap 5** استفاده می‌کند. هیچ فریم‌ورکی لازم نیست.

---

## ✨ امکانات

- **ثبت‌نام** (`login.php`) — ساخت حساب با نام کاربری، شماره تلفن و رمز عبور
- **ورود** (`register.php`) — ورود با نام کاربری و رمز عبور + گزینه «مرا به خاطر بسپار»
- **بازیابی حساب** (`bazyabi.php`) — تأیید شماره تلفن، تأیید کد شش‌رقمی (در نسخه نمایشی `123456`) و سپس تعیین رمز جدید با تکرار آن
- **پنل شخصی** (`index.php`):
  - تغییر نام کاربری
  - تغییر رمز عبور (با تأیید رمز فعلی)
  - آپلود عکس پروفایل (JPG، PNG، WebP، GIF — حداکثر ۲ مگابایت)
- **احراز هویت با Session** + کوکی «مرا به خاطر بسپار» (`auth.php`)
- **فرم‌های Ajax** (با `data-ajax` و `ajax.js`) و پاسخ JSON با پیام‌های زیبا در بالای صفحه
- تولید خودکار آواتار SVG در صورت نداشتن عکس پروفایل

> ⚠️ **نکته نسخه نمایشی:** کد بازیابی به‌صورت ثابت `123456` است و رمز عبور‌ها رمزنگاری‌نشده ذخیره می‌شوند (ستون `password` با طول `varchar(11)`). برای محیط واقعی حتماً سرویس واقعی پیامک/OTP و تابع `password_hash()` / `password_verify()` استفاده کنید.

---

## 📁 ساختار پروژه

| فایل           | کاربرد                                                                                        |
| -------------- | --------------------------------------------------------------------------------------------- |
| `index.php`    | پنل شخصی: پردازش Ajax تغییر نام، رمز و آواتار + رابط کاربری                                   |
| `register.php` | صفحه ورود (نام کاربری + رمز عبور)                                                             |
| `login.php`    | صفحه ثبت‌نام                                                                                  |
| `bazyabi.php`  | بازیابی حساب: بررسی شماره ← تأیید کد ← فرم پاپ‌آپ رمز جدید                                    |
| `logout.php`   | خروج از حساب: پاک‌کردن سشن و کوکی‌ها                                                          |
| `auth.php`     | توابع کمکی سشن و کوکی: `json_response()`، `login_user()`، `current_user()`، `require_login()` |
| `database.php` | اتصال MySQLi (دیتابیس `login`، یوزر root و بدون رمز)                                          |
| `ajax.js`      | پردازشگر عمومی Ajax برای همه فرم‌های `form[data-ajax]`                                        |
| `random.css`   | استایل صفحات ورود/ثبت‌نام                                                                     |
| `panel.css`    | استایل پنل شخصی                                                                               |
| `uploads/`     | عکس‌های پروفایل آپلودشده (به‌صورت خودکار ساخته می‌شود)                                        |

---

## 🚀 راه‌اندازی

### پیش‌نیازها

- PHP 7.4 به بالا (با افزونه‌های `mysqli` و `fileinfo`)
- MySQL / MariaDB
- یک وب‌سرور — [XAMPP](https://www.apachefriends.org/) پیشنهاد می‌شود

### مراحل نصب

1. پوشه پروژه را در ریشه وب کپی کنید؛ مثلاً `C:\xampp\htdocs\random\`
2. **Apache و MySQL** را از XAMPP Control Panel روشن کنید
3. دیتابیس را بسازید (تنظیمات پیش‌فرض در `database.php`: هاست `localhost`، یوزر `root`، بدون رمز، دیتابیس `login`):

   ```sql
   CREATE DATABASE IF NOT EXISTS login CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   USE login;

   CREATE TABLE IF NOT EXISTS `user` (
     `id`       INT(20)     NOT NULL AUTO_INCREMENT,
     `namefull` VARCHAR(20) NOT NULL,
     `password` VARCHAR(11) NOT NULL,
     `phone`    VARCHAR(11) NOT NULL,
     PRIMARY KEY (`id`)
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;
   ```

4. برنامه را باز کنید: `http://localhost/random/register.php`

---

## 🔐 جریان استفاده

| عملیات         | صفحه           | توضیحات                                                   |
| -------------- | -------------- | --------------------------------------------------------- |
| ساخت حساب      | `login.php`    | نام کاربری (۳ تا ۲۰ حرف)، تلفن `09xxxxxxxxx`، رمز ۶ تا ۱۱ |
| ورود           | `register.php` | نام کاربری + رمز عبور                                     |
| بازیابی حساب   | `bazyabi.php`  | شماره ← کد `123456` ← رمز جدید (۶ تا ۱۱)                  |
| مدیریت پروفایل | `index.php`    | تغییر نام/رمز، آپلود عکس پروفایل                          |

---

## ⚙️ تنظیمات

همه تنظیمات دیتابیس در ابتدای `database.php` قرار دارد:

```php
$db = mysqli_connect('localhost', 'root', "", 'login');
```

هاست، نام کاربری، رمز و نام دیتابیس را مطابق محیط خود تغییر دهید.

---

## 🛠️ عیب‌یابی

- **«خطا در ارتباط با سرور» یا پیام منقضی‌شدن سشن در پنل** — با `Ctrl + F5` صفحه را کامل رفرش کنید تا `ajax.js` جدید لود شود و مطمئن شوید وارد حساب شده‌اید.
- **آپلود انجام نمی‌شود** — مجوز نوشتن پوشه `uploads/` را بررسی کنید و از معتبر بودن فایل (JPG/PNG/WebP/GIF و کمتر از ۲ مگابایت) مطمئن شوید.
- **صفحه سفید یا خطای دیتابیس** — از روشن بودن MySQL، وجود دیتابیس `login` و جدول `user` و فعال بودن افزونه `mysqli` مطمئن شوید.

---

## 📄 مجوز

استفاده و تغییر برای یادگیری و پروژه‌های شخصی آزاد است.
