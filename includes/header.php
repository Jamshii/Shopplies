<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopplies</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <header>
        <nav>
            <?php if (isset($_SESSION['username'])): ?>
                <?php if ($_SESSION['username'] === 'admin'): ?>
                    <!-- Admin Navigation -->
                    <a href="/pages/manage_products.php">Manage Products</a>
                    <a href="/pages/manage_orders.php">Manage Orders</a>
                    <a href="/pages/feedback.php">Feedback</a>
                    <a href="/pages/logout.php">Log Out</a>
                <?php else: ?>
                    <!-- User Navigation -->
                    <a href="/pages/homepage.php">Home</a>
                    <a href="/pages/product_list.php">Products</a>
                    <a href="/pages/cart.php">Cart</a>
                    <a href="/pages/profile.php">Profile</a>
                    <a href="/pages/order.php">Orders</a>
                    <a href="/pages/about_us.php">About Us</a>
                    <a href="/pages/contact_us.php">Contact Us</a>
                    <a href="/pages/logout.php">Log Out</a>
                <?php endif; ?>
            <?php else: ?>
                <!-- Guest Navigation -->
                <a href="/pages/homepage.php">Home</a>
                <a href="/pages/product_list.php">Products</a>
                <a href="/pages/about_us.php">About Us</a>
                <a href="/pages/contact_us.php">Contact Us</a>
                <a href="/pages/login.php">Log In</a>
            <?php endif; ?>
        </nav>
    </header>