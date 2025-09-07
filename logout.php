<?php
/**
 * Logout functionality for Velvet Vogue E-Commerce Website
 * Destroys user session and redirects to homepage
 */

// Start session
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to homepage with logout message
header("Location: index.php?message=logged_out");
exit();
?>
