<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

session_start();
include 'connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn=$_SESSION['conn'];

    $courseName = mysqli_real_escape_string($conn,$_POST['name']);
    $courseCodeIn = mysqli_real_escape_string($conn,$_POST['code']);
    $courseCode = str_replace(' ', '', $courseCodeIn);
    $termIn = mysqli_real_escape_string($conn,$_POST['sem']);
    $term = str_replace(' ', '', strtolower($termIn));
    $instructorsArray = explode(',', mysqli_real_escape_string($conn,$_POST['instrs']));  // This will be an array of strings
    $students = "";
    $token = $_SESSION['token'];

    $newInstrID = [];

    if(isset($conn->server_info)){
        $query = "select * from tokens where token = '$token' limit 1";
        $result = mysqli_query($conn, $query);
        if($result && mysqli_num_rows($result) == 0) {
            $conn->close();
            echo json_encode(array("success" => false, "message" => "Error: Not Authorized, try logging in again"));
        }
        if($result && mysqli_num_rows($result) > 0){
            $temp = mysqli_fetch_assoc($result);
            $email = $temp['email'];
            $query = "select * from userAccs where email = '$email' limit 1";
            $result = mysqli_query($conn, $query);
            $temp = mysqli_fetch_assoc($result);
            $temp2 = $temp['instructor'];
            if($temp2==0){
                $conn->close();
                echo json_encode(array("success" => false, "message" => "Error: Not Authorized, try logging in again"));
                return;
            }
        }



        if (empty($courseName) || empty($courseCode) || empty($term) || empty($instructorsArray)) {
            $conn->close();
            echo json_encode(array("success" => false, "message" => "Error: Do not leave inputs empty"));
            exit();
        }

        if (!is_array($instructorsArray) || count($instructorsArray) == 0) {
            $conn->close();
            echo json_encode(array("success" => false, "message" => "Error: Instructors field must not be empty"));
            exit();
        }

        if (!preg_match('/^(spring|summer|fall|winter),\d{4}$/', $term)) {
            $conn->close();
            echo json_encode(array("success" => false, "message" => "Error: The format of the term is incorrect, format should be: Season,Year (ex: Spring,2024)"));
            exit();
        }
        foreach ($instructorsArray as $curr) {
            $temp = strpos($curr, "@buffalo.edu");
            if ($temp === false) {
                $conn->close();
                echo json_encode(array("success" => false, "message" => "Error: Instructor \"".$curr."\" has improper format. Make sure instructor emails are separated by commas, with each email ending in @buffalo.edu"));
                return;
            }
            if ($temp == 0 || strlen($curr) != $temp + 12) {
                $conn->close();
                echo json_encode(array("success" => false, "message" => "Error: Instructor \"".$curr."\" has improper format. Make sure instructor emails are separated by commas, with each email ending in @buffalo.edu"));
                return;
            }
            $query = "select * from userAccs where email = '$curr' limit 1";
            $result = mysqli_query($conn, $query);
            if($result && mysqli_num_rows($result) == 0) {
                $conn->close();
                echo json_encode(array("success" => false, "message" => "Error: Instructor \"".$curr."\" does not exist"));
                return;
            }
            $temp = mysqli_fetch_assoc($result);
            $temp2 = $temp['instructor'];
            if($temp2==0) {
                $conn->close();
                echo json_encode(array("success" => false, "message" => "Error: \"".$curr."\" is not an instructor"));
                return;
            }
        }

        //$instructors = implode(',', $instructorsArray);
        //$emailArray = explode(',', $instructors);

//        foreach ($emailArray as $email) {
//            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@buffalo\.edu$/', $email)) {
//                echo json_encode(array("success" => false, "message" => "Error: the instructors should be a list of valid @buffalo.edu emails separated by commas. Please check the input and try again"));
//                exit();
//            }
//        }
        $query = "select * from courses where term = '$term' and courseCode = '$courseCode' limit 1";
        $result = mysqli_query($conn, $query);
        if($result && mysqli_num_rows($result) > 0) {
            $conn->close();
            echo json_encode(array("success" => false, "message" => "Error: Course with same courseCode and term already exists"));
            return;
        }



        foreach ($instructorsArray as $curr) {
            //get the instructor by their email
            $query = "select * from userAccs where email = '$curr' limit 1";
            $result = mysqli_query($conn, $query);
            $temp = mysqli_fetch_assoc($result);

            //add their id to the course list
            $currID = $temp['id'];
            array_push($newInstrID,$currID);

        }
        $writeBack = implode(",", $newInstrID);
        $query = "insert into courses (courseName, courseCode, term, instructors, students) values ('$courseName', '$courseCode', '$term', '$writeBack', '$students')";
        mysqli_query($conn, $query);
        $query = "select * from courses where term = '$term' and courseCode = '$courseCode' limit 1";
        $result = mysqli_query($conn, $query);
        $temp3 = mysqli_fetch_assoc($result);
        $courseID = $temp3['id'];

        foreach ($newInstrID as $curr) {
            //get the instructor by their email
            $query = "select * from userAccs where id = '$curr' limit 1";
            $result = mysqli_query($conn, $query);
            $temp = mysqli_fetch_assoc($result);

            $tempCourses = $temp['courses'];
            $temp2 = explode(',', $tempCourses);
            array_push($temp2,$courseID);
            $writeBack = implode(",", $temp2);
            if($tempCourses==""){
                $writeBack=$courseID;
            }
            $query = "update userAccs set courses='$writeBack' where id = '$curr' limit 1";
            mysqli_query($conn, $query);

        }

//        $stmt_check = $conn->prepare("SELECT * FROM courses WHERE courseCode = ? AND term = ?");
//        $stmt_check->bind_param("ss", $courseCode, $term);
//        $stmt_check->execute();
//        $result_check = $stmt_check->get_result();
//
//        if ($result_check->num_rows > 0) {
//            echo json_encode(array("success" => false, "message" => "Error: Course with the given course code and term already exists"));
//            $stmt_check->close();
//            $conn->close();
//            exit();
//        }
//        $stmt_check->close();

//        $stmt = $conn->prepare("INSERT INTO courses (courseName, courseCode, term, instructors, students) VALUES (?, ?, ?, ?, ?)");
//        $stmt->bind_param("sssss", $courseName, $courseCode, $term, $instructors, $students);
//
//        if ($stmt->execute()) {
//            echo json_encode(array("success" => true, "message" => "Success: Course successfully created"));
//        } else {
//            echo json_encode(array("success" => false, "message" => "Error: " . $stmt->error));
//        }

//        $stmt->close();

        //add the course id to their courses list
        echo json_encode(array("success" => true, "message" => "Success: Course successfully created"));

        $conn->close();
    }


}
