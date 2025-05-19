<?php

ob_start();






if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo "<script>
        alert('You must log in first as an administrator.');
        window.location.href = '../../index.php';
    </script>";
    exit();
}



require_once "../../db_setup.php";


$user_id = $_SESSION['user_id'];


$new_tickets_query = "SELECT COUNT(*) as count FROM tickets WHERE status = 'new'";
$my_ongoing_query = "SELECT COUNT(*) as count FROM tickets WHERE status = 'ongoing' AND agent_on = ?";
$my_completed_query = "SELECT COUNT(*) as count FROM tickets WHERE status = 'completed' AND agent_on = ?";


$stmt = $conn->prepare($new_tickets_query);
$stmt->execute();
$new_tickets_count = $stmt->get_result()->fetch_assoc()['count'];


$stmt = $conn->prepare($my_ongoing_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$my_ongoing_count = $stmt->get_result()->fetch_assoc()['count'];


$stmt = $conn->prepare($my_completed_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$my_completed_count = $stmt->get_result()->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Management - Citizen Engagement System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .wave-container {
            position: relative;
            overflow: hidden;
        }
        .wave {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0.3;
            left: 0;
            top: 0;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.5));
            z-index: 0;
            transform: translateX(-100%);
            animation: wave 3s infinite linear;
        }
        .wave:nth-child(2) {
            animation-delay: 0.5s;
        }
        .wave:nth-child(3) {
            animation-delay: 1s;
        }
        @keyframes wave {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(100%);
            }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        .fade-in-delay-1 {
            animation-delay: 0.1s;
        }
        .fade-in-delay-2 {
            animation-delay: 0.2s;
        }
        .fade-in-delay-3 {
            animation-delay: 0.3s;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .progress-animation {
            animation: progressGrow 1s ease-out forwards;
            transform-origin: left;
        }
        @keyframes progressGrow {
            from {
                transform: scaleX(0);
            }
            to {
                transform: scaleX(1);
            }
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .bg-rwanda-blue {
            background-color: #0073e6;
        }
        .bg-rwanda-green {
            background-color: #00a651;
        }
        .bg-rwanda-yellow {
            background-color: #ffc72c;
        }
        .text-rwanda-blue {
            color: #0073e6;
        }
        .text-rwanda-green {
            color: #00a651;
        }
        .text-rwanda-yellow {
            color: #ffc72c;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        
        <?php include 'sidebar.php'; ?>

        
        <main class="flex-1 overflow-y-auto overflow-x-hidden bg-gray-100">
            
            <header class="bg-white shadow-sm sticky top-0 z-10">
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <div class="flex items-center">
                            <h1 class="text-xl font-semibold text-gray-800">Ticket Management</h1>
                        </div>

                        <div class="flex items-center space-x-4">
                            <button class="p-1 text-gray-500 hover:text-gray-700">
                                <i class="fas fa-search text-lg"></i>
                            </button>
                            <button class="p-1 text-gray-500 hover:text-gray-700 relative">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute top-0 right-0 w-2 h-2 rounded-full bg-red-500"></span>
                            </button>
                            <button class="hidden md:block p-1 text-gray-500 hover:text-gray-700">
                                <i class="fas fa-cog text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            
            <div class="p-4 sm:p-6 lg:p-8">
                
                <div class="mb-6 fade-in">
                    <h2 class="text-2xl font-bold text-gray-800">Ticket Dashboard</h2>
                    <p class="text-gray-600">Manage and track all citizen engagement tickets in one place.</p>
                </div>

                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    
                    <div class="bg-white rounded-lg shadow-sm p-6 wave-container fade-in fade-in-delay-1">
                        <div class="wave"></div>
                        <div class="wave"></div>
                        <div class="wave"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">New Tickets</h3>
                                <span class="p-2 rounded-full bg-rwanda-blue bg-opacity-10">
                                    <i class="fas fa-ticket-alt text-rwanda-blue"></i>
                                </span>
                            </div>
                            <p class="text-3xl font-bold"><?php echo $new_tickets_count; ?></p>
                            <div class="flex items-center mt-2">
                                <span class="text-gray-500 text-sm">Waiting for assignment</span>
                            </div>
                            <div class="mt-4">
                                <button onclick="switchTab('new')" class="w-full py-2 bg-rwanda-blue text-white rounded-md hover:bg-blue-700 transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-eye mr-2"></i> View All
                                </button>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-lg shadow-sm p-6 wave-container fade-in fade-in-delay-2">
                        <div class="wave"></div>
                        <div class="wave"></div>
                        <div class="wave"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">My Ongoing</h3>
                                <span class="p-2 rounded-full bg-rwanda-yellow bg-opacity-10">
                                    <i class="fas fa-spinner text-rwanda-yellow"></i>
                                </span>
                            </div>
                            <p class="text-3xl font-bold"><?php echo $my_ongoing_count; ?></p>
                            <div class="flex items-center mt-2">
                                <span class="text-gray-500 text-sm">Tickets you're working on</span>
                            </div>
                            <div class="mt-4">
                                <button onclick="switchTab('ongoing')" class="w-full py-2 bg-rwanda-yellow text-gray-800 rounded-md hover:bg-yellow-500 transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-tasks mr-2"></i> View All
                                </button>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-lg shadow-sm p-6 wave-container fade-in fade-in-delay-3">
                        <div class="wave"></div>
                        <div class="wave"></div>
                        <div class="wave"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">My Completed</h3>
                                <span class="p-2 rounded-full bg-rwanda-green bg-opacity-10">
                                    <i class="fas fa-check-circle text-rwanda-green"></i>
                                </span>
                            </div>
                            <p class="text-3xl font-bold"><?php echo $my_completed_count; ?></p>
                            <div class="flex items-center mt-2">
                                <span class="text-gray-500 text-sm">Successfully resolved</span>
                            </div>
                            <div class="mt-4">
                                <button onclick="switchTab('completed')" class="w-full py-2 bg-rwanda-green text-white rounded-md hover:bg-green-700 transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-clipboard-check mr-2"></i> View All
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-lg shadow-sm mb-6 fade-in">
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px overflow-x-auto">
                            <button id="tab-new" onclick="switchTab('new')" class="tab-button text-rwanda-blue border-rwanda-blue whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex-1 text-center">
                                <i class="fas fa-ticket-alt mr-2"></i> New Tickets
                            </button>
                            <button id="tab-ongoing" onclick="switchTab('ongoing')" class="tab-button text-gray-500 hover:text-gray-700 whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm flex-1 text-center">
                                <i class="fas fa-spinner mr-2"></i> My Ongoing Tickets
                            </button>
                            <button id="tab-completed" onclick="switchTab('completed')" class="tab-button text-gray-500 hover:text-gray-700 whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm flex-1 text-center">
                                <i class="fas fa-check-circle mr-2"></i> My Completed Tickets
                            </button>
                        </nav>
                    </div>
                </div>

                
                <div class="tab-content active" id="content-new">
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 fade-in">
                       
<div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full md:w-auto">
    <div class="relative">
        <input type="text" id="searchInput" placeholder="Search tickets..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-rwanda-blue focus:border-transparent">
        <div class="absolute left-3 top-2.5 text-gray-400">
            <i class="fas fa-search"></i>
        </div>
    </div>
    <select id="institutionFilter" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-rwanda-blue focus:border-transparent">
        <option value="">All Institutions</option>
        <?php
        
        $instSql = "SELECT id, name FROM institutions ORDER BY name ASC";
        $instResult = $conn->query($instSql);
        while ($inst = $instResult->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars($inst['name']) . '">' . htmlspecialchars($inst['name']) . '</option>';
        }
        ?>
    </select>
</div>

                        <div class="overflow-x-auto">
                        <?php

require_once "../../db_setup.php";


$sql = "
    SELECT t.*, 
           s.name_en, s.name_rw, s.name_fr, 
           s.name_en, s.name_rw, s.name_fr,
CASE 
    WHEN t.language = 'kinyarwanda' THEN s.name_rw 
    ELSE s.name_en 
END AS service_name,

           ins.name AS institution_name 
    FROM tickets t 
    LEFT JOIN services s ON t.service = s.id 
    LEFT JOIN institutions ins ON t.institution = ins.id 
    WHERE t.status = 'new' 
    ORDER BY t.created_at DESC
";

$result = $conn->query($sql);
?>

<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket #</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Institution</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">On service</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">probrem(title)</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
        </tr>
    </thead>
    <tbody id="ticketTableBody" class="bg-white divide-y divide-gray-200">

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['ticket_number']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['institution_name']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['service_name']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['subject']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['description']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['district']); ?>, <?php echo htmlspecialchars($row['sector']); ?>, <?php echo htmlspecialchars($row['cell']); ?>, <?php echo htmlspecialchars($row['village']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date("Y-m-d H:i", strtotime($row['created_at'])); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
    <a href="process_modal.php?id=<?php echo $row['id']; ?>" 
       class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-md transition">
       Process
    </a>
</td>

                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">No new tickets found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php $conn->close(); ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const institutionFilter = document.getElementById("institutionFilter");
    const tableBody = document.getElementById("ticketTableBody");

    function filterTickets() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedInstitution = institutionFilter.value.toLowerCase();
        let visibleCount = 0;

        const rows = tableBody.querySelectorAll("tr");
        rows.forEach(row => {
            const fullText = row.textContent.toLowerCase();
            const institution = row.cells[3].textContent.toLowerCase(); // 4th column is Institution

            const matchesSearch = fullText.includes(searchTerm);
            const matchesInstitution = !selectedInstitution || institution.includes(selectedInstitution);

            if (matchesSearch && matchesInstitution) {
                row.style.display = "";
                visibleCount++;
            } else {
                row.style.display = "none";
            }
        });

        // Update showing count
        document.getElementById("new-tickets-showing").textContent = visibleCount;
        document.getElementById("new-tickets-total").textContent = rows.length;
    }

    searchInput.addEventListener("input", filterTickets);
    institutionFilter.addEventListener("change", filterTickets);

    // Initial count on load
    filterTickets();
});
</script>


                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                            <span id="new-tickets-showing"><?php echo $result->num_rows; ?>tickets</span> of 
<span id="new-tickets-total"><?php echo $result->num_rows; ?>tickets</span>

                            </div>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Previous</button>
                                <button class="px-3 py-1 bg-rwanda-blue text-white rounded-md text-sm hover:bg-blue-700">Next</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="content-ongoing">
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 fade-in">
        <div class="flex flex-col md:flex-row items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 md:mb-0">My Ongoing Tickets</h3>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full md:w-auto">
                
                <div class="relative w-full md:w-64">
                    <input id="search-ticket" type="text" placeholder="Search tickets..." 
                        class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-rwanda-blue focus:border-transparent">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                
                <select id="sort-ticket" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-rwanda-blue focus:border-transparent">
                    <option value="">Sort by Date</option>
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                </select>
            </div>
        </div>

        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ticket #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">institution</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">On service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">problem(title)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Assigned</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="ongoing-tickets-table">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Loading tickets...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        
        <div class="mt-4 flex justify-between items-center">
            <div class="text-sm text-gray-500">
                Showing <span id="ongoing-tickets-showing">0</span> of <span id="ongoing-tickets-total">0</span> tickets
            </div>
            <div class="flex space-x-2">
                <button id="ongoing-prev" class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Previous</button>
                <button id="ongoing-next" class="px-3 py-1 bg-rwanda-blue text-white rounded-md text-sm hover:bg-blue-700">Next</button>
            </div>
        </div>
    </div>
</div>


                <div class="tab-content" id="content-completed">
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 fade-in">
                        <div class="flex flex-col md:flex-row items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 md:mb-0">My Completed Tickets</h3>
                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full md:w-auto">
                                <div class="relative">
                                    <input type="text" placeholder="Search tickets..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-rwanda-blue focus:border-transparent">
                                    <div class="absolute left-3 top-2.5 text-gray-400">
                                        <i class="fas fa-search"></i>
                                    </div>
                                </div>
                                <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-rwanda-blue focus:border-transparent">
                                    <option value="">Filter by Month</option>
                                    <option value="current">Current Month</option>
                                    <option value="last">Last Month</option>
                                    <option value="all">All Time</option>
                                </select>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket #</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Citizen</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="completed-tickets-table">
                                    
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                            <i class="fas fa-spinner fa-spin mr-2"></i> Loading tickets...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Showing <span id="completed-tickets-showing">0</span> of <span id="completed-tickets-total">0</span> tickets
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Previous</button>
                                <button class="px-3 py-1 bg-rwanda-blue text-white rounded-md text-sm hover:bg-blue-700">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
function loadCompletedTickets(page = 1) {
    const search = document.querySelector('#content-completed input[type="text"]').value;
    const month = document.querySelector('#content-completed select').value;

    fetch(`fetch_completed_tickets.php?search=${encodeURIComponent(search)}&month=${month}&page=${page}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('completed-tickets-table').innerHTML = html;
        });
}

// Attach listeners
document.querySelector('#content-completed input[type="text"]').addEventListener('input', () => loadCompletedTickets(1));
document.querySelector('#content-completed select').addEventListener('change', () => loadCompletedTickets(1));

// Load on first visit
document.addEventListener('DOMContentLoaded', () => {
    loadCompletedTickets();
});
</script>


                
                <div id="ticket-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-screen overflow-y-auto">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xl font-semibold text-gray-800">Ticket Details</h3>
                                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-6" id="ticket-details-content">
                            
                        </div>
                        <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                            <button onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Close</button>
                            <button id="ticket-action-button" class="px-4 py-2 bg-rwanda-blue text-white rounded-md text-sm hover:bg-blue-700">Assign to Me</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="ticket.js"></script>
    <script src="mytick.js" defer></script>
</body>
</html>