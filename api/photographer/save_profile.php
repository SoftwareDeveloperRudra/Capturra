<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/auth.php";
requireRole("photographer", true); // ✅ JSON mode for API
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/config/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/response.php";

$user_id = $_SESSION['user_id'];

// Get POST data
$bio = $_POST['bio'] ?? '';
$city = $_POST['city'] ?? '';
$price = $_POST['price_per_day'] ?? 0;
$experience = $_POST['experience_years'] ?? 0;

// Check if profile exists
$check = $conn->prepare("SELECT id FROM photographers WHERE user_id = ?");
$check->bind_param("i", $user_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    // UPDATE
    $stmt = $conn->prepare("
        UPDATE photographers 
        SET bio=?, city=?, price_per_day=?, experience_years=?
        WHERE user_id=?
    ");
    $stmt->bind_param("ssiii", $bio, $city, $price, $experience, $user_id);
    $stmt->execute();

    response(true, "Profile updated successfully");
} else {
    // INSERT
    $stmt = $conn->prepare("
        INSERT INTO photographers (user_id, bio, city, price_per_day, experience_years)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("issii", $user_id, $bio, $city, $price, $experience);
    $stmt->execute();

    response(true, "Profile created successfully");
}
