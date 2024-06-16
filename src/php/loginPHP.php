<?php
include 'functions.php';
function loginPost($username, $password) {
    if (empty($username) || empty($password)) {
        return false;
    }
    if (!preg_match('/^(?=.*[!@#$%^&*])/', $password)) {
        return false;
    }
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $result = loginPost($username, $password);
    if ($result) {
        #echo checkUser($username, $password);
        echo json_encode([
            'Method' => 'POST',
            'Path' => 'login',
            'Body' => 'username=' . $username . ', password=' . $password
        ]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>