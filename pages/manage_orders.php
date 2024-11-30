<?php
include '../includes/header.php';
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['status'];

    // Update the status for the specific order in the orders table
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
        GROUP_CONCAT(CONCAT(p.name, ' (x', oc.quantity, ')') SEPARATOR ', ') AS product_details,
        GROUP_CONCAT(p.image ORDER BY p.name SEPARATOR ', ') AS product_images
    FROM orders o
    JOIN order_items oc ON o.order_id = oc.order_id
    JOIN products p ON oc.product_id = p.product_id
    JOIN users u ON o.customer_id = u.customer_id
    GROUP BY o.order_id
    ORDER BY o.order_date DESC;
";
$orders = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Manage Orders</title>
</head>

<body>
    <h1>Manage Orders</h1>
    <?php if (isset($message)) echo "<div>$message</div>"; ?>

    <!-- Manage Orders -->
    <section>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Product (Quantity)</th>
                    <th>Total Amount</th>
                    <th>Order Date</th>
                    <th>Delivery Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <?php
                    // Exploding product details and images
                    $product_names = explode(', ', $order['product_details']);
                    $product_images = explode(', ', $order['product_images']);
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td>
                            <?php
                            // Display product names, quantities, and images
                            for ($i = 0; $i < count($product_names); $i++) {
                                echo htmlspecialchars($product_names[$i]); // Product name and quantity
                                echo "<br>";
                                // Display the product image
                                $image_path = trim($product_images[$i]); // Remove any leading/trailing spaces
                                echo "<img src='../assets/images/" . htmlspecialchars($image_path) . "' alt='" . htmlspecialchars($product_names[$i]) . "' style='width: 50px; height: 50px; margin-top: 5px;' /><br>";
                            }
                            ?>
                        </td>
                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo date('F j, Y', strtotime($order['order_date'])); ?></td>
                        <td><?php echo date('F j, Y', strtotime($order['delivery_date'])); ?></td>
                        <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
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

            <!-- <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['product_details']); ?></td>
                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo date('F j, Y', strtotime($order['order_date'])); ?></td>
                        <td><?php echo date('F j, Y', strtotime($order['delivery_date'])); ?></td>
                        <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
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
            </tbody> -->
        </table>
    </section>
</body>

</html>