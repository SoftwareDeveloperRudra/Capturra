<?php

$host = "localhost";   // 🔥 localhost ki jagah ye use karo
$user = "root";
$pass = "";
$db   = "capturra";
$port = 3306;          // agar MySQL port change hai toh yaha update karo

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die(json_encode([
        "error" => "Database connection failed: " . mysqli_connect_error()
    ]));
}

?>