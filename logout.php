<?php

session_start(); //Start The Session
session_unset(); //Unset The Data
session_destroy(); //Destroy the Session
header('location:index.php');
exit();
