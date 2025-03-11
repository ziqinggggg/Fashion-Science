<?php // check_session.php
session_start();

if (isset($_SESSION['email'])) {
    echo "logged_in";
} else {
    echo "not_logged_in";
}
?>
