<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart();
include("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user_id = $_SESSION['user_id'];
    $photo_id = intval($_POST['photo_id']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    $sql = "INSERT INTO comments (photo_id, user_id, comment) 
            VALUES ($photo_id, $user_id, '$comment')";

    mysqli_query($conn, $sql);

    header("Location: photo.php?id=" . $photo_id);
}
?>
