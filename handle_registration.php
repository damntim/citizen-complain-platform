<?php

session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['register-fullname'];
    $email = $_POST['register-email'];
    $phone = $_POST['register-phone'];
    $password = $_POST['register-password'];
    $confirm_password = $_POST['register-confirm-password'];
    
    
    if ($password !== $confirm_password) {
        $_SESSION['register_error'] = "Amagambo y'ibanga ntabwo ahura.";
        header("Location: index.php");
        exit();
    }
    
    
    require_once "db_setup.php";
    
    
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
    
    
    $profile_image_name = "";
    if (isset($_FILES['profile-image']) && $_FILES['profile-image']['error'] == 0) {
        
        $upload_dir = "uploads/profiles/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        
        $file_extension = pathinfo($_FILES['profile-image']['name'], PATHINFO_EXTENSION);
        
        
        $random_number = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        
        
        $sanitized_name = preg_replace('/[^a-zA-Z0-9]/', '_', $fullname);
        $new_filename = $sanitized_name . '_' . $random_number . '.' . $file_extension;
        
        
        $target_path = $upload_dir . $new_filename;
        
        
        if (move_uploaded_file($_FILES['profile-image']['tmp_name'], $target_path)) {
            
            $profile_image_name = $new_filename;
        } else {
            $_SESSION['register_error'] = "Habaye ikibazo mu kubika ifoto y'umwirondoro.";
            header("Location: index.php");
            exit();
        }
    }
    
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    
    $status = "approved";
    
    
    $sql = "INSERT INTO admins (fullname, email, phone, password, profile_image, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $fullname, $email, $phone, $hashed_password, $profile_image_name, $status);
    
    if ($stmt->execute()) {
        $_SESSION['register_success'] = "The account has been created and also you in charger for creating account for another users. Now you can login.";
        $_SESSION['show_login_modal'] = true; 
        header("Location: index.php?registration=success"); 
        exit();
    } else {
        $_SESSION['register_error'] = "There is problem on: " . $stmt->error;
        header("Location: index.php?registration=error"); 
        exit();
    }
    
    $stmt->close();
    $conn->close(); 
}
?>