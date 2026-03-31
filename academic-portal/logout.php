<?php
session_start();

// Remove all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to Home page with logout message
header("Location: index.php?logout=1");
exit();
?>