<?php
$db=mysqli_connect('localhost','root',"",'login');




if (!$db) {
    die("اتصال برقرار نشد: " . mysqli_connect_error());
};

mysqli_set_charset($db, 'utf8mb4');








?>