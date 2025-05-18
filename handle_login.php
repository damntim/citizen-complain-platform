<?php

session_start();

require_once "db_setup.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = $_POST['login-identifier'];
    $password = $_POST['login-password'];
    
    
    $sql = "SELECT * FROM admins WHERE (email = ? OR phone = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        
        if (password_verify($password, $user['password'])) {
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['fullname'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_image'] = $user['profile_image'];
            $_SESSION['is_admin'] = true;
            
            
            header("Location: admin/index.php");
            exit();
        } else {
            
            $_SESSION['login_error'] = "Incorrect password or email/phone.";
            header("Location: index.php");
            exit();
        }
    } 
    
    $stmt->close();
}

$conn->close();
?>