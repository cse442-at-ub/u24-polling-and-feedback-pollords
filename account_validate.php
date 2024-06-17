<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['username']) || !isset($data['code']) || !isset($data['email'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Username, confirmation code, or email not provided.'
    ]);
    exit();
}

if (!preg_match('/^[a-zA-Z0-9._]+$/', $data['username'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid username format.'
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

if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid email format.'
    ]);
    exit();
}

echo json_encode([
    'status' => 'success',
    'message' => 'Account created successfully.'
]);
