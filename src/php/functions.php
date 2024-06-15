<?php


include("connection.php");


function checkUser($email, $pass)
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
        return array(false,"Error: Must be a valid buffalo.edu email");
    }
    if($temp == 0 || strlen($email)!=$temp+12){
        return array(false,"Error: Must be a valid buffalo.edu email");
    }


    if($result) {
        if ($result && mysqli_num_rows($result) > 0) {
            $db_pass = mysqli_fetch_assoc($result)['password'];

            if (password_verify($pass, $db_pass)){
                // Deal with successful login
                return array(true,"Success: User Exists with same login and password");
            }
            else {
                // Handle authentication error
                return array(false,"Error: Information wrong or User does not exist");

            }
        }
    }
    return array(false,"Error: Information wrong or User does not exist");
}

function createConfirmCode($email,$pass){

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
        return array(false,"Error: Must be a valid buffalo.edu email",-1);
    }
    if($temp == 0 || strlen($email)!=$temp+12){
        return array(false,"Error: Must be a valid buffalo.edu email",-1);
    }
    if($result) {
        if ($result && mysqli_num_rows($result) > 0) {
            return array(false,"Error: User with that email already exists",-1);
        }

    }


    $random_hash = substr(md5(uniqid(rand(), true)), 25, 25); // 16 characters long
    return array(true,"Success: Confirmation code created",$random_hash);

}


