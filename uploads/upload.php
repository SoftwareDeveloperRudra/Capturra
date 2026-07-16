<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart();
require_once "../config/database.php";

/* =========================
   CHECK LOGIN
========================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* =========================
   CHECK FILE INPUT
========================= */
if (!isset($_FILES["photo"]) || $_FILES["photo"]["error"] !== 0) {
    $_SESSION['error'] = "No file selected.";
    header("Location: ../public/photographer_home.php");
    exit();
}

/* =========================
   UPLOAD DIRECTORY
========================= */
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/Capturra/uploads/";

/* CREATE FOLDER IF NOT EXISTS */
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

/* =========================
   FILE DETAILS
========================= */
$fileTmp  = $_FILES["photo"]["tmp_name"];
$fileSize = $_FILES["photo"]["size"];
$fileName = $_FILES["photo"]["name"];

$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

/* =========================
   VALIDATION
========================= */

/* Allowed extensions */
$allowedExt = ["jpg", "jpeg", "png", "webp"];
if (!in_array($fileExt, $allowedExt)) {
    $_SESSION['error'] = "Only JPG, JPEG, PNG, WEBP allowed.";
    header("Location: ../public/photographer_home.php");
    exit();
}

/* MIME type check */
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime  = finfo_file($finfo, $fileTmp);
finfo_close($finfo);

$validMimeTypes = ["image/jpeg", "image/png", "image/webp"];
if (!in_array($mime, $validMimeTypes)) {
    $_SESSION['error'] = "Invalid file type.";
    header("Location: ../public/photographer_home.php");
    exit();
}

/* File size check (5MB max) */
if ($fileSize > 5 * 1024 * 1024) {
    $_SESSION['error'] = "File too large. Max 5MB allowed.";
    header("Location: ../public/photographer_home.php");
    exit();
}

/* =========================
   GENERATE UNIQUE NAME
========================= */
$uniqueName = uniqid("IMG_", true) . "." . $fileExt;
$targetPath = $uploadDir . $uniqueName;

/* =========================
   MOVE FILE
========================= */
if (move_uploaded_file($fileTmp, $targetPath)) {

    /* SAVE ONLY FILE NAME (IMPORTANT FIX) */
    $dbPath = $uniqueName;

    $stmt = $conn->prepare("INSERT INTO photos (user_id, image) VALUES (?, ?)");

    if (!$stmt) {
        $_SESSION['error'] = "Prepare failed: " . $conn->error;
        header("Location: ../public/photographer_home.php");
        exit();
    }

    $stmt->bind_param("is", $user_id, $dbPath);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Photo uploaded successfully!";
    } else {
        $_SESSION['error'] = "Database error: " . $stmt->error;
    }

    $stmt->close();

} else {
    $_SESSION['error'] = "Upload failed. Try again.";
}

/* =========================
   REDIRECT BACK
========================= */
header("Location: ../public/photographer_home.php");
exit();
?>