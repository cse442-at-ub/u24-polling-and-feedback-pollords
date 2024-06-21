<?php

session_start();
include('../../php/tokenCreation.php');

tokenCreate("tokenTestStud@buffalo.edu",0);
echo $_SESSION['token'];
$_SESSION['conn']->close();