<?php
session_start();

if(!isset($_SESSION['authenticated']))
{
    $_SESSION['error'] = "Please login to access dashboard";
    header('Location: login.php');
    exit(0);
}


?>
