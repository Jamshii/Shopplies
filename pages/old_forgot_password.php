<?php
session_start();
require_once '../config/db.php'; // Include database connection

$message = "";
$show_reset_form = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if it's the "forgot password" form
    if (isset($_POST['username'])) {
        // Handle username check for "forgot password"
        $username = trim($_POST['username']);

        // Check if username exists in the database
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Store username in session to move to the reset password step
            $_SESSION['reset_username'] = $username;
            $show_reset_form = true;  // Show the reset form
        } else {
            $message = "Username not found.";
        }
    } elseif (isset($_POST['new_password'])) {
        // Handle password reset
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        if ($new_password !== $confirm_password) {
            $message = "Passwords do not match.";
        } elseif (strlen($new_password) < 6) {
            $message = "Password must be at least 6 characters.";
        } else {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $query = "UPDATE users SET password = ? WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $hashed_password, $_SESSION['reset_username']);
            if ($stmt->execute()) {
                unset($_SESSION['reset_username']);
                header("Location: login.php?message=Password+reset+successful");
                exit();
            } else {
                $message = "An error occurred. Please try again.";
            }
        }
    }
} else {
    $show_reset_form = false;  // Don't show reset form initially
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <h1>Forgot Password</h1>

    <!-- Forgot Password Form -->
    <?php if (!$show_reset_form): ?>
        <form method="POST">
            <label for="username">Enter your username:</label>
            <input type="text" id="username" name="username" required>
            <button type="submit">Submit</button>
            <a href="login.php">
                <button type="button">Cancel</button>
            </a>
        </form>
    <?php endif; ?>

    <!-- Display message if any -->
    <?php if (!empty($message)): ?>
        <p style="color: red;"><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- Reset Password Form (Hidden initially) -->
    <?php if ($show_reset_form): ?>
        <form method="POST">
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Reset Password</button>
            <a href="login.php">
                <button type="button">Cancel</button>
            </a>
        </form>
    <?php endif; ?>
</body>

</html>