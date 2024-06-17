<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['username']) || !isset($data['code'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Username or confirmation code not provided.'
    ]);
    exit();
}

if (!preg_match('/^\d{7}$/', $data['code'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid confirmation code format.'
    ]);
    exit();
}

echo json_encode([
    'status' => 'success',
    'message' => 'Confirmation code accepted.'
]);
