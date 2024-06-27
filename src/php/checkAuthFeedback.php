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
    $conn=$_SESSION['conn'];
    $courseID = $_POST['courseID'];

    echo json_encode(checkFeedbackHelper($courseID));
}