<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopplies</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body>
    <header>
    <div class="logo"><img src="../assets/images/Logo(White).png" alt=""></div>
        <nav>
            <ul>
                <?php if (isset($_SESSION['username'])): ?>
                    <?php if ($_SESSION['username'] === 'admin'): ?>
                        <!-- Admin Navigation -->

                        <!-- For James -->
                        <!-- <a href="/pages/manage_products.php">Manage Products</a>
                        <a href="/pages/manage_orders.php">Manage Orders</a>
                        <a href="/pages/feedback.php">Feedback</a>
                        <a href="/pages/logout.php">Log Out</a> -->

                        <!-- For others -->
                        <li><a href="/Shopplies/pages/manage_products.php">Manage Products</a></li>
                        <li><a href="/Shopplies/pages/manage_orders.php">Manage Orders</a></li>
                        <li><a href="/Shopplies/pages/feedback.php">Feedback</a></li>
                        <li><a href="/Shopplies/pages/logout.php">Log Out</a></li>
                    <?php else: ?>
                        <!-- User Navigation -->

                        <!-- For James -->
                        <!-- <a href="/pages/homepage.php">Home</a> -->
                        <!-- <a href="/pages/product_list.php">Products</a>
                        <a href="/pages/cart.php">Cart</a>
                        <a href="/pages/profile.php">Profile</a>
                        <a href="/pages/order.php">Orders</a>
                        <a href="/pages/about_us.php">About Us</a>
                        <a href="/pages/contact_us.php">Contact Us</a>
                        <a href="/pages/logout.php">Log Out</a> -->

                        <!-- For others -->
                        <!-- <li><a href="/Shopplies/pages/homepage.php">Home</a></li> -->
                        <li><a href="/Shopplies/pages/product_list.php">Products</a></li>
                        <li><a href="/Shopplies/pages/cart.php">Cart</a></li>
                        <li><a href="/Shopplies/pages/profile.php">Profile</a></li>
                        <li><a href="/Shopplies/pages/order.php">Orders</a></li>
                        <li><a href="/Shopplies/pages/about_us.php">About Us</a></li>
                        <li><a href="/Shopplies/pages/contact_us.php">Contact Us</a></li>
                        <li><a href="/Shopplies/pages/logout.php">Log Out</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Guest Navigation -->

                    <!-- For James -->
                    <!-- <a href="/pages/homepage.php">Home</a> -->
                    <!-- <a href="/pages/product_list.php">Products</a>
                    <a href="/pages/about_us.php">About Us</a>
                    <a href="/pages/contact_us.php">Contact Us</a>
                    <a href="/pages/login.php">Log In</a> -->
                    
                    <!-- For others -->
                    <!-- <li><a href="/Shopplies/pages/homepage.php">Home</a></li> -->
                    <li><a href="/Shopplies/pages/product_list.php">Products</a></li>
                    <li><a href="/Shopplies/pages/about_us.php">About Us</a></li>
                    <li><a href="/Shopplies/pages/contact_us.php">Contact Us</a></li>
                    <li><a href="/Shopplies/pages/login.php">Log In</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>