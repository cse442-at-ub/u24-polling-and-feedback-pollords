<!DOCTYPE html>

<html>
<body>
<div>
    <h1>Example to create a table</h1>

    Logging in for MySQL

</div></br>
<?php
//fields for connecting to the database
$servername = "";
$username = "";
$password = "";
$dbname = "";

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


$tableName = "TestTable";

$query = $conn->query("SHOW TABLES LIKE '$tableName'");
$count = count($query->fetch_all());


if (empty($count)) {
    // sql to create table
    echo "No Table Found".nl2br("\n");
    $sql = "CREATE TABLE $tableName (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    email VARCHAR(50),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";


    if ($conn->query($sql) === TRUE) {
        echo "Table \"".$tableName."\" created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }
}
elseif ($count == 1) {
    echo "Table \"".$tableName."\" already Exists";
}
$conn->close();
?>
</body>
</html>