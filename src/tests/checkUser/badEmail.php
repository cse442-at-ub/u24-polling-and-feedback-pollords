<?php

session_start();
include($_SERVER['DOCUMENT_ROOT'].'/u24-polling-and-feedback-pollords/src/php/functions.php');

list($passed,$message) = checkUser("wrongemail@gmail.edu","fortnite");
echo $message;