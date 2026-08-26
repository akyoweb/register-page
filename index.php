<?php
session_start();


if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="utf-8">
    <title>پنل کاربری</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>خوش آمدید، <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
        <p>شما با موفقیت وارد شدید.</p>
        <a href="logout.php" class="btn btn-danger">خروج</a>
    </div>
</body>
</html>