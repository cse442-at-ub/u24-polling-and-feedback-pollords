<?php

session_start();
include('../../php/checkUser.php');

list($passed,$message) = checkUser("@buffalo.eduwrongemail","fortnite");
echo $message;