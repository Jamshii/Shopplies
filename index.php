<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    if ($_SESSION['username'] === 'admin')
        header("Location: pages/manage_products.php");
    else {
        // Redirect to the homepage if logged in
        header("Location: pages/homepage.php");
        exit();
    }
} else {
    // Redirect to the login page if not logged in
    header("Location: pages/login.php");
    exit();
}
