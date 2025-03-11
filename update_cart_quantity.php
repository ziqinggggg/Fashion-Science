<?php   // update_cart_quantity.php
include 'constants.php';
session_start();

if (isset($_POST['CartItemID']) && isset($_POST['Action'])) {

    $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $cartItemId = $_POST['CartItemID'];
    $action = $_POST['Action'];

    if ($action === 'update') {
        // Update the quantity in the database
        $newQuantity = $_POST['Quantity'];

        $sql = "UPDATE CartItems SET Quantity = ? WHERE CartItemID = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $newQuantity, $cartItemId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "success"; // Quantity updated successfully
        } else {
            echo "error"; // Failed to update the quantity
        }
    } elseif ($action === 'remove') {
        // Remove the item from the database
        $sql = "DELETE FROM CartItems WHERE CartItemID = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $cartItemId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "success"; // Item removed from the database
        } else {
            echo "error"; // Failed to remove the item from the database
        }
    }

    $stmt->close();
    $db->close();
}
?>
