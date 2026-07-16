<?php

header('Content-Type: application/json; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* =========================
   DATABASE CONNECTION
========================= */

$conn = new mysqli("localhost", "root", "", "capturra");

if ($conn->connect_error) {

    die(json_encode([
        "success" => false,
        "error" => $conn->connect_error
    ]));
}

/* =========================
   GET SEARCH QUERY
========================= */

$q = trim($_GET['q'] ?? '');

if ($q === '') {

    echo json_encode([
        "success" => true,
        "users" => [],
        "photos" => []
    ]);

    exit;
}

$searchTerm = "%$q%";

$users = [];
$photos = [];

/* =========================
   SEARCH USERS
========================= */

$userSql = "
SELECT first_name, last_name, username
FROM users
WHERE first_name LIKE ?
   OR last_name LIKE ?
   OR username LIKE ?
   OR email LIKE ?
LIMIT 6
";

$stmt = $conn->prepare($userSql);

if (!$stmt) {

    die(json_encode([
        "success" => false,
        "sql_error" => $conn->error
    ]));
}

$stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);

$stmt->execute();

$stmt->bind_result($f_name, $l_name, $u_name);

while ($stmt->fetch()) {

    $users[] = [
        "name" => trim($f_name . ' ' . $l_name),
        "username" => $u_name
    ];
}

$stmt->close();

/* =========================
   SEARCH PHOTOS
========================= */

$photoSql = "
SELECT p.id, p.image, p.caption, u.username
FROM photos p
JOIN users u ON p.user_id = u.id
WHERE p.caption LIKE ?
   OR u.username LIKE ?
ORDER BY p.upload_date DESC
LIMIT 6
";

$stmt = $conn->prepare($photoSql);

if ($stmt) {

    $stmt->bind_param("ss", $searchTerm, $searchTerm);

    $stmt->execute();

    $stmt->bind_result($p_id, $p_img, $p_caption, $p_user);

    while ($stmt->fetch()) {

        $photos[] = [
            "id" => $p_id,
            "image_path" => "/Capturra/" . $p_img,
            "username" => $p_user,
            "title" => !empty($p_caption) ? $p_caption : "Untitled"
        ];
    }

    $stmt->close();
}

/* =========================
   FINAL JSON RESPONSE
========================= */

echo json_encode([
    "success" => true,
    "users" => $users,
    "photos" => $photos
], JSON_UNESCAPED_SLASHES);