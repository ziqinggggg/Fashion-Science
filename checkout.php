<?php     //checkout.php
include 'constants.php';
session_start();

$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// After verifying that the user is logged in, retrieve the address information
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Fetch the user's address from the database
    $addressQuery = "SELECT FirstName, LastName, Address, AddressDetails, PostalCode, ContactNumber 
                     FROM ShippingInformation 
                     WHERE UserID = (SELECT UserID FROM Users WHERE Email = '$email')";
    $addressResult = $db->query($addressQuery);

    if ($addressResult->num_rows > 0) {
        $addressData = $addressResult->fetch_assoc();
        extract($addressData); // Extract variables from the array
    }
        // Fetch the user's payment information from the database
    $paymentQuery = "SELECT CardNumber, ExpiryDate, SecurityCode, FullName 
                    FROM PaymentInformation 
                    WHERE UserID = (SELECT UserID FROM Users WHERE Email = '$email')";
    $paymentResult = $db->query($paymentQuery);

    if ($paymentResult->num_rows > 0) {
        $paymentData = $paymentResult->fetch_assoc();
        extract($paymentData); // Extract variables from the array
    }

    // Fetch cart items and calculate subtotal
    $cartQuery = "SELECT C.CartItemID, C.ProductID, C.Quantity, P.Price 
                    FROM CartItems C
                    JOIN Products P ON C.ProductID = P.ProductID
                    JOIN Users U ON C.UserID = U.UserID
                    WHERE U.Email = '$email'";
    $cartResult = $db->query($cartQuery);

    $subtotal = 0;
    while ($cartRow = $cartResult->fetch_assoc()) {
        $subtotal += $cartRow['Quantity'] * $cartRow['Price'];
    }
}

if (isset($_POST['place-order'])) {
    // Get form data
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $streetAddress = $_POST['street-address'];
    $addressDetails = $_POST['address-details'];
    $postalCode = $_POST['postal-code'];
    $contactNumber = $_POST['contact'];

    // Save or update the user's address information in the database
    $sql = "INSERT INTO ShippingInformation (UserID, FirstName, LastName, Address, AddressDetails, PostalCode, ContactNumber)
    VALUES ((SELECT UserID FROM Users WHERE Email = ?), ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE FirstName = VALUES(FirstName), LastName = VALUES(LastName), Address = VALUES(Address), AddressDetails = VALUES(AddressDetails), PostalCode = VALUES(PostalCode), ContactNumber = VALUES(ContactNumber)";

    if ($stmt = $db->prepare($sql)) {
        $stmt->bind_param("sssssii", $email, $firstName, $lastName, $streetAddress, $addressDetails, $postalCode, $contactNumber);

        if ($stmt->execute()) {
            // Address information saved successfully
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: " . $db->error;
    }

    $cardNumber = $_POST['card-number'];
    $expiryDate = $_POST['expiration-date'];
    $securityCode = $_POST['security-code'];
    $fullName = $_POST['full-name'];
    $savePaymentInfo = isset($_POST['save-payment-info']) ? 1 : 0;

    if ($savePaymentInfo) {
            // Save or update the user's payment information in the database
        $sql = "INSERT INTO PaymentInformation (UserID, CardNumber, ExpiryDate, SecurityCode, FullName)
        VALUES ((SELECT UserID FROM Users WHERE Email = ?), ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE CardNumber = VALUES(CardNumber), ExpiryDate = VALUES(ExpiryDate), SecurityCode = VALUES(SecurityCode), FullName = VALUES(FullName)";

        if ($stmt = $db->prepare($sql)) {
            $stmt->bind_param("sssis", $email, $cardNumber, $expiryDate, $securityCode, $fullName);

            if ($stmt->execute()) {
                // Payment information saved successfully
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error: " . $db->error;
        }
    }

    $userIdQuery = "SELECT UserID FROM Users WHERE Email = '$email'";
    $userIdResult = $db->query($userIdQuery);

    if ($userIdResult->num_rows > 0) {
        $userIdData = $userIdResult->fetch_assoc();
        $userId = $userIdData['UserID'];

        // Insert a new row into the Orders table
        $orderDate = date('Y-m-d H:i:s');
        $status = "Pending";
        $totalAmount = $subtotal;

        $insertOrderQuery = "INSERT INTO Orders (UserID, OrderDate, Status, TotalAmount) 
                            VALUES ($userId, '$orderDate', '$status', $totalAmount)";
        if ($db->query($insertOrderQuery) === TRUE) {
            // Get the ID of the newly inserted order
            $orderId = $db->insert_id;

            // Retrieve the user's cart items
            $cartItemsQuery = "SELECT ProductID, Quantity, Size FROM CartItems WHERE UserID = $userId";
            $cartItemsResult = $db->query($cartItemsQuery);

            if ($cartItemsResult->num_rows > 0) {
                while ($cartItemData = $cartItemsResult->fetch_assoc()) {
                    // Insert cart item into the OrderItems table
                    $productId = $cartItemData['ProductID'];
                    $quantity = $cartItemData['Quantity'];
                    $size = $cartItemData['Size'];
                    
                    // Fetch the price based on the product ID
                    $priceQuery = "SELECT Price FROM Products WHERE ProductID = $productId";
                    $priceResult = $db->query($priceQuery);
                    
                    if ($priceResult->num_rows > 0) {
                        $priceData = $priceResult->fetch_assoc();
                        $price = $priceData['Price'];
                    } else {
                        // Handle the case where the product price is not found
                        $price = 0;
                    }

                    $insertOrderItemQuery = "INSERT INTO OrderItems (OrderID, ProductID, Quantity, Price, Size) 
                                            VALUES ($orderId, $productId, $quantity, $price, '$size')";
                    $db->query($insertOrderItemQuery);
                }
            }

            // Delete the cart items
            $deleteCartItemsQuery = "DELETE FROM CartItems WHERE UserID = $userId";
            $db->query($deleteCartItemsQuery);
            echo "<script>alert('We appreciate your order! It\\'s on its way to being processed.'); window.location = 'index.php';</script>";
        } else {
            echo "Error creating the order: " . $db->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>CHECKOUT | Fashion Science</title>
<meta charset="utf-8">
<link rel="stylesheet" href="styling.css">
<style>
    #table-container {
        text-align: left; 
        padding: 40px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    button {
        cursor: pointer; 
        font-weight: bold;
    }
    .address, .payment, .order-summary {
        width: 590px;
        border: 1px solid #000;
        padding: 15px;
        box-shadow: 0 3px 7px 0 rgba(0, 0, 0, .13), 0 1px 2px 0 rgba(0, 0, 0, .11);
    }
    .address h4, .payment h4, .order-summary h4 {
        padding: 0 6px;
    }
    .address th, .payment th {
        padding: 20px 6px;
    }
    #table-container input[type="text"]{
        height: 30px; 
        background-color: #eeeeee;
        border: none;
        border-bottom: 1px solid #000;
        outline: none;
    }
    #table-container input[type="text"]:focus {
        border: 1px solid #87563148;
        border-bottom: 3px solid #875631;
        background-color: #fff;
    }
    th.invalid {
        color: red;
    }
    #table-container input.invalid[type="text"]{
        border-bottom: 4px solid red;
    }
    #table-container input.invalid[type="text"]:focus{
        border: 1px solid #ff000048;
        border-bottom: 4px solid red;
    }
    input.invalid::placeholder {
        color: rgb(204, 0, 0);
    }
    #continue-payment-button, #continue-button, #place-order { 
        width: 60%; 
        height: 40px;
        background: none;
        font-size: 16px;
        margin: 15px 10px;
        box-shadow: 0 3px 7px 0 rgba(0, 0, 0, .13), 0 1px 2px 0 rgba(0, 0, 0, .11);
    }
    .address-filled, .payment-filled{
        display: none;
        width: 580px;
        border-collapse: collapse;
        border: 1px solid #000;
        padding: 20px;
        position: relative;
        box-shadow: 0 3px 7px 0 rgba(0, 0, 0, .13), 0 1px 2px 0 rgba(0, 0, 0, .11);
    }
    .edit-button {
        position: absolute;
        right: 20px;
        text-decoration: underline;
        cursor: pointer;
    }
    .payment, .order-summary {
        display: none;
    }
    .payment-notfilled, .order-summary-hidden{
        background-color: #f4f4f4;
        color: #ababab;
        width: 580px;
        border-collapse: collapse;
        border: 1px solid #000;
        padding:20px;
        box-shadow: 0 3px 7px 0 rgba(0, 0, 0, .13), 0 1px 2px 0 rgba(0, 0, 0, .11);
    }
    .order-summary table{
        width: 590px;
    }
    .order-summary td {
        padding: 15px 6px;
    }
    .order-summary td:nth-child(2) {
        text-align: right;
    }
    .order-summary tr:nth-child(3) td {
        padding: 0;
    }
    #place-order { 
        width:60% ;height: 40px;
        background: red;
        color:#fff;
    }
    .summary-table {
        margin-left: 40px;
        text-align: center;
        width: 356px;
        text-align: center;
        box-sizing: border-box;
        position: sticky;
        top: 70px;
        z-index: 1;
    }
    .summary-table table{
        width: 356px;
        border-collapse: collapse;
        border: 1px solid #000;
        box-shadow: 0 3px 7px 0 rgba(0, 0, 0, .13), 0 1px 2px 0 rgba(0, 0, 0, .11);
    }
    .summary-table td {
        padding: 10px;
    }
    .summary-table td:nth-child(1) {
        text-align: left;
    }
    .summary-table td:nth-child(2) {
        text-align: right;
    }

    #ordered-items-table {
        border: 1px solid #000;
        max-height: 400px;
        overflow-y: auto; /* Add vertical scrollbar if necessary */
        display: flex;
        flex-wrap: wrap; /* Allow items to wrap to the next line */
        justify-content: flex-start;
        padding: 10px;
        box-shadow: 0 3px 7px 0 rgba(0, 0, 0, .13), 0 1px 2px 0 rgba(0, 0, 0, .11);
    }
    #ordered-items-table img{
        width: 83px;
        height: 106px; 
        margin: 5px 4px;
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
            <h2>Checkout</h2>
            <div id="table-container">
                <form method="post">
                    <div class="address">
                        <h4>DELIVERY OPTIONS</h4>
                        <table>
                            <tr>
                                <th class="input-label" for="input-firstname">FIRST NAME*</th>
                                <td><input type="text" name="firstname" id="input-firstname" value="<?php echo isset($FirstName) ? $FirstName : ''; ?>" onblur="validateFirstName()" size=50 required placeholder="Please enter your first name."></td>
                            </tr>
                            <tr>
                                <th class="input-label" for="input-lastname">LAST NAME*</th>
                                <td><input type="text" name="lastname" id="input-lastname" value="<?php echo isset($LastName) ? $LastName : ''; ?>" onblur="validateLastName()" size=50 required placeholder="Please enter your last name."></td>
                            </tr>
                            <tr>
                                <th class="input-label" for="input-street-address">STREET ADDRESS*</th>
                                <td><input type="text" name="street-address" id="input-street-address" value="<?php echo isset($Address) ? $Address : ''; ?>" onblur="validateStreetAddress()" size=50 required placeholder="Please enter your street address."></td>
                            </tr>
                            <tr>
                                <th class="input-label" for="input-address-details">ADDRESS DETAILS</th>
                                <td><input type="text" name="address-details" id="input-address-details" value="<?php echo isset($AddressDetails) ? $AddressDetails : ''; ?>" size=50 placeholder="Building name, block number and unit number."></td>
                            </tr>
                            <tr>
                                <th class="input-label" style="color: gray;">CITY*</th>
                                <td><input type="text" id="input-city" size=50 value="Singapore" disabled></td>
                            </tr>
                            <tr>
                                <th class="input-label" for="input-postal-code">POSTAL CODE*</th>
                                <td><input type="text" name="postal-code" id="input-postal-code" value="<?php echo isset($PostalCode) ? $PostalCode : ''; ?>" onblur="validatePostalCode()" size=50 required maxlength="6" placeholder="Please enter postal code."></td>
                            </tr>
                            <tr>
                                <th class="input-label" for="input-contact">CONTACT NUMBER*</th>
                                <td><input type="text" name="contact" id="input-contact" value="<?php echo isset($ContactNumber) ? $ContactNumber : ''; ?>" onblur="validateContactNumber()" size=50 required maxlength="8" placeholder="Please enter your contact number (Example. 81234567)."></td>
                            </tr>
                        </table>
                        <button type="button" id="continue-payment-button">CONTINUE TO PAYMENT</button>
                    </div>
                    <div class="address-filled">
                        <span class="edit-button" id="edit-address">EDIT</span>
                        <b>DELIVERY OPTIONS (Completed)</b>
                        <p id="name"></p>
                        <p id="street"></p>
                        <p id="address-details"></p>
                        <p id="postcode"></p>
                        <p id="contact"></p>
                        <hr>
                        <b>DELIVERY DATE</b>
                        <p>Estimated delivery date: <span id="estimated-date"></span></p>
                    </div>
                    <br><br>
                    <div class="payment-notfilled">
                        <b>PAYMENT OPTIONS</b>
                    </div>

                    <div class="payment">
                        <h4>PAYMENT OPTIONS</h4>
                        <table>
                            <tr>
                                <th class="input-label" for="input-card-number">CARD NUMBER*</th>
                                <td><input type="text" name="card-number" id="input-card-number" value="<?php echo isset($CardNumber) ? $CardNumber : ''; ?>" onblur="validateCardNumber()" oninput="formatCardNumber()" size=50 maxlength="16" required ></td>
                            </tr>
                            <tr>
                                <th class="input-label" for="expiration-date">EXPIRATION DATE*</td>
                                <td><input type="text" name="expiration-date" id="expiration-date" value="<?php echo isset($ExpiryDate) ? $ExpiryDate : ''; ?>" onblur="validateExpDate()" size=50 maxlength="5" required placeholder="MM/YY"></td>
                            </tr>
                            <tr>
                                <th class="input-label" for="security-code">SECURITY CODE*</th>
                                <td><input type="text" name="security-code" id="security-code" value="<?php echo isset($SecurityCode) ? $SecurityCode : ''; ?>" onblur="validateSecurityCode()" size=50 maxlength="3" required placeholder="CVV"></td>
                            </tr>
                            <tr>
                                <th class="input-label" for="input-full-name">FULL NAME*</th>
                                <td><input type="text" name="full-name" id="input-full-name" value="<?php echo isset($FullName) ? $FullName : ''; ?>" onblur="validateFullName()" size=50></td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="checkbox" name="save-payment-info" value="save">Save or update card information</td>
                            </tr>
                        </table>
                        <button type="button" id="continue-button">CONTINUE</button>
                    </div>
                    <div class="payment-filled">
                        <span class="edit-button" id="edit-payment">EDIT</span>
                        <b>PAYMENT OPTIONS (Completed)</b>
                        <p>CREDIT/DEBIT CARD</p>
                        <p id="card-number"></p>
                        <p id="full-name"></p>
                    </div>
                    <br><br>
                    <div class="order-summary-hidden">
                        <b>ORDER SUMMARY</b>
                    </div>
                    <div class="order-summary">
                        <h4>ORDER SUMMARY</h4>
                        <table>
                            <tr>
                                <td>Items subtotal</td>
                                <td>$<?php echo number_format($subtotal, 2); ?></td>
                            </tr>
                            <tr>
                                <td>Shipping</td>
                                <td>$<?php echo ($subtotal < 50) ? 5.00 : 0.00; ?></td>                            
                            </tr>
                            <tr>
                                <td colspan="2"><hr></td>
                            </tr>
                            <tr>
                                <td><b>SUBTOTAL</b></td>
                                <td><b>$<?php echo number_format(($subtotal + ($subtotal < 50 ? 5.00 : 0.00)), 2); ?></b></td>
                            </tr>
                        </table>
                        <button type="submit" name="place-order" id="place-order">PLACE ORDER</button>
                    </div>
                </form>
                <div class="summary-table">
                    <h4>Order Summary</h4>
                    <table>
                        <tr>
                            <td>Subtotal: </td>
                            <td>$<?php echo number_format($subtotal, 2); ?></td>
                        </tr>
                        <tr>
                            <td>Shipping: </td>
                            <td>$<?php echo ($subtotal < 50) ? 5.00 : 0.00; ?></td>
                        </tr>
                        <tr>
                            <td>Estimated Total:</td>
                            <td>$<?php echo number_format(($subtotal + ($subtotal < 50 ? 5.00 : 0.00)), 2); ?></td>
                        </tr>
                    </table>
                    <h4>Ordered Item(s)</h4>
                    <div id=ordered-items-table>
                        <?php
                            $sql = "SELECT C.CartItemID, C.ProductID, P.ImageURL, C.Quantity FROM CartItems C
                                    JOIN Products P ON C.ProductID = P.ProductID
                                    JOIN Users U ON C.UserID = U.UserID
                                    WHERE U.Email = '$email'";
                            
                            $result = $db->query($sql);

                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="ordered-item">';
                                echo '<a href="product.php?id=' . $row['ProductID'] . '"><img src="' . $row['ImageURL'] . '" alt="Product Image"></a><span>x' . $row['Quantity'] . '</span>' ;
                                echo '</div>';
                            }
                        ?>
                    </div>
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
        const continuePaymentButton = document.getElementById("continue-payment-button");
        const continueButton = document.getElementById("continue-button");
        const placeOrderButton = document.getElementById("place-order");
        const editAddressButton = document.getElementById("edit-address");
        const editPaymentButton = document.getElementById("edit-payment");

        continuePaymentButton.addEventListener("click", () => {
            if (validateFirstName() && validateLastName() && validateStreetAddress() && validatePostalCode() && validateContactNumber()) {
                document.getElementById("name").innerText = document.getElementById("input-firstname").value + " " + document.getElementById("input-lastname").value;
                document.getElementById("street").innerText = document.getElementById("input-street-address").value;
                document.getElementById("address-details").innerText = document.getElementById("input-address-details").value;
                document.getElementById("postcode").innerText = document.getElementById("input-city").value + " " + document.getElementById("input-postal-code").value;
                document.getElementById("contact").innerText = document.getElementById("input-contact").value;

                calculateEstimatedDeliveryDate();

                document.querySelector(".address").style.display = "none";
                document.querySelector(".address-filled").style.display = "block";
                document.querySelector(".payment").style.display = "block";
                document.querySelector(".payment-notfilled").style.display = "none";
            }
        });

        function calculateEstimatedDeliveryDate() {
            const currentDate = new Date();

            // Calculate the earliest date (current date + 5 days)
            const earliestDate = new Date(currentDate);
            earliestDate.setDate(currentDate.getDate() + 5);

            // Calculate the latest date (current date + 10 days)
            const latestDate = new Date(currentDate);
            latestDate.setDate(currentDate.getDate() + 10);

            // Format the dates (e.g., "06 June 2023")
            const options = { day: '2-digit', month: 'long', year: 'numeric' };
            const earliestDateFormatted = earliestDate.toLocaleDateString('en-GB', options);
            const latestDateFormatted = latestDate.toLocaleDateString('en-GB', options);

            document.getElementById("estimated-date").innerText = earliestDateFormatted + ' - ' + latestDateFormatted;
        }

        editAddressButton.addEventListener("click", () => {
            const userConfirmed = confirm("Are you sure you want to edit? Any unsaved changes will be lost.");
            if (userConfirmed) {
                document.querySelector(".address").style.display = "block";
                document.querySelector(".address-filled").style.display = "none";
                document.querySelector(".payment-notfilled").style.display = "block";
                document.querySelector(".payment").style.display = "none";
                document.querySelector(".payment-filled").style.display = "none";
                document.querySelector(".order-summary").style.display = "none";
                document.querySelector(".order-summary-hidden").style.display = "block";
            }
        });

        continueButton.addEventListener("click", () => {
            if (validateCardNumber() && validateExpDate() && validateSecurityCode() && validateFullName()) {
                const cardNumberInput = document.getElementById("input-card-number").value;
        
                // Display only the last 4 digits and format the rest as **** **** ****
                const formattedCardNumber = "**** **** **** " + cardNumberInput.slice(-4);
                document.getElementById("card-number").innerText = formattedCardNumber;
                document.getElementById("full-name").innerText = document.getElementById("input-full-name").value;

                document.querySelector(".payment").style.display = "none";
                document.querySelector(".payment-filled").style.display = "block";
                document.querySelector(".order-summary").style.display = "block";
                document.querySelector(".order-summary-hidden").style.display = "none";
                }
        });

        editPaymentButton.addEventListener("click", () => {
            const userConfirmed = confirm("Are you sure you want to edit? Any unsaved changes will be lost.");
            if (userConfirmed) {
                document.querySelector(".payment").style.display = "block";
                document.querySelector(".payment-filled").style.display = "none";
                document.querySelector(".order-summary").style.display = "none";
                document.querySelector(".order-summary-hidden").style.display = "block";
            }
        });

        function validateField(inputFieldID, pattern) {
            const inputField = document.getElementById(inputFieldID);
            const inputLabel = document.querySelector(`th[for="${inputFieldID}"]`);
            const inputFieldValue = inputField.value.trim();
            const isValid = pattern.test(inputFieldValue);
            inputField.classList.toggle("invalid", !isValid);
            inputLabel.classList.toggle("invalid", !isValid);
            return isValid;
        }

        function validateFirstName() {
            return validateField("input-firstname", /.+/);
        }
        function validateLastName() {
            return validateField("input-lastname", /.+/);
        }
        function validateStreetAddress() {
            return validateField("input-street-address", /.+/);
        }
        function validatePostalCode() {
            return validateField("input-postal-code", /^\d{6}$/);
        }
        function validateContactNumber() {
            return validateField("input-contact", /^\d{8}$/);
        }
        function validateCardNumber() {
            return validateField("input-card-number", /^\d{16}$/);
        }
        function validateExpDate() {
            return validateField("expiration-date", /^(0[1-9]|1[0-2])\/\d{2}$/);
        }
        function validateSecurityCode() {
            return validateField("security-code", /^\d{3}$/);
        }
        function validateFullName() {
            return validateField("input-full-name", /.+/);
        }


    </script>
</body>
</html>