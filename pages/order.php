<?php
include '../includes/header.php';
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../pages/login.php");
    exit;
}

$username = $_SESSION['username'];

// Fetch customer ID based on username
$stmt = $conn->prepare("SELECT customer_id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "<p>User not found.</p>";
    include '../includes/footer.php';
    exit;
}

$customer_id = $user['customer_id'];
$message = "";

// Cancel an order if requested
// Handle order cancellation if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['status'];

    // Ensure the status is being set to "Cancelled"
    if ($new_status === 'Cancelled') {
        // Update the order status to "Cancelled" only if it is currently "Pending"
        $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ? AND customer_id = ? AND order_status = 'Pending'");
        $stmt->bind_param("sii", $new_status, $order_id, $customer_id);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $message = "Order cancelled successfully.";
        } else {
            $message = "Failed to cancel the order. Only pending orders can be cancelled.";
        }

        $stmt->close();
    }
}


// Fetch orders for the logged-in user
$stmt = $conn->prepare("
    SELECT 
        o.order_id, 
        o.order_token,
        o.total_amount, 
        o.order_date, 
        o.delivery_date, 
        o.order_status, 
        GROUP_CONCAT(p.name ORDER BY p.name SEPARATOR ', ') AS product_names,
        GROUP_CONCAT(oc.quantity ORDER BY p.name SEPARATOR ', ') AS quantities,
        GROUP_CONCAT(p.image ORDER BY p.name SEPARATOR ', ') AS product_images
    FROM orders o
    JOIN order_items oc ON o.order_id = oc.order_id
    JOIN products p ON oc.product_id = p.product_id
    WHERE o.customer_id = ?
    GROUP BY o.order_id
    ORDER BY o.order_date DESC
");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
    <main class = "order-page">
        <h1>Your Orders</h1>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (!empty($orders)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product/s</th>
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

                        // Exploding the concatenated product details (product names, quantities, images)
                        $product_names = explode(',', $order['product_names']);
                        $quantities = explode(',', $order['quantities']);
                        $product_images = explode(',', $order['product_images']);
                        ?>
                        <tr>
                            <td><?php echo $order['order_id']; ?></td>
                            <td>
                                <?php
                                // Loop through and display product names, quantities, and images
                                for ($i = 0; $i < count($product_names); $i++) {
                                    echo htmlspecialchars($product_names[$i]) . " (Quantity: " . htmlspecialchars($quantities[$i]) . ")<br>";

                                    // Displaying the product image after trimming any spaces
                                    $image_path = trim($product_images[$i]); // Trim leading/trailing spaces
                                    echo "<img src='../assets/images/" . htmlspecialchars($image_path) . "' alt='" . htmlspecialchars($product_names[$i]) . "' style='width: 50px; height: 50px; margin-top: 5px;' /><br>";
                                }
                                ?>
                            </td>
                            <td>&#8369;<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><?php echo date('F j, Y', strtotime($order['order_date'])); ?></td>
                            <td><?php echo date('F j, Y', strtotime($order['delivery_date'])); ?></td>
                            <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                            <td>
                                <?php if ($order['order_status'] === 'Pending'): ?>
                                    <a href="order_confirmation.php?order_token=<?= htmlspecialchars($order['order_token']) ?>">View Receipt</a>
                                    <form method="post" action="">
                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                        <input type="hidden" name="status" value="Cancelled">
                                        <button type="submit" name="update_status" class="btn btn-danger">Cancel</button>
                                    </form>
                                <?php else: ?>
                                    <!-- No buttons for other statuses -->
                                    <a href="order_confirmation.php?order_token=<?= htmlspecialchars($order['order_token']) ?>">View Receipt</a>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have no orders yet. <a href="homepage.php">Start shopping now!</a></p>
        <?php endif; ?>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>

</html>