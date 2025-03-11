<?php // product.php
include 'constants.php';

// connect to database
$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}


if (isset($_GET['id'])) {
    // Retrieve the 'id' parameter from the URL
    $productID = $_GET['id'];

    // Sanitize the input to prevent security issues such as SQL injection
    $productID = filter_var($productID, FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL statement to prevent SQL injection
    $stmt = $db->prepare("SELECT * FROM Products WHERE ProductID = ?");
    $stmt->bind_param("i", $productID); // 'i' denotes the type: i

    // Execute the query
    $stmt->execute();
    
    // Fetch the result into an associative array
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    // Always check if you have a product
    if ($product) {
        // Now you can use $product['column_name'] to access its data
    } else {
        // Alert 
        echo '<script>alert("Product not found.")</script>';
        // Redirect to index.php
        echo '<script>window.location.href = "product_page.php";</script>';
    }
    // Close statement
    $stmt->close();
} else {
    // Handle the case where 'id' parameter is not set
    // echo "Product ID is missing";
    // Optionally redirect to another page
    // header('Location: some_other_page.php');
    exit;
}




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Fashion Science</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styling.css">
    <style>
        header {
            grid-area: header;
        }

        nav {
            grid-area: nav;
            display: flex;
            justify-content: space-between;
        }
        #category {
            grid-area: category;
            display: grid;
            grid-row:1;
            grid-column:span 2;
            text-align: center;
        }

        #content {
            padding: 20px;
            width: 1060px;
        }

        footer {
            grid-area: footer;
            text-align: center;
        }

        h1{
            text-align: start;
        }

        .top-container {
            display: flex;
        }

        .product-page {
            margin: 20px auto;
            display: flex;
            justify-content: space-between;
            width: 50%;
        }

        .product-images {
            width: 50%;
        }

        .thumbnails {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            align-items: center;
            justify-content: center;
        }

        .thumbnails img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            cursor: pointer;
        }

        .main-image img {
            width: 80%;
            height: auto;
        }

        .product-details {
            width: 45%;
            display: flex;
            flex-direction: column;
            align-items: start;
        }

        .product-details h1 {
            margin: 0;
            font-size: 24px;
        }

        .price {
            font-size: 20px;
            color: #E44D26;
            margin: 20px 0;
        }

        .size-selection {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        

        .size-selection button {
            border: 1px solid #ccc;
            cursor: pointer;
        }
        
        .size-button-group{
            width: 80%;
            display: flex;
            justify-content: space-between;
        }

        .size-label {
            width: 20%;
            text-align: start;
        }

        .size-btn{
            width: 22%;
            background-color: white;
            color: white;
            padding: 10px 0px;
            cursor: pointer;
            color: black;
        }
        

        .size-btn-selected {
            background-color: #E44D26;
            color: white;
        }

        .quantity-selection{
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .quantity-label{
            width: 20%;
            text-align: start;
        }

        .quantity-selection input {
            margin: 20px 0;
            text-align: center;
            padding: 10px 0;
            width: 80%;
            border: 1px solid #ccc;
        }

        .product-tabs{
            width:100%;
        }
        .product-tabs ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            border-bottom: 1px solid #ccc;
            margin-bottom: 0;

        }

        .product-tabs ul li {
          margin-right: 20px;
        }

        .product-tabs ul li a {
            text-decoration: none;
            color: #000;
            padding-bottom: 5px;
            display: inline-block;
        }

        .product-tabs ul li a:hover {
            border-bottom: 2px solid #E44D26;
        }

        .product-description, .features {
            margin-bottom: 20px;
            display: grid;
            text-align: left;
            line-height: 1.6;
        }

        .features li {
            line-height: 2; 
        }


        .cart_button{
            width:100%;
            background: #E44D26;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            margin-right: 10px;
        }


        /* Responsive layout adjustments */
        @media (max-width: 768px) {
            .product-page {
                flex-direction: column;
            }

            .product-images, .product-details {
                width: 100%;
            }
        }


        
    </style> 
</head>
<script type="text/javascript" src="common.js"></script>
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
            <div class = "top-container">
                <div class="product-images">
                    <!-- Main product image -->
                    <div class="main-image">
                        <?php
                            echo '<img src="Product/product' . $product['ProductID'] . '/1.webp" alt="Thumbnail 1">';
                        ?>
                    </div>
                    <!-- Small thumbnail images -->
                    <div class="thumbnails">
                        <?php
                            for ($i = 1; $i <= 4; $i++) {
                                echo '<img class="thumbnail" src="Product/product' . $product['ProductID'] . '/' . $i . '.webp" alt="Thumbnail ' . $i . '" data-fullsize="Product/product' . $product['ProductID'] . '/' . $i . '.webp">';
                            }
                        ?>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="product-details">
                    <?php
                        echo '<h1>' . $product['ProductName'] . '</h1>';
                        echo '<p class="price">$' . $product['Price'] . '</p>';
                    ?>

                    <!-- Size Selection -->
                    <div class="size-selection">
                        <label class="size-label">Size</label>
                        <div class="size-button-group">
                            <button class="size-btn" data-size="S" onclick="setSize(this)">S</button>
                            <button class="size-btn" data-size="M" onclick="setSize(this)">M</button>
                            <button class="size-btn" data-size="L" onclick="setSize(this)">L</button>
                            <button class="size-btn" data-size="XL" onclick="setSize(this)">XL</button>
                        </div>
                        <input type="hidden" name="Size" id="selectedSize">
                    </div>


                    <!-- Quantity Selection -->
                    <div class="quantity-selection">
                        <label class="quantity-label" for="quantity">Quantity</label>
                        <input type="number" id="quantity" value="1" min="1">
                    </div>

                    <!-- Action Buttons -->
                    <button type="button" id="addToCartButton" class="cart_button">ADD TO CART</button>

                    <!-- Product Description Tabs -->
                    <div class="product-tabs">
                    <ul>
                        <li><a href="#description">DESCRIPTION</a></li>
                    </ul>
                    </div>

                    <?php
                        echo '<p class="product-description">' . $product['Description'] . '</p>';
                    ?>
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
</body>
<script>
    document.getElementById('addToCartButton').addEventListener('click', function() {
        var productID = <?php echo json_encode($product['ProductID']); ?>;
        var size = document.getElementById('selectedSize').value;
        var quantity = document.getElementById('quantity').value;

        // Create a FormData object to build key-value pairs of the form fields
        var formData = new FormData();
        formData.append('ProductID', productID);
        formData.append('Size', size);
        formData.append('Quantity', quantity);

        // Use the fetch API to make the HTTP request
        fetch('push_to_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Assuming the server responds with JSON
        .then(response => {
            // Handle the response from the server
            console.log(response);
            // Alert
            alert(response.message);

            // You can redirect to the cart page or update the UI accordingly
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    function setSize(button) {
        // Remove the selected class from all buttons
        const buttons = document.querySelectorAll('.size-btn');
        buttons.forEach(btn => btn.classList.remove('size-btn-selected'));

        // Add the selected class to the clicked button
        button.classList.add('size-btn-selected');


        // Set the value of the hidden input field
        document.getElementById('selectedSize').value = button.getAttribute('data-size');

    }
    // Wait for the DOM to be loaded
    document.addEventListener('DOMContentLoaded', function () {
        // Get the main image element
        var mainImage = document.querySelector('.main-image img');
        // Get all thumbnail elements
        var thumbnails = document.querySelectorAll('.thumbnail');

        // Function to change the main image source
        function changeImage(e) {
            mainImage.src = this.getAttribute('data-fullsize');
        }

        // Add hover event to each thumbnail
        thumbnails.forEach(function (thumbnail) {
            thumbnail.addEventListener('mouseenter', changeImage);
        });
    });


</script>
</html>