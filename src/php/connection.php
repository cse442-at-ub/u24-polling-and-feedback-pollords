<?php

$servername = "oceanus.cse.buffalo.edu:3306";
$username = "jacobzal";
$password = "50346440";
$dbname = "cse442_2024_summer_team_c_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    $_SESSION['conn']=$conn;
}
