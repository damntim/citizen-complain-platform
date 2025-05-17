<?php
// enhanced_device_checker.php - Checks device status

// Include configuration file
require_once 'config.php';

// Define the API endpoints
define('PUSHBULLET_API_URL', 'https://api.pushbullet.com/v2');
define('PUSHBULLET_DEVICES_ENDPOINT', PUSHBULLET_API_URL . '/devices');

// Use credentials from config file
$api_key = PUSHBULLET_API_KEY;
$device_id = PUSHBULLET_DEVICE_ID;

// Function to check device status
function checkDeviceStatus($api_key, $device_id) {
    $ch = curl_init(PUSHBULLET_DEVICES_ENDPOINT);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Access-Token: ' . $api_key,
            'Content-Type: application/json'
        ]
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    $result = [
        'timestamp' => date('Y-m-d H:i:s'),
        'status' => 'unknown',
        'message' => 'Status check failed',
        'details' => [],
        'raw_response' => null
    ];

    // Check for cURL errors
    if (curl_errno($ch)) {
        $result['status'] = 'error';
        $result['message'] = 'Connection error: ' . curl_error($ch);
        curl_close($ch);
        return $result;
    }

    curl_close($ch);

    // Process API response
    if ($http_code !== 200) {
        $result['status'] = 'error';
        $result['message'] = 'API error (HTTP ' . $http_code . ')';
        $result['raw_response'] = $response;
        return $result;
    }

    // Parse the response
    $devices_data = json_decode($response, true);
    $result['raw_response'] = $devices_data;
    
    if (!isset($devices_data['devices']) || !is_array($devices_data['devices'])) {
        $result['status'] = 'error';
        $result['message'] = 'Invalid API response format';
        return $result;
    }

    // Look for the specified device
    $device_found = false;
    foreach ($devices_data['devices'] as $device) {
        if ($device['iden'] === $device_id) {
            $device_found = true;
            $result['details'] = [
                'nickname' => $device['nickname'] ?? 'Unknown device',
                'model' => $device['model'] ?? 'Unknown model',
                'manufacturer' => $device['manufacturer'] ?? 'Unknown manufacturer',
                'created' => isset($device['created']) ? date('Y-m-d H:i:s', $device['created']) : 'Unknown',
                'modified' => isset($device['modified']) ? date('Y-m-d H:i:s', $device['modified']) : 'Unknown',
                'active' => isset($device['active']) ? ($device['active'] ? 'Yes' : 'No') : 'Unknown',
                'pushable' => isset($device['pushable']) ? ($device['pushable'] ? 'Yes' : 'No') : 'Unknown',
                'has_sms' => isset($device['has_sms']) ? ($device['has_sms'] ? 'Yes' : 'No') : 'Unknown'
            ];
            
            // Determine device status
            if (isset($device['active']) && $device['active'] && 
                isset($device['pushable']) && $device['pushable']) {
                $result['status'] = 'online';
                $result['message'] = 'Device is online and ready to send messages';
            } else {
                $result['status'] = 'offline';
                $result['message'] = 'Device is offline or not ready to send messages';
                
                // Provide more specific reason
                if (isset($device['active']) && !$device['active']) {
                    $result['message'] .= ' (Device is inactive)';
                }
                if (isset($device['pushable']) && !$device['pushable']) {
                    $result['message'] .= ' (Device is not pushable)';
                }
            }
            break;
        }
    }

    if (!$device_found) {
        $result['status'] = 'not_found';
        $result['message'] = 'Device ID not found in your Pushbullet account';
    }

    return $result;
}

// Helper function to format time difference in a human-readable format
function human_time_diff($timestamp) {
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return $diff . ' seconds';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' minutes';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' hours';
    } else {
        return floor($diff / 86400) . ' days';
    }
}

// Process API request mode
if (isset($_GET['api']) && $_GET['api'] == 1) {
    header('Content-Type: application/json');
    echo json_encode(checkDeviceStatus($api_key, $device_id));
    exit;
}

// Get device status for UI display
$device_status = checkDeviceStatus($api_key, $device_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Test SMS Message</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-4">
    <div class="max-w-3xl mx-auto">
        <header class="bg-white shadow rounded-lg p-4 mb-4">
            <h1 class="text-2xl font-bold text-gray-800">SMS Test Message</h1>
            <p class="text-gray-600">Send a test message to verify SMS functionality</p>
        </header>

        <div class="bg-white shadow rounded-lg p-6 mb-4">
            <div class="flex items-center mb-4">
                <div class="mr-4">
                    <?php if($device_status['status'] === 'online'): ?>
                        <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    <?php else: ?>
                        <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <h2 class="text-xl font-semibold">
                        <?php echo htmlspecialchars($device_status['message']); ?>
                    </h2>
                    <p class="text-gray-600">
                        Last checked: <?php echo htmlspecialchars($device_status['timestamp']); ?>
                    </p>
                </div>
            </div>

            <?php if($device_status['status'] !== 'online'): ?>
            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <h3 class="font-semibold text-yellow-800 mb-2">Device Offline</h3>
                <p class="text-yellow-700">The SMS device appears to be offline. Please call <strong>0785498054</strong> to start the app before sending messages.</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Test message form -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Send a Test Message</h2>
                
                <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Important!</p>
                    <p>Please send a test message to your phone number first. If you receive it, you can proceed to send SMS messages to others. If not, please call <strong>0785498054</strong> for starting app.</p>
                </div>
                
                <form method="post" action="">
                    <div class="mb-4">
                        <label for="phone_number" class="block text-gray-700 text-sm font-bold mb-2">Your Phone Number:</label>
                        <input type="tel" id="phone_number" name="phone_number" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="0780000000"
                            value="<?php echo isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number']) : ''; ?>">
                        <p class="text-gray-500 text-xs mt-1">Enter phone number with country code</p>
                    </div>
                    
                    <div class="mb-6">
                        <label for="message" class="block text-gray-700 text-sm font-bold mb-2">Message:</label>
                        <textarea id="message" name="message" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            rows="4"
                            placeholder="This is a test message from the Admin."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : 'This is a test message from the school SMS system.'; ?></textarea>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Send Test Message
                        </button>
                        <a href="index.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                            Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <?php
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['phone_number']) && isset($_POST['message'])) {
            $phone_number = trim($_POST['phone_number']);
            $message = trim($_POST['message']);
            
            try {
                // Include the PushbulletSMS class
                require_once '../pushbullet_sms.php';
                
                // Initialize PushbulletSMS
                $pushbullet = new PushbulletSMS($api_key, $device_id);
                
                // Check if device is ready
                if (!$pushbullet->isDeviceReady()) {
                    throw new Exception("Device is not online or not connected to the internet. Please call 0785498054 to start the app.");
                }
                
                // Send the test message
                $response = $pushbullet->sendSMS($phone_number, $message);
                
                // Display success message
                echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Success!</p>
                    <p>Test message sent successfully!</p>
                    <p class="text-sm mt-1">Message sent at: ' . date('Y-m-d H:i:s') . '</p>
                    <p class="mt-2">If you received the message, you can now proceed to send SMS messages to others. If not, please call <strong>0785498054</strong> for starting app.</p>
                </div>';
                
            } catch (Exception $e) {
                // Display error message
                echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Error!</p>
                    <p>' . $e->getMessage() . '</p>
                    <p class="text-sm mt-1">Error occurred at: ' . date('Y-m-d H:i:s') . '</p>
                    <p class="mt-2">Please call <strong>0785498054</strong> for starting app.</p>
                </div>';
            }
        }
        ?>
        
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Need Help?</h3>
            <p class="text-gray-600 mb-4">If you're having trouble sending SMS messages or the test message doesn't arrive, please call <strong>0785498054</strong> for immediate starting app.</p>
            
            <div class="flex flex-wrap gap-3">
                <a href="index.php" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                    Back to Dashboard
                </a>
                <button id="refreshBtn" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg">
                    Refresh Status
                </button>
            </div>
        </div>
    </div>
    
    <script>
        document.getElementById('refreshBtn').addEventListener('click', function() {
            location.reload();
        });
    </script>
</body>
</html>