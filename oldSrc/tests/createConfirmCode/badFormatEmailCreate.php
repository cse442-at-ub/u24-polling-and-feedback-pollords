<?php

session_start();
include('../../php/createConfirmCode.php');

list($passed,$message,$code) = createConfirmCode("@buffalo.edutestcreate","testcreate");
echo $message;