<?php //create_account.php
include 'constants.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    $sql = "INSERT INTO Users (Username, Birthday, Email, Password) VALUES ('$name', '$dob', '$email', '$password')";

    if ($db->query($sql) === TRUE) {
        $db->close();
        header("Location: login.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $db->error;
    }

    $db->close();
}
?>
