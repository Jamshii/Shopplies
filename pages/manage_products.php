<?php
include '../includes/header.php';
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle adding a new product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $description = strip_tags(trim($_POST['description']));
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);
    $category_id = intval($_POST['category_id']);
    $image = $_FILES['image']['name'];

    // Upload product image
    $target_dir = "../assets/images/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    // Insert product into database
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock_quantity, category_id, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiss", $name, $description, $price, $stock, $category_id, $image);
    $stmt->execute();
    $stmt->close();
    $message = "Product added successfully!";
    header("Location: manage_products.php?category=$category_id&message=" . urlencode($message));
}

// Check for edit mode
$editing_product_id = isset($_GET['edit_product_id']) ? intval($_GET['edit_product_id']) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $product_id = intval($_POST['product_id']);
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $stock_quantity = intval($_POST['stock_quantity']);
    $current_image = $_POST['current_image']; // Hidden input for the current image
    $category = isset($_POST['category']) ? htmlspecialchars($_POST['category']) : '';

    // Check if a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];

        // Ensure the image is stored in the assets/images folder
        move_uploaded_file($image_tmp, "../assets/images/" . $image);
    } else {
        // Use the current image if no new image is uploaded
        $image = $current_image;
    }

    // Update product in the database
    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock_quantity = ?, image = ? WHERE product_id = ?");
    $stmt->bind_param("ssdisi", $name, $description, $price, $stock_quantity, $image, $product_id);

    if ($stmt->execute()) {
        // Redirect back to the filtered category with a success message
        $message = "Product updated successfully!";
        header("Location: manage_products.php?category=$category&message=" . urlencode($message));
        exit;
    } else {
        echo "Error updating product: " . $conn->error;
    }

    $stmt->close();
}



// Fetch all products
$products = $conn->query("SELECT * FROM products")->fetch_all(MYSQLI_ASSOC);

// Fetch categories
$categories = $conn->query("SELECT category_id, name FROM categories")->fetch_all(MYSQLI_ASSOC);


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
<html>

<head>
    <title>Manage Products</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/script.js"></script>
</head>

<body>
    <h1>Add Products</h1>
    <?php
    // Display success message
    $message = isset($_GET['message']) ? urldecode($_GET['message']) : null;

    if ($message) {
        echo "<div class='message'>{$message}</div>";
    }
    ?>

    <!-- Add Product Button -->
    <button id="showAddProductForm" class="btn btn-primary">Add Product</button>
    <!-- Add Product Form -->
    <div id="addProductForm" style="display: none;">
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock Quantity:</label>
                <input type="number" id="stock" name="stock" min="0" required>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category_id" required>
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['category_id']; ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" required>
            </div>
            <button type="submit" name="add_product" class="btn btn-success">Confirm</button>
            <button type="button" id="cancelAddProductForm" class="btn btn-secondary">Cancel</button>
        </form>
    </div>

    <section>
        <h2>Manage Products</h2>
        <div class="category-filter">
            <form method="get" action="">
                <label for="category">Filter by Category:</label>
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
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <?php if ($editing_product_id === (int)$product['product_id']): ?>
                        <!-- Edit Form -->
                        <tr>
                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                <input type="hidden" name="current_image" value="<?php echo $product['image']; ?>">
                                <input type="hidden" name="category" value="<?php echo htmlspecialchars($selected_category_id); ?>">
                                <td><?php echo $product['product_id']; ?></td>
                                <td><input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required></td>
                                <td><textarea name="description"><?php echo htmlspecialchars($product['description']); ?></textarea></td>
                                <td><input type="number" name="price" value="<?php echo $product['price']; ?>" step="0.01"></td>
                                <td><input type="number" name="stock_quantity" value="<?php echo $product['stock_quantity']; ?>"></td>
                                <td>
                                    <input type="file" name="image">
                                    <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="50">
                                </td>
                                <td>
                                    <button type="submit" name="update_product">Save</button>
                                    <a href="manage_products.php<?php echo $selected_category_id ? '?category=' . htmlspecialchars($selected_category_id) : ''; ?>">Cancel</a>
                                </td>
                            </form>
                        </tr>
                    <?php else: ?>
                        <!-- Regular Row -->
                        <tr>
                            <td><?php echo $product['product_id']; ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($product['description'])); ?></td>
                            <td>&#8369;<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo $product['stock_quantity']; ?></td>
                            <td><img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" width="50"></td>
                            <td>
                                <a href="manage_products.php?edit_product_id=<?php echo $product['product_id']; ?>&category=<?php echo htmlspecialchars($selected_category_id); ?>">Edit</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>


</body>

</html>