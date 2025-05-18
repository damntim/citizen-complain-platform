<?php
session_start();
require_once "../../db_setup.php";
require_once "pushbullet_sms.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';


if (!isset($_POST['ticket_id']) || empty($_POST['ticket_id'])) {
    echo json_encode(['success' => false, 'message' => 'Ticket ID is required']);
    exit;
}

$ticketId = intval($_POST['ticket_id']);


$ticketQuery = "SELECT * FROM tickets WHERE id = ?";
$stmt = $conn->prepare($ticketQuery);
$stmt->bind_param("i", $ticketId);
$stmt->execute();
$ticketResult = $stmt->get_result();

if ($ticketResult->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Ticket not found']);
    exit;
}

$ticket = $ticketResult->fetch_assoc();
$stmt->close();


$messages = [
    'en' => [
        'subject' => 'Dear' . $ticket['full_name'] . '',
        'body' => "\nWe are pleased to inform you that your request (Ticket #" . $ticket['ticket_number'] . ") has been received by our team. Our agents are now working on your case and you will receive a response soon.\n\nTo check the status of your request, please visit our website at www.ctm.com and enter your ticket number: " . $ticket['ticket_number'] . "\n\nThank you for your patience.\n\nBest regards,\nCustomer Support Team"
    ],
    'rw' => [
        'subject' => 'Mukomere' . $ticket['full_name'] . '',
        'body' => "\nTunejejwe no kubamenyesha ko ikibazo cyanyu (Nomero #" . $ticket['ticket_number'] . ") cyakiriwe n'ikipe yacu. Abakozi bacu barimo gukora ku kibazo cyanyu kandi muzabona igisubizo vuba.\n\nKugira ngo murebe aho ikibazo cyanyu kigeze, musure urubuga rwacu www.ctm.com maze mwandike nomero y'ikibazo cyanyu: " . $ticket['ticket_number'] . "\n\nTurabashimira kwihangana kwanyu.\n\nMurakoze,\nIkipe Ishinzwe Kwakira Abaturage"
    ],
    'fr' => [
        'subject' => 'Cher(e)' . $ticket['full_name'] . '',
        'body' => "\nNous avons le plaisir de vous informer que votre demande (Ticket #" . $ticket['ticket_number'] . ") a été reçue par notre équipe. Nos agents travaillent actuellement sur votre cas et vous recevrez une réponse prochainement.\n\nPour vérifier l'état de votre demande, veuillez visiter notre site web à www.ctm.com et saisir votre numéro de ticket: " . $ticket['ticket_number'] . "\n\nNous vous remercions de votre patience.\n\nCordialement,\nL'équipe du Support Client"
    ]
];


$languageMapping = [
    'kinyarwanda' => 'rw',
    'english' => 'en',
    'french' => 'fr',
    
];


$codeLanguage = 'en'; 
if (isset($ticket['language'])) {
    if (array_key_exists(strtolower($ticket['language']), $languageMapping)) {
        $codeLanguage = $languageMapping[strtolower($ticket['language'])];
    } elseif (in_array($ticket['language'], ['en', 'rw', 'fr'])) {
        $codeLanguage = $ticket['language'];
    }
}

$notificationsSent = [];
$errors = [];


if ($ticket['notify_email'] == 1 && !empty($ticket['email'])) {
    $emailSent = sendEmailNotification($ticket, $messages[$codeLanguage]);
    if ($emailSent) {
        $notificationsSent[] = 'Email';
    } else {
        $errors[] = 'Failed to send email notification';
    }
}


if ($ticket['notify_sms'] == 1 && !empty($ticket['phone']) && isset($_POST['confirm_sms']) && $_POST['confirm_sms'] == 1) {
    $smsSent = sendSMSNotification($ticket, $messages[$codeLanguage]);
    if ($smsSent) {
        $notificationsSent[] = 'SMS';
    } else {
        $errors[] = 'Failed to send SMS notification';
    }
}


if ($ticket['status'] == 'new') {
    
    $agentId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    
    $updateQuery = "UPDATE tickets SET status = 'ongoing', updated_at = NOW(), 
                    agent_on = ?, notified = 1 WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ii", $agentId, $ticketId);
    $stmt->execute();
    $stmt->close();
    
    
    $notificationQuery = "INSERT INTO ticket_notifications 
                     (ticket_id, ticket_number, full_name, language, email_sent, sms_sent, 
                      agent_on, created_at) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($notificationQuery);
    $emailSent = in_array('Email', $notificationsSent) ? 1 : 0;
    $smsSent = in_array('SMS', $notificationsSent) ? 1 : 0;
    $stmt->bind_param("isssiis", 
                     $ticketId, 
                     $ticket['ticket_number'], 
                     $ticket['full_name'], 
                     $ticket['language'], 
                     $emailSent, 
                     $smsSent, 
                     $agentId);
    $stmt->execute();
    $stmt->close();
}


$conn->close();


if (!empty($notificationsSent)) {
    echo json_encode([
        'success' => true, 
        'message' => 'Notifications sent via: ' . implode(' and ', $notificationsSent),
        'errors' => $errors
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'No notifications were sent',
        'errors' => $errors
    ]);
}


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
        $mail->addAddress($ticket['email'], $ticket['full_name']);
        
        
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


function sendSMSNotification($ticket, $message) {
    try {
        
        if (!is_array($message) || !isset($message['body'])) {
            return false;
        }
        
        
        $apiKey = 'o.3M270774dHnTkOqF71kCHcoh4l1EPkB9';
        $deviceId = 'ujBxBb7gN88sjwHDEIYWFU';
        
        $sms = new PushbulletSMS($apiKey, $deviceId);
        
        
        $smsText = "Ticket #" . $ticket['ticket_number'] . ": " . 
                  substr($message['body'], 0, 500); 
        
        
        $result = $sms->sendSMS($ticket['phone'], $smsText);
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>