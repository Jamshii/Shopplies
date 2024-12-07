<?php
include '../includes/header.php';
include '../config/db.php';
$message = "";
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Add Bootstrap for modern UI -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .mb-4 {
            text-align: center;
            margin-top: 20px;
        }

        .message {
            margin-bottom: 20px;
        }
        table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f1f1f1;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .category-filter {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .btn {
            margin-right: 5px;
        }

        .add-product {
    padding: 0.5rem;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
            background-color: #00c864;
        }

        .add-product:hover {
            background-color: #009c4e;;
        }

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Manage Products</h1>

        <!-- Success/Error Message -->
        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Add Product Modal -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Product Name</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="number" id="price" name="price" class="form-control" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="stock">Stock Quantity</label>
                                <input type="number" id="stock" name="stock" class="form-control" min="0" required>
                            </div>
                            <div class="form-group">
                                <label for="category">Category</label>
                                <select id="category" name="category_id" class="form-select" required>
                                    <option value="">Select a category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['category_id']; ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" id="image" name="image" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="add_product" class="btn btn-success">Add Product</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Category Filter -->
        <div class="category-filter">
            <form method="get" action="">
                <label for="category" class="form-label">Filter by Category:</label>
                <select id="category" name="category" class="form-select" onchange="this.form.submit()">
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

                        <!-- Add Product Button -->
        <button class="add-product" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>

        <!-- Product Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
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
                        <tr>
                            <td><?php echo $product['product_id']; ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($product['description'])); ?></td>
                            <td>&#8369;<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo $product['stock_quantity']; ?></td>
                            <td><img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" width="50"></td>
                            <td>
                                <!-- Edit Button -->
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal<?php echo $product['product_id']; ?>">Edit</button>
                            </td>
                        </tr>

                        <!-- Edit Product Modal -->
                        <div class="modal fade" id="editProductModal<?php echo $product['product_id']; ?>" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                            <input type="hidden" name="current_image" value="<?php echo $product['image']; ?>">
                                            
                                            <div class="form-group">
                                                <label for="name">Product Name</label>
                                                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea id="description" name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="price">Price</label>
                                                <input type="number" id="price" name="price" class="form-control" value="<?php echo $product['price']; ?>" step="0.01" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="stock">Stock Quantity</label>
                                                <input type="number" id="stock" name="stock" class="form-control" value="<?php echo $product['stock_quantity']; ?>" min="0" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="category">Category</label>
                                                <select id="category" name="category_id" class="form-select" required>
                                                    <option value="">Select a category</option>
                                                    <?php foreach ($categories as $category): ?>
                                                        <option value="<?php echo $category['category_id']; ?>" <?php echo $category['category_id'] == $product['category_id'] ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($category['name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="image">Image</label>
                                                <input type="file" id="image" name="image" class="form-control">
                                                <small>Leave empty to keep current image.</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="update_product" class="btn btn-warning">Update Product</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
