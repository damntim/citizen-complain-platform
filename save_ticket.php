<?php
// Start session for potential future use
session_start();

// Set headers to prevent caching and specify JSON response
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Database connection configuration
$db_config = [
    'host' => 'localhost',
    'username' => 'root', // Change to your database username
    'password' => '', // Change to your database password
    'database' => 'citizen_portal' // Change to your database name
];

// Response array
$response = [
    'success' => false,
    'message' => '',
    'ticket_number' => ''
];

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Function to generate a unique ticket number
function generate_ticket_number() {
    return 'TKT-' . date('y') . '-' . mt_rand(1000, 9999);

}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $required_fields = [
            'full_name', 'phone', 'province', 'district', 'sector', 'cell', 'village',
            'institution', 'service', 'subject', 'description', 'terms'
        ];
        
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }
        
        // Sanitize input data
        $full_name = sanitize_input($_POST['full_name']);
        $phone = sanitize_input($_POST['phone']);
        $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
        
        // Location data
        $province = sanitize_input($_POST['province']);
        $district = sanitize_input($_POST['district']);
        $sector = sanitize_input($_POST['sector']);
        $cell = sanitize_input($_POST['cell']);
        $village = sanitize_input($_POST['village']);
        
        // Institution and service
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
        
        // Problem details
        $subject = sanitize_input($_POST['subject']);
        $description = sanitize_input($_POST['description']);
        
        // Notification preferences
        $notify_sms = isset($_POST['notify_sms']) && $_POST['notify_sms'] === '1' ? 1 : 0;
        $notify_email = isset($_POST['notify_email']) && $_POST['notify_email'] === '1' ? 1 : 0;
        $language = isset($_POST['language']) ? sanitize_input($_POST['language']) : 'kinyarwanda';
        
        // Validate phone number format
        if (!preg_match('/^[0-9]{10}$/', $phone)) {
            throw new Exception("Invalid phone number format");
        }
        
        // Validate email if provided
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        // Generate a unique ticket number
        $ticket_number = generate_ticket_number();
        
        // Connect to database
        $conn = new mysqli($db_config['host'], $db_config['username'], $db_config['password'], $db_config['database']);
        
        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }
        
        // Set character set
        $conn->set_charset("utf8mb4");
        
        // Prepare SQL statement for tickets table
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
        
        // Bind parameters
        $stmt->bind_param(
            "sssssssssssssssiis",
            $ticket_number, $full_name, $phone, $email,
            $province, $district, $sector, $cell, $village,
            $institution, $institution_text, $service, $service_text,
            $subject, $description, $notify_sms, $notify_email, $language
        );
        
        // Execute the statement
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        // Handle file uploads if any
        if (isset($_FILES['file-upload']) && !empty($_FILES['file-upload']['name'][0])) {
            $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
            $max_size = 10 * 1024 * 1024; // 10MB
            
            $upload_dir = 'uploads/';
            
            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // Loop through each uploaded file
            foreach ($_FILES['file-upload']['name'] as $key => $name) {
                if ($_FILES['file-upload']['error'][$key] === 0) {
                    $tmp_name = $_FILES['file-upload']['tmp_name'][$key];
                    $size = $_FILES['file-upload']['size'][$key];
                    $type = $_FILES['file-upload']['type'][$key];
                    
                    // Validate file type
                    if (!in_array($type, $allowed_types)) {
                        continue; // Skip this file
                    }
                    
                    // Validate file size
                    if ($size > $max_size) {
                        continue; // Skip this file
                    }
                    
                    // Generate a unique filename
                    $file_extension = pathinfo($name, PATHINFO_EXTENSION);
                    $new_filename = $ticket_number . '_' . uniqid() . '.' . $file_extension;
                    $destination = $upload_dir . $new_filename;
                    
                    // Move the file
                    if (move_uploaded_file($tmp_name, $destination)) {
                        // Insert file information into database
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
        
        // Close statement and connection
        $stmt->close();
        $conn->close();
        
        // Set success response
        $response['success'] = true;
        $response['message'] = 'Ticket submitted successfully';
        $response['ticket_number'] = $ticket_number;
        
    } catch (Exception $e) {
        // Set error response
        $response['success'] = false;
        $response['message'] = $e->getMessage();
    }
} else {
    // Not a POST request
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}

// Return JSON response
echo json_encode($response);
?>