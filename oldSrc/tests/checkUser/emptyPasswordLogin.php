<?php

session_start();
include('../../php/checkUser.php');

list($passed,$message) = checkUser("","fortnite");
echo $message;