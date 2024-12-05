<?php

// Include database configuration
include '../config/db.php';

// Initialize variables
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate inputs
    if (
        empty($first_name) || empty($last_name) ||
        empty($username) || empty($email) ||
        empty($password) || empty($confirm_password)
    ) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
        /*} elseif (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        $error = "Invalid phone number. It should contain 10-15 digits.";*/
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if the username or email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert the user into the database
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, password) VALUES (?, ?, /*?, ?,*/ ?, ?, ?)");
            $stmt->bind_param('sssss', $first_name, $last_name, $username, $email, $hashed_password);

            if ($stmt->execute()) {
                $success = "Registration successful. You can now <a href='login.php'>log in</a>.";
            } else {
                $error = "Error: Could not register. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>
<?php include '../includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
<div class="register-container">
        <div class="register">
            <h1>Register</h1>
            <form method="POST" class="register-form">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" required>
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                </div>
                <button type="submit" class="submit-btn">Register</button>
            </form>
            <?php if ($error): ?>
                <div class="error" style="color: red;"><?php echo $error; ?></div>
            <?php endif; ?>
            <p>Already have an account? <a href="login.php">Log in here</a>.</p>
        </div>
    </div>
</body>

<?php include '../includes/footer.php'; ?>

</html>