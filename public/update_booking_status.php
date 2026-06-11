<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/config/database.php";

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = intval($_POST['booking_id'] ?? 0);
    $status = trim($_POST['status'] ?? '');
    
    // Validate status
    $allowed_statuses = ['accepted', 'rejected', 'completed', 'pending'];
    if (!in_array($status, $allowed_statuses)) {
        $response['message'] = 'Invalid status';
        echo json_encode($response);
        exit;
    }
    
    if ($booking_id <= 0) {
        $response['message'] = 'Invalid booking ID';
        echo json_encode($response);
        exit;
    }
    
    try {
        $conn = getDBConnection();
        
        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $booking_id);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Booking status updated';
        } else {
            $response['message'] = 'Failed to update booking';
        }
        
        $stmt->close();
        $conn->close();
        
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);