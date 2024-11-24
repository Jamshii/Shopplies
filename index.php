<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the homepage if logged in
    header("Location: pages/home.php");
    exit();
} else {
    // Redirect to the login page if not logged in
    header("Location: pages/login.php");
    exit();
}
