<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$code = $data['code'];

$response = [];

if (empty($email) || empty($code)) {
    http_response_code(400);
    $response['message'] = 'Missing code information';
} else if ($code === 'ABC123') { // This should be replaced with actual code validation logic
    http_response_code(200);
    $response['message'] = 'Code confirmed successfully';
} else {
    http_response_code(400);
    $response['message'] = 'Invalid code';
}

echo json_encode($response);
?>