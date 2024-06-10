<!DOCTYPE html>

<html>
<body>
<div>
    <h1>Example for connecting to DB</h1>

    Logging in for MySQL

</div></br>
<?php
//fields for connecting to the database
$servername = "oceanus.cse.buffalo.edu:3306";
$username = "jacobzal";
$password = "50346440";
$dbname = "cse442_2024_summer_team_c_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    $result = false;
    die("Connection failed: " . $conn->connect_error);
}
else{
    $result = true;
}
echo "Connected successfully to oceanus database".nl2br("\n");

$conn->close();
?>


</body>
</html>