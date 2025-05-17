<?php
header('Content-Type: application/json');

require_once "db_setup.php";

// Get institution_id and language from query parameters
$institution_id = isset($_GET['institution_id']) ? intval($_GET['institution_id']) : 0;
$language = isset($_GET['lang']) ? $_GET['lang'] : 'rw';

// Determine which name field to select based on language
$name_field = $language === 'en' ? 'name_en' : ($language === 'fr' ? 'name_fr' : 'name_rw');

$sql = "SELECT id, institution_id, $name_field AS name_rw, name_en, name_fr 
        FROM services 
        WHERE institution_id = ? 
        ORDER BY $name_field ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $institution_id);
$stmt->execute();
$result = $stmt->get_result();

$services = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

$stmt->close();
$conn->close();
echo json_encode($services);
?>