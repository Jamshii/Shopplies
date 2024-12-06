<?php
include '../includes/header.php';
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle adding a new product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $description = strip_tags(trim($_POST['description']));
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);
    $category_id = intval($_POST['category_id']);
    $image = $_FILES['image']['name'];

    // Upload product image
    $target_dir = "../assets/images/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    // Insert product into database
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock_quantity, category_id, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiss", $name, $description, $price, $stock, $category_id, $image);
    $stmt->execute();
    $stmt->close();
    $message = "Product added successfully!";
    header("Location: manage_products.php?category=$category_id&message=" . urlencode($message));
    exit;
}

// Check for edit mode
$editing_product_id = isset($_GET['edit_product_id']) ? intval($_GET['edit_product_id']) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $product_id = intval($_POST['product_id']);
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $stock_quantity = intval($_POST['stock_quantity']);
    $current_image = $_POST['current_image'];
    $category = isset($_POST['category']) ? htmlspecialchars($_POST['category']) : '';

    // Check if a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($image_tmp, "../assets/images/" . $image);
    } else {
        $image = $current_image;
    }

    // Update product in the database
    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock_quantity = ?, image = ? WHERE product_id = ?");
    $stmt->bind_param("ssdisi", $name, $description, $price, $stock_quantity, $image, $product_id);

    if ($stmt->execute()) {
        $message = "Product updated successfully!";
        header("Location: manage_products.php?category=$category&message=" . urlencode($message));
        exit;
    } else {
        echo "Error updating product: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all products and categories
$categories = $conn->query("SELECT category_id, name FROM categories")->fetch_all(MYSQLI_ASSOC);
$selected_category_id = isset($_GET['category']) ? intval($_GET['category']) : null;

if ($selected_category_id) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ?");
    $stmt->bind_param("i", $selected_category_id);
    $stmt->execute();
    $products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $products = $conn->query("SELECT * FROM products")->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="../assets/js/script.js" defer></script>
    <style>
        /* Basic styling for the page */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
        }

        header nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
        }

        header .btn-danger {
            background-color: #dc3545;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f1f1f1;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <div>Admin Dashboard</div>
            <div>
                <a href="dashboard.php">Dashboard</a>
                <a href="manage_products.php" class="active">Products</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </nav>
    </header>

    <div class="container">
        <h1>Manage Products</h1>
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
        <?php endif; ?>

        <div>
            <button class="btn btn-primary" id="openModal">Add Product</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['product_id']; ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td><?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo $product['stock_quantity']; ?></td>
                        <td><img src="../assets/images/<?php echo $product['image']; ?>" alt="Image" width="50"></td>
                        <td>
                            <a href="#" class="btn btn-success">Edit</a>
                            <a href="#" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
