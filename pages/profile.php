<?php
include '../includes/header.php';
// Include database configuration
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get user data
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);

    // Update user data in the database
    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, address = ?, phone_number = ? WHERE username = ?");
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $address, $phone, $username);
    $stmt->execute();
    $stmt->close();

    // Update session data if needed
    $message = "Profile updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>

    <main>
        <h1>My Profile</h1>

        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="post" action="profile.php">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>

</html>