<?php
// Start the session
session_start();

// Destroy all session data
session_destroy();

// Redirect user to login page
header("Location: login.php");
exit();
?>