<?php     //about_us.php
include 'constants.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>ABOUT US | Fashion Science</title>
<meta charset="utf-8">
<link rel="stylesheet" href="styling.css">
<style>
    #about-us {
        text-align: left;
        padding: 50px 80px;
    }
    #about-us h1 {
        float:left;
        margin: 16px 40px 16px 0;
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
            <div id="about-us">
                <h1>About Us</h1>
                <p>Welcome to Fashion Science, your ultimate destination for stylish and affordable women's clothing in the heart of Singapore!</p><br>
                <h3>Our Story</h3>
                <p>At Fashion Science, we believe that fashion is not just about clothes; it's an expression of your unique style and personality. Our journey began with a simple yet ambitious goal: to provide women across Singapore with a wide range of high-quality, trendy clothing that not only makes them look good but also feel confident and empowered.</p><br>
                <h3>Our Vision</h3>
                <p>Our vision is to be more than just another online clothing store. We aim to be your trusted fashion companion, offering a carefully curated selection of clothing that meets the latest fashion trends and caters to women of all styles and sizes. We want to help you discover the perfect outfit that suits your individuality.</p><br>
                <h3>What Sets Us Apart</h3>
                <ul>
                    <li><b>Quality Matters: </b>We are committed to providing our customers with clothing of the highest quality. Each piece is handpicked to ensure it meets our stringent quality standards.</li><br>
                    <li><b>Affordable Elegance: </b>Fashion Science believes that looking fabulous doesn't have to come with a high price tag. We offer affordable fashion without compromising on style.</li><br>
                    <li><b>Diverse Selection: </b>Our collections are as diverse as the women who wear them. From casual wear to elegant evening dresses, we have something for every occasion.</li><br>
                </ul>
                <h3>Our Commitment</h3>
                <p>We are not just a clothing store; we're a community that celebrates the beauty of every woman. We want you to feel confident, comfortable, and radiant in every outfit you choose from Fashion Science.</p><br>
                <h3>Connect with Us</h3>
                <p>Explore our collections, discover your next favorite outfit, and join us on this exciting fashion journey. Connect with us on social media, sign up for our newsletter, or drop us a message. We're here to make your fashion dreams come true.</p><br>
                <p>Thank you for choosing Fashion Science. Let's redefine fashion, together!</p><br>


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
    </script>
</body>
</html>