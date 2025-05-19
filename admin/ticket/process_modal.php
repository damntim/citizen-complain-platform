<?php

session_start();


if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['error' => 'Ticket ID is required']);
    exit;
}

$ticketId = intval($_GET['id']);

require_once "../../db_setup.php";


$ticketQuery = "SELECT * FROM tickets WHERE id = ?";
$stmt = $conn->prepare($ticketQuery);
$stmt->bind_param("i", $ticketId);
$stmt->execute();
$ticketResult = $stmt->get_result();

if ($ticketResult->num_rows === 0) {
    echo json_encode(['error' => 'Ticket not found']);
    exit;
}

$ticket = $ticketResult->fetch_assoc();
$stmt->close();


$institutionName = "";
if (!empty($ticket['institution'])) {
    $institutionQuery = "SELECT name FROM institutions WHERE id = ?";
    $stmt = $conn->prepare($institutionQuery);
    $stmt->bind_param("i", $ticket['institution']);
    $stmt->execute();
    $institutionResult = $stmt->get_result();
    if ($institutionResult->num_rows > 0) {
        $institution = $institutionResult->fetch_assoc();
        $institutionName = $institution['name'];
    }
    $stmt->close();
} else if (!empty($ticket['institution_text'])) {
    $institutionName = $ticket['institution_text'];
}


$serviceName = "";
if (!empty($ticket['service'])) {
    $language = $ticket['language'] ?? 'en';
    $serviceField = 'name_en'; 
    
    if ($language == 'rw') {
        $serviceField = 'name_rw';
    } else if ($language == 'fr') {
        $serviceField = 'name_fr';
    }
    
    $serviceQuery = "SELECT $serviceField FROM services WHERE id = ? AND institution_id = ?";
    $stmt = $conn->prepare($serviceQuery);
    $stmt->bind_param("ii", $ticket['service'], $ticket['institution']);
    $stmt->execute();
    $serviceResult = $stmt->get_result();
    if ($serviceResult->num_rows > 0) {
        $service = $serviceResult->fetch_assoc();
        $serviceName = $service[$serviceField];
    }
    $stmt->close();
} else if (!empty($ticket['service_text'])) {
    $serviceName = $ticket['service_text'];
}


$createdDate = !empty($ticket['created_at']) ? date('M d, Y h:i A', strtotime($ticket['created_at'])) : 'N/A';
$updatedDate = !empty($ticket['updated_at']) ? date('M d, Y h:i A', strtotime($ticket['updated_at'])) : 'N/A';
$completedDate = !empty($ticket['completed_at']) ? date('M d, Y h:i A', strtotime($ticket['completed_at'])) : 'N/A';


$conn->close();


$statusClass = 'bg-gray-500';
switch (strtolower($ticket['status'])) {
    case 'open':
        $statusClass = 'bg-green-500';
        break;
    case 'in progress':
        $statusClass = 'bg-blue-500';
        break;
    case 'pending':
        $statusClass = 'bg-yellow-500';
        break;
    case 'resolved':
        $statusClass = 'bg-purple-500';
        break;
    case 'closed':
        $statusClass = 'bg-red-500';
        break;
}


$notifications = [];
if ($ticket['notify_sms'] == 1) {
    $notifications[] = 'SMS';
}
if ($ticket['notify_email'] == 1) {
    $notifications[] = 'Email';
}
$notificationString = !empty($notifications) ? implode(', ', $notifications) : 'None';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rwanda Citizen Engagement - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --rwanda-blue: #00a0e9;
            --rwanda-yellow: #fad201;
            --rwanda-green: #00a651;
        }

        .bg-rwanda-blue {
            background-color: var(--rwanda-blue);
        }

        .bg-rwanda-yellow {
            background-color: var(--rwanda-yellow);
        }

        .bg-rwanda-green {
            background-color: var(--rwanda-green);
        }

        .text-rwanda-blue {
            color: var(--rwanda-blue);
        }

        .text-rwanda-yellow {
            color: var(--rwanda-yellow);
        }

        .text-rwanda-green {
            color: var(--rwanda-green);
        }

        .border-rwanda-blue {
            border-color: var(--rwanda-blue);
        }

        .border-rwanda-yellow {
            border-color: var(--rwanda-yellow);
        }

        .border-rwanda-green {
            border-color: var(--rwanda-green);
        }

        .nav-link {
            position: relative;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: var(--rwanda-yellow);
        }

        .ticket-card {
            transition: all 0.3s ease;
        }

        .ticket-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        #globe-container {
            width: 100%;
            height: 200px;
            position: relative;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
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

        .fade-in-delay-4 {
            animation-delay: 0.4s;
        }

        /* Progress Bar Animation */
        @keyframes progress {
            0% {
                width: 0;
            }

            100% {
                width: 100%;
            }
        }

        .progress-animation {
            animation: progress 1.5s ease-out forwards;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--rwanda-blue);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #0088cc;
        }

        /* Wave effect */
        .wave-container {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            height: 100%;
        }

        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 40%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: scale(1.5);
            animation: wave 8s infinite linear;
        }

        .wave:nth-child(2) {
            bottom: -5%;
            animation-delay: 0.5s;
            opacity: 0.6;
        }

        .wave:nth-child(3) {
            bottom: -10%;
            animation-delay: 1s;
            opacity: 0.4;
        }

        @keyframes wave {
            0% {
                transform: scale(1.5) translateX(-10%) rotate(0deg);
            }

            100% {
                transform: scale(1.5) translateX(10%) rotate(360deg);
            }
        }
    </style>
</head>

<div class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-full overflow-y-auto">
        
        <div class="bg-gray-100 px-6 py-4 border-b border-gray-200 rounded-t-lg flex justify-between items-center sticky top-0">
            <h2 class="text-xl font-bold text-gray-800">
                Ticket #<?php echo htmlspecialchars($ticket['ticket_number']); ?>
            </h2>
            <div class="flex items-center space-x-3">
                <span class="<?php echo $statusClass; ?> text-white text-sm px-3 py-1 rounded-full">
                    <?php echo htmlspecialchars(ucfirst($ticket['status'])); ?>
                </span>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeModal()">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        
        <div class="p-6">
            
            <div class="mb-10">
    <div class="flex items-center justify-between">
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">1</div>
            <div class="text-sm mt-2">Assess Info</div>
        </div>
        <div class="flex-1 h-1 bg-gray-300 mx-2"></div>
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold">2</div>
            <div class="text-sm mt-2">Notify Case Received</div>
        </div>
    </div>
</div>


            
            <div class="bg-gray-50 rounded-lg p-6 mb-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Personal Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                    <div>
                        <p class="text-sm text-gray-600">Full Name</p>
                        <p class="font-medium"><?php echo htmlspecialchars($ticket['full_name']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p class="font-medium"><?php echo htmlspecialchars($ticket['phone']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium"><?php echo htmlspecialchars($ticket['email'] ?: 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Preferred Language</p>
                        <p class="font-medium">
                            <?php 
                            $languageMap = [
                                'en' => 'English',
                                'fr' => 'French',
                                'rw' => 'Kinyarwanda'
                            ];
                            echo htmlspecialchars($languageMap[$ticket['language']] ?? $ticket['language']); 
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            
            <div class="bg-gray-50 rounded-lg p-6 mb-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Location</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                    <div>
                        <p class="text-sm text-gray-600">Province</p>
                        <p class="font-medium"><?php echo htmlspecialchars($ticket['province'] ?: 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">District</p>
                        <p class="font-medium"><?php echo htmlspecialchars($ticket['district'] ?: 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Sector</p>
                        <p class="font-medium"><?php echo htmlspecialchars($ticket['sector'] ?: 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Cell</p>
                        <p class="font-medium"><?php echo htmlspecialchars($ticket['cell'] ?: 'N/A'); ?></p>
                    </div>
                    <?php if (!empty($ticket['village'])): ?>
                    <div>
                        <p class="text-sm text-gray-600">Village</p>
                        <p class="font-medium"><?php echo htmlspecialchars($ticket['village']); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="bg-gray-50 rounded-lg p-6 mb-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Service Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                    <div>
                        <p class="text-sm text-gray-600">Institution</p>
                        <p class="font-medium"><?php echo htmlspecialchars($institutionName ?: 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Service</p>
                        <p class="font-medium"><?php echo htmlspecialchars($serviceName ?: 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Assigned Agent</p>
                        <p class="font-medium"><?php echo htmlspecialchars($ticket['agent_on'] ?: 'Not assigned'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Notification Preferences</p>
                        <p class="font-medium"><?php echo htmlspecialchars($notificationString); ?></p>
                    </div>
                </div>
            </div>

            
            <div class="bg-gray-50 rounded-lg p-6 mb-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Problem Details</h3>
                
                <div class="mt-3">
                    <p class="text-sm text-gray-600">Subject</p>
                    <p class="font-medium text-lg"><?php echo htmlspecialchars($ticket['subject']); ?></p>
                </div>
                
                <div class="mt-4">
                    <p class="text-sm text-gray-600">Description</p>
                    <div class="bg-white p-4 rounded border mt-1">
                        <p class="whitespace-pre-wrap"><?php echo nl2br(htmlspecialchars($ticket['description'])); ?></p>
                    </div>
                </div>
            </div>

            
            <div class="bg-gray-50 rounded-lg p-6 mb-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Ticket Timeline</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-green-500 rounded-full w-4 h-4 mt-1"></div>
                        <div class="ml-3">
                            <p class="font-medium">Created</p>
                            <p class="text-sm text-gray-600"><?php echo $createdDate; ?></p>
                        </div>
                    </div>
                    
                    <?php if (!empty($ticket['updated_at']) && $ticket['updated_at'] != $ticket['created_at']): ?>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-blue-500 rounded-full w-4 h-4 mt-1"></div>
                        <div class="ml-3">
                            <p class="font-medium">Last Updated</p>
                            <p class="text-sm text-gray-600"><?php echo $updatedDate; ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($ticket['completed_at'])): ?>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-purple-500 rounded-full w-4 h-4 mt-1"></div>
                        <div class="ml-3">
                            <p class="font-medium">Completed</p>
                            <p class="text-sm text-gray-600"><?php echo $completedDate; ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="flex justify-between mt-8">
                <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-6 rounded-md transition" onclick="closeModal()">
                    Close
                </button>
                <div class="space-x-3">
                    <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-md transition" onclick="openNotificationModal()">
                        Process Ticket
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="notificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 rounded-t-lg">
            <h2 class="text-xl font-bold text-white">
                Notification Preferences
            </h2>
        </div>

        
        <div class="p-6">
            <div class="mb-6">
                <p class="text-gray-700 mb-4">This ticket will be processed with the following notification methods:</p>
                
                <div class="space-y-3">
                    <?php if ($ticket['notify_email'] == 1): ?>
                    <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-envelope text-blue-500"></i>
                        </div>
                        <div>
                            <p class="font-medium">Email Notification</p>
                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($ticket['email'] ?: 'No email provided'); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($ticket['notify_sms'] == 1): ?>
                    <div class="flex items-center p-3 bg-green-50 rounded-lg">
                        <div class="bg-green-100 p-2 rounded-full mr-3">
                            <i class="fas fa-sms text-green-500"></i>
                        </div>
                        <div>
                            <p class="font-medium">SMS Notification</p>
                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($ticket['phone']); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($ticket['notify_sms'] != 1 && $ticket['notify_email'] != 1): ?>
                    <div class="flex items-center p-3 bg-yellow-50 rounded-lg">
                        <div class="bg-yellow-100 p-2 rounded-full mr-3">
                            <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                        </div>
                        <div>
                            <p class="font-medium">No Notification Methods</p>
                            <p class="text-sm text-gray-600">User has not selected any notification methods.</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($ticket['notify_sms'] == 1): ?>
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h3 class="font-semibold text-yellow-800 mb-2">Important!</h3>
                    <p class="text-yellow-700 mb-3">Before sending SMS notifications, please verify that the SMS system is working properly.</p>
                    <a href="check/1.php" target="_blank" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-md transition">
                        <i class="fas fa-check-circle mr-2"></i>Check SMS System
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <div class="flex justify-between pt-4 border-t border-gray-200">
                <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md transition" onclick="closeNotificationModal()">
                    Cancel
                </button>
                
                <button type="button" id="sendNotificationsBtn" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-6 rounded-md transition">
                    <i class="fas fa-paper-plane mr-2"></i>Send Notifications
                </button>
                
                
                <script>
                document.getElementById('sendNotificationsBtn').addEventListener('click', function() {
                    <?php if ($ticket['notify_sms'] == 1): ?>
                    // If SMS notification is enabled, ask for confirmation
                    if (confirm('This will send an SMS notification to <?php echo htmlspecialchars($ticket['phone']); ?>. Do you want to proceed with sending the SMS?')) {
                        processNotifications(true);
                    } else {
                        // User declined SMS, ask if they want to proceed with email only
                        if (<?php echo $ticket['notify_email'] == 1 ? 'true' : 'false' ?> && confirm('Do you want to send email notification only?')) {
                            processNotifications(false);
                        }
                    }
                    <?php else: ?>
                    // No SMS notification, proceed directly
                    processNotifications(false);
                    <?php endif; ?>
                });
                
                function processNotifications(includeSMS) {
                    // Show loading indicator
                    const btn = document.getElementById('sendNotificationsBtn');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
                    btn.disabled = true;
                    
                    // Create form data
                    const formData = new FormData();
                    formData.append('ticket_id', <?php echo $ticketId; ?>);
                    formData.append('confirm_sms', includeSMS ? 1 : 0);
                    
                    // Send AJAX request
                    fetch('process_notification.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            // Reload the page to reflect any status changes
                            window.location.reload();
                        } else {
                            alert('Error: ' + data.message + '\n' + (data.errors ? data.errors.join('\n') : ''));
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while processing the notification.');
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    });
                }
                </script>
            </div>
        </div>
    </div>
</div>

<script>
function closeModal() {
    // You can implement custom close behavior here
    // For example, redirect back to ticket list or use JavaScript to hide modal
    window.location.href = 'index.php'; // Redirect to ticket list
    
    // Or if using JavaScript to show/hide:
    // document.querySelector('.modal').style.display = 'none';
}

function openNotificationModal() {
    document.getElementById('notificationModal').classList.remove('hidden');
}

function closeNotificationModal() {
    document.getElementById('notificationModal').classList.add('hidden');
}
</script>