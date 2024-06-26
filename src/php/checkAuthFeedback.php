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
        $instr = $_SESSION['instructor'];
        $token = $_SESSION['token'];
        $userID = $_SESSION['userID'];
        $courseID = $_POST['courseID'];
        $query = $conn->prepare("select * from tokens where token = ? limit 1");
        $query->bind_param("s", $token);
        $query->execute();
        $result = $query->get_result();
        if($result) {
            if ($result && mysqli_num_rows($result) == 0) {
                $conn->close();
                //header("Location: ../index.html");
                //exit;
                echo json_encode(array("instructor" => -1,"message"=>"Error: You are not authorized, try logging in again","feedbackOpen"=>0));
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
                            echo json_encode(array("instructor" => -1, "message" => "Error: Course does not exist", "feedbackOpen" => 0));
                            return;
                            //header("Location: ../mainStud.html", true,  301);
                            //exit;
                        } else {
                            echo json_encode(array("instructor" => -1, "message" => "Error: Course does not exist", "feedbackOpen" => 0));
                            return;
                            //header("Location: ../main.html", true,  301);
                            //exit;

                        }

                    } else {
                        $temp = "";
                        if($instr==0){
                            $temp = mysqli_fetch_assoc($result)['students'];
                        } else {
                            $temp = mysqli_fetch_assoc($result)['instructors'];
                        }
                        if($temp==""){
                            $conn->close();
                            if($instr==0){
                                echo json_encode(array("instructor" => -1, "message" => "Error: You are not a member of this course", "feedbackOpen" => 0));
                                return;
                                //header("Location: ../mainStud.html", true,  301);
                                //exit;
                            } else {
                                echo json_encode(array("instructor" => -1, "message" => "Error: You are not a member of this course", "feedbackOpen" => 0));
                                return;
                                //header("Location: ../main.html", true,  301);
                                //exit;
                            }
                            //echo json_encode(array("instructor" => -1, "message" => "Error: You are not a member of this course", "feedbackOpen" => 0));
                            //return;
                        }
                        $members = explode(',', $temp);
                        $isMember = false;
                        foreach ($members as $curr) {
                            if($curr==$userID){
                                $isMember = true;
                            }
                        }
                        if(!$isMember){
                            $conn->close();
                            if($instr==0){
                                echo json_encode(array("instructor" => -1, "message" => "Error: You are not a member of this course", "feedbackOpen" => 0));
                                return;
                                //header("Location: ../mainStud.html", true,  301);
                                //exit;
                            } else {
                                echo json_encode(array("instructor" => -1, "message" => "Error: You are not a member of this course", "feedbackOpen" => 0));
                                return;
                                //header("Location: ../main.html", true,  301);
                                //exit;
                            }
                            //echo json_encode(array("instructor" => -1, "message" => "Error: You are not a member of this course", "feedbackOpen" => 0));
                            //return;
                        }
                        $query = $conn->prepare("select * from courses where id = ? limit 1");
                        $query->bind_param("i", $courseID);
                        $query->execute();
                        $result = $query->get_result();
                        $feedbackOpen = mysqli_fetch_assoc($result)['feedbackOpen'];
                        $conn->close();
                        echo json_encode(array("instructor" => $instr, "message" => "Success: User Authorized", "feedbackOpen" => $feedbackOpen));
                        return;

                    }
                } else {
                    $conn->close();
                    if($instr==0){
                        echo json_encode(array("instructor" => -1, "message" => "Error: Course does not exist", "feedbackOpen" => 0));
                        return;
                        //header("Location: ../mainStud.html", true,  301);
                        //exit;
                    } else {
                        echo json_encode(array("instructor" => -1, "message" => "Error: Course does not exist", "feedbackOpen" => 0));
                        return;
                        //header("Location: ../main.html", true,  301);
                        //exit;
                    }
                    //echo json_encode(array("instructor" => -1, "message" => "Error: Course does not exist", "feedbackOpen" => 0));
                    //return;
                }
            }
        } else {
            $conn->close();
            //header("Location: ../index.html", true,  301);
            //exit;
            echo json_encode(array("instructor" => -1,"message"=>"Error: You are not authorized, try logging in again","feedbackOpen"=>0));
            return;

        }
    } else {
        $conn->close();
        //header("Location: ../index.html", true,  301);
        //exit;
        echo json_encode(array("instructor" => -1,"message"=>"Error: You are not authorized, try logging in again","feedbackOpen"=>0));
        return;
    }
}