<?php   // update_order_status.php
include 'constants.php';
session_start();

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    if (isset($_POST['Action'])) {
        $action = $_POST['Action'];

        if($action === 'received') {
            $sql = "UPDATE Orders SET Status = 'Review' WHERE Status = 'Delivered' AND UserID = (SELECT UserID FROM Users WHERE Email = ?)";

            $stmt = $db->prepare($sql);
            $stmt->bind_param("s", $email);

            if ($stmt->execute()) {
                echo "Order status updated successfully!";
            } else {
                echo "Failed to update order status.";
            }
            $stmt->close();

        }   elseif ($action === 'cancel') {

            $sql = "UPDATE Orders SET Status = 'Canceled' WHERE Status = 'Pending' AND UserID = (SELECT UserID FROM Users WHERE Email = ?)";

            $stmt = $db->prepare($sql);
            $stmt->bind_param("s", $email);

            if ($stmt->execute()) {
                echo "Order successfully canceled!";
            } else {
                echo "Failed to cancel order.";
            }
            $stmt->close();
        }
    }   
    if (isset($_POST['submit-review'])) {

        $orderIDs = $_POST['orderID'];
        $ratings = $_POST['ratings'];
        $comments = $_POST['comments'];
        $productIDs = $_POST['productID'];
        $userIDs = $_POST['userID'];
        $reviewDate = date('Y-m-d H:i:s');
    
        $success = true;
        $ratingsSet = true;
    
        for ($i = 0; $i < count($orderIDs); $i++) {
            $rating = $ratings[$i];
            if (!isset($rating) || $rating === '') {
                // If rating is not set or empty, set $ratingsSet to false
                $ratingsSet = false;
                echo "<script>alert('Rating is required. Please submit your review with a rating.');</script>";
                echo "<script>window.location = 'myorders.php';</script>";
                break;
            }
        }
        if ($ratingsSet) {
            for ($i = 0; $i < count($orderIDs); $i++) {
            // Loop through the reviews
                $ratingsSet = true;
                $orderID = $orderIDs[$i];
                $rating = $ratings[$i];
                $comment = $comments[$i];
                $productID = $productIDs[$i];
                $userID = $userIDs[$i];
                $photoFileName = '';
    
                if (isset($_FILES['photo']['tmp_name'][$i])) {
                    // Check if a photo was uploaded for this review
                    $photoTmpName = $_FILES['photo']['tmp_name'][$i];
                    $uploadDir = 'uploads/';
                    $photoFileName = $uploadDir . basename($_FILES['photo']['name'][$i]);
                }
    
                // Insert the review into the database
                // Use prepared statements to prevent SQL injection
                if (!empty($photoFileName)) {
                    $insertReviewSQL = "INSERT INTO Reviews (OrderID, ProductID, UserID, Rating, Comment, Date, Photo) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $db->prepare($insertReviewSQL);
                    $stmt->bind_param("iiiisss", $orderID, $productID, $userID, $rating, $comment, $reviewDate, $photoFileName);
                } else {
                    $insertReviewSQL = "INSERT INTO Reviews (OrderID, ProductID, UserID, Rating, Comment, Date) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $db->prepare($insertReviewSQL);
                    $stmt->bind_param("iiiiss", $orderID, $productID, $userID, $rating, $comment, $reviewDate);
                }
    
                if ($stmt->execute()) {
                    // Update the corresponding order status to 'completed' for each item
                    $updateOrderSQL = "UPDATE Orders SET Status = 'Completed' WHERE OrderID = ?";
                    $stmt = $db->prepare($updateOrderSQL);
                    $stmt->bind_param("i", $orderID);
                    if (!$stmt->execute()) {
                        $success = false; // Mark operation as unsuccessful
                    }
                } else {
                    $success = false; // Mark operation as unsuccessful
                }
            }
            
        }
        if ($success) {
            echo "<script>alert('Reviews submitted successfully.');</script>";
            echo "<script>window.location ='myorders.php';</script>";
        } else {
            echo "<script>alert('Failed to submit some reviews. Please try again.');</script>";
        }
    
    }
        


    $db->close();
}
?>
