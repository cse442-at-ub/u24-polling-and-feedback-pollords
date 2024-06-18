<?php

session_start();
include('../../php/checkUser.php');

list($passed,$message) = checkUser("fortnite@buffalo.edu","fortnite");
echo $message;
