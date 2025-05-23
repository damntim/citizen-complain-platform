<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "db_setup.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'admin/ticket/vendor/autoload.php'; 


$ticketId = $_POST['ticket_id'] ?? '';
$message = $_POST['message'] ?? '';


$response = [];

if (empty($ticketId) || empty($message)) {
    $response['error'] = 'Please provide both ticket ID and message.';
    echo json_encode($response);
    exit;
}


$query = "SELECT * FROM tickets WHERE id = ? AND status = 'completed'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $ticketId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $response['error'] = 'Ticket not found or not in completed status.';
    echo json_encode($response);
    exit;
}


$insertQuery = "INSERT INTO response_ticket (ticket_id, sender, message, created_at) VALUES (?, 'citizen', ?, NOW())";
$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("is", $ticketId, $message);

if ($stmt->execute()) {
    
    $updateQuery = "UPDATE tickets SET updated_at = NOW(), status = 'ongoing' WHERE id = ?";

    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $ticketId);
    $stmt->execute();
    
    
    $agentQuery = "SELECT a.email, a.fullname FROM admins a 
                  JOIN tickets t ON a.id = t.agent_on 
                  WHERE t.id = ?";
    $agentStmt = $conn->prepare($agentQuery);
    $agentStmt->bind_param("i", $ticketId);
    $agentStmt->execute();
    $agentResult = $agentStmt->get_result();
    
    if ($agentResult->num_rows > 0) {
        $agent = $agentResult->fetch_assoc();
        
        
        $ticketQuery = "SELECT * FROM tickets WHERE id = ?";
        $ticketStmt = $conn->prepare($ticketQuery);
        $ticketStmt->bind_param("i", $ticketId);
        $ticketStmt->execute();
        $ticketResult = $ticketStmt->get_result();
        $ticket = $ticketResult->fetch_assoc();
        
        
        $emailMessage = [
            'subject' => 'New Response on Ticket #' . $ticket['ticket_number'] . ' ' . $ticket['subject'],
            'body' => "A citizen has responded to ticket #$ticketId.\n\nMessage: $message\n\nPlease log in to the system to respond."
        ];
        
        
        $emailSent = sendEmailNotification($agent, $emailMessage);
        if ($emailSent) {
            $response['email_sent'] = true;
        } else {
            $response['email_sent'] = false;
        }
    }
    
    $response['success'] = true;
    $response['message'] = 'Response saved successfully. And Status Updated To Ongoing ';
} else {
    $response['error'] = 'Failed to save response: ' . $conn->error;
}

echo json_encode($response);


function sendEmailNotification($ticket, $message) {
    $mail = new PHPMailer(true);
    
    try {
        
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ykdann53@gmail.com';
        $mail->Password   = 'kviz zxzn lkdp ccju';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        
        $mail->setFrom('ykdann53@gmail.com', 'Citizen Engagement Portal');
        $mail->addAddress($ticket['email'], $ticket['fullname']);
        
        
        $mail->isHTML(true);
        $mail->Subject = $message['subject'];
        
        
        $htmlMessage = nl2br(htmlspecialchars($message['body']));
        
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #3366cc;'>$message[subject]</h2>
                <div style='background-color: #f5f5f5; padding: 15px; border-radius: 5px;'>
                    $htmlMessage
                </div>
                <p style='font-size: 12px; color: #666; margin-top: 20px;'>
                    This is an automated message. Please do not reply to this email.
                </p>
            </div>
        ";
        $mail->AltBody = $message['body'];
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>