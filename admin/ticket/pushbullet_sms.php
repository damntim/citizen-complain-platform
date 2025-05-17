<?php
class PushbulletSMS {
    private $apiKey;
    private $deviceId;
    private $apiUrl = 'https://api.pushbullet.com/v2/texts';
    private $devicesUrl = 'https://api.pushbullet.com/v2/devices';

    public function __construct($apiKey, $deviceId) {
        $this->apiKey = $apiKey;
        $this->deviceId = $deviceId;
    }

    /**
     * Check if the device is online and connected to the internet
     * 
     * @return bool Whether the device is online and ready to send messages
     * @throws Exception If the API request fails
     */
    public function isDeviceReady() {
        $ch = curl_init($this->devicesUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Access-Token: ' . $this->apiKey,
                'Content-Type: application/json'
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception('cURL error when checking device status: ' . $error);
        }

        curl_close($ch);

        // Handle API response
        if ($httpCode !== 200) {
            $error = json_decode($response, true);
            throw new Exception('API error when checking device status: ' . ($error['error']['message'] ?? 'Unknown error'));
        }

        $devices = json_decode($response, true);
        
        // Look for our device in the list
        foreach ($devices['devices'] as $device) {
            if ($device['iden'] === $this->deviceId) {
                // Check if device is active and online
                return isset($device['active']) && $device['active'] === true && 
                       isset($device['pushable']) && $device['pushable'] === true;
            }
        }

        return false; // Device not found
    }

    /**
     * Send SMS through Pushbullet
     * 
     * @param string $phoneNumber Recipient phone number
     * @param string $message The message to send
     * @return array The API response
     * @throws Exception On various errors
     */
    public function sendSMS($phoneNumber, $message) {
        // Validate input
        if (empty($phoneNumber) || empty($message)) {
            throw new Exception('Phone number and message are required');
        }

        // First check if device is ready
        if (!$this->isDeviceReady()) {
            throw new Exception('Sending device is offline or not connected to the internet');
        }

        // Clean phone number
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Structure data according to API requirements
        $data = [
            'data' => [
                'target_device_iden' => $this->deviceId,
                'addresses' => [$phoneNumber],
                'message' => $message
            ]
        ];

        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Access-Token: ' . $this->apiKey,
                'Content-Type: application/json'
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception('cURL error: ' . $error);
        }

        curl_close($ch);

        // Handle API response
        if ($httpCode !== 200) {
            $error = json_decode($response, true);
            throw new Exception('API error: ' . ($error['error']['message'] ?? 'Unknown error'));
        }

        return json_decode($response, true);
    }
}