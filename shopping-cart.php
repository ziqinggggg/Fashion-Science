<?php     //shopping_cart.php
include 'constants.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>SHOPPING CART | Fashion Science</title>
<meta charset="utf-8">
<link rel="stylesheet" href="styling.css">
<style>
    #table-container {
        display: flex;
        padding: 22px;
    }
    .cart-item {
        border-collapse: collapse;
        margin-right:  40px;
        text-align: left;
    }
    .cart-item tr {
        border-bottom: 1px solid #000;
        position: relative;
    }
    .cart-item tr:last-child{
        border-bottom: none;
    }
    .cart-item td:last-child{
        width: 442px;
    }
    .cart-item td {
        padding: 15px;
    }
    .cart-item h3 a {
        text-decoration: none;
        color: #6E473F;
    }
    .cart-item img {
        width: 138px;
        height: 178px;
    }
    .quantity-control{
        cursor: pointer;
        width: 55px;
        height: 30px;
        font-size: 16px;
    }
    .remove-button {
        position: absolute;
        top: 0;
        right: 0;
        padding: 10px;
        cursor: pointer;
        height: 15px;
    }
    .item-total {
        position: absolute;
        bottom: 0;
        right: 0;
        padding: 15px;
    }

    button {
        cursor: pointer; 
        font-weight: bold;
        box-shadow: 0 3px 7px 0 rgba(0, 0, 0, .13), 0 1px 2px 0 rgba(0, 0, 0, .11);
    }

    .order-summary{
        height: max-content;
        position: sticky;
        top: 70px;
        z-index: 1;
        background-color: white;
    }

    .order-summary table{
        border-collapse: collapse;
        border: 1px solid #000;
        box-shadow: 0 3px 7px 0 rgba(0, 0, 0, .13), 0 1px 2px 0 rgba(0, 0, 0, .11);
    }
    .order-summary td {
        padding: 10px;
    }
    .order-summary td:nth-child(1) {
        text-align: left;
    }
    .order-summary td:nth-child(2) {
        text-align: right;
    }

    #checkout-button{
        width: 100%; 
        height: 40px;
        background-color: red;
        color: #fff;
        margin: 20px 0px 5px 0px;
    }

    #continue-shopping{
        width: 100%; 
        height: 40px;
        background-color: #000;
        color: #fff;
        margin: 5px 0px;
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
            <h2>Shopping Cart</h2>
            <?php
                if (isset($_SESSION['email'])) {
                    // User is logged in, fetch their cart items
                    $email = $_SESSION['email'];
                    
                    $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
                    
                    if ($db->connect_error) {
                        die("Connection failed: " . $db->connect_error);
                    }
                    
                    // Fetch the user's cart items from the database
                    $sql = "SELECT C.CartItemID, C.ProductID, P.ImageURL, P.ProductName, C.Size, P.Price, C.Quantity FROM CartItems C
                            JOIN Products P ON C.ProductID = P.ProductID
                            JOIN Users U ON C.UserID = U.UserID
                            WHERE U.Email = '$email'";
                    
                    $result = $db->query($sql);
                    
                    if ($result->num_rows > 0) {
                        echo '<div id="table-container"><table class="cart-item">';
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr class="product-details" id="' . $row['CartItemID'] . '">';
                            echo '<td><a href="product.php?id=' . $row['ProductID'] . '"><img src="' . $row['ImageURL'] . '" alt="Product Image"></a></td>';
                            echo '<td>';
                            echo '<h3><a href="product.php?id=' . $row['ProductID'] . '">' . $row['ProductName'] . '</a></h3><br>';
                            echo 'Size: ' . $row['Size'] . '<br>';
                            echo 'Price: $<span class="price">' . $row['Price'] . '</span><br><br>';
                            echo '<select size="1" name="quantity" class="quantity-control">';
                            for ($i = 1; $i <= 6; $i++) {
                                echo '<option value="' . $i . '"';
                                if ($i == $row['Quantity']) {
                                    echo ' selected';
                                }
                                echo '>' . $i . '</option>';
                            }
                            echo '</select>';
                            echo '<span class="item-total">Total: $<span class="item-total-price">' . ($row['Price'] * $row['Quantity']) . '</span></span>';
                            echo '<span class="remove-button" id="' . $row['CartItemID'] . '">&times;</span></td>';
                            echo '</tr>';
                        }
                        echo '</table>';

                        // Display the "Order Summary" section
                        echo '<div class="order-summary">';
                        echo '<h4>Order Summary</h4>';
                        echo '<table>';
                        echo '<tr>';
                        echo '<td>Subtotal: </td>';
                        echo '<td>$<span class="subtotal"></span></td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>Shipping: </td>';
                        echo '<td>Calculated at checkout</td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>Estimated Total:</td>';
                        echo '<td>$<span class="subtotal"></span></td>';
                        echo '</tr>';
                        echo '</table>';
                        echo '<a href="checkout.php"><button id="checkout-button">CHECKOUT</button></a>';
                        echo '<a href="index.php"><button id="continue-shopping">CONTINUE SHOPPING</button></a>';
                        echo '</div>';
                        echo '</div>';
                    } else {
                        echo '<h3>No items in your cart yet? <br>Start exploring our products and add your favorites right away!</h3>';
                    }
                    $db->close();
                } 
            ?>
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
            const tableContainer = document.querySelector(".cart-item");
            const quantityControls = document.querySelectorAll(".quantity-control");
            updateSubtotal();

            quantityControls.forEach((quantityControl) => {
                quantityControl.addEventListener("change", () => {
                    // Find the relevant elements in the same row
                    const row = quantityControl.parentElement.parentElement;
                    const itemTotalPrice = row.querySelector(".item-total-price");
                    const priceElement = row.querySelector(".price");

                    const newQuantity = parseInt(quantityControl.value, 10);
                    const price = parseFloat(priceElement.textContent);
                    const newTotalPrice = newQuantity * price;

                    // Update the item total price in the current row
                    itemTotalPrice.textContent = newTotalPrice.toFixed(2);

                    // Calculate and update the subtotal based on the item total prices
                    updateSubtotal();

                    const cartItemId = row.id;
                    updateQuantityInDatabase(cartItemId, newQuantity);
                });
            });

            // Function to update the subtotal
            function updateSubtotal() {
                const itemTotalPrices = document.querySelectorAll(".item-total-price");
                const subtotalElement = document.querySelectorAll(".subtotal");
                let subtotal = 0;

                itemTotalPrices.forEach((itemTotalPrice) => {
                    const price = parseFloat(itemTotalPrice.textContent);
                    subtotal += price;
                });
                subtotalElement.forEach((subtotalElement) => {
                    subtotalElement.textContent = subtotal.toFixed(2);
                });
            }

            function updateQuantityInDatabase(cartItemId, newQuantity) {
                // Create a new XMLHttpRequest object
                var xhr = new XMLHttpRequest();

                // Configure the request
                xhr.open("POST", "update_cart_quantity.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                // Define the data to be sent in the request
                var data = "Action=update&CartItemID=" + cartItemId + "&Quantity=" + newQuantity;

                // Set up a callback function to handle the response from the server
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = xhr.responseText;

                        if (response === "success") {
                            // The update was successful
                            console.log("Quantity updated successfully.");
                            // You can optionally update the UI to reflect the new quantity
                        } else {
                            console.log("Failed to update quantity.");
                        }
                    }
                };

                // Send the request with the data
                xhr.send(data);
            }

            // Attach click event listeners for the remove buttons (remove items from cart)
            const removeButtons = document.querySelectorAll(".remove-button");
            removeButtons.forEach((removeButton) => {
                removeButton.addEventListener("click", () => {
                    const cartItemId = removeButton.id;

                    // Display a confirmation alert before removing the item
                    if (window.confirm("Are you sure you want to remove this item from your cart?")) {
                        // Use AJAX to remove the cart item from the server's database
                        const xhrRemove = new XMLHttpRequest();
                        xhrRemove.open("POST", "update_cart_quantity.php", true);
                        xhrRemove.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                        // Set the action to 'remove' and provide the CartItemID
                        const data = "Action=remove&CartItemID=" + cartItemId;
                        xhrRemove.onreadystatechange = function () {
                            if (xhrRemove.readyState === 4 && xhrRemove.status === 200) {
                                if (xhrRemove.responseText === "success") {
                                    // Remove the corresponding row from the table
                                    const row = removeButton.parentElement.parentElement;
                                    row.style.display = "none";
                                    location.reload();
                                } else {
                                    alert("Failed to remove the item from the cart.");
                                }
                            }
                        };

                        xhrRemove.send(data);
                    }
                });
            });

        });
    </script>
</body>
</html>