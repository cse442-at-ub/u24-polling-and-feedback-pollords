<?php

include("connection.php");
//session_start();
function tokenCreate($email,$instr)
{
    $conn=$_SESSION['conn'];

    if(isset($conn->server_info)){
        $query = $conn->prepare("select * from tokens where email = ? limit 1");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();

        //$query = "select * from tokens where email = '$email' limit 1";
        //$result = mysqli_query($conn, $query);
        $token = md5(uniqid(rand(), true));

        if($result) {
            if ($result && mysqli_num_rows($result) > 0) {
                $temp = mysqli_fetch_assoc($result)['token'];
                //$query2 = "update tokens set token=replace(token,'$temp','$token')";
                $query = $conn->prepare("update tokens set token=replace(token,?,?)");
                $query->bind_param("ss", $temp,$token);
                $query->execute();

                $_SESSION['token'] = $token;
                $_SESSION['instructor'] = $instr;
                //mysqli_query($conn, $query2);


                $query = $conn->prepare("select * from userAccs where email = ? limit 1");
                $query->bind_param("s", $email);
                $query->execute();
                $result = $query->get_result();
                $id = mysqli_fetch_assoc($result)['id'];
                $_SESSION['userID'] = $id;
                //echo $token. "        ".$id;
            }
            else{
                //$query2 = "insert into tokens (email,token) values ('$email','$token')";
                //mysqli_query($conn, $query2);
                $query = $conn->prepare("insert into tokens (email,token) values (?,?)");
                $query->bind_param("ss", $email,$token);
                $query->execute();
                $_SESSION['token'] = $token;
                $_SESSION['instructor'] = $instr;

                $query = $conn->prepare("select * from userAccs where email = ? limit 1");
                $query->bind_param("s", $email);
                $query->execute();
                $result = $query->get_result();
                $id = mysqli_fetch_assoc($result)['id'];
                $_SESSION['userID'] = $id;

            }
        }
    }
}

