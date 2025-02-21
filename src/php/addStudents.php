<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

session_start();

include_once "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn=$_SESSION['conn'];
    // Assuming the form fields are named "txt-email" and "txt-pass"
    // Prepare the data for inserting into the database (you should sanitize the data to prevent SQL injection)
    $courseID = mysqli_real_escape_string($conn, $_POST['id']);
    $students = mysqli_real_escape_string($conn, $_POST['students']);
    $token = $_SESSION['token'];

    $newStudentsID = [];


    if(isset($conn->server_info)){
        if(empty($courseID)){
            $conn->close();
            echo json_encode(array("success" => false, "message" => "Error: No course provided, try logging in again"));
            return;
        }
        $query = $conn->prepare("select * from tokens where token = ? limit 1");
        $query->bind_param("s", $token);
        $query->execute();
        $result = $query->get_result();
        //$query = "select * from tokens where token = '$token' limit 1";
        //$result = mysqli_query($conn, $query);

        if($result) {
            if ($result && mysqli_num_rows($result) > 0) {
                if (empty($students)) {
                    $conn->close();
                    echo json_encode(array("success" => false, "message" => "Error: The uploaded file is empty"));
                } else {
                    $format = preg_match('/^([\w\s]+@buffalo.edu,)*[\w\s]+@buffalo.edu$/', $students);
                    $newStudents = explode(',', $students);
                    foreach ($newStudents as $curr) {

                        $temp = strpos($curr, "@buffalo.edu");
                        if ($temp === false) {
                            $conn->close();
                            echo json_encode(array("success" => false, "message" => "Error: Item \"".$curr."\" has improper format. Make sure there is only 1 student per line, with each email ending in @buffalo.edu"));
                            return;
                        }
                        if ($temp == 0 || strlen($curr) != $temp + 12) {
                            $conn->close();
                            echo json_encode(array("success" => false, "message" => "Error: Item \"".$curr."\" has improper format. Make sure there is only 1 student per line, with each email ending in @buffalo.edu"));
                            return;
                        }
                        $query = $conn->prepare("select * from userAccs where email = ? limit 1");
                        $query->bind_param("s", $curr);
                        $query->execute();
                        $result = $query->get_result();

                        //$query = "select * from userAccs where email = '$curr' limit 1";
                        //$result = mysqli_query($conn, $query);
                        if($result && mysqli_num_rows($result) == 0) {
                            echo json_encode(array("success" => false, "message" => "Error: Item \"".$curr."\" does not exist"));
                            return;
                        }
                    }
                    if (!$format) {
                        $conn->close();
                        echo json_encode(array("success" => false, "message" => "Error: Formatting of students is incorrect. Make sure there is only 1 student per line, with each email ending in @buffalo.edu"));
                    } else {
                        $query = $conn->prepare("select * from courses where id = ? limit 1");
                        $query->bind_param("s", $courseID);
                        $query->execute();
                        $result = $query->get_result();

                        //$query = "select * from courses where id = '$courseID' limit 1";
                        //$result = mysqli_query($conn, $query);
                        if ($result && mysqli_num_rows($result) > 0) {
                            $course = mysqli_fetch_assoc($result);
                            $temp = $course['students'];
                            $oldStuds = explode(',', $temp);
                            if($temp!=""){
                                foreach ($oldStuds as $curr) {
                                    //get student by their id
                                    $query = $conn->prepare("select * from userAccs where id = ? limit 1");
                                    $query->bind_param("s", $curr);
                                    $query->execute();
                                    $result = $query->get_result();

                                    //$query = "select * from userAccs where id = '$curr' limit 1";
                                    //$result = mysqli_query($conn, $query);
                                    $stud = mysqli_fetch_assoc($result);

                                    //get their courses string and split it into an array
                                    $studCourses = $stud['courses'];
                                    $temp2 = explode(',', $studCourses);

                                    //look for the current course, and delete it
                                    $key = array_search($courseID, $temp2);
                                    unset($temp2[$key]);

                                    //write back the string without the current course id
                                    $writeBack = implode(",", $temp2);
                                    if($writeBack==","){
                                        $writeBack="";
                                    }
                                    $query = $conn->prepare("update userAccs set courses=replace(courses,?,?) where id = ? limit 1");
                                    $query->bind_param("sss", $studCourses,$writeBack,$curr);
                                    $query->execute();

                                    //$query = "update userAccs set courses=replace(courses,'$studCourses','$writeBack') where id = '$curr' limit 1";
                                    //mysqli_query($conn, $query);
                                }
                            }
                            foreach ($newStudents as $curr) {
                                //get the students by their email
                                $query = $conn->prepare("select * from userAccs where email = ? limit 1");
                                $query->bind_param("s", $curr);
                                $query->execute();
                                $result = $query->get_result();

                                //$query = "select * from userAccs where email = '$curr' limit 1";
                                //$result = mysqli_query($conn, $query);
                                $stud = mysqli_fetch_assoc($result);

                                //add their id to the course list
                                $studID = $stud['id'];
                                array_push($newStudentsID,$studID);

                                //add the course id to their courses list
                                $studCourses = $stud['courses'];
                                $temp2 = explode(',', $studCourses);
                                array_push($temp2,$courseID);
                                $writeBack = implode(",", $temp2);
                                if($studCourses==""){
                                    $writeBack=$courseID;
                                }
                                $query = $conn->prepare("update userAccs set courses = ? where id = ? limit 1");
                                $query->bind_param("ss", $writeBack,$studID);
                                $query->execute();

                                //$query = "update userAccs set courses='$writeBack' where id = '$studID' limit 1";
                                //mysqli_query($conn, $query);
                            }
                            $query = $conn->prepare("select * from courses where id = ? limit 1");
                            $query->bind_param("s", $courseID);
                            $query->execute();
                            $result = $query->get_result();
                            //$query = "select * from courses where id = '$courseID' limit 1";
                            //$result = mysqli_query($conn, $query);

                            $course = mysqli_fetch_assoc($result);
                            $temp3 = $course['students'];
                            $writeBack = implode(",", $newStudentsID);

                            $query = $conn->prepare("update courses set students = ? where id = ? limit 1");
                            $query->bind_param("ss", $writeBack,$courseID);
                            $query->execute();
                            //$query = "update courses set students='$writeBack' where id = '$courseID' limit 1";
                            //mysqli_query($conn, $query);

                            $conn->close();
                            echo json_encode(array("success" => true, "message" => "Success: Students set to new list"));

                        } else {
                            $conn->close();
                            echo json_encode(array("success" => false, "message" => "Error: Course does not exist, please create one"));
                        }
                    }
                }
            }
            else{
                $conn->close();
                echo json_encode(array("success" => false, "message" => "Error: Not Authorized, try logging in again"));
            }
        } else {
            $conn->close();
            echo json_encode(array("success" => false, "message" => "Error: Not Authorized, try logging in again"));
        }
    }



}
