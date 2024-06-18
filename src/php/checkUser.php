<?php


include("connection.php");

function checkUser($email, $pass): array
{
    //echo $email;
    //echo $pass;
    if(empty($email) || empty($pass))
    {
        return array(false,"Error: Don't leave inputs empty");
    }

    $conn=$_SESSION['conn'];

    if(!isset($conn->server_info)){
        return array(false,"Error: Login is currently down, please try again later");
    }

    $query = "select * from userAccs where email = '$email' limit 1";
    $result = mysqli_query($conn, $query);

    $temp = strpos($email,"@buffalo.edu");
    if($temp === false){
        $_SESSION['conn']->close();
        return array(false,"Error: Must be a valid buffalo.edu email");
    }
    if($temp == 0 || strlen($email)!=$temp+12){
        $_SESSION['conn']->close();
        return array(false,"Error: Must be a valid buffalo.edu email");
    }


    if($result) {
        if ($result && mysqli_num_rows($result) > 0) {
            $db_pass = mysqli_fetch_assoc($result)['password'];

            if (password_verify($pass, $db_pass)){
                // Deal with successful login
                $_SESSION['conn']->close();
                return array(true,"Success: User Exists with same login and password");
            }
            else {
                // Handle authentication error
                $_SESSION['conn']->close();
                return array(false,"Error: Information wrong or User does not exist");

            }
        }
    }
    $_SESSION['conn']->close();
    return array(false,"Error: Information wrong or User does not exist");
}




