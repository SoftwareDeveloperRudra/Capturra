<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/auth.php";
requireRole("photographer", true); // ✅ JSON mode for API

require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/config/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/response.php";

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT bio, city, price_per_day, experience_years FROM photographers WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    response(true, "No profile yet", null);
}

$response = $result->fetch_assoc();
response(true, "Profile loaded", $response);
