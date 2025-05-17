<?php
// Start session
session_start();

require_once "db_setup.php";

// Process forgot password form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['forgot-email'];
    
    // Check if email exists
    $sql = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Generate a random token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Store token in database
        $update_sql = "UPDATE admins SET reset_token = ?, reset_expires = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sss", $token, $expires, $email);
        $update_stmt->execute();
        
        // In a real application, you would send an email with a reset link
        // For this example, we'll just show a message
        $_SESSION['forgot_success'] = "Ubutumwa bwo guhindura ijambo ry'ibanga bwoherejwe kuri email yawe.";
        
        // In a real application, you would send an email like:
        // $reset_link = "https://yourdomain.com/reset_password.php?token=$token";
        // mail($email, "Reset Your Password", "Click this link to reset your password: $reset_link");
        
        header("Location: index.php");
        exit();
    } else {
        // Email not found
        $_SESSION['forgot_error'] = "Email ntibashije kuboneka mu bubiko bw'amakuru.";
        header("Location: index.php");
        exit();
    }
    
    $stmt->close();
}

$conn->close();
?>