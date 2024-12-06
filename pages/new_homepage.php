<?php
include '../includes/header.php';
// Include database configuration
include '../config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Website</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/styless.css">    
    <style>
        body{
            background-image: url("../assets/images/welc.png");
            background-color: lightgray;
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 1800px;
            background-position-y: 11cap;
        }
    </style>
    
</head>
<body>
    <main>
        <section class="hero">
            <h1>Welcome to Our Store</h1>
            <p>Discover amazing products at unbeatable prices.</p>
            <a href="#" class="cta-button">Shop Now</a>
        </section>
        

        <table class="position"
        style="padding: 20px;">
            <!--2st column-->
            <tr>
                <th>CATEGORIES</th>

            </tr>
            <tr>
                <td>
                    <div class="bag">
                        <img src="../assets/images/IMG_9145.jpeg" alt="">
                        <p>Backpacks</p>
                    </div>
                </td>

                <td>
                    <div class="ballpen">
                        <img src="../assets/images/IMG_9159.jpeg" alt="">
                        <p>Ballpens</p>
                    </div>
                </td>

                <td>
                    <div class="stabilo">
                        <img src="../assets/images/IMG_9139.jpeg" alt="">
                        <p>Stabilo</p>
                    </div>
                </td>

                <td>    
                    <div class="clips">
                        <img src="../assets/images/IMG_9107.jpeg" alt="">
                        <p>Paperclips</p>
                    </div>
                </td>

                <td>    
                    <div class="cboard">
                        <img src="../assets/images/IMG_9187.jpeg" alt="">
                        <p>Clipboards</p>
                    </div>
                </td>

                
                <!-- 2nd column-->
                <tr>
                <td>    
                    <div class="nbook">
                        <img src="../assets/images/IMG_9198.jpeg" alt="">
                        <p>Notebooks</p>
                    </div>
                </td>

                <td>    
                    <div class="stapler">
                        <img src="../assets/images/IMG_9190.jpeg" alt="">
                        <p>Staplers</p>
                    </div>
                </td>

                <td>    
                    <div class="pencil">
                        <img src="../assets/images/IMG_9202.jpeg" alt="">
                        <p>Pencils</p>
                    </div>
                </td>

                <td>    
                    <div class="ggun">
                        <img src="../assets/images/IMG_9291.jpeg" alt="">
                        <p>Glue Guns</p>
                    </div>
                </td>

                <td>    
                    <div class="gstick">
                        <img src="../assets/images/IMG_9290.jpeg" alt="">
                        <p>Glue Sticks</p>
                    </div>
                </td>
               </tr>

                <!--3rd column-->
                <tr>
                <td>    
                    <div class="mtape">
                        <img src="../assets/images/IMG_9279.jpeg" alt="">
                        <p>Measuring Tapes</p>
                    </div>
                </td>

                <td>    
                    <div class="puncher">
                        <img src="../assets/images/IMG_9277.jpeg" alt="">
                        <p>Punchers</p>
                    </div>
            </tr>
        </table>

        
        <table class="positions"
        style="padding: 20px;">
            <tr>
                <th>TOP PRODUCTS</th>
            </tr>
            <tr>
                <td>
                    <div class="pencil">
                        <img src="../assets/images/IMG_9202.jpeg" alt="">
                        <p>Pencils</p>
                    </div>
                </td>

                <td>
                    <div class="ballpen">
                        <img src="../assets/images/IMG_9159.jpeg" alt="">
                        <p>Ballpens</p>
                    </div>
                </td>

                <td>    
                    <div class="nbook">
                        <img src="../assets/images/IMG_9198.jpeg" alt="">
                        <p>Notebooks</p>
                    </div>
                </td>

                <td>    
                    <div class="sharp">
                        <img src="../assets/images/IMG_9196.jpeg" alt="">
                        <p>Sharpeners</p>
                    </div>
                </td>

                <td>    
                    <div class="rule">
                        <img src="../assets/images/IMG_9203.jpeg" alt="">
                        <p>Rulers</p>
                    </div>
                </td>
        </table>


        <!--<section class="products">
            <h2>Featured Products</h2>
            <div class="product-grid">
                <div class="product-card">
                    <img src="https://via.placeholder.com/150" alt="Product 1">
                    <h3>Product 1</h3>
                    <p>$19.99</p>
                    <button>Add to Cart</button>
                </div>
                <div class="product-card">
                    <img src="https://via.placeholder.com/150" alt="Product 2">
                    <h3>Product 2</h3>
                    <p>$29.99</p>
                    <button>Add to Cart</button>
                </div>
                <div class="product-card">
                    <img src="https://via.placeholder.com/150" alt="Product 3">
                    <h3>Product 3</h3>
                    <p>$39.99</p>
                    <button>Add to Cart</button>
                    
                </div>
            </div>
        </section>-->
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
