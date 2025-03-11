<?php   // push_to_cart.php
include 'constants.php';
session_start();

// Check if all required POST data is set
if (isset($_SESSION['userId'])) {
    if (isset($_POST['ProductID']) && isset($_POST['Quantity']) && isset($_POST['Size']) && ($_POST['Size']!='')){
        $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

        // Check connection
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        // Sanitize input data
        $userId = $_SESSION['userId'];
        $productId = $db->real_escape_string($_POST['ProductID']);
        $quantity = $db->real_escape_string($_POST['Quantity']);
        $size = $db->real_escape_string($_POST['Size']);

        // Check if item already exists in the cart for the user
        $sql = "SELECT CartItemID, Quantity FROM CartItems WHERE UserID = ? AND ProductID = ? AND Size = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iis", $userId, $productId, $size);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        // echo json_encode(["status" => "success", "message" => "Message.".$db->connect_error]);
        // return;
        if ($result->num_rows > 0) {
            // If the item already exists, update the quantity
            $row = $result->fetch_assoc();
            $newQuantity = $row['Quantity'] + $quantity;

            $update_sql = "UPDATE CartItems SET Quantity = ? WHERE CartItemID = ?";
            $update_stmt = $db->prepare($update_sql);
            $update_stmt->bind_param("ii", $newQuantity, $row['CartItemID']);
            $update_stmt->execute();
            $update_stmt->close();

            // error handling
            if ($db->error) {
                echo json_encode(["status" => "error", "message" => "Error: " . $db->error]);
                return;
            }
            
        } else {
            // If the item does not exist, insert a new row
            $insert_sql = "INSERT INTO CartItems (UserID, ProductID, Quantity, Size) VALUES (?, ?, ?, ?)";
            $insert_stmt = $db->prepare($insert_sql);
            $insert_stmt->bind_param("iiis", $userId, $productId, $quantity, $size);
            $insert_stmt->execute();
            $insert_stmt->close();
            // error handling
            if ($db->error) {
                echo json_encode(["status" => "error", "message" => "Error: " . $db->error]);
                return;
            }
        }

        // Return message
        echo json_encode(["status" => "success", "message" => "Item added to cart."]);

        // Close connection
        $db->close();
    } else {
        // Not all data was provided
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
    }
}
else {
    // Not sign in
    echo json_encode(["status" => "error", "message" => "Please log in or sign up first."]);
    return;
}
?>
