<?php
include '../includes/header.php';
include '../config/db.php';

if (!isset($_GET['order_id'])) {
    echo "No order ID found!";
    include '../includes/footer.php';
    exit;
}

$order_id = $_GET['order_id'];

// Fetch the order details
$stmt = $conn->prepare("SELECT o.order_id, o.total_amount, o.order_date, o.delivery_date, o.order_status, 
    CONCAT(u.first_name, ' ', u.last_name)AS customer_name
    FROM orders o
    JOIN users u ON o.customer_id = u.customer_id
    WHERE o.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_details = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order_details) {
    echo "<p>Order not found.</p>";
    include '../includes/footer.php';
    exit;
}

// Fetch order items
$stmt = $conn->prepare("SELECT p.name AS product_name, oc.quantity, (oc.quantity * p.price) AS subtotal 
    FROM order_items oc
    JOIN products p ON oc.product_id = p.product_id
    WHERE oc.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <main>
        <h1>Thank You for Your Order!</h1>

        <h2>Order Summary</h2>
        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_details['order_id']); ?></p>
        <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order_details['customer_name']); ?></p>
        <p><strong>Total Amount:</strong> $<?php echo number_format($order_details['total_amount'], 2); ?></p>
        <p><strong>Order Date:</strong> <?php echo date('F j, Y', strtotime($order_details['order_date'])); ?></p>
        <p><strong>Estimated Delivery Date:</strong> <?php echo date('F j, Y', strtotime($order_details['delivery_date'])); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($order_details['order_status']); ?></p>

        <h3>Ordered Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p><a href="order.php">View All Orders</a></p>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>

</html>