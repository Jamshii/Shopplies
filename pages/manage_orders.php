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
        CONCAT(u.first_name, ' ', u.last_name) AS customer_name,
        u.address,
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
    <main class="manage-order-page">
    <div class="container">
        <h1>Manage Orders</h1>
        <?php if (isset($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Address</th>
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
                        <td><?php echo htmlspecialchars($order['address']); ?></td>
                        <td>
                            <?php for ($i = 0; $i < count($product_names); $i++): ?>
                                <div>
                                    <span><?php echo htmlspecialchars($product_names[$i]); ?></span><br>
                                    <img src="../assets/images/<?php echo htmlspecialchars(trim($product_images[$i])); ?>" alt="Product Image">
                                </div>
                            <?php endfor; ?>
                        </td>
                        <td>&#8369;<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo date('F j, Y', strtotime($order['order_date'])); ?></td>
                        <td><?php echo date('F j, Y', strtotime($order['delivery_date'])); ?></td>
                        <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                        <td class="actions">
                            <?php if ($order['order_status'] === 'Pending' || $order['order_status'] === 'Confirmed'): ?>
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
                            <?php else: ?>
                                <span>N/A</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </main>
</body>

</html>
