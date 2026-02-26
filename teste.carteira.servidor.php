<?php
// URL where you are sending the request
$url = 'http://10.121.23.194:3375/login';

// Login credentials
$data = array(
    'login' => 'sistemaCarteirinhas',
    'senha' => '016da00846b7ac38'
);

// Initialize cURL
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return response as a string
curl_setopt($ch, CURLOPT_POST, true);            // Send POST request
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json'             // Tell server the content type is JSON
));

// Encode the data to JSON and send it
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Execute the request and get the response
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
    exit;
}

// Close the cURL session
curl_close($ch);

print_r($response);

// Decode the JSON response
$responseData = json_decode($response, true);

print_r($responseData);

// Check if the token is present inside the "cookies" object
if (isset($responseData['cookies']['token'])) {
    // Successfully received the token
    $token = $responseData['cookies']['token'];
    echo "Token received: " . $token;
} else {
    // Handle error if token is not found
    echo "Error: Token not found in the response.";
}

?>