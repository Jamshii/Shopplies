<?php
include '../includes/header.php';
include '../config/db.php';

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    $username = "";
} else {
    $username = $_SESSION['username'];

    // Get the customer_id based on the username
    $stmt = $conn->prepare("SELECT customer_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $user_id = $user['customer_id']; // Store the user_id from the users table
}


// Get product details
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    echo "<p>Product not found.</p>";
    include '../includes/footer.php';
    exit;
}

// Add to cart logic
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = max(1, intval($_POST['quantity'])); // Ensure quantity is at least 1

    // Check if the product is already in the cart for the logged-in user
    $stmt = $conn->prepare("SELECT cart_id, quantity FROM shopping_cart WHERE customer_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $cart_item = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($cart_item) {
        // Update the quantity if the product is already in the cart
        $new_quantity = $cart_item['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE shopping_cart SET quantity = ? WHERE cart_id = ?");
        $stmt->bind_param("ii", $new_quantity, $cart_item['cart_id']);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert a new record if the product is not in the cart
        $stmt = $conn->prepare("INSERT INTO shopping_cart (customer_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt->execute();
        $stmt->close();
    }

    $message = "Product added to cart successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>

    <div class="product-wrapper">
        <div class="product-details">
            <!-- Product Image -->
            <div class="product-image">
            <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="max-width: 300px;">
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="product-description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <!-- Price & Stock -->
                <div class="price-stock">
                    <p class="price"><strong>Price:</strong> &#8369;<?php echo number_format($product['price'], 2); ?></p>
                    <p class="stock"><strong>Stock:</strong> <?php echo $product['stock_quantity'] > 0 ? $product['stock_quantity'] : "Out of Stock"; ?></p>
                </div>

                <?php if ($product['stock_quantity'] > 0): ?>
                        <?php if (!isset($_SESSION['username'])): ?>
                            <!-- User not logged in -->
                            <p>Please <a href="login.php">log in</a> to add this product to your cart.</p>
                        <?php else: ?>
                            <!-- User logged in and stock is available -->
                            <form method="post" action="">
                                <label for="quantity">Quantity:</label>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
                                <?php if ($message): ?>
                                <div class="message" style="color:green;"><?php echo $message; ?></div>
                            <?php endif; ?>
                                <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Product is out of stock -->
                        <p class="out-of-stock" style="color:red;">This product is currently out of stock.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- <div class="clear"></div> -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>