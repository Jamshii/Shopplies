<?php
include '../includes/header.php';

// Include database configuration
include '../config/db.php';

// Check if user is logged in as admin
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
    $category_id = intval($_POST['category_id']); // Get the selected category ID
    $image = $_FILES['image']['name'];

    // Upload product image
    $target_dir = "../assets/images/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    // Insert product into the database
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

// Handle deleting a product
if (isset($_GET['delete_product_id'])) {
    $product_id = $_GET['delete_product_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();

    $message = "Product deleted successfully!";
}

// Fetch all products
$products = $conn->query("SELECT * FROM products")->fetch_all(MYSQLI_ASSOC);

// Update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    $stmt->close();

    $message = "Order status updated successfully.";
}

// Fetch all orders with related order items and customer details
$sql = "
    SELECT 
        o.order_id, 
        o.customer_id, 
        u.username AS customer_name, 
        o.total_amount, 
        o.order_date, 
        o.delivery_date, 
        o.order_status, 
        p.name AS product_name, 
        oc.quantity
    FROM orders o
    JOIN order_items oc ON o.order_id = oc.order_id
    JOIN products p ON oc.product_id = p.product_id
    JOIN users u ON o.customer_id = u.customer_id
    ORDER BY o.order_date DESC
";
$orders = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

// Fetch categories from the database
$categories = [];
$sql = "SELECT category_id, name FROM categories";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <main>
        <h1>Admin Dashboard</h1>

        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Add Product Form -->
        <section>
            <h2>Add New Product</h2>
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
        </section>

        <!-- Manage Products -->
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
                                <a href="admin.php?delete_product_id=<?php echo $product['product_id']; ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Manage Orders -->
        <section>
            <h2>Manage Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo $order['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo $order['quantity']; ?></td>
                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><?php echo date('F j, Y', strtotime($order['order_date'])); ?></td>
                            <td><?php echo date('F j, Y', strtotime($order['delivery_date'])); ?></td>
                            <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                            <td>
                                <!-- Update order status action buttons -->
                                <form method="post" action="">
                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                    <select name="status">
                                        <option value="Pending" <?php echo $order['order_status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Confirmed" <?php echo $order['order_status'] === 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                        <option value="Completed" <?php echo $order['order_status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="Cancelled" <?php echo $order['order_status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        <option value="Refunded" <?php echo $order['order_status'] === 'Refunded' ? 'selected' : ''; ?>>Refunded</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-primary">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <?php //include '../includes/footer.php'; 
    ?>
</body>

</html>