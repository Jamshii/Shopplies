<?php
include '../includes/header.php';
include '../config/db.php'; // Include database connection

/*// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: homepage.php");
    exit();
}*/

$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if admin credentials are used
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['user_id'] = 0; // Assign a unique ID for admin
        $_SESSION['username'] = 'admin';
        $_SESSION['is_admin'] = true; // Flag to identify admin

        // Redirect to admin page
        header("Location: manage_products.php");
        exit();
    }

    // If not admin, proceed with normal user authentication
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Check if the user exists
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user info in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = false; // Regular user

            // Redirect to home page
            header("Location: homepage.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "No account found with that username.";
    }
}
?>
<h1>Log In</h1>



<!-- Login Form -->
<form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <!-- Display error messages -->
    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <a href="register.php">Don't have an account? Register</a><br>
    <a href="forgot_password.php">Forgot Password?</a><br>
    <button type="submit">Log In</button>
</form>

<?php include '../includes/footer.php'; ?>