<?php

session_start();
include('../../php/createConfirmCode.php');

list($passed,$message,$code) = createConfirmCode("testcreate@buffalo.edu","testcreate");
echo $message.nl2br("\n").$code;