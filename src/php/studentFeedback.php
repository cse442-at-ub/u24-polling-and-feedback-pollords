<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

session_start();
include 'connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn=$_SESSION['conn'];

    $courseID = mysqli_real_escape_string($conn,$_POST['courseID']);
    $studentID = mysqli_real_escape_string($conn,$_POST['studentID']);
    $response = mysqli_real_escape_string($conn,$_POST['response']);
    //$timeUpdated = mysqli_real_escape_string($conn,$_POST['timeUpdated']);

    if(isset($conn->server_info)) {
        if (empty($courseId) || empty($studentId) || empty($response)) {
            $conn->close();
            echo json_encode(array("success" => false, "message" => "Error: Unable to collect required data","id"=>-1));
            exit();
        }

        // replace below with prepared statement
        $query = "insert into feedbackAnswers (courseID, studentID, response) values ('$courseID', '$studentID', '$response')";
        mysqli_query($conn, $query);
        echo json_encode(array("success" => true, "message" => "Success: Feedback sent correctly","id"=>$studentID)); // I am a titan of PHP
        $conn->close();
    }
}