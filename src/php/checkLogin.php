<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

session_start();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['token']) && isset($_SESSION['instructor'])) {
        $instr = $_SESSION['instructor'];
        echo json_encode(array("instructor" => "$instr"));
    } else {
        echo json_encode(array("instructor" => -1));
    }
}