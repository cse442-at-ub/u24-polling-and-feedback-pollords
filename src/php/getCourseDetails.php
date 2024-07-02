<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

session_start();

include_once "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $conn=$_SESSION['conn'];
    $courseId = mysqli_real_escape_string($conn, $_GET['courseId']);

    $query = $conn->prepare("SELECT * FROM courses WHERE id = ? limit 1");
    $query->bind_param("s", $courseId);
    $query->execute();
    $result = $query->get_result();

    //$query = "SELECT * FROM courses WHERE id = '$courseId' limit 1";
    //$result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $course = mysqli_fetch_assoc($result);
        echo json_encode($course);
    } else {
        echo json_encode(array("error" => "Course not found"));
    }

    if(isset($conn->server_info)){
        $conn->close();
    }
}