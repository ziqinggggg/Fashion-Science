<?php //login.php
include 'constants.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT Password, UserID FROM Users WHERE Email = '$email'";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPasswordFromDB = $row["Password"];
        $userId = $row["UserID"];

        if (password_verify($password, $hashedPasswordFromDB)) {
            session_start(); // Start a session
            $_SESSION['email'] = $email; // Store user's email in the session
            $_SESSION['userId'] = $userId;
            echo '<script>alert("Successfully signed in");</script>';
            echo '<script>window.location.href = "index.php";</script>';
            exit();
        } else {
            echo '<script>alert("Invalid password. Please try again.");</script>';
            echo '<script>window.location.href = "login.html";</script>';
            exit();
        }
    } else {
        echo '<script>alert("User not found. Please try again.");</script>';
        echo '<script>window.location.href = "login.html";</script>';
        exit();
    }


    $db->close();
}
?>
