<?php
// Database connection configuration
$db_config = [
    'host' => 'localhost',
    'username' => 'root', // Change to your database username
    'password' => '', // Change to your database password
    'database' => 'citizen_portal' // Change to your database name
];

try {
    // Connect to MySQL server
    $conn = new mysqli($db_config['host'], $db_config['username'], $db_config['password']);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS " . $db_config['database'] . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (!$conn->query($sql)) {
        throw new Exception("Error creating database: " . $conn->error);
    }
    
    // Select the database
    $conn->select_db($db_config['database']);
    

    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    if (isset($conn)) {
       
    }
}
?>