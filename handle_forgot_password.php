<?php

session_start();

require_once "db_setup.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['forgot-email'];
    
    
    $sql = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        
        $update_sql = "UPDATE admins SET reset_token = ?, reset_expires = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sss", $token, $expires, $email);
        $update_stmt->execute();
        
        
        
        $_SESSION['forgot_success'] = "Ubutumwa bwo guhindura ijambo ry'ibanga bwoherejwe kuri email yawe.";
        
        
        
        
        
        header("Location: index.php");
        exit();
    } else {
        
        $_SESSION['forgot_error'] = "Email ntibashije kuboneka mu bubiko bw'amakuru.";
        header("Location: index.php");
        exit();
    }
    
    $stmt->close();
}

$conn->close();
?>