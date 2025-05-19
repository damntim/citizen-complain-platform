<?php
session_start();
require_once "../../db_setup.php";


$ticket_id = isset($_GET['ticket_id']) ? intval($_GET['ticket_id']) : 0;
$response_message = '';
$success_message = '';
$error_message = '';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit;
}


$agent_id = $_SESSION['user_id'];


if ($ticket_id <= 0) {
    header("Location: tickets.php");
    exit;
}


$ticket_query = "SELECT 
    t.id, 
    t.ticket_number, 
    t.full_name, 
    t.phone, 
    t.email, 
    t.province, 
    t.district, 
    t.sector, 
    t.cell, 
    t.village, 
    t.institution, 
    t.service, 
    t.subject, 
    t.description, 
    t.language, 
    t.status,
    t.notify_sms,
    t.notify_email,
    i.name AS institution_name,
    CASE 
        WHEN t.language = 'kinyarwanda' THEN s.name_rw 
        ELSE s.name_en 
    END AS service_name
FROM 
    tickets t
LEFT JOIN 
    institutions i ON t.institution = i.id
LEFT JOIN 
    services s ON t.service = s.id
WHERE 
    t.id = ?";

$stmt = $conn->prepare($ticket_query);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$ticket_result = $stmt->get_result();

if ($ticket_result->num_rows === 0) {
    header("Location: tickets.php");
    exit;
}

$ticket = $ticket_result->fetch_assoc();


$responses_query = "SELECT 
    r.id, 
    r.ticket_id, 
    r.sender, 
    r.message, 
    r.created_at,
    r.agent_id,
    IFNULL(a.fullname, 'Agent') AS agent_name
FROM 
    response_ticket r
LEFT JOIN
    admins a ON r.agent_id = a.id
WHERE 
    r.ticket_id = ?
ORDER BY 
    r.created_at ASC";

$stmt = $conn->prepare($responses_query);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$responses_result = $stmt->get_result();
$responses = [];

while ($row = $responses_result->fetch_assoc()) {
    $responses[] = $row;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['response'])) {
    $response_message = trim($_POST['response']);
    $mark_completed = isset($_POST['mark_completed']) ? 1 : 0;
    $decision = isset($_POST['decision']) ? trim($_POST['decision']) : '';
    
    $agent_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;

    if (!empty($response_message)) {
        
        $conn->begin_transaction();
        
        try {
            
            $insert_query = "INSERT INTO response_ticket (ticket_id, sender, message, agent_id, created_at) VALUES (?, 'agent', ?, ?, NOW())";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("isi", $ticket_id, $response_message, $agent_id);
            $stmt->execute();
            
            
            if ($mark_completed) {
                $status = 'completed';
                $update_query = "UPDATE tickets SET status = ?, decision = ?, completed_at = NOW(), updated_at = NOW() WHERE id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("ssi", $status, $decision, $ticket_id);
                $stmt->execute();
            } else {
                $status = 'in_progress';
                $update_query = "UPDATE tickets SET status = ?, updated_at = NOW() WHERE id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("si", $status, $ticket_id);
                $stmt->execute();
            }
            
            
            if (($ticket['notify_sms'] == 1 || $ticket['notify_email'] == 1) && $mark_completed) {
                $notified_query = "UPDATE tickets SET notified = 1 WHERE id = ?";
                $stmt = $conn->prepare($notified_query);
                $stmt->bind_param("i", $ticket_id);
                $stmt->execute();
                
                
                
            }
            
            
            $conn->commit();
            
            $success_message = "Response added successfully!";
            
            
            header("Location: add_response.php?ticket_id=" . $ticket_id . "&success=1");
            exit;
            
        } catch (Exception $e) {
            
            $conn->rollback();
            $error_message = "Error: " . $e->getMessage();
        }
    } else {
        $error_message = "Response message cannot be empty!";
    }
}


if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success_message = "Response added successfully!";
}


$location = implode(', ', array_filter([$ticket['district'], $ticket['sector'], $ticket['cell'], $ticket['village']]));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Response - Ticket #<?php echo htmlspecialchars($ticket['ticket_number']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    
    
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Ticket #<?php echo htmlspecialchars($ticket['ticket_number']); ?>
            </h1>
            <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Back to Tickets
            </a>
        </div>
        
        <?php if (!empty($error_message)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo htmlspecialchars($success_message); ?></span>
        </div>
        <?php endif; ?>
        
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Ticket Information</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-700"><span class="font-semibold">Name:</span> <?php echo htmlspecialchars($ticket['full_name']); ?></p>
                    <p class="text-gray-700"><span class="font-semibold">Phone:</span> <?php echo htmlspecialchars($ticket['phone']); ?></p>
                    <p class="text-gray-700"><span class="font-semibold">Email:</span> <?php echo htmlspecialchars($ticket['email']); ?></p>
                    <p class="text-gray-700"><span class="font-semibold">Location:</span> <?php echo htmlspecialchars($location); ?></p>
                </div>
                <div>
                    <p class="text-gray-700"><span class="font-semibold">Institution:</span> <?php echo htmlspecialchars($ticket['institution_name']); ?></p>
                    <p class="text-gray-700"><span class="font-semibold">Service:</span> <?php echo htmlspecialchars($ticket['service_name']); ?></p>
                    <p class="text-gray-700"><span class="font-semibold">Subject:</span> <?php echo htmlspecialchars($ticket['subject']); ?></p>
                    <p class="text-gray-700"><span class="font-semibold">Status:</span> 
                        <span class="<?php echo $ticket['status'] === 'ongoing' ? 'text-yellow-600' : ($ticket['status'] === 'completed' ? 'text-green-600' : 'text-blue-600'); ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $ticket['status'])); ?>
                        </span>
                    </p>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <p class="text-gray-700"><span class="font-semibold">Description:</span></p>
                    <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                        <?php echo nl2br(htmlspecialchars($ticket['description'])); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Conversation History</h2>
            </div>
            <div class="p-6">
                <?php if (empty($responses)): ?>
                <p class="text-gray-600 italic">No responses yet.</p>
                <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($responses as $response): ?>
                    <div class="flex">
                        <div class="<?php echo $response['sender'] === 'client' ? 'ml-auto bg-blue-100' : 'mr-auto bg-gray-100'; ?> rounded-lg p-4 max-w-lg">
                            <p class="font-semibold text-sm text-gray-600">
                                <?php echo $response['sender'] === 'citizen' ? htmlspecialchars($ticket['full_name']) : htmlspecialchars($response['agent_name']); ?>
                                <span class="font-normal ml-2 text-xs text-gray-500">
                                    <?php echo date('M j, Y g:i A', strtotime($response['created_at'])); ?>
                                </span>
                            </p>
                            <div class="mt-1 text-gray-800">
                                <?php echo nl2br(htmlspecialchars($response['message'])); ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($ticket['status'] !== 'completed'): ?>
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Add Response</h2>
            </div>
            <div class="p-6">
                <form action="" method="post">
                    <div class="mb-4">
                        <label for="response" class="block text-gray-700 font-bold mb-2">Your Response</label>
                        <textarea id="response" name="response" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required><?php echo htmlspecialchars($response_message); ?></textarea>
                    </div>
                    
                    <div class="mb-4 flex items-center">
                        <input type="checkbox" id="mark_completed" name="mark_completed" class="mr-2">
                        <label for="mark_completed" class="text-gray-700">Mark ticket as completed</label>
                    </div>
                    
                    <div id="decision_container" class="mb-4 hidden">
                        <label for="decision" class="block text-gray-700 font-bold mb-2">Decision</label>
                        <select id="decision" name="decision" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select a decision</option>
                           
                            <option value="rejected">Rejected</option>
                            <option value="information_provided">Information Provided</option>
                            <option value="referred">Referred to Another Department</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Submit Response
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-8 max-w-md mx-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Response Added Successfully!</h3>
                <p class="text-gray-600 mb-4">
                    Your response has been added to ticket #<?php echo htmlspecialchars($ticket['ticket_number']); ?>.
                </p>
                
                <?php if ($ticket['notify_email'] || $ticket['notify_sms']): ?>
                <div class="mt-4 mb-4 p-3 bg-blue-50 rounded-lg text-left">
                    <p class="font-semibold mb-2">Notification Options:</p>
                    
                    <?php if ($ticket['notify_email']): ?>
                    <div class="flex items-center mb-2">
                        <input type="checkbox" id="notify_email" class="mr-2" checked>
                        <label for="notify_email">Send email notification to <?php echo htmlspecialchars($ticket['email']); ?></label>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($ticket['notify_sms']): ?>
                    <div class="flex items-center mb-2">
                        <input type="checkbox" id="notify_sms" class="mr-2" checked>
                        <label for="notify_sms">Send SMS notification to <?php echo htmlspecialchars($ticket['phone']); ?></label>
                    </div>
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 p-2 mt-1 text-sm">
                        <p class="font-semibold">Warning:</p>
                        <p>Please ensure the SMS system is properly configured. <a href="check/1.php" class="text-blue-600 underline" target="_blank">Check SMS system status</a></p>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div id="notification_status" class="hidden mt-3 p-3 rounded-lg"></div>
                
                <div class="mt-4 flex justify-center space-x-3">
                    <button id="sendNotifications" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Send Notifications
                    </button>
                    <button id="closeModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Close
                    </button>
                </div>
                <?php else: ?>
                <div class="mt-4">
                    <button id="closeModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Close
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show/hide decision dropdown when marking as completed
            const markCompletedCheckbox = document.getElementById('mark_completed');
            const decisionContainer = document.getElementById('decision_container');
            
            if (markCompletedCheckbox && decisionContainer) {
                markCompletedCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        decisionContainer.classList.remove('hidden');
                    } else {
                        decisionContainer.classList.add('hidden');
                    }
                });
            }
            
            // Show success modal if success parameter is present
            const urlParams = new URLSearchParams(window.location.search);
            const successParam = urlParams.get('success');
            const successModal = document.getElementById('successModal');
            const closeModal = document.getElementById('closeModal');
            
            if (successParam === '1' && successModal) {
                successModal.classList.remove('hidden');
            }
            
            if (closeModal) {
                closeModal.addEventListener('click', function() {
                    successModal.classList.add('hidden');
                });
            }
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === successModal) {
                    successModal.classList.add('hidden');
                }
            });
            
            // Handle notification sending
            const sendNotificationsBtn = document.getElementById('sendNotifications');
            if (sendNotificationsBtn) {
                sendNotificationsBtn.addEventListener('click', function() {
                    const notifyEmail = document.getElementById('notify_email')?.checked || false;
                    const notifySms = document.getElementById('notify_sms')?.checked || false;
                    const notificationStatus = document.getElementById('notification_status');
                    
                    if (!notifyEmail && !notifySms) {
                        notificationStatus.innerHTML = '<div class="bg-yellow-100 text-yellow-800 p-2">No notification method selected.</div>';
                        notificationStatus.classList.remove('hidden');
                        return;
                    }
                    
                    // Show loading state
                    sendNotificationsBtn.disabled = true;
                    sendNotificationsBtn.innerHTML = 'Sending...';
                    notificationStatus.innerHTML = '<div class="bg-blue-100 text-blue-800 p-2">Sending notifications...</div>';
                    notificationStatus.classList.remove('hidden');
                    
                    // Send AJAX request to process_notification.php
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'push_response.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        sendNotificationsBtn.disabled = false;
                        sendNotificationsBtn.innerHTML = 'Send Notifications';
                        
                        if (xhr.status === 200) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    notificationStatus.innerHTML = '<div class="bg-green-100 text-green-800 p-2">' + response.message + '</div>';
                                    // Disable the send button after successful sending
                                    sendNotificationsBtn.disabled = true;
                                    sendNotificationsBtn.innerHTML = 'Notifications Sent';
                                } else {
                                    notificationStatus.innerHTML = '<div class="bg-red-100 text-red-800 p-2">Error: ' + response.message + '</div>';
                                    if (response.errors && response.errors.length > 0) {
                                        notificationStatus.innerHTML += '<div class="mt-2 text-sm">' + response.errors.join('<br>') + '</div>';
                                    }
                                }
                            } catch (e) {
                                notificationStatus.innerHTML = '<div class="bg-red-100 text-red-800 p-2">Error processing response</div>';
                            }
                        } else {
                            notificationStatus.innerHTML = '<div class="bg-red-100 text-red-800 p-2">Error: Server returned status ' + xhr.status + '</div>';
                        }
                    };
                    xhr.onerror = function() {
                        sendNotificationsBtn.disabled = false;
                        sendNotificationsBtn.innerHTML = 'Send Notifications';
                        notificationStatus.innerHTML = '<div class="bg-red-100 text-red-800 p-2">Network error occurred</div>';
                    };
                    
                    // Prepare data
                    const data = 'ticket_id=<?php echo $ticket_id; ?>' + 
                                '&confirm_email=' + (notifyEmail ? '1' : '0') + 
                                '&confirm_sms=' + (notifySms ? '1' : '0');
                    
                    xhr.send(data);
                });
            }
        });
    </script>
</body>
</html>