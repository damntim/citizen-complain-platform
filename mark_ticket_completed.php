<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "db_setup.php";


$ticketId = $_POST['ticket_id'] ?? '';


$response = [];

if (empty($ticketId)) {
    $response['error'] = 'Please provide a ticket ID.';
    echo json_encode($response);
    exit;
}


$query = "UPDATE tickets SET citizen_remark = 'satisfied', decision = 'solved', completed_at = NOW(), updated_at = NOW() WHERE id = ?";


$stmt = $conn->prepare($query);
$stmt->bind_param("i", $ticketId);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    
    $message = "Ticket marked as completed by the citizen.";
    $insertQuery = "INSERT INTO response_ticket (ticket_id, sender, message, created_at) VALUES (?, 'citizen', ?, NOW())";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("is", $ticketId, $message);
    $stmt->execute();
    
    $response['success'] = true;
    $response['message'] = 'Ticket marked as completed.';
} else {
    $response['error'] = 'Failed to update ticket status or ticket is not in ongoing status.';
}

echo json_encode($response);
?>