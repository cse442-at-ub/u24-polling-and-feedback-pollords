<?php
header('Content-Type: application/json');

$valid_users = [
    'jake' => 'password',
    'user2' => 'pass2'
];

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['username']) || !isset($data['password'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Username or password not provided.'
    ]);
    exit();
}

if (!array_key_exists($data['username'], $valid_users)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Incorrect username'
    ]);
    exit();
}

if ($valid_users[$data['username']] !== $data['password']) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Incorrect password'
    ]);
    exit();
}

echo json_encode([
    'status' => 'success',
    'message' => 'Login successful'
]);
