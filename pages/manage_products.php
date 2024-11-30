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
    $description = trim($_POST['description']);
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
}

// Handle updating stock quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $product_id = $_POST['product_id'];
    $new_stock = $_POST['stock'];

    $stmt = $conn->prepare("UPDATE products SET stock_quantity = ? WHERE product_id = ?");
    $stmt->bind_param("ii", $new_stock, $product_id);
    $stmt->execute();
    $stmt->close();

    $message = "Stock updated successfully!";
}

// // Handle product update
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
//     foreach ($_POST['name'] as $product_id => $name) {
//         $description = $_POST['description'][$product_id];
//         $price = $_POST['price'][$product_id];
//         $stock = $_POST['stock'][$product_id];

//         // Handle image upload
//         if (isset($_FILES['image']['name'][$product_id]) && $_FILES['image']['error'][$product_id] === 0) {
//             $image_tmp = $_FILES['image']['tmp_name'][$product_id];
//             $image_name = $_FILES['image']['name'][$product_id];
//             $image_path = "../assets/images/" . basename($image_name);
//             move_uploaded_file($image_tmp, $image_path);
//         } else {
//             $image_name = $_POST['current_image'][$product_id]; // retain the old image if no new file is uploaded
//         }

//         // Update product details
//         $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock_quantity = ?, image = ? WHERE product_id = ?");
//         $stmt->bind_param("ssdiss", $name, $description, $price, $stock, $image_name, $product_id);
//         $stmt->execute();
//         $stmt->close();
//     }

//     $message = "Product details updated successfully.";
// }


// Handle deleting a product
if (isset($_GET['delete_product_id'])) {
    $product_id = $_GET['delete_product_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();
    $message = "Product deleted successfully!";
}

// Fetch all products
$products = $conn->query("SELECT * FROM products")->fetch_all(MYSQLI_ASSOC);

// Fetch categories
$categories = $conn->query("SELECT category_id, name FROM categories")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Products</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <h1>Manage Products</h1>
    <?php if (isset($message)) echo "<div>$message</div>"; ?>
    <!-- Add Product Form -->
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
        <button type="submit" name="add_product">Add Product</button>
    </form>

    <section>
        <h2>Manage Products</h2>
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
                    <tr>
                        <td><?php echo $product['product_id']; ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo $product['stock_quantity']; ?></td>
                        <td><img src="../assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="50"></td>
                        <td>
                            <!-- Update Stock -->
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                <input type="number" name="stock" value="<?php echo $product['stock_quantity']; ?>" min="0" style="width:60px;">
                                <button type="submit" name="update_stock">Update Stock</button>
                            </form>
                            <!-- Delete Product -->
                            <a href="manage_products.php?delete_product_id=<?php echo $product['product_id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>


</body>

</html>