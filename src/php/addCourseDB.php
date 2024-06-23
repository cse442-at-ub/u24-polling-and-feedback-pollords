<?php
include 'connection.php';

$courseName = $_POST['courseName'];
$courseCode = $_POST['courseCode'];
$term = $_POST['term'];
$instructorsArray = $_POST['instructors'];  // This will be an array of strings
$students = $_POST['students'];  

if (empty($courseName) || empty($courseCode) || empty($term) || empty($instructorsArray)) {
    echo json_encode(array("success" => false, "message" => "Error: Do not leave inputs empty"));
    exit();
}

if (!is_array($instructorsArray) || count($instructorsArray) == 0) {
    echo json_encode(array("success" => false, "message" => "Error: Instructors field must be a non-empty array"));
    exit();
}

if (!preg_match('/^(Spring|Summer|Fall|Winter),\s\d{4}$/', $term)) {
    echo json_encode(array("success" => false, "message" => "Error: the format of the term is incorrect (format should be: Season, Year)"));
    exit();
}

$instructors = implode(',', $instructorsArray);
$emailArray = explode(',', $instructors);

foreach ($emailArray as $email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@buffalo\.edu$/', $email)) {
        echo json_encode(array("success" => false, "message" => "Error: the instructors should be a list of valid @buffalo.edu emails separated by commas. Please check the input and try again"));
        exit();
    }
}

$conn = $_SESSION['conn'];
$stmt_check = $conn->prepare("SELECT * FROM courses WHERE courseCode = ? AND term = ?");
$stmt_check->bind_param("ss", $courseCode, $term);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo json_encode(array("success" => false, "message" => "Error: Course with the given course code and term already exists"));
    $stmt_check->close();
    $conn->close();
    exit();
}
$stmt_check->close();

$stmt = $conn->prepare("INSERT INTO courses (courseName, courseCode, term, instructors, students) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $courseName, $courseCode, $term, $instructors, $students);

if ($stmt->execute()) {
    echo json_encode(array("success" => true, "message" => "Success: Course successfully created"));
} else {
    echo json_encode(array("success" => false, "message" => "Error: " . $stmt->error));
}

$stmt->close();
$conn->close();
?>