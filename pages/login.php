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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    
    <style>
      .bottom {
        background-color: white;
        height: 30vh;
        display: flex;
      }
    </style>
    
</head>
<body>
    <!-- Login Form -->
    <!-- Login Page Section -->
    <section class="login-page">
        <!-- Left Side: Image -->
        <div class="left-side">
            <img src="../assets/images/Background.png" alt="Login Image" width="600" height="600">
        </div>
        

        <!-- Right Side: Login Form -->
        <div class="right-side">
            <div class="container">
                <h1>Login</h1>
                <p>Please enter your username and password to log in.</p>

                <form action="" method="POST" class="login-form">
                    <div class = "login-info">
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

    <section class="bottompage">
    <div class="first">
        <h1>CUSTOMER SERVICE</h1>
        <div class="Customer">
            <p><a href="wala pa functionality">Help Center</a></p>
            <p><a href="wala pa functionality">Return & Refund</a></p>
            <p><a href="wala pa functionality">Contact Us</a></p>
        </div>
    </div>
    <div class="second">
        <h1>ABOUT SHOPPLIES</h1>
        <div class="About">
            <p><a href="wala pa functionality">Privacy and Policy</a></p>
            <p><a href="wala pa functionality">Shopplies Policies</a></p>
        </div>
    </div>

    <div class="third">
        <h1>FOLLOW US</h1>
        <div class="Follow">
            <p><a href="wala pa functionality">Instagram</a></p>
            <p><a href="wala pa functionality">Facebook</a></p>
            <p><a href="wala pa functionality">Twitter</a></p>
            <p><a href="wala pa functionality">LinkedIn</a></p>
        </div>
    </div>
    </section>
     
<?php include '../includes/footer.php'; ?>
</body>
</html>