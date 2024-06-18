<?php

session_start();
include('../../php/createConfirmCode.php');

list($passed,$message,$code) = createConfirmCode("testcreate@gmail.edu","testcreate");
echo $message;