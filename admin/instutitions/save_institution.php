<?php
require_once "../../db_setup.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];

    // Prepare and execute the insert statement
    $stmt = $conn->prepare("INSERT INTO institutions (name) VALUES (?)");
    $stmt->bind_param("s", $name);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save institution']);
    }

    $stmt->close();
    $conn->close();
}
?>