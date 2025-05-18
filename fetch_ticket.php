<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "db_setup.php";


$identifier = $_POST['identifier'] ?? '';


$response = [];

if (empty($identifier)) {
    $response['error'] = 'Please provide a ticket number or phone number.';
    echo json_encode($response);
    exit;
}


$isTicketNumber = (strpos($identifier, 'TKT-') === 0);


if ($isTicketNumber) {
    
    $query = "SELECT t.*, 
              i.name AS institution_name,
              CASE 
                  WHEN t.language = 'kinyarwanda' THEN s.name_rw 
                  WHEN t.language = 'english' THEN s.name_en
                  ELSE s.name_fr
              END AS service_name
              FROM tickets t
              LEFT JOIN institutions i ON t.institution = i.id
              LEFT JOIN services s ON t.service = s.id
              WHERE t.ticket_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $identifier);
} else {
    
    $query = "SELECT t.*, 
              i.name AS institution_name,
              CASE 
                  WHEN t.language = 'kinyarwanda' THEN s.name_rw 
                  WHEN t.language = 'english' THEN s.name_en
                  ELSE s.name_fr
              END AS service_name
              FROM tickets t
              LEFT JOIN institutions i ON t.institution = i.id
              LEFT JOIN services s ON t.service = s.id
              WHERE t.phone = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $identifier);
}


$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $response['error'] = 'No ticket found with the provided information.';
    echo json_encode($response);
    exit;
}


$tickets = [];
while ($row = $result->fetch_assoc()) {
    $tickets[] = $row;
}


$html = '';

if (count($tickets) === 1) {
    
    $ticket = $tickets[0];
    $html .= generateTicketDetailView($ticket, $conn);
} else {
    
    $html .= '<h4 class="text-lg font-medium mb-4">Found ' . count($tickets) . ' tickets:</h4>';
    $html .= '<div class="space-y-4">';
    
    foreach ($tickets as $ticket) {
        $html .= '<div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer ticket-item" data-ticket-id="' . $ticket['id'] . '">';
        $html .= '<div class="flex justify-between">';
        $html .= '<div class="font-medium">' . $ticket['ticket_number'] . '</div>';
        $html .= '<div class="text-sm text-gray-500">' . date('Y-m-d', strtotime($ticket['created_at'])) . '</div>';
        $html .= '</div>';
        $html .= '<div class="mt-2">' . $ticket['subject'] . '</div>';
        $html .= '<div class="mt-1 text-sm text-gray-600">' . $ticket['institution_name'] . ' - ' . $ticket['service_name'] . '</div>';
        $html .= '<div class="mt-1 text-sm ' . getStatusClass($ticket['status']) . '">' . ucfirst($ticket['status']) . '</div>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    
    // Fix the issue with ticket item click event listeners
    $html .= '<script>
        document.querySelectorAll(".ticket-item").forEach(item => {
            item.addEventListener("click", function() {
                const ticketId = this.getAttribute("data-ticket-id");
                
                // Show loading
                document.getElementById("ticket-loading").classList.remove("hidden");
                document.getElementById("ticket-results").classList.add("hidden");
                
                // Fetch ticket details
                fetch("fetch_ticket_detail.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: "ticket_id=" + encodeURIComponent(ticketId)
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById("ticket-loading").classList.add("hidden");
                    
                    if (data.error) {
                        document.getElementById("ticket-error").classList.remove("hidden");
                        document.getElementById("ticket-error").querySelector("p").textContent = data.error;
                    } else {
                        document.getElementById("ticket-results").classList.remove("hidden");
                        document.getElementById("ticket-results").innerHTML = data.html;
                        
                        // Initialize response interface
                        if (typeof initializeResponseInterface === "function") {
                            initializeResponseInterface();
                        }
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    document.getElementById("ticket-loading").classList.add("hidden");
                    document.getElementById("ticket-error").classList.remove("hidden");
                    document.getElementById("ticket-error").querySelector("p").textContent = "An error occurred while fetching the ticket details.";
                });
            });
        });
    </script>';
}

$response['html'] = $html;
echo json_encode($response);


function generateTicketDetailView($ticket, $conn) {
    $html = '<div class="bg-white rounded-lg shadow-sm p-6">';
    
    
    $html .= '<div class="border-b pb-4 mb-4">';
    $html .= '<div class="flex justify-between items-center">';
    $html .= '<h3 class="text-xl font-bold">' . $ticket['ticket_number'] . '</h3>';
    $html .= '<span class="px-3 py-1 rounded-full text-sm font-medium ' . getStatusClass($ticket['status']) . '">' . ucfirst($ticket['status']) . '</span>';
    $html .= '</div>';
    $html .= '<p class="text-gray-600 mt-1">' . date('F j, Y', strtotime($ticket['created_at'])) . '</p>';
    $html .= '</div>';
    
    
    $html .= '<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">';
    $html .= '<div>';
    $html .= '<h4 class="font-medium text-gray-700">Institution</h4>';
    $html .= '<p>' . $ticket['institution_name'] . '</p>';
    $html .= '</div>';
    
    $html .= '<div>';
    $html .= '<h4 class="font-medium text-gray-700">Service</h4>';
    $html .= '<p>' . $ticket['service_name'] . '</p>';
    $html .= '</div>';
    
    $html .= '<div>';
    $html .= '<h4 class="font-medium text-gray-700">Subject</h4>';
    $html .= '<p>' . $ticket['subject'] . '</p>';
    $html .= '</div>';
    
    $html .= '<div>';
    $html .= '<h4 class="font-medium text-gray-700">Submitted By</h4>';
    $html .= '<p>' . $ticket['full_name'] . '</p>';
    $html .= '</div>';
    $html .= '</div>';
    
    
    $html .= '<div class="mb-6">';
    $html .= '<h4 class="font-medium text-gray-700 mb-2">Description</h4>';
    $html .= '<div class="bg-gray-50 p-4 rounded-lg">' . nl2br(htmlspecialchars($ticket['description'])) . '</div>';
    $html .= '</div>';
    
    
    $html .= '<div class="border-t pt-4">';
    $html .= '<h4 class="font-medium text-gray-700 mb-4">Conversation History</h4>';
    
    
    $responseQuery = "SELECT r.*, a.fullname AS agent_name 
                     FROM response_ticket r
                     LEFT JOIN admins a ON r.agent_id = a.id
                     WHERE r.ticket_id = ?
                     ORDER BY r.created_at ASC";
    $stmt = $conn->prepare($responseQuery);
    $stmt->bind_param("i", $ticket['id']);
    $stmt->execute();
    $responses = $stmt->get_result();
    
    if ($responses->num_rows > 0) {
        $html .= '<div id="chat-messages" class="flex flex-col space-y-3 max-h-80 overflow-y-auto mb-4">';
        
        $lastResponse = null;
        while ($response = $responses->fetch_assoc()) {
            $lastResponse = $response;
            
            if ($response['sender'] === 'agent') {
                
                $html .= '<div class="bg-gray-100 p-3 rounded-lg mb-2 self-start max-w-3/4">';
                $html .= '<div class="font-medium text-sm">' . ($response['agent_name'] ?? 'Agent') . '</div>';
                $html .= '<p class="text-sm">' . nl2br(htmlspecialchars($response['message'])) . '</p>';
                $html .= '<p class="text-xs text-gray-500">' . date('M j, Y g:i a', strtotime($response['created_at'])) . '</p>';
                $html .= '</div>';
            } else {
                
                $html .= '<div class="bg-blue-100 p-3 rounded-lg mb-2 self-end max-w-3/4">';
                $html .= '<p class="text-sm">' . nl2br(htmlspecialchars($response['message'])) . '</p>';
                $html .= '<p class="text-xs text-gray-500 text-right">' . date('M j, Y g:i a', strtotime($response['created_at'])) . '</p>';
                $html .= '</div>';
            }
        }
        
        $html .= '</div>';
        
        
        if ($ticket['status'] === 'completed' && $lastResponse && $lastResponse['sender'] === 'agent') {
            $html .= '<div id="satisfaction-options" class="bg-gray-50 p-4 rounded-lg mb-4">';
            $html .= '<p class="mb-3">Are you satisfied with the response to your issue?</p>';
            $html .= '<div class="flex space-x-4">';
            $html .= '<button id="satisfied-btn" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Yes, I\'m satisfied</button>';
            $html .= '<button id="not-satisfied-btn" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">No, I need more help</button>';
            $html .= '</div>';
            $html .= '</div>';
            
            
            $html .= '<div id="satisfied-message" class="bg-green-100 p-4 rounded-lg mb-4 hidden">';
            $html .= '<p class="text-green-700">We\'re happy to hear that your problem is solved! Feel free to contact us anytime if you need further assistance.</p>';
            $html .= '</div>';
            
            
            $html .= '<div id="reopen-chat" class="hidden">';
            $html .= '<form id="send-message-form" data-ticket-id="' . $ticket['id'] . '">';
            $html .= '<div class="mb-3">';
            $html .= '<label for="new-message" class="block text-sm font-medium text-gray-700 mb-1">Please explain what additional help you need:</label>';
            $html .= '<textarea id="new-message" name="message" rows="4" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-rwandan-blue" required></textarea>';
            $html .= '</div>';
            $html .= '<div class="flex justify-between items-center">';
            $html .= '<button type="submit" class="bg-rwandan-blue text-white px-4 py-2 rounded-lg hover:bg-blue-600">Send Message</button>';
            $html .= '<div id="message-loading" class="hidden flex items-center">';
            $html .= '<svg class="animate-spin h-5 w-5 mr-2 text-rwandan-blue" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">';
            $html .= '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>';
            $html .= '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>';
            $html .= '</svg>';
            $html .= '<span>Sending...</span>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div id="message-sent" class="mt-2 text-green-600 hidden">Message sent successfully!</div>';
            $html .= '</form>';
            $html .= '</div>';
        }
    } else {
        $html .= '<p class="text-gray-500 italic">No responses yet. Please check back later.</p>';
    }
    
    $html .= '</div>'; 
    
    $html .= '</div>'; 
    
    return $html;
}


function getStatusClass($status) {
    switch ($status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'ongoing':
            return 'bg-blue-100 text-blue-800';
        case 'completed':
            return 'bg-green-100 text-green-800';
        case 'rejected':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}
?>