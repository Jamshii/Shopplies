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

$user_id = $user['customer_id']; // Store the user_id from the users table

$message = "";

// Add to cart logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = max(1, intval($_POST['quantity'])); // Ensure quantity is at least 1

    // Update the shopping_cart table with the new quantity
    $stmt = $conn->prepare("UPDATE shopping_cart SET quantity = ? WHERE cart_id = ? AND customer_id = ?");
    $stmt->bind_param("iii", $quantity, $cart_id, $user_id);
    $stmt->execute();
    $stmt->close();

    $message = "Cart updated successfully!";
}

// Remove item from cart
if (isset($_GET['remove_cart_id'])) {
    $cart_id = $_GET['remove_cart_id'];

    $stmt = $conn->prepare("DELETE FROM shopping_cart WHERE cart_id = ? AND customer_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();
    $stmt->close();

    $message = "Item removed from cart.";
}

// Fetch cart items
$stmt = $conn->prepare("
    SELECT c.cart_id, p.name, p.price, p.image, c.quantity, (p.price * c.quantity) AS subtotal
    FROM shopping_cart c
    JOIN products p ON c.product_id = p.product_id
    WHERE c.customer_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Calculate total
$total = array_sum(array_column($cart_items, 'subtotal'));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <main>
        <h1>Your Shopping Cart</h1>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (!empty($cart_items)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" width="50"></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>&#8369;<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                    <button type="submit" name="update_quantity" class="btn btn-secondary">Update Quantity</button>
                                </form>
                            </td>
                            <td>&#8369;<?php echo number_format($item['subtotal'], 2); ?></td>
                            <td>
                                <a href="cart.php?remove_cart_id=<?php echo $item['cart_id']; ?>" class="btn btn-danger">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Total: &#8369;<?php echo number_format($total, 2); ?></h3>
            <form method="post" action="checkout.php">
                <button type="submit" class="btn btn-primary">Checkout</button>
            </form>
        <?php else: ?>
            <p>Your cart is empty. <a href="homepage.php">Continue Shopping</a></p>
        <?php endif; ?>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>

</html>