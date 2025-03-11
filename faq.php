<?php     //faq.php
include 'constants.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>FAQ | Fashion Science</title>
<meta charset="utf-8">
<link rel="stylesheet" href="styling.css">
<style>
    .questions, .answer {
        width: 700px;
        border: 1px solid #000;
        padding: 15px;
        margin: 0 auto;
        text-align: left;
        max-height: fit-content;
    }

    .questions {
        background-color: #eeeeee;
        cursor: pointer;
    }

    .answer {
        display: none;
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
            <h2>FAQs</h2>
            <div class="questions"><b>Q1. Do you offer international shipping?</b></div>
            <div class="answer">No, we currently offer shipping only within Singapore.</div><br><br>

            <div class="questions"><b>Q2. What is the shipping cost within Singapore?</b></div>
            <div class="answer">Shipping is free for orders above $50. For orders below $50, a flat rate of $5 is charged.</div><br><br>

            <div class="questions"><b>Q3. How long does it take for my order to be delivered?</b></div>
            <div class="answer">Orders are typically delivered within 5-7 business days. You'll receive a tracking link once your order is dispatched.</div><br><br>

            <div class="questions"><b>Q4. What payment methods do you accept?</b></div>
            <div class="answer">We accept payments via credit cards and debit cards for Singapore customers.</div><br><br>

            <div class="questions"><b>Q5. Can I return or exchange items if they don't fit or I change my mind?</b></div>
            <div class="answer">Yes, we offer hassle-free returns and exchanges. Please refer to our Returns & Exchanges policy for details.</div><br><br>

            <div class="questions"><b>Q6. What is your sizing chart?</b></div>
            <div class="answer">You can find our sizing chart on the product page to help you choose the right size. If you need further assistance, feel free to contact our customer support.</div><br><br>

            <div class="questions"><b>Q7. How can I track my order?</b></div>
            <div class="answer">You will receive an email with a tracking link once your order is shipped. You can use this link to track the delivery status of your order.</div><br><br>

            <div class="questions"><b>Q8. Can I cancel my order after it's been placed?</b></div>
            <div class="answer">You can cancel your order within 24 hours of placing it. Please contact our customer support team for assistance.</div><br><br>

            <div class="questions"><b>Q9. Are the colors of the products accurate in the pictures?</b></div>
            <div class="answer">We make every effort to display product colors accurately. However, please note that colors may appear slightly different due to variations in monitor settings.</div><br><br>

            <div class="questions"><b>Q10. Do you restock items that are out of stock?</b></div>
            <div class="answer">We try our best to restock popular items, but availability is subject to supplier and seasonality. You can sign up for product notifications to be informed when an item is back in stock.</div><br><br>

            <div class="questions"><b>Q11. How can I contact customer support?</b></div>
            <div class="answer">You can reach our customer support team through our Contact Us page or by emailing <a href="mailto:support@fashionscience.com">support@fashionscience.com</a>. We're here to assist you with any inquiries or issues.</div><br><br>

            <div class="questions"><b>Q12. Do you have a physical store in Singapore where I can try on the clothing?</b></div>
            <div class="answer">We operate exclusively as an online store and do not have physical retail locations. However, we offer hassle-free returns and exchanges if items do not meet your expectations.</div><br><br>

            <div class="questions"><b>Q13. What is your privacy policy regarding customer information?</b></div>
            <div class="answer">We take customer privacy seriously. You can review our Privacy Policy to understand how we collect, use, and protect your personal information.</div><br><br>

            <div class="questions"><b>Q14. Are gift cards available for purchase?</b></div>
            <div class="answer">No, gift cards are not available for purchase.</div><br><br>
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
        document.addEventListener("DOMContentLoaded", function() {
            // Get all questions and answers
            const questions = document.querySelectorAll(".questions");
            const answers = document.querySelectorAll(".answer");

            // Loop through questions
            questions.forEach((question, index) => {
                question.addEventListener("click", () => {
                    // Toggle the visibility of the corresponding answer
                    if (answers[index].style.display === "block") {
                        answers[index].style.display = "none";
                    } else {
                        answers[index].style.display = "block";
                    }
                });
            });
        });
    </script>
</body>
</html>