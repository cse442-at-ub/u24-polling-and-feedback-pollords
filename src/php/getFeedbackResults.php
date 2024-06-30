<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

session_start();

include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = $_SESSION['conn'];
    $courseId = mysqli_real_escape_string($conn, $_POST['courseId']);
    //$instructorEmail = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Get the instructor ID based on the email
    //$instructorQuery = "SELECT id FROM userAccs WHERE email = '$instructorEmail'";
    //$instructorResult = mysqli_query($conn, $instructorQuery);

//    $instructorQuery = $conn->prepare("SELECT id FROM userAccs WHERE email = ?");
//    $instructorQuery->bind_param("s", $instructorEmail);
//    $instructorQuery->execute();
//    $instructorResult = $instructorQuery->get_result();

    //if (mysqli_num_rows($instructorResult) > 0) {
    //$instructor = mysqli_fetch_assoc($instructorResult);
    //$instructorId = $instructor['id'];
    $instructorId = $_SESSION['userID'];

    // Check if course exists
    //$courseQuery = "SELECT * FROM courses WHERE id = '$courseId'";
    //$courseResult = mysqli_query($conn, $courseQuery);

    $courseQuery = $conn->prepare("SELECT * FROM courses WHERE id = ?");
    $courseQuery->bind_param("s", $courseId);
    $courseQuery->execute();
    $courseResult = $courseQuery->get_result();

    $one = 0;
    $two = 0;
    $three = 0;
    $four = 0;
    $five = 0;

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
            //$feedbackQuery = "SELECT AVG(response) as averageResponse FROM feedbackAnswers WHERE courseId = '$courseId'";
            //$feedbackResult = mysqli_query($conn, $feedbackQuery);
            //$feedbackData = mysqli_fetch_assoc($feedbackResult);

            $feedbackQuery = $conn->prepare("SELECT response FROM feedbackAnswers WHERE courseID = ? AND timeUpdated >= now() - interval 2 minute");
            $feedbackQuery->bind_param("s", $courseId);
            $feedbackQuery->execute();
            $feedbackResult = $feedbackQuery->get_result();


                if ($feedbackResult) {
                    //$averageResponse = $feedbackData['averageResponse'];
                    foreach ($feedbackResult as $row){
                        $temp = $row['response'];
                        //echo $row['timeUpdated'];
                        if($temp == 1){
                            $one = $one+1;
                        } else if($temp == 2){
                            $two = $two+1;
                        }else if($temp == 3){
                            $three = $three+1;
                        }else if($temp == 4){
                            $four = $four+1;
                        }else if($temp == 5){
                            $five = $five+1;
                        }
                    }
                    echo json_encode(array("message" => "Success: Average response", "one"=>$one,"two"=>$two,"three"=>$three,"four"=>$four,"five"=>$five));
                } else {
                    echo json_encode(array("message" => "Error: No feedback data found", "one"=>$one,"two"=>$two,"three"=>$three,"four"=>$four,"five"=>$five));
                }
            } else {
                echo json_encode(array("message" => "Error: You are not authorized for this class", "one"=>$one,"two"=>$two,"three"=>$three,"four"=>$four,"five"=>$five));
            }
        } else {
            echo json_encode(array("message" => "Error: Requested class does not exist", "one"=>$one,"two"=>$two,"three"=>$three,"four"=>$four,"five"=>$five));
        }
//    } else {
//        echo json_encode(array("success" => "Error: Instructor not found", "averageResponse" => -1));
//    }

    // Close the database connection
    if (isset($conn->server_info)) {
        $conn->close();
    }
}
?>