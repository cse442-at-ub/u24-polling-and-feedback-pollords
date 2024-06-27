<?php
//ob_end_clean();
//ob_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

session_start();
include("connection.php");
include("checkAuthFeedbackHelper.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = $_SESSION['conn'];
    $data = json_decode(file_get_contents('php://input'), true);
    $courseID = $data['courseID'];

    error_log("Received courseID: " . $courseID);

    echo json_encode(checkFeedbackHelper($courseID));
    
    if (isset($conn->server_info)) {
        $conn->close();
    }
}