<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart();
$host = "localhost"; $dbname = "capturra"; $username = "root"; $password = "";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Login required']);
    exit;
}

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$my_id = $_SESSION['user_id'];
$creator_id = $_POST['creator_id'];
$my_name = $_POST['follower_name'];

// Check Follow Status
$check = $pdo->prepare("SELECT id FROM follows WHERE follower_id = ? AND following_id = ?");
$check->execute([$my_id, $creator_id]);

if ($check->rowCount() > 0) {
    $pdo->prepare("DELETE FROM follows WHERE follower_id = ? AND following_id = ?")->execute([$my_id, $creator_id]);
    echo json_encode(['status' => 'unfollowed']);
} else {
    // 1. Follow
    $pdo->prepare("INSERT INTO follows (follower_id, following_id) VALUES (?, ?)")->execute([$my_id, $creator_id]);
    
    // 2. Create Notification
    $msg = "$my_name started following you!";
    $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)")->execute([$creator_id, $msg]);
    
    echo json_encode(['status' => 'followed']);
}
