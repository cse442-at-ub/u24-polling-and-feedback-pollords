<?php
session_start();
include("connection.php");
    $conn=$_SESSION['conn'];
    $token = $_SESSION['token'];
    if(isset($conn->server_info)) {
        $query = "delete from tokens where token = '$token' limit 1";
        mysqli_query($conn, $query);
    }
    $_SESSION['token'] = "";
    $_SESSION['instructor'] = "";
    session_destroy();
    session_abort();
header("Location:..");
