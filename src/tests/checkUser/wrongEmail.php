<?php

session_start();
include('../../php/checkUser.php');

list($passed,$message) = checkUser("wrongemail@buffalo.edu","fortnite");
echo $message;