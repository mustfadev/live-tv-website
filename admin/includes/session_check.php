<?php
session_start(); // Starts a new session or resumes an existing session.

if (!isset($_SESSION['user_id'])) { // Checks if the 'user_id' session variable is set.
    header("Location: login.php"); // Redirects to the login page if not authenticated.
    exit(); // Terminates the script to prevent further execution.
}
?>

