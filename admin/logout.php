<?php
session_start(); 

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Regenerate session ID for security
session_regenerate_id(true);

// Redirect to login page
header("Location: login.php");
exit();
?>
