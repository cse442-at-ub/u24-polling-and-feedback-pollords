<!DOCTYPE html>

<html>
<body>
<div>
    <h1>Example to create a table item</h1>

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



$query = $conn->query("SHOW TABLES LIKE 'TestTable'");
$count = count($query->fetch_all());


if (empty($count)) {
    // sql to create table
    echo "No Table Found";
}
elseif ($count == 1) {
    echo "Table Exists".nl2br("\n");
    $ins = "INSERT INTO TestTable (firstname, lastname, email)
    VALUES ('Test', 'Guy', 'test@example.com')";

    if ($conn->query($ins) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $ins . "<br>" . $conn->error;
    }
}
$conn->close();
?>


</body>
</html>
