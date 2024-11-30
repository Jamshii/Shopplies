<?php
include '../includes/header.php';
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../pages/login.php");
    exit;
}

$username = $_SESSION['username'];

// Get the customer_id based on the username
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

// Fetch cart items
$stmt = $conn->prepare("
    SELECT c.cart_id, p.product_id, p.name, p.price, c.quantity, (p.price * c.quantity) AS subtotal
    FROM shopping_cart c
    JOIN products p ON c.product_id = p.product_id
    WHERE c.customer_id = ?
");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($cart_items)) {
    echo "<p>Your cart is empty. <a href='homepage.php'>Continue Shopping</a></p>";
    include '../includes/footer.php';
    exit;
}

// Calculate total
$total = array_sum(array_column($cart_items, 'subtotal'));

// Calculate estimated delivery date (current date + 3 days)
$delivery_date = date('Y-m-d', strtotime('+3 days')); // MySQL DATE format

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $order_date = date('Y-m-d H:i:s');
    $status = 'Pending';

    // Insert the order
    $stmt = $conn->prepare("INSERT INTO orders (customer_id, total_amount, order_date, order_status, delivery_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("idsss", $customer_id, $total, $order_date, $status, $delivery_date);
    $stmt->execute();
    $order_id = $stmt->insert_id; // Get the inserted order ID
    $stmt->close();

    // Insert order items
    foreach ($cart_items as $item) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $stmt->execute();
        $stmt->close();
    }

    // Clear the shopping cart
    $stmt = $conn->prepare("DELETE FROM shopping_cart WHERE customer_id = ?");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to order confirmation
    header("Location: order_confirmation.php?order_id=$order_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <main>
        <h1>Checkout</h1>

        <h2>Order Summary</h2>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Total: $<?php echo number_format($total, 2); ?></h3>
        <p><strong>Estimated Delivery Date:</strong> <?php echo $delivery_date; ?></p>

        <form method="post" action="">
            <button type="submit" name="checkout" class="btn btn-primary">Place Order</button>
        </form>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>

</html>