<?php

$db_config = [
    'host' => 'localhost',
    'username' => 'root', 
    'password' => '', 
    'database' => 'citizen_portal' 
];


try {
    
    $conn = new mysqli($db_config['host'], $db_config['username'], $db_config['password']);
    
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    
    $sql = "CREATE DATABASE IF NOT EXISTS " . $db_config['database'] . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (!$conn->query($sql)) {
        throw new Exception("Error creating database: " . $conn->error);
    }
    
    
    $conn->select_db($db_config['database']);
    

    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    if (isset($conn)) {
       
    }
}
?>