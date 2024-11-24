<?php
// Start session
session_start();

// Include database configuration
include '../config/db.php';

/*// Check if user is logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit;
}*/

// Handle adding a new product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $image = $_FILES['image']['name'];

    // Upload product image
    $target_dir = "../assets/images/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    // Insert product into the database
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $description, $price, $image);
    $stmt->execute();
    $stmt->close();

    $message = "Product added successfully!";
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

// Handle confirming/rejecting an order
if (isset($_GET['order_id']) && isset($_GET['action'])) {
    $order_id = $_GET['order_id'];
    $action = $_GET['action']; // 'confirm' or 'reject'

    $status = $action === 'confirm' ? 'Confirmed' : 'Rejected';
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    $stmt->close();

    $message = "Order $status!";
}

// Fetch all products
$products = $conn->query("SELECT * FROM products")->fetch_all(MYSQLI_ASSOC);

// Fetch all orders
$orders = $conn->query("SELECT * FROM orders")->fetch_all(MYSQLI_ASSOC);
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
    <?php include '../includes/header.php'; ?>

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
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['description']); ?></td>
                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                            <td><img src="../assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="50"></td>
                            <td>
                                <a href="admin.php?delete_product_id=<?php echo $product['id']; ?>" class="btn btn-danger">Delete</a>
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
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_id']); ?></td>
                            <td><?php echo $order['quantity']; ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                            <td>
                                <a href="admin.php?order_id=<?php echo $order['id']; ?>&action=confirm" class="btn btn-success">Confirm</a>
                                <a href="admin.php?order_id=<?php echo $order['id']; ?>&action=reject" class="btn btn-danger">Reject</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>

</html>