<?php
include("../config/database.php");
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart();

$user_id = $_SESSION['user_id'];

if(isset($_FILES['profile_image'])) {

    $file = $_FILES['profile_image'];
    $filename = time() . "_" . basename($file['name']);
    $target = "../uploads/profiles/" . $filename;

    // Validate type
    $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

    if(in_array($file['type'], $allowed)) {

        if(move_uploaded_file($file['tmp_name'], $target)) {

            // Save to DB
            $sql = "UPDATE users SET profile_image=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $filename, $user_id);
            $stmt->execute();

            header("Location: setting.php?success=1");
            exit;

        } else {
            header("Location: setting.php?error=1");
        }

    } else {
        header("Location: setting.php?error=1");
    }

}
?>