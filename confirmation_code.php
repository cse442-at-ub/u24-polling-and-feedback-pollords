<?php
header('Content-Type: application/json');

$valid_codes = [
    'jake' => '1234567',
    'user2' => '7654321'
];

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['username']) || !isset($data['code'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Username or confirmation code not provided.'
    ]);
    exit();
}

if (!array_key_exists($data['username'], $valid_codes)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Incorrect username'
    ]);
    exit();
}

if ($valid_codes[$data['username']] !== $data['code']) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Incorrect confirmation code'
    ]);
    exit();
}

echo json_encode([
    'status' => 'success',
    'message' => 'Confirmation code accepted'
]);

