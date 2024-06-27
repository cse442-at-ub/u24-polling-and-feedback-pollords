<?php
header('Content-Type: application/json');
session_start();
$servername = "oceanus.cse.buffalo.edu:3306";
$username = "jacobzal";
$password = "50346440";
$dbname = "cse442_2024_summer_team_c_db";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Connection failed: ' . $conn->connect_error
    ]));
}

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['action'])) {
    $action = $data['action'];

    $query = $conn->prepare("SELECT `state` FROM `feedbackState` WHERE 1");
    $query->execute();
    $result = $query->get_result();

    if ($result === FALSE) {
        $response = [
            'status' => 'error',
            'message' => 'Error executing query: ' . $conn->error
        ];
    } elseif ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentState = $row['state'];

        if ($action === 'open' && $currentState !== 'open') {

            $updateQuery = $conn->prepare("UPDATE `feedbackState` SET `state` = 'open' WHERE 1");
            if ($updateQuery->execute()) {
                $response = [
                    'status' => 'success',
                    'message' => 'Feedback has been opened.'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to open feedback.'
                ];
            }
        } elseif ($action === 'close' && $currentState !== 'close') {

            $updateQuery = $conn->prepare("UPDATE `feedbackState` SET `state` = 'close' WHERE 1");
            if ($updateQuery->execute()) {
                $response = [
                    'status' => 'success',
                    'message' => 'Feedback has been closed.'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to close feedback.'
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Invalid Request, must be open or close'
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'No rows found in feedbackState table.'
        ];
    }

    $query->close();
} else {
    $response = [
        'status' => 'error',
        'message' => 'No action specified.'
    ];
}

$conn->close();

echo json_encode($response);
