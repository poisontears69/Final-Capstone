<?php

// Database connection settings for Hostinger
$host = 'healthconnect.website';  // For Hostinger, it might be 'localhost' or something like 'mysql.hostinger.com'
$username = 'adminMatoy';  // The database username
$password = 'b53Q;L*ew|2K';  // The database password
$database = 'healthconnect';  // The database name

// Create connection using mysqli
$con = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
