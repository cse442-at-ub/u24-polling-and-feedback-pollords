<?php

include("tokenCreation.php");
include("connection.php");

function checkUser($email, $pass): array
{
    //echo $email;
    //echo $pass;
    if(empty($email) || empty($pass))
    {
        return array(false,"Error: Don't leave inputs empty",-1);
    }

    $conn=$_SESSION['conn'];

    if(!isset($conn->server_info)){
        return array(false,"Error: Login is currently down, please try again later",-1);
    }

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
        if ($result && mysqli_num_rows($result) > 0) {
            $db_pass = mysqli_fetch_assoc($result)['password'];

            if (password_verify($pass, $db_pass)){
                // Deal with successful login
                $db_instr = mysqli_fetch_assoc(mysqli_query($conn, $query))['instructor'];
                tokenCreate($email,$db_instr);
                $_SESSION['conn']->close();
                return array(true,"Success: User Exists with same login and password",$db_instr);
            }
            else {
                // Handle authentication error
                $_SESSION['conn']->close();
                return array(false,"Error: Information wrong or User does not exist",-1);

            }
        }
    }
    $_SESSION['conn']->close();
    return array(false,"Error: Information wrong or User does not exist",-1);
}




