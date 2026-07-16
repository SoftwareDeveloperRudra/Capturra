<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/config/database.php";

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $event_date = trim($_POST['date'] ?? '');
    $event_type = trim($_POST['event'] ?? 'Others');
    $payment_id = trim($_POST['pay_id'] ?? '');
    $photographer_id = intval($_POST['photographer_id'] ?? 0);
    
    // Get client_id from session (if logged in)
    $client_id = $_SESSION['user_id'] ?? 0;
    
    // Validation
    if (empty($name) || empty($phone) || empty($event_date)) {
        $response['message'] = 'All fields are required';
        echo json_encode($response);
        exit;
    }
    
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $response['message'] = 'Invalid phone number';
        echo json_encode($response);
        exit;
    }
    
    try {
        $conn = getDBConnection();
        
        // Insert booking
        $stmt = $conn->prepare("INSERT INTO bookings (client_id, photographer_id, event_date, event_type, status, message, payment_id, created_at) VALUES (?, ?, ?, ?, 'pending', ?, ?, NOW())");
        
        $message = json_encode([
            'name' => $name,
            'phone' => $phone,
            'event_type' => $event_type
        ]);
        
        $stmt->bind_param("iissss", $client_id, $photographer_id, $event_date, $event_type, $message, $payment_id);
        
        if ($stmt->execute()) {
            $booking_id = $conn->insert_id;
            $response['success'] = true;
            $response['message'] = 'Booking confirmed successfully!';
            $response['booking_id'] = $booking_id;
        } else {
            $response['message'] = 'Failed to save booking';
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