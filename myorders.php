<?php     //myorders.php
include 'constants.php';
session_start();

$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>MY ORDERS | Fashion Science</title>
<meta charset="utf-8">
<link rel="stylesheet" href="styling.css">
<style>

    button {
        cursor: pointer; 
        font-weight: bold;
    }

    .navigation button{ 
        background: none;
        border: none;
        text-decoration: underline;
        margin: 0 10px;
    }
    /* styling the tables */
    #table-container {
        text-align: center;
    }
    #table-container img{ 
        width: 138px;
        height: 178px;
    }
    #table-container table { 
        border-collapse: collapse;
        display: inline-block;
        border: 1px solid #b5b5b5;
        text-align: left;
        width: 750px;
        box-shadow: 0 3px 7px 0 rgba(0, 0, 0, .13), 0 1px 2px 0 rgba(0, 0, 0, .11);
    }
    #table-container table td {
        padding: 15px; 
    }
    #table-container table td:nth-child(2){
        width: 532px;
        padding: 15px 30px 15px 15px; 
        position: relative;
    }
    #table-container table tr:last-child td{
        border-top: 1px solid #b5b5b5;
        text-align: right;
        padding-right: 30px;
    }
    #table-container a {
        text-decoration: none;
        color: #6E473F;
    }
    .item-total {
        position: absolute;
        bottom: 0;
        right: 0;
        padding: 15px 30px;
    }
    .manage-order-button { 
        background: none;
        margin-top: 10px;
        font-size: medium;
        padding: 3px;
        width: 150px;
        height: 35px;
        border: 1px solid #000;
    }
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 2;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 5px;
        box-shadow: 0px 0px 10px 0px #000;
        width: 650px;
        max-height: 80%;
        overflow-y: auto;
        text-align: center;
        position: relative;
    }
    .item-to-review {
        padding:10px;
    }
    .modal-content img {
        margin: 20px;
        width: 103px;
        height: 133px;
        float: left;
    }
    .modal-content p, .ratings {
        text-align: left;
        margin-left: 150px;
    }
    .ratings {
        width: max-content;
    }
    .modal-content hr {
        border-color: #ffcba4;
    }
    .item-to-review:last-of-type hr {
        display: none;
    }
    .modal-content textarea {
        margin: 30px 0 0 0;
        resize: none;
        width: 580px;
        height: 80px;
        font-family: Verdana, Arial, sans-serif;
        font-size: medium;
        box-shadow: #000;
    }
    input[type="file"] {
        text-align: right;
        font-size: 16px;
        padding: 10px;
        float: left;
        cursor: pointer;
    }
    .submit-button {
        width: 40%; 
        height: 40px;
        background: none;
        font-size: 18px;
    }
    .close-button {
        position: absolute;
        top: 0;
        right: 0;
        padding: 10px;
        cursor: pointer;
    }
    .star-button{
        font-size: 50px;
        height: 60px;
        color: orange;
        cursor: pointer;
    }

</style> 
<script type="text/javascript" src="common.js"></script>
</head>
<body>
    <div id="wrapper">
        <header>
            <a href="index.php"><img src="logo.jpg" height="100" width="311"></a>
        </header>
        <nav>
        <div id="left-nav">
                <a class="active" href="index.php">What's New</a>
                <a href="product_page.php">All Product</a>
            </div>
            <div id="right-nav">
                <img src="login.jpg" id="profile-icon" onclick="showDropdown()" width="46" height="40">
                    <div id="dropdown">
                        <a href="myorders.php">My Order</a>
                        <a href="#" onclick="signOut()">Sign Out</a>
                    </div>
                <img src="shopping-cart.jpg" onclick="showCart()" width="48" height="40">
            </div>

        </nav>
        <div id="content">
            <h2>My Orders</h2>
            <div class="navigation">
                <small>Filter:</small>
                <button class="filter-button" id="all-button" data-filter="all">All</button>
                <button class="filter-button" id="pending-button" data-filter="pending">Pending</button>
                <button class="filter-button" id="shipped-button" data-filter="shipped">Shipped</button>
                <button class="filter-button" id="delivered-button" data-filter="delivered">Delivered</button>
                <button class="filter-button" id="to-review-button" data-filter="review">To Review</button>
                <button class="filter-button" id="completed-button" data-filter="completed">Completed</button>
                <button class="filter-button" id="canceled-orders-button" data-filter="canceled">Canceled</button>
            </div>
            <br>
            <div id="table-container">
                <?php
                    if (isset($_SESSION['email'])) {
                        $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

                        if ($db->connect_error) {
                            die("Connection failed: " . $db->connect_error);
                        }
                        
                        $email = $_SESSION['email'];
                        $statuses = array("Pending", "Shipped", "Delivered", "Review", "Completed", "Canceled");

                        foreach ($statuses as $status) {
                            $sql = "SELECT I.OrderItemID, I.ProductID, P.ImageURL, P.ProductName, I.Size, I.Price, I.Quantity, O.Status, O.TotalAmount, O.OrderDate, O.OrderID FROM OrderItems I
                                    JOIN Products P ON I.ProductID = P.ProductID
                                    JOIN Orders O ON I.OrderID = O.OrderID
                                    JOIN Users U ON O.UserID = U.UserID
                                    WHERE U.Email = '$email' AND O.Status = '$status'";
                            
                            $result = $db->query($sql);
                            
                            if ($result->num_rows > 0) {
                                echo '<div class="order-table" id="' . strtolower($status) . '-orders">';
                                echo "<h3>" . ($status === 'Review' ? "Orders to Review" : "$status orders") . "</h3>";
                                echo '<table>';
                                $totalPrice = 0;
                                $currentDate = new DateTime();
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td><a href="product.php?id=' . $row['ProductID'] . '"><img src="' . $row['ImageURL'] . '" alt="Product Image"></a></td>';
                                    echo '<td><h3><a href="product.php?id=' . $row['ProductID'] . '">' . $row['ProductName'] . '</a></h3><br>';
                                    echo 'Size: ' . $row['Size'] . '<br>';
                                    echo 'Price: $<span class="item-price">' . $row['Price'] . '</span><br>';
                                    echo 'Quantity: <span class="quantity">' . $row['Quantity'] . '</span><br><br><br><br>';
                                    echo '<span class="item-total">Total: $' . ($row['Price'] * $row['Quantity']) . '</span></td>';
                                    echo '</tr>';
                                    $totalPrice += ($row['Price'] * $row['Quantity']);
                                    $OrderDate = new DateTime($row['OrderDate']);
                                }
                                echo '<tr><td colspan="3">Total Price: $<span class="total-price">' . $totalPrice . '</span>';
                                
                                if ($status === 'Delivered') {
                                    echo '<br><button class="manage-order-button" id="received-button">Received</button>';
                                } elseif ($status === 'Review') {
                                    echo '<br><button  class="manage-order-button" id="rate-button">Rate</button>';
                                } elseif ($status === 'Pending' && ($currentDate->getTimestamp() - $OrderDate->getTimestamp()) < (24 * 3600)) {
                                    echo '<br><button  class="manage-order-button" id="cancel-button">Cancel</button>';
                                }

                                echo '</td></tr></table>';
                                echo '<br><br></div>';
                            } else {
                                echo '<div style="display:none" class="no-orders" id="no-' . strtolower($status) . '-msg">';
                                echo "<br><h3>You have no " . ($status === 'Review' ? "orders to review" : strtolower($status) . " orders") . "</h3><br><br></div>";
                            }
                        }
                        $db->close();
                    }
                    ?>
            </div>
            <div id="rateModal" class="modal">
                <div class="modal-content">
                    <span class="close-button" id="closeModal">&times;</span>
                    <form method="post" action="update_order_status.php">
                        <h3>Rate Your Order</h3>
                        <?php
                            if (isset($_SESSION['email'])) {
                                $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

                                if ($db->connect_error) {
                                    die("Connection failed: " . $db->connect_error);
                                }

                                $sql = "SELECT I.OrderItemID, I.ProductID, P.ImageURL, P.ProductName, O.Status, O.TotalAmount, O.OrderID, O.UserID FROM OrderItems I
                                        JOIN Products P ON I.ProductID = P.ProductID
                                        JOIN Orders O ON I.OrderID = O.OrderID
                                        JOIN Users U ON O.UserID = U.UserID
                                        WHERE U.Email = '$email' AND O.Status = 'Review'";

                                $result = $db->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<input type="hidden" name="orderID[]" value="' . $row['OrderID'] . '">';
                                        echo '<input type="hidden" name="productID[]" value="' . $row['ProductID'] . '">';
                                        echo '<input type="hidden" name="userID[]" value="' . $row['UserID'] . '">';
                                        echo '<div class="item-to-review" id="' . $row['ProductID'] . '">';
                                        echo '<img src="' . $row['ImageURL'] . '" alt="Product Image">';
                                        echo '<p>' . $row['ProductName'] . '</p>';
                                        echo '<div class="ratings" id="ratings-' . $row['ProductID'] . '">';
                                        echo '<input type="hidden" name="ratings[]" id="ratingvalue-' . $row['ProductID'] . '">';
                                        echo '<span class="star-button" id="star-1-' . $row['ProductID'] . '" name="1">&star;</span>';
                                        echo '<span class="star-button" id="star-2-' . $row['ProductID'] . '" name="2">&star;</span>';
                                        echo '<span class="star-button" id="star-3-' . $row['ProductID'] . '" name="3">&star;</span>';
                                        echo '<span class="star-button" id="star-4-' . $row['ProductID'] . '" name="4">&star;</span>';
                                        echo '<span class="star-button" id="star-5-' . $row['ProductID'] . '" name="5">&star;</span>';
                                        echo '</div>';
                                        echo '<textarea type="textarea" name="comments[]" placeholder="Enter your comments here "></textarea>';
                                        echo '<input type="file" name="photo[]" accept="image/*" class="upload-photo"><br><br><br><br>';
                                        echo '<hr></div>';
                                    }
                                }
                                $db->close();
                            }
                        ?>
                        <button type="submit" class="submit-button" name="submit-review">Submit</button> 
                    </form>
                </div>
            </div>
        </div>
        <footer>
            <small>
                <i>
                Copyright &copy; 2023 Fashion Science<br>
                <a href="mailto:support@fashionscience.com">support@fashionscience.com</a>
                <br><br>
                </i>
                <a href="index.php">Home</a> | <a href="about_us.php">About Us</a> | <a href="faq.php">FAQ</a>
            </small>
        </footer>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const filters = document.querySelectorAll('.filter-button');
            const tables = document.querySelectorAll('.order-table');
            const noMessages = document.querySelectorAll('.no-orders');

            filters.forEach(filter => {
                filter.addEventListener('click', () => {
                    const filterType = filter.getAttribute('data-filter');
                    tables.forEach(table => {
                        if (filterType === 'all' || table.id.includes(filterType)) {
                            table.style.display = 'block';
                        } else {
                            table.style.display = 'none';
                        }
                    });
                    noMessages.forEach(noMessage => {
                        if (noMessage.id.includes(filterType)) {
                            noMessage.style.display = 'block';
                        } else {
                            noMessage.style.display = 'none';
                        }
                    });
                });
            });

            const receivedButton = document.getElementById('received-button');
            if (receivedButton) {
                receivedButton.addEventListener("click", () => {
                    // Display a confirmation alert
                    const confirmed = confirm("Are you sure you want to mark this order as received?");
                    if (confirmed) {
                        // User confirmed, send an AJAX request to update the order status
                        const xhr = new XMLHttpRequest();
                        xhr.open("POST", "update_order_status.php", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        var data = "Action=received";
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                alert(xhr.responseText); 
                                location.reload();
                            }
                        };
                        xhr.send(data);
                    } else {
                        // User canceled, do nothing or provide feedback
                        alert("Order status update canceled.");
                    }
                });
            }

            const cancelButton = document.getElementById('cancel-button');
            if (cancelButton) {     // change order status from 'Pending' to 'Canceled'
                cancelButton.addEventListener("click", () => {
                    // Display a confirmation alert
                    const confirmed = confirm("Are you sure you want to cancel this order?");
                    
                    if (confirmed) {
                        // User confirmed, send an AJAX request to update the order status
                        const xhr = new XMLHttpRequest();
                        xhr.open("POST", "update_order_status.php", true);
                        var data = "Action=cancel";
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                alert(xhr.responseText); 
                                location.reload();
                            }
                        };
                        xhr.send(data);
                    } else {
                        // User canceled, do nothing or provide feedback
                        alert("Order status update canceled.");
                    }
                });
            }

            const rateModal = document.getElementById("rateModal");
            const closeModal = document.getElementById("closeModal");
            const rateButton = document.getElementById("rate-button");

            if (rateButton) {
                // When the user clicks the "Rate" button, show the modal
                rateButton.addEventListener("click", () => {
                    rateModal.style.display = "flex";
                });
            }

            // When the user clicks the close button or outside the modal, hide the modal
            if (closeModal) {
                // When the user clicks the "Rate" button, show the modal
                closeModal.addEventListener("click", () => {
                    rateModal.style.display = "none";
                });
            }

            window.addEventListener("click", (event) => {
                if (event.target === rateModal) {
                    rateModal.style.display = "none";
                }
            });

            const starButtons = document.querySelectorAll('.star-button');
            let selectedStars = {};

            starButtons.forEach((button) => {
                button.addEventListener('mouseover', () => {
                    const [_, rating, productID] = button.id.split('-'); // Split the button's ID to get the productID and rating
                    highlightStars(productID, rating);
                });

                button.addEventListener('mouseout', () => {
                    const [_, rating, productID] = button.id.split('-'); // Split the button's ID to get the productID and rating
                    resetStars(productID);
                });

                button.addEventListener('click', () => {
                    const [_, rating, productID] = button.id.split('-'); // Split the button's ID to get the productID and rating
                    selectStars(productID, rating);
                });
            });

            function highlightStars(productID, rating) {
                for (let i = 1; i <= rating; i++) {
                    document.getElementById(`star-${i}-${productID}`).innerHTML = '&#9733;';
                }
            }

            function resetStars(productID) {
                const maxRating = selectedStars[productID] || 0;
                for (let i = 1; i <= 5; i++) {
                    if (i > maxRating) {
                        document.getElementById(`star-${i}-${productID}`).innerHTML = '&#9734;';
                    }
                }
            }

            function selectStars(productID, rating) {
                selectedStars[productID] = rating;
                document.getElementById(`ratingvalue-${productID}`).value = rating;
                for (let i = 1; i <= rating; i++) {
                    document.getElementById(`star-${i}-${productID}`).innerHTML = '&#9733;';
                }
            }
        });
        
    </script>
</body>
</html>