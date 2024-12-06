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
    <title>About Us</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>

        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1,
        h2 {
            text-align: center;
            color: #444;
        }

        /* Mission and Vision Section */
        .mission-vision {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 40px;
        }

        .mission,
        .vision {
            flex: 1;
            min-width: 300px;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .team {
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .team-members {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            /* 3 columns */
            gap: 20px;
            /* Space between grid items */
            margin-top: 20px;
        }

        .team-member {
            text-align: center;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .team-member img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>About Us</h1>

        <div class="mission-vision">
            <!-- Mission Section -->
            <div class="mission">
                <h2>Our Mission</h2>
                <p>Shopplies aim to provide affordable and accessible school supplies to assist students in their endeavors.
                    Stylish, reliable and empowering these students to thrive on their journey to academic excellence.
                </p>
            </div>

            <!-- Vision Section -->
            <div class="vision">
                <h2>Our Vision</h2>
                <p>We, at Shopplies, envision a simplified school supply shopping experience, guaranteeing every student a
                    means to secure vital tools to aid in their studies and boost their productivity and enthusiasm.
                </p>
            </div>
        </div>

        <!-- About the Team Section -->
        <div class="team">
            <h2>About the Team</h2>
            <p>Our team is composed of dedicated college students
                passionate to provide a unique shopping experience for professors, parents and students alike. </p>
            <div class="team-members">
                <div class="team-member">
                    <img src="../assets/images/Fanio, James Edward.jpg" alt="James Edward Fanio">
                    <h3>Fanio, James Edward</h3>
                    <p>Leader, Backend and Database</p>
                </div>
                <div class="team-member">
                    <img src="../assets/images/Arcangel, Herson Fergus.jpg" alt="Herson Fergus Arcangel">
                    <h3>Arcangel, Herson Fergus S.</h3>
                    <p>Frontend</p>
                </div>
                <div class="team-member">
                    <img src="../assets/images/Austero, Abdiel.jpg" alt="Abdiel Austero">
                    <h3>Austero, Abdiel R.</h3>
                    <p>Frontend and Backend</p>
                </div>
                <div class="team-member">
                    <img src="../assets/images/Mabaet, John Regory.png" alt="John Regory Mabaet">
                    <h3>Mabaet, John Regory M.</h3>
                    <p>UI/UX Designer</p>
                </div>
                <div class="team-member">
                    <img src="../assets/images/Tecson, Dannah.jpg" alt="Dannah Yzzabella Tecson">
                    <h3>Tecson, Dannah Yzzabella Q.</h3>
                    <p>Quality Assurance</p>
                </div>
                <div class="team-member">
                    <img src="../assets/images/Tominio, Marian.jpeg" alt="Marian Tominio">
                    <h3>Tominio, Marian</h3>
                    <p>Data Manager</p>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>