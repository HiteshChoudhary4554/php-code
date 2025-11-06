<?php
session_start();
unset($_SESSION['username']); // Remove specific session variable
unset($_SESSION['password']); 
session_destroy(); // Destroy the session
header("Location: login.php");
exit();
?>