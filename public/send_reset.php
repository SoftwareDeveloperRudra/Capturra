<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/config/database.php";

$email = $_POST['email'] ?? '';

if(empty($email)){
    die("Email required");
}

/* CHECK USER */
$res = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
if(mysqli_num_rows($res)==0){
    die("Email not found");
}

/* GENERATE TOKEN */
$token = bin2hex(random_bytes(50));
$expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

mysqli_query($conn, "INSERT INTO password_resets (email, token, expires_at)
VALUES ('$email','$token','$expiry')");

/* 🔥 RESET LINK */
$link = "http://localhost/Capturra/public/reset_password.php?token=$token";

/* ⚠️ FOR NOW: SHOW LINK (NO EMAIL SERVER) */
echo "<h2>Copy this link:</h2>";
echo "<a href='$link'>$link</a>";