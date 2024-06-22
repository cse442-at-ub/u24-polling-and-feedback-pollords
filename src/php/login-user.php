<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

session_start();

include_once "connection.php";
include_once "checkUser.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conn=$_SESSION['conn'];
    // Assuming the form fields are named "txt-email" and "txt-pass"
    // Prepare the data for inserting into the database (you should sanitize the data to prevent SQL injection)
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);


    list($passed,$message,$instr,$courses)=checkUser($email,$password);

    echo json_encode(array("success"=>$passed,"message"=>$message, "email"=>$email, "instructor"=>$instr, "courses"=>$courses));

    // Close the database connection
    if(isset($conn->server_info)){
        $conn->close();
    }

}
