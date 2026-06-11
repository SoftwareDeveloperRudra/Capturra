<?php
include("../config/database.php");
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/session.php";
secureSessionStart();

// ✅ Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Safely get values (avoid undefined errors)
$first    = $_POST['first_name'] ?? null;
$last     = $_POST['last_name'] ?? null;
$username = $_POST['username'] ?? null;
$role     = $_POST['role'] ?? null;
$bio      = $_POST['bio'] ?? null;
$location = $_POST['location'] ?? null;
$website  = $_POST['website'] ?? null;

// ✅ Prepare query
$sql = "UPDATE users 
        SET first_name=?, last_name=?, username=?, role=?, bio=?, location=?, website=? 
        WHERE id=?";

$stmt = $conn->prepare($sql);

// ✅ Bind params
$stmt->bind_param(
    "sssssssi",
    $first,
    $last,
    $username,
    $role,
    $bio,
    $location,
    $website,
    $user_id
);

// ✅ Execute
if ($stmt->execute()) {
    header("Location: setting.php?success=1");
} else {
    header("Location: setting.php?error=1");
}

exit();
?>