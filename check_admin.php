<?php

require_once "db_setup.php";


$table_check = $conn->query("SHOW TABLES LIKE 'admins'");
$table_exists = $table_check->num_rows > 0;

$admin_exists = false;

if ($table_exists) {
    
    $result = $conn->query("SELECT COUNT(*) as count FROM admins");
    $row = $result->fetch_assoc();
    $admin_exists = $row['count'] > 0;
}


header('Content-Type: application/json');
echo json_encode(['adminExists' => $admin_exists]);


?>