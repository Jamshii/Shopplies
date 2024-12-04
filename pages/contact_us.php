<?php
include '../includes/header.php';
// Include database configuration
include '../config/db.php';

$user = null; // Default: no user logged in
if (isset($_SESSION['username'])) { // Check if user is logged in
    $username = $_SESSION['username'];

    // Fetch user details from the database
    $stmt = $conn->prepare("SELECT first_name, last_name, phone_number, email FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc(); // Fetch user details as associative array
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));  // Remove spaces and sanitize name
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL); // Sanitize email
    $phone_number = htmlspecialchars(trim($_POST['phone_number']));  // Sanitize phone number
    $message = strip_tags(trim($_POST['message']));

    // Check if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Insert the message into the database
    $stmt = $conn->prepare("INSERT INTO messages (name, email, phone_number, message, date_sent) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $name, $email, $phone_number, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Your message has been sent successfully!');</script>";
    } else {
        echo "<script>alert('Failed to send your message. Please try again.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }

        .social-media {
            text-align: center;
            margin-bottom: 20px;
        }

        .social-media a {
            margin: 0 10px;
            text-decoration: none;
            color: #007BFF;
        }

        .social-media a:hover {
            text-decoration: underline;
        }

        .form-container {
            margin-top: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            display: flex;
            gap: 10px;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        textarea {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        button {
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h1>Contact Us</h1>
    <p>Have questions? In need of assistance? We’d be glad to help. </p>
    <p>Shopplies doesn’t just provide a seamless and unique shopping experience, but also excellent customer service. </p>
    <p>Message us for more inquiries about our available products, concerns and feedback. We look forward to assisting you with anything! </p>
    <div class="container">
        <!-- Comment Form -->
        <div class="form-container">
            <h2>Send Us a Message</h2>
            <form method="post" action="contact_us.php">
                <div class="form-group">
                    <div style="flex: 1;">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name"
                            value="<?php echo $user ? htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) : ''; ?>"
                            placeholder="Enter your name"
                            <?php echo $user ? 'readonly' : 'required'; ?>>
                    </div>
                    <div style="flex: 1;">
                        <label for="phone">Your Phone</label>
                        <input type="tel" id="phone" name="phone_number"
                            value="<?php echo $user ? htmlspecialchars($user['phone_number']) : ''; ?>"
                            placeholder="Enter your phone number"
                            <?php echo $user ? 'readonly' : 'required'; ?>>
                    </div>
                </div>

                <label for="email">Your Email</label>
                <input type="email" id="email" name="email"
                    value="<?php echo $user ? htmlspecialchars($user['email']) : ''; ?>"
                    placeholder="Enter your email"
                    <?php echo $user ? 'readonly' : 'required'; ?>>

                <label for="message">Your Message</label>
                <textarea id="message" name="message" rows="5" required placeholder="Enter your message"></textarea>

                <button type="submit" name="submit_message">Send Message</button>
            </form>

            <!-- Social Media Links -->
            <div class="social-media">
                <p>Connect with us on social media:</p>
                <a href="https://facebook.com" target="_blank">Facebook</a>
                <a href="https://twitter.com" target="_blank">Twitter</a>
                <a href="https://instagram.com" target="_blank">Instagram</a>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>