<?php

session_start();


header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');


$db_config = [
    'host' => 'localhost',
   'username' => 'root', 
'password' => '', 
'database' => 'citizen_portal'
];




$response = [
    'success' => false,
    'message' => '',
    'ticket_number' => ''
];


function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}


function generate_ticket_number() {
    return 'TKT-' . date('y') . '-' . mt_rand(1000, 9999);

}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        
        $required_fields = [
            'full_name', 'phone', 'province', 'district', 'sector', 'cell', 'village',
            'institution', 'service', 'subject', 'description', 'terms'
        ];
        
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }
        
        
        $full_name = sanitize_input($_POST['full_name']);
        $phone = sanitize_input($_POST['phone']);
        $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
        
        
        $province = sanitize_input($_POST['province']);
        $district = sanitize_input($_POST['district']);
        $sector = sanitize_input($_POST['sector']);
        $cell = sanitize_input($_POST['cell']);
        $village = sanitize_input($_POST['village']);
        
        
        $institution = sanitize_input($_POST['institution']);
        $institution_text = $institution;
        if ($institution === 'other' && isset($_POST['other_institution'])) {
            $institution_text = sanitize_input($_POST['other_institution']);
        }
        
        $service = sanitize_input($_POST['service']);
        $service_text = $service;
        if ($service === 'other' && isset($_POST['other_service'])) {
            $service_text = sanitize_input($_POST['other_service']);
        }
        
        
        $subject = sanitize_input($_POST['subject']);
        $description = sanitize_input($_POST['description']);
        
        
        $notify_sms = isset($_POST['notify_sms']) && $_POST['notify_sms'] === '1' ? 1 : 0;
        $notify_email = isset($_POST['notify_email']) && $_POST['notify_email'] === '1' ? 1 : 0;
        $language = isset($_POST['language']) ? sanitize_input($_POST['language']) : 'kinyarwanda';
        
        
        if (!preg_match('/^[0-9]{10}$/', $phone)) {
            throw new Exception("Invalid phone number format");
        }
        
        
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        
        $ticket_number = generate_ticket_number();
        
        
        $conn = new mysqli($db_config['host'], $db_config['username'], $db_config['password'], $db_config['database']);
        
        
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }
        
        
        $conn->set_charset("utf8mb4");
        
        
        $stmt = $conn->prepare("INSERT INTO tickets (
            ticket_number, full_name, phone, email, 
            province, district, sector, cell, village,
            institution, institution_text, service, service_text,
            subject, description, notify_sms, notify_email, language,
            status, created_at
        ) VALUES (
            ?, ?, ?, ?, 
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?, ?,
            'new', NOW()
        )");
        
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        
        
        $stmt->bind_param(
            "sssssssssssssssiis",
            $ticket_number, $full_name, $phone, $email,
            $province, $district, $sector, $cell, $village,
            $institution, $institution_text, $service, $service_text,
            $subject, $description, $notify_sms, $notify_email, $language
        );
        
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        
        if (isset($_FILES['file-upload']) && !empty($_FILES['file-upload']['name'][0])) {
            $allowed_types = ['image/jpeg','image/jpg', 'image/png', 'application/pdf'];
            $max_size = 10 * 1024 * 1024; 
            
            $upload_dir = 'uploads/';
            
            
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            
            foreach ($_FILES['file-upload']['name'] as $key => $name) {
                if ($_FILES['file-upload']['error'][$key] === 0) {
                    $tmp_name = $_FILES['file-upload']['tmp_name'][$key];
                    $size = $_FILES['file-upload']['size'][$key];
                    $type = $_FILES['file-upload']['type'][$key];
                    
                    
                    if (!in_array($type, $allowed_types)) {
                        continue; 
                    }
                    
                    
                    if ($size > $max_size) {
                        continue; 
                    }
                    
                    
                    $file_extension = pathinfo($name, PATHINFO_EXTENSION);
                    $new_filename = $ticket_number . '_' . uniqid() . '.' . $file_extension;
                    $destination = $upload_dir . $new_filename;
                    
                    
                    if (move_uploaded_file($tmp_name, $destination)) {
                        
                        $file_stmt = $conn->prepare("INSERT INTO ticket_attachments (
                            ticket_number, file_name, original_name, file_type, file_size, created_at
                        ) VALUES (?, ?, ?, ?, ?, NOW())");
                        
                        $file_stmt->bind_param(
                            "ssssi",
                            $ticket_number, $new_filename, $name, $type, $size
                        );
                        
                        $file_stmt->execute();
                        $file_stmt->close();
                    }
                }
            }
        }
        
        
        $stmt->close();
        $conn->close();
        
        
        $response['success'] = true;
        $response['message'] = 'Ticket submitted successfully';
        $response['ticket_number'] = $ticket_number;
        
    } catch (Exception $e) {
        
        $response['success'] = false;
        $response['message'] = $e->getMessage();
    }
} else {
    
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}


echo json_encode($response);
?>