<?php
session_start();
include("connection.php");
    $conn=$_SESSION['conn'];
    $token = $_SESSION['token'];
    if(isset($conn->server_info)) {
        $query = $conn->prepare("delete from tokens where token = ? limit 1");
        $query->bind_param("s", $token);
        $query->execute();

        //$query = "delete from tokens where token = '$token' limit 1";
        //mysqli_query($conn, $query);
    }
    $_SESSION['token'] = "";
    $_SESSION['instructor'] = "";
    session_destroy();
    session_abort();
header("Location:..");
