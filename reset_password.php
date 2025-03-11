<?php   // reset_password.php
include 'constants.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $email = $_POST["email"];
    $new_password = $_POST["new_password"];
    $confirm_new_password = $_POST["confirm_new_password"];

    $sql = "SELECT Password, UserID FROM Users WHERE Email = '$email'";
    $result = $db->query($sql);
        
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPasswordFromDB = $row["Password"];
        $userId = $row["UserID"];

        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE Users SET Password = '$new_hashed_password' WHERE UserID = '$userId'";
        $result = $db->query($sql);
        echo '<script>alert("Successfully changed password");</script>';
        echo '<script>window.location.href = "login.html";</script>';
        exit();

    } else {
        echo '<script>alert("User not found. Please try again.");</script>';
        echo '<script>window.location.href = "forgot_password.html";</script>';
        exit();
    }
}
