<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $code = $_POST['code'];

    if ($code == '123456') {
        echo 'Code confirmed for username: ' . htmlspecialchars($username);
    } else {
        echo 'Invalid confirmation code.';
    }
}
