<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

session_start();

include_once "connection.php";
include_once "createConfirmCode.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conn=$_SESSION['conn'];
    // Assuming the form fields are named "txt-email" and "txt-pass"
    // Prepare the data for inserting into the database (you should sanitize the data to prevent SQL injection)
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $instr = mysqli_real_escape_string($conn, $_POST['instr']);





    list($passed,$message,$code)=createConfirmCode($email,$password);
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;
    $_SESSION['code'] = $code;
    $_SESSION['instructor'] = $instr;
	if($code!=-1){
		mail($email,"Account Confirmation Code",$code);
	}
    

    echo json_encode(array("success"=>$passed,"message"=>$message));


    // Close the database connection
    if(isset($conn->server_info)){
        $conn->close();
    }

}