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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .product-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .product-card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
    </style>
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
                        <button class="btn btn-primary view-details" 
                                data-bs-toggle="modal" 
                                data-bs-target="#productModal" 
                                data-id="<?php echo $product['product_id']; ?>">View Details</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?> <p>No products available at the moment. Check back later!</p>
            <?php endif; ?>
            </div>
        </section>
    </main>

<!-- Modal Template -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Dynamic Product Details will load here -->
                <div id="productDetails" class="text-center">
                    <p>Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.view-details').on('click', function() {
            const productId = $(this).data('id');
            $('#productDetails').html('<p>Loading...</p>'); // Reset modal content

            // Fetch product details via AJAX
            $.ajax({
                url: 'fetch_product.php', // Create a PHP file for fetching product data
                method: 'GET',
                data: { id: productId },
                success: function(response) {
                    $('#productDetails').html(response); // Inject content into modal body
                },
                error: function() {
                    $('#productDetails').html('<p>Error loading product details.</p>');
                }
            });
        });
    });
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
