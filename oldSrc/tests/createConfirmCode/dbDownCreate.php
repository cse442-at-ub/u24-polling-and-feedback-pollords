<?php

session_start();
include('../../php/createConfirmCode.php');
$_SESSION['conn']->close();

list($passed,$message,$code) = createConfirmCode("testcreate@buffalo.edu","testcreate");
echo $message;