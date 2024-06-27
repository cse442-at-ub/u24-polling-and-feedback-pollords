<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

session_start();

include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = $_SESSION['conn'];
    $courseId = mysqli_real_escape_string($conn, $_POST['courseId']);
    $instructorEmail = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Get the instructor ID based on the email
    $instructorQuery = "SELECT id FROM userAccs WHERE email = '$instructorEmail'";
    $instructorResult = mysqli_query($conn, $instructorQuery);

    if (mysqli_num_rows($instructorResult) > 0) {
        $instructor = mysqli_fetch_assoc($instructorResult);
        $instructorId = $instructor['id'];
        
        // Check if course exists
        $courseQuery = "SELECT * FROM courses WHERE id = '$courseId'";
        $courseResult = mysqli_query($conn, $courseQuery);

        if (mysqli_num_rows($courseResult) > 0) {
            // Debug: Log course found
            error_log("Course found: $courseId");

            // Check if the instructor is authorized for this course
            $course = mysqli_fetch_assoc($courseResult);
            $instructors = explode(',', $course['instructors']);

            // Debug: Log instructor check
            error_log("Instructors for the course: " . implode(',', $instructors));

            if (in_array($instructorId, $instructors)) {
                // Debug: Log instructor authorized
                error_log("Instructor authorized for the course: $instructorId");

                // Fetch feedback results
                $feedbackQuery = "SELECT AVG(response) as averageResponse FROM feedbackAnswers WHERE courseId = '$courseId'";
                $feedbackResult = mysqli_query($conn, $feedbackQuery);
                $feedbackData = mysqli_fetch_assoc($feedbackResult);

                if ($feedbackData) {
                    $averageResponse = $feedbackData['averageResponse'];
                    echo json_encode(array("success" => "Success: Average response", "averageResponse" => $averageResponse));
                } else {
                    echo json_encode(array("success" => "Error: No feedback data found", "averageResponse" => -1));
                }
            } else {
                echo json_encode(array("success" => "Error: You are not authorized for this class", "averageResponse" => -1));
            }
        } else {
            echo json_encode(array("success" => "Error: Requested class does not exist", "averageResponse" => -1));
        }
    } else {
        echo json_encode(array("success" => "Error: Instructor not found", "averageResponse" => -1));
    }

    // Close the database connection
    if (isset($conn->server_info)) {
        $conn->close();
    }
}
?>