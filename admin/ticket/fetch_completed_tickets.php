<?php
session_start();
require_once "../../db_setup.php";

$agentId = $_SESSION['user_id'] ?? null;
if (!$agentId) {
    echo "<tr><td colspan='6'>Agent not logged in.</td></tr>";
    exit;
}

$search = $_GET['search'] ?? '';
$monthFilter = $_GET['month'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 5;
$offset = ($page - 1) * $limit;

$searchTerm = '%' . $conn->real_escape_string($search) . '%';
$orderBy = "DESC";


$where = "
    t.status = 'completed'
    AND t.agent_on = $agentId
    AND (
        t.ticket_number LIKE '$searchTerm' OR
        t.subject LIKE '$searchTerm' OR
        t.full_name LIKE '$searchTerm' OR
        t.phone LIKE '$searchTerm' OR
        ins.name LIKE '$searchTerm' OR
        s.name_en LIKE '$searchTerm' OR
        s.name_rw LIKE '$searchTerm' OR
        s.name_fr LIKE '$searchTerm'
    )
";


if ($monthFilter === 'current') {
    $where .= " AND MONTH(t.completed_at) = MONTH(CURRENT_DATE()) AND YEAR(t.completed_at) = YEAR(CURRENT_DATE())";
} elseif ($monthFilter === 'last') {
    $where .= " AND MONTH(t.completed_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(t.completed_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)";
}


$query = "
    SELECT 
        t.*, 
        s.name_en, s.name_rw, s.name_fr,
        CASE 
            WHEN t.language = 'kinyarwanda' THEN s.name_rw 
            ELSE s.name_en 
        END AS service_name,
        ins.name AS institution_name,
        a.fullname AS agent_name
    FROM tickets t
    LEFT JOIN services s ON t.service = s.id
    JOIN admins a ON t.agent_on = a.id
    LEFT JOIN institutions ins ON t.institution = ins.id 
    WHERE $where
    ORDER BY t.completed_at $orderBy
    LIMIT $limit OFFSET $offset
";

$result = $conn->query($query);


$countQuery = "SELECT COUNT(*) as total FROM tickets t 
    LEFT JOIN services s ON t.service = s.id
    JOIN admins a ON t.agent_on = a.id
    LEFT JOIN institutions ins ON t.institution = ins.id 
    WHERE $where";

$countResult = $conn->query($countQuery);
$total = ($countResult && $row = $countResult->fetch_assoc()) ? (int)$row['total'] : 0;

if (!$result) {
    echo "<tr><td colspan='6'>Error: " . htmlspecialchars($conn->error) . "</td></tr>";
    exit;
}

$showing = $result->num_rows;

if ($showing > 0) {
    while ($ticket = $result->fetch_assoc()) {
        echo "<tr>
            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-700'>{$ticket['ticket_number']}</td>
            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-700'>{$ticket['subject']}</td>
            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-700'>{$ticket['full_name']}</td>
            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-700'>{$ticket['service_name']}</td>
            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-700'>" . date('Y-m-d', strtotime($ticket['completed_at'])) . "</td>
            <td class='px-6 py-4 whitespace-nowrap text-sm'>
                <button class='text-blue-600 hover:underline'>View</button>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='6' class='px-6 py-4 text-center text-sm text-gray-500'>No completed tickets found.</td></tr>";
}

echo "<script>
    document.getElementById('completed-tickets-showing').textContent = '$showing';
    document.getElementById('completed-tickets-total').textContent = '$total';
</script>";
