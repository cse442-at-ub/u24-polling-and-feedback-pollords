<?php

session_start();
include('../../php/checkUser.php');

list($passed,$message) = checkUser("wrongemail@gmail.edu","fortnite");
echo $message;