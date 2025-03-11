<?php     //product_page.php
include 'constants.php';
session_start();


// connect to database
$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Fetch all products from the database
$sql = "SELECT * FROM Products";
$result = $db->query($sql);

// save result to array
$products = array();
$filteredProducts = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$filteredProducts = $products;


// filter product function 
function filterProducts(
    array $products,
    String $type,
    int $value
){
    // save result to array
    $filteredProducts = array();
    foreach ($products as $product) {
        if ($product[$type] == $value) {
            $filteredProducts[] = $product;
        }
    }
    echo '<script>console.log(' . json_encode($filteredProducts) . ');</script>';
    return $filteredProducts;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Fashion Science</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styling.css">
    <style>
        #category {
            grid-area: category;
            display: grid;
            grid-row:1;
            grid-column:span 2;
            text-align: center;
        }

        #content {
            grid-area: content;
            display: grid;
            grid-template-columns: 1fr 5fr; /* Adjust as per your preference */
            gap: 20px; /* Gap between filters and products */
            padding: 20px;
            width: 1060px;
        }

        #filters {
            grid-column: 1;
            grid-row:2;
            text-align: left;
            padding-right: 10px;
            border-right: 2px solid #ccc;
            
        }

        #products {
            padding-left: 20px;
            grid-column: 2;
            grid-row:2;
            display: grid;
            gap: 10px; /* Gap between product images */
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); 
        }
        @media (min-width: 1200px) {
            #products {
                grid-template-columns: repeat(3, 1fr); /* Display 4 images per row on larger screens */
            }
        }
        .product-item {
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
            margin: 0 0 10px 0;
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

        footer {
            grid-area: footer;
            text-align: center;
        }

        /* Styling details remain the same */
        .filter-header {
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 5px 0;
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
            <div id="category">
                <h1>All Products</h1>
            </div>
            <div id="filters">
                <h3 class="filter-header">Filter</h3>
                <h4>Category</h4>     
                <label><input type="checkbox" onclick="filterProducts('CategoryID',1)"> Two-piece</label>
                <label><input type="checkbox" onclick="filterProducts('CategoryID',2)"> Top</label>
                <label><input type="checkbox" onclick="filterProducts('CategoryID',3)"> Dress</label>
                <label><input type="checkbox" onclick="filterProducts('CategoryID',4)"> Bottom</label>
            
                <h4>Length</h4>
                <label><input type="checkbox" onclick="filterProducts('LengthID',1)"> Short</label>
                <label><input type="checkbox" onclick="filterProducts('LengthID',2)"> Long</label>
        
                <h4>Sleeve Length</h4>
                <label><input type="checkbox" onclick="filterProducts('SleeveLengthID',1)"> Sleeveless</label>
                <label><input type="checkbox" onclick="filterProducts('SleeveLengthID',2)"> Short Sleeve</label>
                <label><input type="checkbox" onclick="filterProducts('SleeveLengthID',3)"> Long Sleeve</label>
            </div>

            <div id="products"></div>
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
<script src="filter.js"></script>
<script>
// run on start

// store products array locally
var products = <?php echo json_encode($products); ?>;
var filteredProducts = products;

var activeFilters = {
    CategoryID: [],
    LengthID: [],
    SleeveLengthID: []
};

renderProducts();

function renderProducts() {
    var productList = document.getElementById("products");
    productList.innerHTML = "";

    for (var i = 0; i < filteredProducts.length; i++) {
        var product = filteredProducts[i];
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


function filterProducts(
    type,
    value
){
    if (activeFilters[type].includes(value)) {
        // remove filter
        activeFilters[type].splice(activeFilters[type].indexOf(value), 1);
    } else {
        // add filter
        activeFilters[type].push(value);
    }
    console.log(activeFilters, "boom")

    // if all empty, show all products
    var count = 0;
    for (var i = 0; i < Object.keys(activeFilters).length; i++) {
        var filterType = Object.keys(activeFilters)[i];
        var filterValues = activeFilters[filterType];
        if (filterValues.length > 0) {
            count++;
        }
    }
    if (count == 0) {
        filteredProducts = products;
        renderProducts();
        return;
    }

    // filter products
    filteredProducts = [];
    for (var i = 0; i < Object.keys(activeFilters).length; i++) {
        var filterType = Object.keys(activeFilters)[i];
        var filterValues = activeFilters[filterType];
        for (var j = 0; j < filterValues.length; j++) {
            for (var k = 0; k < products.length; k++) {
                var product = products[k];
                if (product[filterType] == filterValues[j]) {
                    filteredProducts.push(product);
                }
            }
        }
    }
    
    // Count active filter types
    var activeFilterTypesCount = Object.values(activeFilters).filter(values => values.length > 0).length;

    // Retain only duplicates that appear as many times as there are active filter types
    filteredProducts = filteredProducts.filter(product => {
        var productAppearanceCount = filteredProducts.filter(p => p === product).length;
        return productAppearanceCount === activeFilterTypesCount;
    });

    // remove duplicates
    filteredProducts = filteredProducts.filter((v, i, a) => a.indexOf(v) === i);
    
    renderProducts();
}




</script>
</html>