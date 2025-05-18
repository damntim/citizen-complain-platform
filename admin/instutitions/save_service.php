<?php
require_once "../../db_setup.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $institution_id = $_POST['institution_id'];
    $name_rw = $_POST['name_rw'];
    $name_en = $_POST['name_en'];
    $name_fr = $_POST['name_fr'];

    
    $stmt = $conn->prepare("INSERT INTO services (institution_id, name_rw, name_en, name_fr) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $institution_id, $name_rw, $name_en, $name_fr);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save service']);
    }

    $stmt->close();
    $conn->close();
}
?>