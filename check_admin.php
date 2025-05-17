<?php
// Database connection
require_once "db_setup.php";

// Check if admin table exists
$table_check = $conn->query("SHOW TABLES LIKE 'admins'");
$table_exists = $table_check->num_rows > 0;

$admin_exists = false;

if ($table_exists) {
    // Check if any admin exists
    $result = $conn->query("SELECT COUNT(*) as count FROM admins");
    $row = $result->fetch_assoc();
    $admin_exists = $row['count'] > 0;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode(['adminExists' => $admin_exists]);


?>