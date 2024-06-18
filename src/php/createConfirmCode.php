<?php

include("connection.php");
function createConfirmCode($email,$pass): array
{

    if(empty($email) || empty($pass))
    {
        return array(false,"Error: Don't leave inputs empty",-1);
    }

    $conn=$_SESSION['conn'];
    if(!isset($conn->server_info)){
        return array(false,"Error: Account creation is currently down, please try again later",-1);
    }

    $hashed = password_hash($pass, PASSWORD_BCRYPT);

    $query = "select * from userAccs where email = '$email' limit 1";
    $result = mysqli_query($conn, $query);


    $temp = strpos($email,"@buffalo.edu");
    if($temp === false){
        $_SESSION['conn']->close();
        return array(false,"Error: Must be a valid buffalo.edu email",-1);
    }
    if($temp == 0 || strlen($email)!=$temp+12){
        $_SESSION['conn']->close();
        return array(false,"Error: Must be a valid buffalo.edu email",-1);
    }
    if($result) {
        if (mysqli_num_rows($result) > 0) {
            $_SESSION['conn']->close();
            return array(false,"Error: User with that email already exists",-1);
        }

    }


    $random_hash = substr(md5(uniqid(rand(), true)), 25, 25); // 16 characters long
    $_SESSION['conn']->close();
    return array(true,"Success: Confirmation code created",$random_hash);



}