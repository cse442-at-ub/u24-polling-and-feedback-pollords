<?php

session_start();
include('../../php/createConfirmCode.php');

list($passed,$message,$code) = createConfirmCode("fortnite@buffalo.edu","testcreate");
echo $message;