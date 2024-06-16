<?php

session_start();
include($_SERVER['DOCUMENT_ROOT'].'/u24-polling-and-feedback-pollords/src/php/functions.php');
$_SESSION['conn']->close();

list($passed,$message) = checkUser("fortnite@buffalo.edu","fortnite");
echo $message;