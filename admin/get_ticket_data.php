<?php
require_once "../db_setup.php";


header('Content-Type: application/json');

try {
    $weekStart = date('Y-m-d', strtotime('monday this week'));
    $weekEnd = date('Y-m-d', strtotime('sunday this week'));

    $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    $data = [
        'labels' => $days,
        'new' => array_fill(0, 7, 0),
        'resolved' => array_fill(0, 7, 0)
    ];

    
    if (!$conn) {
        throw new Exception("Database connection failed");
    }

    $sql = "SELECT DATE(created_at) AS date, DAYOFWEEK(created_at) AS dow, status, COUNT(*) AS total
            FROM tickets
            WHERE DATE(created_at) BETWEEN ? AND ?
            GROUP BY DATE(created_at), status";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Query preparation failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $weekStart, $weekEnd);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $index = $row['dow'] - 2; 
        if ($index < 0) $index = 6; 
        if ($index > 6) continue;

        if ($row['status'] === 'new') {
            $data['new'][$index] = (int)$row['total'];
        } elseif ($row['status'] === 'completed') {
            $data['resolved'][$index] = (int)$row['total'];
        }
    }

    echo json_encode($data);
} catch (Exception $e) {
    
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
        'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        'new' => [0, 0, 0, 0, 0, 0, 0],
        'resolved' => [0, 0, 0, 0, 0, 0, 0]
    ]);
}
