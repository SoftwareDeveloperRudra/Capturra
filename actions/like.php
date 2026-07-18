<?php
header('Content-Type: application/json');
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart();
include("../config/database.php");

// Check login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "not_logged_in"]);
    exit();
}

// Check image
if (!isset($_POST['photo_id'])) {
    echo json_encode(["status" => "error", "message" => "no_photo_id"]);
    exit();
}

$user_id  = $_SESSION['user_id'];
$photo_id = intval($_POST['photo_id']);

// Check if already liked
$stmt = $conn->prepare("SELECT id FROM likes WHERE user_id=? AND photo_id=?");
$stmt->bind_param("ii", $user_id, $photo_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    // Unlike
    $stmt = $conn->prepare("DELETE FROM likes WHERE user_id=? AND photo_id=?");
    $stmt->bind_param("ii", $user_id, $photo_id);
    $stmt->execute();

    // Decrease likes count in photos table
    $conn->query("UPDATE photos SET likes = GREATEST(likes - 1, 0) WHERE id = $photo_id");

    echo json_encode(["status" => "unliked"]);

} else {

    // Like
    $stmt = $conn->prepare("INSERT INTO likes (user_id, photo_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $photo_id);
    $stmt->execute();

    // Increase likes count in photos table
    $conn->query("UPDATE photos SET likes = likes + 1 WHERE id = $photo_id");

    echo json_encode(["status" => "liked"]);
}
?>