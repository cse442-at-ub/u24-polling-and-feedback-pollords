<?php

session_start();
include('../../php/createConfirmCode.php');

list($passed,$message,$code) = createConfirmCode("","");
echo $message;