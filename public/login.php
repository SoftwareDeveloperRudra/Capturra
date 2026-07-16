<?php

// Debug - write to file
file_put_contents("login_debug.log", date("Y-m-d H:i:s") . " - Request: " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);

require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart();

ini_set('display_errors', 1);
error_reporting(E_ALL);

$base_path = $_SERVER['DOCUMENT_ROOT'] . "/Capturra/";
require_once $base_path . "config/database.php";

/* ✅ ONLY POST REQUEST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    file_put_contents("login_debug.log", "Not POST, redirecting\n", FILE_APPEND);
    header("Location: /Capturra/public/login.html");
    exit();
}



/* 🧹 INPUT CLEAN */
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

/* ❌ VALIDATION */
if (empty($email) || empty($password)) {
    header("Location: /Capturra/public/login.html?error=empty");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: /Capturra/public/login.html?error=invalid_email");
    exit();
}

/* 🚨 RATE LIMIT (SESSION BASED) */
$_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;

if ($_SESSION['login_attempts'] > 5) {
    die("Too many attempts. Try again later.");
}

/* 🔍 PREPARED STATEMENT */
$stmt = mysqli_prepare($conn, 
    "SELECT id, first_name, username, password, role FROM users WHERE email = ?"
);

if (!$stmt) {
    error_log("DB Error: " . mysqli_error($conn));
    header("Location: /Capturra/public/login.html?error=server");
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

/* ❌ USER NOT FOUND */
if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: /Capturra/public/login.html?error=user");
    exit();
}

$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

/* ❌ WRONG PASSWORD */
if (!password_verify($password, $user['password'])) {
    file_put_contents("login_debug.log", "Wrong password for: $email\n", FILE_APPEND);
    header("Location: /Capturra/public/login.html?error=pass");
    exit();
}

/* ✅ RESET LOGIN ATTEMPTS ON SUCCESS */
$_SESSION['login_attempts'] = 0;

/* 🔐 REGENERATE SESSION */
session_regenerate_id(true);

/* ✅ STORE SESSION */
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];
$_SESSION['name'] = $user['first_name'];

file_put_contents("login_debug.log", "Login success for: $email, role: " . $user['role'] . "\n", FILE_APPEND);

/* 🔐 SAFE SESSION BINDING */
$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];

/* ⏱️ SESSION TIME TRACK */
$_SESSION['LAST_ACTIVITY'] = time();

/* 🚀 ROLE BASED REDIRECT */
if ($user['role'] === "photographer") {
    $redirect = "photographer_home.php";
} else {
    $redirect = "client_home.php";
}

header("Location: $redirect");
exit();
?>