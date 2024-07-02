<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

session_start();
include("tokenCreation.php");
include_once "connection.php";
include_once "createConfirmCode.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conn=$_SESSION['conn'];
    // Assuming the form fields are named "txt-email" and "txt-pass"
    // Prepare the data for inserting into the database (you should sanitize the data to prevent SQL injection)
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];
    $instr = $_SESSION['instructor'];
    $codeIn = mysqli_real_escape_string($conn, $_POST['codeIn']);
    $code = $_SESSION['code'];

    $passed = false;
    $message = "";
    $temp = 0;

    if($code == $codeIn){

        if($instr==="Instructor"){
            $temp=1;
        }
        $passed = true;
        $message = "Success: Account Created";
        $hashed =
            password_hash($password,
                PASSWORD_BCRYPT);

        $query = $conn->prepare("insert into userAccs (email,password,instructor) values (?,?,?)");
        $query->bind_param("ssi", $email,$hashed,$temp);
        $query->execute();

        //$query = "insert into userAccs (email,password,instructor) values ('$email','$hashed',$temp)";
        //mysqli_query($conn, $query);

        tokenCreate($email,$temp);

    } else {
        $message = "Error: Incorrect Code";
    }


    echo json_encode(array("success"=>$passed,"message"=>$message,"email"=>$email,"instructor"=>$temp));


    // Close the database connection
    if(isset($conn->server_info)){
        $conn->close();
    }

}