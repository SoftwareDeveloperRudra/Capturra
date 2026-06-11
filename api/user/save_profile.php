<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/auth.php";
requireLogin(true);
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/config/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/response.php";

$input = json_decode(file_get_contents('php://input'), true);
$bio = isset($input['bio']) ? trim($input['bio']) : null;
if($bio === null){ response(false, 'Missing bio'); }

$user_id = $_SESSION['user_id'];

// Ensure 'bio' column exists on users table; if not create it
$colCheck = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'bio'");
if(!$colCheck || mysqli_num_rows($colCheck) === 0){
    $alter = "ALTER TABLE users ADD COLUMN bio TEXT";
    if(!mysqli_query($conn, $alter)){
        response(false, 'Unable to create bio column');
    }
}

$stmt = $conn->prepare("UPDATE users SET bio = ? WHERE id = ?");
$stmt->bind_param('si', $bio, $user_id);
$ok = $stmt->execute();
if($ok){
    response(true, 'Bio saved');
} else {
    response(false, 'Failed to save bio');
}
?>