<?php
session_start();

// Destroy all sessions
session_unset();
session_destroy();

// Redirect to login or home page
header("Location: ../Pages/login.php");
exit;
?>
