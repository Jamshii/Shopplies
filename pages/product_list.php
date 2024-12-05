<?php
include '../includes/header.php';
// Include database configuration
include '../config/db.php';

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
    <title>Shop Page</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

    <main class = 'products-list'>
        <!-- Filter Section -->
        <section class="filters">
        <form method="get" action="">
            <label for="category-filter">Filter by Category:</label>
            <select id="category" name="category" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category['category_id']); ?>"
                    <?php echo $selected_category_id === (int)$category['category_id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
            </form>
        </section>

        <!-- Products Section -->
        <section class="products">
            <div class="product-grid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><strong>₱<?php echo number_format($product['price'], 2); ?></strong></p>
                        <a href="product.php?id=<?php echo $product['product_id']; ?>" class="btn <?php echo $product['stock_quantity'] > 0 ? '' : ''; ?>"><button>View Details</button></a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?> <p>No products available at the moment. Check back later!</p>
            <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>