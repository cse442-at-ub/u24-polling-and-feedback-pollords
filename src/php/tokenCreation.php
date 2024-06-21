<?php

include("connection.php");
//session_start();
function tokenCreate($email,$instr)
{
    $conn=$_SESSION['conn'];

    if(isset($conn->server_info)){
        $query = "select * from tokens where email = '$email' limit 1";
        $result = mysqli_query($conn, $query);
        $token = md5(uniqid(rand(), true));

        if($result) {
            if ($result && mysqli_num_rows($result) > 0) {
                $temp = mysqli_fetch_assoc($result)['token'];
                $query = "update tokens set token=replace(token,'$temp','$token')";
                $_SESSION['token'] = $token;
                $_SESSION['instructor'] = $instr;
                mysqli_query($conn, $query);
            }
            else{
                $query = "insert into tokens (email,token) values ('$email','$token')";
                $_SESSION['token'] = $token;
                $_SESSION['instructor'] = $instr;
                mysqli_query($conn, $query);
            }
        }
    }
}

