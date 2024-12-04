<?php
include '../includes/header.php';
// Include database configuration
include '../config/db.php';

if (isset($_SESSION['username'])) {
    echo "Welcome back! " . $_SESSION['username'];
} else {
    echo "No session found.";
}

// if (!isset($_SESSION['username'])) {
//     header('Location: login.php');
//     exit;
// }


// Fetch categories for the dropdown
$categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);

// Check for category filter
$selected_category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

// If a category is selected, fetch products for that category, else fetch all products
if ($selected_category_id) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ?");
    $stmt->bind_param("i", $selected_category_id);
    $stmt->execute();
    $products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    // Fetch all products if no category is selected
    $products = $conn->query("SELECT * FROM products")->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - My E-Commerce</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <main>
        <h1>Shopplies</h1>
        <p>SHOPping for school supPLIES made easy!</p>

        <a href="/pages/product_list.php">SHOP NOW!</a>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>

</html>