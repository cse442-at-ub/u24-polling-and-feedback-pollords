<?php

session_start();
include('../../php/createConfirmCode.php');

list($passed,$message,$code) = createConfirmCode("testcreate@buffalo.edu","");
echo $message;