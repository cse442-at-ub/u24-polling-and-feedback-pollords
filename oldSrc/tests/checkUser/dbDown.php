<?php

session_start();
include('../../php/checkUser.php');
$_SESSION['conn']->close();

list($passed,$message) = checkUser("fortnite@buffalo.edu","fortnite");
echo $message;