<?php     //index.php
include 'constants.php';
session_start();

// connect to database
$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if (isset($_SESSION['email'])) {
    // $welcomeMessage = 'Welcome, ' . $_SESSION['email'];
    $email = $_SESSION['email'];
    $usernameQuery = "SELECT Username FROM Users
                    WHERE UserID = (SELECT UserID FROM Users WHERE Email = '$email')";
    $usernameResult = $db->query($usernameQuery);
    if ($usernameResult->num_rows > 0) {
        $usernameData = $usernameResult->fetch_assoc();
    }
} else {
    // $welcomeMessage = 'Welcome to Fashion Science! \nDiscover the latest trends, shop for your favorite styles, and save your preferences by signing in. Get ready to experience a personalized shopping journey!';
}

// Fetch all products from the database
$sql = "SELECT * FROM Products";
$result = $db->query($sql);

// save result to array
$products = array();
$randomisedNewProducts = array();
$randomisedSaleProducts = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Randomly pick 8 products from the array
$randomisedNewProducts = $products;
shuffle($randomisedNewProducts);
$randomisedNewProducts = array_slice($randomisedNewProducts, 0, 8);

// Randomly pick 6 products from the array
$randomisedSaleProducts = $products;
shuffle($randomisedSaleProducts);
$randomisedSaleProducts = array_slice($randomisedSaleProducts, 0, 6);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>HOME | Fashion Science</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styling.css">
    <style>

        img[src*="Product"] {
            width: 255px; 
            height: 300px;
        }

        .scrollable-content {
            flex-grow: 1;
            display: flex;
            overflow-x: auto;
            gap: 12.5px;
            padding: 10px 0;
            scroll-behavior: smooth;
        }

        .scrollable-content::-webkit-scrollbar {
            height: 0; /* for horizontal scrollbars */
            display: none;
        }

        .scroll-btn {
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            cursor: pointer;
            font-size: 20px;
            vertical-align: middle;
            width: 20px;
        }

        .scroll-btn:focus {
            outline: none;
        }

        .product-item {
            width: 255px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .product-name {
            font-size: 16px;
            font-weight: bold;
            width: 90%;
            vertical-align: center;
            color: #333;
        }

        .product-price {
            font-size: 16px; 
            color: #666;
            width: 90%;
            margin: 5px 0 10px 0;
        }

        .product-item-link{
            height: 100%;
            text-decoration: none;
            color: black;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
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
                <div id="welcome-msg" style='display:<?php echo isset($_SESSION['email']) ? 'block' : 'none'; ?>'>
                    <span><?php echo isset($_SESSION['email']) ? 'Welcome, ' . $usernameData['Username'] : ''; ?></span>
                </div>
                <img src="login.jpg" id="profile-icon" onclick="showDropdown()" width="46" height="40">
                    <div id="dropdown">
                        <a href="myorders.php">My Order</a>
                        <a href="#" onclick="signOut()">Sign Out</a>
                    </div>
                <img src="shopping-cart.jpg" onclick="showCart()" width="48" height="40">
            </div>
        </nav>
        <div id="content">
            <h2>What's New</h2>
            <div id="whats-new" class="flex-container">
                <button class="scroll-btn left" onclick="scrollContent('whats-new', 'left')">&#10094;</button>
                <div class="scrollable-content" id="whats-new-scrollable">
                </div>
                <button class="scroll-btn right" onclick="scrollContent('whats-new', 'right')">&#10095;</button>
            </div>
            <br><br>
            <h2>Sale</h2>
            <div id="sale" class="flex-container">    
                <button class="scroll-btn left" onclick="scrollContent('sale', 'left')">&#10094;</button>
                <div class="scrollable-content" id="sale-scrollable">
                </div>
                <button class="scroll-btn right" onclick="scrollContent('sale', 'right')">&#10095;</button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function scrollContent(containerId, direction) {
            const container = document.querySelector(`#${containerId} .scrollable-content`);
            let scrollAmount = 0;
            const scrollWidth = 270;
            
            if (direction === 'right') {
                scrollAmount = container.scrollLeft + scrollWidth;
            } else {
                scrollAmount = container.scrollLeft - scrollWidth;
            }
            
            $(container).animate({ scrollLeft: scrollAmount }, 400);
        }

        // document.addEventListener("DOMContentLoaded", function () {
            // alert('<?php //echo $welcomeMessage; ?>');
        // });

        function renderProducts() {
            var productList = document.getElementById("products");
            productList.innerHTML = "";

            for (var i = 0; i < filteredProducts.length; i++) {
                var product = filteredProducts[i];
                var productItem = document.createElement("div");
                productItem.className = "product-item";

                // Wrap the content in an anchor (a) tag to make it clickable
                productItem.innerHTML = '<a href="product.php?id=' + product.ProductID + '">' +
                    '<img src="' + product.ImageURL + '" alt="' + product.ProductName + '" width="260" height="300">' +
                    '<p class="product-name">' + product.ProductName + '</p>' +
                    '<p class="product-price">$' + product.Price + '</p>' +
                    '</a>';
                productList.appendChild(productItem);
            }
        }



        function renderNewProducts() {
            var productList = document.getElementById("whats-new-scrollable");
            productList.innerHTML = "";

            randomisedNewProducts = <?php echo json_encode($randomisedNewProducts); ?>;

            for (var i = 0; i < randomisedNewProducts.length; i++) {
                var product = randomisedNewProducts[i];
                var productItem = document.createElement("div");
                productItem.className = "product-item";

                // Wrap the content in an anchor (a) tag to make it clickable
                productItem.innerHTML = '<a class="product-item-link" href="product.php?id=' + product.ProductID + '">' +
                    '<img src="' + product.ImageURL + '" alt="' + product.ProductName + '" width="260" height="300">' +
                    '<p class="product-name">' + product.ProductName + '</p>' +
                    '<p class="product-price">$' + product.Price + '</p>' +
                    '</a>';
                productList.appendChild(productItem);
            }
        }

        function renderSaleProducts() {
            var productList = document.getElementById("sale-scrollable");
            productList.innerHTML = "";

            randomisedSaleProducts = <?php echo json_encode($randomisedSaleProducts); ?>;

            for (var i = 0; i < randomisedSaleProducts.length; i++) {
                var product = randomisedSaleProducts[i];
                var productItem = document.createElement("div");
                productItem.className = "product-item";

                // Wrap the content in an anchor (a) tag to make it clickable
                productItem.innerHTML = '<a class="product-item-link" href="product.php?id=' + product.ProductID + '">' +
                    '<img src="' + product.ImageURL + '" alt="' + product.ProductName + '" width="260" height="300">' +
                    '<p class="product-name">' + product.ProductName + '</p>' +
                    '<p class="product-price">$' + product.Price + '</p>' +
                    '</a>';
                productList.appendChild(productItem);
            }
        }

        renderNewProducts();
        renderSaleProducts();

    </script>    
</body>
</html>