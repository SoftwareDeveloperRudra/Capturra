<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart();
include("../config/database.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../public/login.php");
    exit();
}

if(!isset($_POST['follow_id'])){
    header("Location: ../public/photographer_home.php");
    exit();
}

$follower_id = $_SESSION['user_id'];
$following_id = intval($_POST['follow_id']);

if($follower_id == $following_id){
    header("Location: ../public/photographer_home.php");
    exit();
}

// Check if already following
$stmt = $conn->prepare("SELECT id FROM followers WHERE follower_id=? AND following_id=?");
$stmt->bind_param("ii", $follower_id, $following_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){

    // Unfollow
    $stmt = $conn->prepare("DELETE FROM followers WHERE follower_id=? AND following_id=?");
    $stmt->bind_param("ii", $follower_id, $following_id);
    $stmt->execute();

}else{

    // Follow
    $stmt = $conn->prepare("INSERT INTO followers (follower_id, following_id) VALUES (?,?)");
    $stmt->bind_param("ii", $follower_id, $following_id);
    $stmt->execute();

}

header("Location: ".$_SERVER['HTTP_REFERER']);
exit();
?>