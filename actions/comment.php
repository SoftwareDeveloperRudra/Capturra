<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart();
include("../config/database.php");

// Check login
if(!isset($_SESSION['user_id'])){
    header("Location: ../public/login.php");
    exit();
}

$user_id  = $_SESSION['user_id'];
$photo_id = intval($_POST['photo_id']);
$comment  = $_POST['comment'];

// Insert comment using prepared statement
$stmt = $conn->prepare("INSERT INTO comments (user_id, photo_id, comment) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $photo_id, $comment);
$stmt->execute();

// Redirect back
header("Location: ../public/photographer_home.php");
exit();
?>
