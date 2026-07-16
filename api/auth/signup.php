<?php
require_once "../../config/database.php";
require_once "../../includes/response.php";
require_once "../../includes/validation.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    response(false, "Invalid request method");
}

$first_name = clean($_POST['first_name'] ?? '');
$last_name  = clean($_POST['last_name'] ?? '');
$username   = clean($_POST['username'] ?? '');
$email      = clean($_POST['email'] ?? '');
$password   = $_POST['password'] ?? '';
$role       = clean($_POST['role'] ?? '');

if (
    empty($first_name) || empty($last_name) ||
    empty($username) || empty($email) ||
    empty($password) || empty($role)
) {
    response(false, "All fields are required");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    response(false, "Invalid email format");
}

if (!in_array($role, ['client', 'photographer'])) {
    response(false, "Invalid role selected");
}

// check if email or username already exists
$check = mysqli_prepare(
    $conn,
    "SELECT id FROM users WHERE email = ? OR username = ?"
);
mysqli_stmt_bind_param($check, "ss", $email, $username);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);

if (mysqli_stmt_num_rows($check) > 0) {
    response(false, "Email or username already exists");
}

// hash password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// insert user
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO users (first_name, last_name, username, email, password, role)
     VALUES (?, ?, ?, ?, ?, ?)"
);
mysqli_stmt_bind_param(
    $stmt,
    "ssssss",
    $first_name,
    $last_name,
    $username,
    $email,
    $hashed_password,
    $role
);

if (!mysqli_stmt_execute($stmt)) {
    response(false, "Signup failed");
}

$user_id = mysqli_insert_id($conn);

// if photographer, create photographer profile
if ($role === 'photographer') {
    $pstmt = mysqli_prepare(
        $conn,
        "INSERT INTO photographers (user_id) VALUES (?)"
    );
    mysqli_stmt_bind_param($pstmt, "i", $user_id);
    mysqli_stmt_execute($pstmt);
}

response(true, "Signup successful");
