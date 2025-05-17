<?php
// Start session
session_start();

// Process registration form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['register-fullname'];
    $email = $_POST['register-email'];
    $phone = $_POST['register-phone'];
    $password = $_POST['register-password'];
    $confirm_password = $_POST['register-confirm-password'];
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['register_error'] = "Amagambo y'ibanga ntabwo ahura.";
        header("Location: index.php");
        exit();
    }
    
    // Include database connection - moved here to ensure fresh connection
    require_once "db_setup.php";
    
    // Check if admin table is empty
    $check_sql = "SELECT COUNT(*) as count FROM admins";
    $check_result = $conn->query($check_sql);
    
    if (!$check_result) {
        $_SESSION['register_error'] = "Database error: " . $conn->error;
        header("Location: index.php");
        exit();
    }
    
    $row = $check_result->fetch_assoc();
    
    if ($row['count'] > 0) {
        $_SESSION['register_error'] = "Konti y'umuyobozi isanzwe ihari. Ntushobora gukora indi.";
        header("Location: index.php");
        exit();
    }
    
    // Process profile image
    $profile_image_name = "";
    if (isset($_FILES['profile-image']) && $_FILES['profile-image']['error'] == 0) {
        // Create uploads directory if it doesn't exist
        $upload_dir = "uploads/profiles/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Get file extension
        $file_extension = pathinfo($_FILES['profile-image']['name'], PATHINFO_EXTENSION);
        
        // Generate random 3-digit number
        $random_number = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        
        // Create sanitized filename from fullname + random number
        $sanitized_name = preg_replace('/[^a-zA-Z0-9]/', '_', $fullname);
        $new_filename = $sanitized_name . '_' . $random_number . '.' . $file_extension;
        
        // Set the target path for file upload
        $target_path = $upload_dir . $new_filename;
        
        // Move uploaded file
        if (move_uploaded_file($_FILES['profile-image']['tmp_name'], $target_path)) {
            // Store only the filename, not the full path
            $profile_image_name = $new_filename;
        } else {
            $_SESSION['register_error'] = "Habaye ikibazo mu kubika ifoto y'umwirondoro.";
            header("Location: index.php");
            exit();
        }
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Set default status as approved
    $status = "approved";
    
    // Insert new admin with profile image and status
    $sql = "INSERT INTO admins (fullname, email, phone, password, profile_image, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $fullname, $email, $phone, $hashed_password, $profile_image_name, $status);
    
    if ($stmt->execute()) {
        $_SESSION['register_success'] = "The account has been created and also you in charger for creating account for another users. Now you can login.";
        $_SESSION['show_login_modal'] = true; // Add this to automatically show the login modal
        header("Location: index.php?registration=success"); // Add query parameter for better tracking
        exit();
    } else {
        $_SESSION['register_error'] = "There is problem on: " . $stmt->error;
        header("Location: index.php?registration=error"); // Add query parameter for better tracking
        exit();
    }
    
    $stmt->close();
    $conn->close(); // Only close connection at the end of the script
}
?>