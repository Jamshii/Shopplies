<?php
include '../config/db.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

// Get the user ID from the session
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT customer_id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$user_id = $user['customer_id'];
$stmt->close();

// Validate the input
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'], $_POST['quantity'])) {
    $cart_id = intval($_POST['cart_id']);
    $quantity = max(1, intval($_POST['quantity'])); // Ensure quantity is at least 1

    // Check if the cart item belongs to the logged-in user
    $stmt = $conn->prepare("SELECT cart_id FROM shopping_cart WHERE cart_id = ? AND customer_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();
    $cart_item = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$cart_item) {
        http_response_code(403); // Forbidden
        echo json_encode(['status' => 'error', 'message' => 'Invalid cart item']);
        exit;
    }

    // Update the quantity in the database
    $stmt = $conn->prepare("UPDATE shopping_cart SET quantity = ? WHERE cart_id = ?");
    $stmt->bind_param("ii", $quantity, $cart_id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Cart updated successfully']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['status' => 'error', 'message' => 'Failed to update cart']);
    }
    $stmt->close();
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
