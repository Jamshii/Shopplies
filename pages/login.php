<?php
include '../includes/header.php';
include '../config/db.php';

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
    $query = "SELECT * FROM users WHERE BINARY username = '$username'";
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
            header("Location: product_list.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "No account found with that username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
    <!-- Login Form -->
    <!-- Login Page Section -->
    <section class="login-page">
        <!-- Left Side: Image -->
        <div class="left-side">
            <img class="login-image" src="../assets/images/Background.png" alt="Login Image">
        </div>

        <!-- Right Side: Login Form -->
        <div class="right-side">
            <div class="container">
                <h1>Login</h1>

                <form action="" method="POST" class="login-form">
                    <div class="login-info">
                        <label for="username">Username</label>
                        <div class="form-group">
                            <input type="text" id="username" name="username" placeholder="Enter your username" required>
                        </div>

                        <label for="password">Password</label>
                        <div class="form-group">
                            <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                    </div>
                    <?php if ($error): ?>
                        <p style="color: red;"><?php echo $error; ?></p>
                    <?php endif; ?>

                    <button type="submit" class="submit-btn">Login</button>

                    <!-- Forgot Password Link -->
                    <div class="form-footer">
                        <p>Don't have an account? <a href="register.php">Register here</a></p>
                        <p><a href="forgot_password.php">Forgot your password?</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>
</body>

</html>