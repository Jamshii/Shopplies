<?php
include '../includes/header.php';
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../pages/login.php");
    exit;
}

$username = $_SESSION['username'];

// Fetch user details
$stmt = $conn->prepare("SELECT customer_id, address, phone_number FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

//Get customer id
$user_id = $user['customer_id']; // Store the user_id from the users table

//Check if user is found
if (!$user) {
    echo "<p>User not found.</p>";
    include '../includes/footer.php';
    exit;
}

// Check if address or contact is missing
if (empty($user['address']) || empty($user['contact'])) {
    $message_err = "Please add your address and contact information in your profile to proceed to checkout.";
    $address_missing = empty($user['address']);
}

$message = "";

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/script.js" defer></script>
</head>

<body>
    <main class="cart-page">
        <h1>Your Shopping Cart</h1>

        <?php if ($address_missing): ?>
            <div class="message_error"><?php echo $message_err; ?></div>
        <?php endif; ?>
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
                        <tr data-cart-id="<?= $item['cart_id'] ?>">
                            <td>
                                <img src="../assets/images/<?= htmlspecialchars($item['image']); ?>"
                                    alt="<?= htmlspecialchars($item['name']); ?>" width="50">
                            </td>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td>&#8369;<?= number_format($item['price'], 2); ?></td>
                            <td>
                                <input
                                    type="number"
                                    class="quantity-input"
                                    value="<?= $item['quantity']; ?>"
                                    min="1"
                                    data-cart-id="<?= $item['cart_id']; ?>"
                                    data-price="<?= $item['price']; ?>">
                            </td>
                            <td class="subtotal">&#8369;<?= number_format($item['subtotal'], 2); ?></td>
                            <td>
                                <a href="cart.php?remove_cart_id=<?= $item['cart_id']; ?>" class="btn btn-danger">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Total: &#8369;<span id="total"><?= number_format($total, 2); ?></span></h3>
            <form method="post" action="checkout.php">
                <button type="submit" <?= $address_missing ? 'disabled' : '' ?>>Checkout</button>
            </form>
        <?php else: ?>
            <p>Your cart is empty. <a href="product_list.php">Continue Shopping</a></p>
        <?php endif; ?>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>

</html>