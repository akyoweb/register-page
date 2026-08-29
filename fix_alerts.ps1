$p = 'c:\xampp\htdocs\random\register.php'
$c = Get-Content $p -Raw -Encoding UTF8

$old1 = "echo '<div class=`"alert-slide-pro`"><span class=`"alert-icon`">✅</span></div>'. `$row['namefull'];
echo'<div class=`"success`"><p>درحال انتقال به خانه...</p></div>
<div class=`"loader`"></div>
';
echo' <meta http-equiv=`"refresh`" content=`"2;url=index.php`">';"

$new1 = "echo '<div class=`"alert-slide-pro`"><span class=`"alert-icon`">✅</span> خوش آمدی '. htmlspecialchars(`$row['namefull']) .' — در حال انتقال به خانه...</div>
<div class=`"loader`"></div>
<meta http-equiv=`"refresh`" content=`"2;url=index.php`">';"

$c = $c.Replace($old1, $new1)

$old2 = "echo'<div style=`"direction:rtl; width:30%;`" class=`"alert alert-danger d-flex align-center`">نام کاربری یا روز عبور اشتباه است</div>';"
$new2 = "echo '<div class=`"alert-error-center`">نام کاربری یا رمز عبور اشتباه است</div>';"

$c = $c.Replace($old2, $new2)

[IO.File]::WriteAllText($p, $c, (New-Object Text.UTF8Encoding($false)))
