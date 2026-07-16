<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request");
}

/* INPUT CLEANING */
$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name'] ?? '');
$username   = trim($_POST['username'] ?? '');
$email      = trim($_POST['email'] ?? '');
$password   = $_POST['password'] ?? '';
$role       = trim($_POST['role'] ?? '');

/* VALIDATION */
if (empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($password) || empty($role)) {
    die("All fields are required");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format");
}

if (strlen($password) < 6) {
    die("Password must be at least 6 characters");
}

/* CHECK EMAIL EXIST */
$stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    die("Email already registered");
}
mysqli_stmt_close($stmt);

/* HASH PASSWORD */
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

/* INSERT USER */
$stmt = mysqli_prepare($conn, "INSERT INTO users (first_name, last_name, username, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssssss", $first_name, $last_name, $username, $email, $hashed_password, $role);

if (mysqli_stmt_execute($stmt)) {
    header("Location: /Capturra/public/login.html");
    exit();
} else {
    die("Registration failed");
}