<?php
// Set timezone to match your location (Surat/India)
date_default_timezone_set('Asia/Kolkata');

require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/config/database.php";

// Sync MySQL timezone with PHP timezone
mysqli_query($conn, "SET time_zone = '+05:30'");

header('Content-Type: application/json');

$filter = $_GET['filter'] ?? 'today';

switch($filter){
    case 'today':
        // Specifically targets the current calendar date
        $time_condition = "DATE(photos.upload_date) = CURDATE()";
        break;
    case 'week':
        $time_condition = "photos.upload_date >= NOW() - INTERVAL 7 DAY";
        break;
    case 'month':
        $time_condition = "photos.upload_date >= NOW() - INTERVAL 30 DAY";
        break;
    default:
        $time_condition = "1"; // All Time
}

// Check if comments table exists to prevent SQL errors
$checkComments = mysqli_query($conn, "SHOW TABLES LIKE 'comments'");
$hasComments = mysqli_num_rows($checkComments) > 0;

$comments_join = $hasComments ? "LEFT JOIN comments ON comments.photo_id = photos.id" : "";
$comment_count = $hasComments ? "COUNT(DISTINCT comments.id)" : "0";

$sql = "
SELECT 
    photos.id,
    photos.image,
    photos.upload_date,
    users.username,
    COUNT(DISTINCT likes.id) AS like_count,
    $comment_count AS comment_count
FROM photos
JOIN users ON photos.user_id = users.id
LEFT JOIN likes ON likes.photo_id = photos.id
$comments_join
WHERE $time_condition
GROUP BY photos.id
ORDER BY like_count DESC
LIMIT 12
";

$result = mysqli_query($conn, $sql);

$data = [];
if($result) {
    while($row = mysqli_fetch_assoc($result)){
        $data[] = $row;
    }
}

echo json_encode([
    "status" => true,
    "data" => $data
]);