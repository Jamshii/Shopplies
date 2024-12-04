<?php
include '../includes/header.php';
// Include database connection
include '../config/db.php';

// Fetch all messages from the database, ordered by created_at (newest first)
$query = "SELECT * FROM messages ORDER BY date_sent DESC";
$result = $conn->query($query);

// Check if there are any messages
if ($result->num_rows > 0) {
    echo "<h2>Feedback Messages</h2>";
    echo "<table>";
    echo "<thead><tr><th>Name</th><th>Email</th><th>Phone Number</th><th>Message</th><th>Date</th></tr></thead>";
    echo "<tbody>";

    // Display each message
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
        echo "<td>" . nl2br(htmlspecialchars(html_entity_decode($row['message']))) . "</td>";
        echo "<td>" . date('F j, Y, g:i a', strtotime($row['date_sent'])) . "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>No feedback messages found.</p>";
}

$conn->close();
