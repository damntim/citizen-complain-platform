<?php
session_start();
require_once "../../db_setup.php";
$agentId = $_SESSION['user_id'] ?? null;
if (!$agentId) {
    echo "<tr><td colspan='6'>Agent not logged in.</td></tr>";
    exit;
}

$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'newest';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 5;
$offset = ($page - 1) * $limit;

$searchTerm = '%' . $search . '%';
$orderBy = $sort === 'oldest' ? 'ASC' : 'DESC';

// Escape inputs for security
$searchTermEscaped = $conn->real_escape_string($searchTerm);
$agentId = (int)$agentId;

$query = "
   SELECT 
    t.*, 
    s.name_en, 
    s.name_rw, 
    s.name_fr,
    CASE 
        WHEN t.language = 'kinyarwanda' THEN s.name_rw 
        ELSE s.name_en 
    END AS service_name,
    ins.name AS institution_name,
    a.fullname AS agent_name
FROM 
    tickets t
LEFT JOIN 
    services s ON t.service = s.id
JOIN 
    admins a ON t.agent_on = a.id
LEFT JOIN 
    institutions ins ON t.institution = ins.id 
WHERE 
    t.status = 'ongoing' 
    AND t.agent_on = $agentId
    AND (
        t.ticket_number LIKE '$searchTermEscaped' OR
        t.subject LIKE '$searchTermEscaped' OR
        t.full_name LIKE '$searchTermEscaped' OR
        t.phone LIKE '$searchTermEscaped' OR
        ins.name LIKE '$searchTermEscaped' OR
        s.name_en LIKE '$searchTermEscaped' OR
        s.name_rw LIKE '$searchTermEscaped' OR
        s.name_fr LIKE '$searchTermEscaped'
    )
ORDER BY 
    t.updated_at $orderBy
LIMIT 
    $limit OFFSET $offset;
";

$result1 = $conn->query($query);

if (!$result1) {
    echo "<tr><td colspan='6'>Error: " . htmlspecialchars($conn->error) . "</td></tr>";
    exit;
}

if ($result1->num_rows > 0) {
    while ($ticket = $result1->fetch_assoc()) {
        echo "<tr>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-700'>{$ticket['ticket_number']}</td>
                 <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-700'>{$ticket['institution_name']}</td>
                 <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-700'>{$ticket['service_name']}</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-700'>{$ticket['subject']}</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-700'>" . date('Y-m-d', strtotime($ticket['updated_at'])) . "</td>
               
                
                <td class='px-6 py-4 whitespace-nowrap text-sm '>
                    <a href='add_response.php?ticket_id={$ticket['id']}' class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'>Add response</a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='6' class='px-6 py-4 text-center text-sm text-gray-500'>No tickets found.</td></tr>";
}
?>
