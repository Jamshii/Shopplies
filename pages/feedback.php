<?php
include '../includes/header.php';
// Include database connection
include '../config/db.php';

// Fetch all messages from the database, ordered by created_at (newest first)
$query = "SELECT * FROM messages ORDER BY date_sent DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Messages</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
    <main class="feedback-page">
    <div class="container">
        <h2>Feedback Messages</h2>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars(html_entity_decode($row['message']))); ?></td>
                            <td><?php echo date('F j, Y, g:i a', strtotime($row['date_sent'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-messages">No feedback messages found.</p>
        <?php endif; ?>

        <?php $conn->close(); ?>
    </div>
    </main>
</body>

</html>
