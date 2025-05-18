<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');


$host = 'localhost';
$dbname = 'citizen_portal';
$username = 'root';
$password = '';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}


$action = isset($_GET['action']) ? $_GET['action'] : '';
$institution_id = isset($_GET['institution_id']) ? $_GET['institution_id'] : '';
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'rw';


$valid_languages = ['rw', 'en', 'fr'];
if (!in_array($lang, $valid_languages)) {
    $lang = 'rw';
}

if ($action === 'get_institutions') {
    try {
        $stmt = $pdo->prepare("SELECT `id`, `name` FROM `institutions`");
        $stmt->execute();
        $institutions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($institutions);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch institutions']);
    }
} elseif ($action === 'get_services' && !empty($institution_id)) {
    try {
        
        $name_field = $lang === 'rw' ? 'name_rw' : ($lang === 'fr' ? 'name_fr' : 'name_en');
        
        $stmt = $pdo->prepare("SELECT `id`, `institution_id`, `$name_field` as `name` 
                             FROM `services` 
                             WHERE `institution_id` = ?");
        $stmt->execute([$institution_id]);
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($services);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch services']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action or parameters']);
}
?>