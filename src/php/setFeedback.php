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
        'status' => 0,
        'message' => 'Connection failed: ' . $conn->connect_error
    ]));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode([
        'status' => 0,
        'message' => 'Invalid request method.'
    ]));
}

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['action'])) {
    $action = intval($data['action']);

    $query = $conn->prepare("SELECT `feedbackOpen` FROM `courses` WHERE 1");
    $query->execute();
    $result = $query->get_result();

    if ($result === FALSE) {
        $response = [
            'status' => 0,
            'message' => 'Error executing query: ' . $conn->error
        ];
    } elseif ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentState = $row['feedbackOpen'];

        if ($action === 1 && $currentState != 1) {

            $updateQuery = $conn->prepare("UPDATE `courses` SET `feedbackOpen` = 1 WHERE 1");
            if ($updateQuery->execute()) {
                $response = [
                    'status' => 1,
                    'message' => 'Feedback has been opened.'
                ];
            } else {
                $response = [
                    'status' => 0,
                    'message' => 'Failed to open feedback.'
                ];
            }
        } elseif ($action === 0 && $currentState != 0) {
            $updateQuery = $conn->prepare("UPDATE `courses` SET `feedbackOpen` = 0 WHERE 1");
            if ($updateQuery->execute()) {
                $response = [
                    'status' => 1,
                    'message' => 'Feedback has been closed.'
                ];
            } else {
                $response = [
                    'status' => 0,
                    'message' => 'Failed to close feedback.'
                ];
            }
        } else {
            $response = [
                'status' => 0,
                'message' => 'Invalid Request, must be 1 (open) or 0 (close)'
            ];
        }
    } else {
        $response = [
            'status' => 0,
            'message' => 'No rows found in courses table.'
        ];
    }

    $query->close();
} else {
    $response = [
        'status' => 0,
        'message' => 'No action specified.'
    ];
}

$conn->close();

echo json_encode($response);