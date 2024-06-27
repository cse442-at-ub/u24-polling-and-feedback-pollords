<?php
include("connection.php");

function checkFeedbackHelper($courseID): array {
    $conn = $_SESSION['conn'];
    error_log("Session Data: " . print_r($_SESSION, true)); // Log session data

    if (isset($_SESSION['token']) && isset($_SESSION['instructor']) && isset($_SESSION['userID'])) {
        $instr = $_SESSION['instructor'];
        $token = $_SESSION['token'];
        $userID = $_SESSION['userID'];
        
        error_log("Received courseID: " . $courseID); // Log received courseID
        $query = $conn->prepare("SELECT * FROM tokens WHERE token = ? LIMIT 1");
        $query->bind_param("s", $token);
        $query->execute();
        $result = $query->get_result();
        if ($result) {
            if ($result && mysqli_num_rows($result) == 0) {
                return array("instructor" => -1, "message" => "Error: You are not authorized, try logging in again", "feedbackOpen" => 0);
            } else {
                $query = $conn->prepare("SELECT * FROM courses WHERE id = ? LIMIT 1");
                $query->bind_param("i", $courseID);
                $query->execute();
                $result = $query->get_result();
                if ($result) {
                    error_log("Course query result: " . mysqli_num_rows($result)); // Log course query result
                    if ($result && mysqli_num_rows($result) == 0) {
                        return array("instructor" => -1, "message" => "Error: Course does not exist", "feedbackOpen" => 0);
                    } else {
                        $temp = "";
                        if ($instr == 0) {
                            $temp = mysqli_fetch_assoc($result)['students'];
                        } else {
                            $temp = mysqli_fetch_assoc($result)['instructors'];
                        }
                        if ($temp == "") {
                            return array("instructor" => -1, "message" => "Error: You are not a member of this course", "feedbackOpen" => 0);
                        }
                        $members = explode(',', $temp);
                        $isMember = false;
                        foreach ($members as $curr) {
                            if ($curr == $userID) {
                                $isMember = true;
                            }
                        }
                        if (!$isMember) {
                            return array("instructor" => -1, "message" => "Error: You are not a member of this course", "feedbackOpen" => 0);
                        }
                        $query = $conn->prepare("SELECT * FROM courses WHERE id = ? LIMIT 1");
                        $query->bind_param("i", $courseID);
                        $query->execute();
                        $result = $query->get_result();
                        $feedbackOpen = mysqli_fetch_assoc($result)['feedbackOpen'];
                        return array("instructor" => $instr, "message" => "Success: User Authorized", "feedbackOpen" => $feedbackOpen);
                    }
                } else {
                    return array("instructor" => -1, "message" => "Error: Course does not exist", "feedbackOpen" => 0);
                }
            }
        } else {
            return array("instructor" => -1, "message" => "Error: You are not authorized, try logging in again", "feedbackOpen" => 0);
        }
    } else {
        return array("instructor" => -1, "message" => "Error: You are not authorized, try logging in again", "feedbackOpen" => 0);
    }
}