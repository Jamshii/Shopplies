<?php
session_start();
include '../config/db.php'; // Include database connection

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if admin credentials are used
    if ($email === 'admin' && $password === 'admin') {
        $_SESSION['user_id'] = 0; // Assign a unique ID for admin
        $_SESSION['user_name'] = 'Admin';
        $_SESSION['is_admin'] = true; // Flag to identify admin

        // Redirect to admin page
        header("Location: admin.php");
        exit();
    }

    // If not admin, proceed with normal user authentication
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Check if the user exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user info in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['is_admin'] = false; // Regular user

            // Redirect to home page
            header("Location: home.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>

<?php include '../includes/header.php'; ?>
<h1>Log In</h1>

<!-- Display error messages -->
<?php if ($error): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<!-- Login Form -->
<form method="post" action="">
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" required>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <button type="submit">Log In</button>
</form>
<a href="register.php">Don't have an account? Register</a>
<?php include '../includes/footer.php'; ?>