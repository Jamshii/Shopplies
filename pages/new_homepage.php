<?php
include '../includes/header.php';
// Include database configuration
include '../config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Website</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <main>
        <section class="hero">
            <h1>Welcome to Our Store</h1>
            <p>Discover amazing products at unbeatable prices.</p>
            <a href="#" class="cta-button">Shop Now</a>
        </section>

        <section class="products">
            <h2>Featured Products</h2>
            <div class="product-grid">
                <div class="product-card">
                    <img src="https://via.placeholder.com/150" alt="Product 1">
                    <h3>Product 1</h3>
                    <p>$19.99</p>
                    <button>Add to Cart</button>
                </div>
                <div class="product-card">
                    <img src="https://via.placeholder.com/150" alt="Product 2">
                    <h3>Product 2</h3>
                    <p>$29.99</p>
                    <button>Add to Cart</button>
                </div>
                <div class="product-card">
                    <img src="https://via.placeholder.com/150" alt="Product 3">
                    <h3>Product 3</h3>
                    <p>$39.99</p>
                    <button>Add to Cart</button>
                </div>
            </div>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
