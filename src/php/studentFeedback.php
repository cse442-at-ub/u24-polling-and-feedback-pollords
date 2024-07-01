<?php
//ob_end_clean();
//ob_start();


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn=$_SESSION['conn'];

    if (isset($_SESSION['token']) && isset($_SESSION['instructor']) && isset($_SESSION['userID'])) {
        $instr = $_SESSION['instructor']; // 1 === ERROR
        $token = $_SESSION['token'];
        $userID = $_SESSION['userID'];
        $courseID = $_POST['courseID'];
        $response = $_POST['response']; // forgot to keep this before
        $query = $conn->prepare("select * from tokens where token = ? limit 1");
        $query->bind_param("s", $token);
        $query->execute();
        $result = $query->get_result();
        if($result) {
            if ($result && mysqli_num_rows($result) == 0) {
                $conn->close();
                //header("Location: ../index.html");
                //exit;
                echo json_encode(array("success" => false,"message"=>"Error: You are not authorized, try logging in again","feedbackOpen"=>0));
                return;
            } else {
                $query = $conn->prepare("select * from courses where id = ? limit 1");
                $query->bind_param("i", $courseID);
                $query->execute();
                $result = $query->get_result();
                if($result) {
                    if ($result && mysqli_num_rows($result) == 0) {
                        $conn->close();
                        if($instr==0){
                            echo json_encode(array("success" => false, "message" => "Error: Course does not exist", "feedbackOpen" => 0));
                            return;
                            //header("Location: ../mainStud.html", true,  301);
                            //exit;
                        } else {
                            echo json_encode(array("success" => false, "message" => "Error: Course does not exist", "feedbackOpen" => 0));
                            return;
                            //header("Location: ../main.html", true,  301);
                            //exit;

                        }

                    } else {
                        $temp = "";
                        if ($instr == 1) { // NEW - check for if user is instructor
                            echo json_encode(array("success" => false, "message" => "Error: You are not a student", "feedbackOpen" => 0));
                            return;
                        }
                        //if()
                        if ($instr == 0) {
                            $temp = mysqli_fetch_assoc($result)['students'];
                        } else {
                            $temp = mysqli_fetch_assoc($result)['instructors'];
                        }
                        if ($temp == "") {
                            $conn->close();
                            if ($instr == 0) {
                                echo json_encode(array("success" => false, "message" => "Error: You are not a member of this course", "feedbackOpen" => 0));
                                return;
                                //header("Location: ../mainStud.html", true,  301);
                                //exit;
                            } else {
                                echo json_encode(array("success" => false, "message" => "Error: You are not a member of this course", "feedbackOpen" => 0));
                                return;
                                //header("Location: ../main.html", true,  301);
                                //exit;
                            }
                            //echo json_encode(array( -1, "message" => "Error: You are not a member of this course", "feedbackOpen" => 0));
                            //return;
                        }
                        $members = explode(',', $temp);
                        $isMember = false;
                        foreach ($members as $curr) {
                            if ($curr == $userID) {
                                $isMember = true;
                            }
                        }
                        if (!$isMember) {
                            $conn->close();
                            if ($instr == 0) {
                                echo json_encode(array("success" => false, "message" => "Error: You are not a member of this course", "feedbackOpen" => 0));
                                return;
                                //header("Location: ../mainStud.html", true,  301);
                                //exit;
                            } else {
                                echo json_encode(array("success" => false, "message" => "Error: You are not a member of this course", "feedbackOpen" => 0));
                                return;
                                //header("Location: ../main.html", true,  301);
                                //exit;
                            }
                            //echo json_encode(array( -1, "message" => "Error: You are not a member of this course", "feedbackOpen" => 0));
                            //return;
                        }
                        $query = $conn->prepare("select * from courses where id = ? limit 1");
                        $query->bind_param("i", $courseID);
                        $query->execute();
                        $result = $query->get_result();
                        $feedbackOpen = mysqli_fetch_assoc($result)['feedbackOpen'];
                        if ($feedbackOpen == 0) { // NEW - checking if feedback is in fact open
                            $conn->close();
                            echo json_encode(array("success" => false, "message" => "Error: Feedback is closed", "id" => -1));
                            exit();
                        }
                        //echo $courseID. "   ". $userID."      ".$response;
                        if (empty($response)) {
                            $conn->close();
                            echo json_encode(array("success" => false, "message" => "Error: Unable to collect required data", "id" => -1));
                            exit();
                        }
                        //if ()
                        if ($response > 5 || $response < 1) {
                            $conn->close();
                            echo json_encode(array("success" => false, "message" => "Error: Invalid response value", "id" => -1));
                            exit();
                        }

                        //$preppedQuery = $conn->prepare("INSERT INTO feedbackAnswers (courseID, studentID, response) value (?, ?, ?)");
                        //$preppedQuery = $conn->prepare("UPDATE feedbackAnswers SET response = ? WHERE courseID = ? and studentID = ?"); // precludes students from submitting multiple responses
                        $preppedQuery = $conn->prepare("select * from feedbackAnswers where courseID = ? and studentID = ? limit 1");
                        $preppedQuery->bind_param("ss", $courseID, $userID);//, $response);
                        $preppedQuery->execute();
                        $pqResult = $preppedQuery->get_result();
                        //mysqli_query($conn, $preppedQuery);

                        if ($pqResult) {
                            if ($pqResult && mysqli_num_rows($pqResult) > 0) {
                                $preppedUpdate = $conn->prepare("UPDATE feedbackAnswers SET response = ?, timeUpdated = CURRENT_TIMESTAMP WHERE courseID = ? and studentID = ?");
                                $preppedUpdate->bind_param("sss", $response, $courseID, $userID);
                                $preppedUpdate->execute();
                            } else {
                                $preppedInsert = $conn->prepare("INSERT INTO feedbackAnswers (courseID, studentID, response) value (?, ?, ?)");
                                $preppedInsert->bind_param("sss", $courseID, $userID, $response);
                                $preppedInsert->execute();
                            }
                            echo json_encode(array("success" => true, "message" => "Success: Feedback sent correctly", "id" => $userID)); // I am a titan of PHP
                            $conn->close();

                        }
                    }
                } else {
                    $conn->close();
                    if($instr==0){
                        echo json_encode(array("success" => false, "message" => "Error: Course does not exist", "feedbackOpen" => 0));
                        return;
                        //header("Location: ../mainStud.html", true,  301);
                        //exit;
                    } else {
                        echo json_encode(array("success" => false, "message" => "Error: Course does not exist", "feedbackOpen" => 0));
                        return;
                        //header("Location: ../main.html", true,  301);
                        //exit;
                    }
                    //echo json_encode(array( -1, "message" => "Error: Course does not exist", "feedbackOpen" => 0));
                    //return;
                }
            }
        } else {
            $conn->close();
            //header("Location: ../index.html", true,  301);
            //exit;
            echo json_encode(array("success" => false,"message"=>"Error: You are not authorized, try logging in again","feedbackOpen"=>0));
            return;

        }
    } else {
        $conn->close();
        //header("Location: ../index.html", true,  301);
        //exit;
        echo json_encode(array("success" => false,"message"=>"Error: You are not authorized, try logging in again","feedbackOpen"=>0));
        return;
    }
}