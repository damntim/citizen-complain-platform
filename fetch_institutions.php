<?php
header('Content-Type: application/json');
require_once "db_setup.php";

$sql = "SELECT id, name FROM institutions ORDER BY name ASC";
$result = $conn->query($sql);

$institutions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $institutions[] = $row;
    }
}

$conn->close();
echo json_encode($institutions);
?>