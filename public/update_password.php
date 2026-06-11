<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/config/database.php";

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';

if(empty($token) || empty($password)){
    die("Invalid request");
}

/* GET EMAIL FROM TOKEN */
$res = mysqli_query($conn, "SELECT * FROM password_resets WHERE token='$token'");
if(mysqli_num_rows($res)==0){
    die("Invalid token");
}

$data = mysqli_fetch_assoc($res);
$email = $data['email'];

/* HASH PASSWORD */
$hashed = password_hash($password, PASSWORD_DEFAULT);

/* UPDATE USER */
mysqli_query($conn, "UPDATE users SET password='$hashed' WHERE email='$email'");

/* DELETE TOKEN */
mysqli_query($conn, "DELETE FROM password_resets WHERE email='$email'");

echo "Password updated successfully! <a href='login.html'>Login</a>";