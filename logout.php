<?php
session_start();

// Unset all session variables
unset($_SESSION['authenticated']);
unset($_SESSION['auth_user']);

// Optionally, if you want to destroy the entire session
session_unset(); // Clears all session variables
session_destroy(); // Destroys the session

// Set a success message to be displayed on the next page
$_SESSION['success'] = "Logout successful";

// Redirect to the login page
header('Location: login.php');
exit;
?>
