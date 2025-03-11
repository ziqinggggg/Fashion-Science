<?php // logout.php
session_start();
if (isset($_SESSION['email'])) {
    unset($_SESSION['email']); // Remove the email session variable
    session_destroy(); // Destroy the session
} 

?>
