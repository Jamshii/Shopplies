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

// Use today's date as the purchase date
$purchaseDate = date('Y-m-d'); // Current date in MySQL format
$delivery_date = calculateDeliveryDate($purchaseDate);

function calculateDeliveryDate($purchaseDate)
{
    // Convert purchase date to DateTime object
    $date = new DateTime($purchaseDate);
    $dayOfWeek = $date->format('N'); // 1 = Monday, ..., 7 = Sunday

    // If the day is Saturday
    if ($dayOfWeek == 6) {
        $date->modify('+1 day');
    }

    // Add 3 weekdays
    $deliveryDays = 0;
    while ($deliveryDays < 3) {
        $date->modify('+1 day');
        if ($date->format('N') < 6) { // Only count weekdays (1-5)
            $deliveryDays++;
        }
    }

    return $date;
}

// Get today's date
$purchaseDate = date('Y-m-d'); // Current date
$deliveryDate = calculateDeliveryDate($purchaseDate);

// For database storage (MySQL DATE format)
$mysqlDate = $deliveryDate->format('Y-m-d');

// For display (formatted)
$displayDate = $deliveryDate->format('l, F j, Y');

function generateOrderToken()
{
    return bin2hex(random_bytes(16)); // Generates a 32-character hexadecimal string
}

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $order_date = date('Y-m-d H:i:s');
    $order_token = generateOrderToken();
    $status = 'Pending';

    // Insert the order
    $stmt = $conn->prepare("INSERT INTO orders (customer_id, order_token, total_amount, order_date, order_status, delivery_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdsss", $customer_id, $order_token, $total, $order_date, $status, $mysqlDate);
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

    // Redirect to order confirmation using order_token
    header("Location: order_confirmation.php?order_token=$order_token");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
    <main class="checkout-container">
        <h1 class="text-center mb-4">Checkout</h1>

        <?php if (!empty($cart_items)): ?>
            <section class="order-summary mb-4">
                <h2>Order Summary</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
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
                                    <td>&#8369;<?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>&#8369;<?php echo number_format($item['subtotal'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="checkout-details">
                <h3>Total: <span class="total-amount">&#8369;<?php echo number_format($total, 2); ?></span></h3>
                <p class="mb-4"><strong>Estimated Delivery Date:</strong> <?php echo $displayDate; ?></p>

                <form method="post" action="">
                    <button type="submit" name="checkout" class="btn btn-primary btn-lg w-100">Place Order</button>
                </form>
            </section>
        <?php else: ?>
            <section class="empty-cart text-center">
                <p class="lead">Your cart is empty.</p>
                <a href="homepage.php" class="continue-shopping-link">Continue Shopping</a>
            </section>
        <?php endif; ?>
    </main>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include '../includes/footer.php'; ?>
</body>

</html>

