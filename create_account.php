<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];


    echo 'Account created for username: ' . htmlspecialchars($username) . ', email: ' . htmlspecialchars($email);
}