<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

session_start();

include_once "connection.php";
include_once "checkAuthFeedbackHelper.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $courseID = $_POST['courseID'];


    //$data = json_decode(file_get_contents('php://input'), true);
    //echo $action;
    $conn=$_SESSION['conn'];

    if (isset($action) && isset($courseID)) {
        //$action = intval($data['action']);
        //$courseID = intval($data['courseID']);
        $check = checkFeedbackHelper($courseID);
        //echo $check['instructor'];
        if ($check['instructor'] == -1) {
            $response = [
                'status' => 0,
                'message' => '' . $check['message']
            ];
        } else {

            $query = $conn->prepare("SELECT `feedbackOpen` FROM `courses` WHERE id = ?");
            $query->bind_param("s", $courseID);
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

                if ($action == 1 && $currentState != 1) {

                    $updateQuery = $conn->prepare("UPDATE `courses` SET `feedbackOpen` = 1 WHERE id = ?");
                    $updateQuery->bind_param("s", $courseID);
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
                } elseif ($action == 0 && $currentState != 0) {
                    $updateQuery = $conn->prepare("UPDATE `courses` SET `feedbackOpen` = 0 WHERE id = ?");
                    $updateQuery->bind_param("s", $courseID);
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
        }

    } else {
        $response = [
            'status' => 0,
            'message' => 'No action specified.'
        ];
    }

    if(isset($conn->server_info)){
        $conn->close();
    }

    echo json_encode($response);
}

