<?php
include('Database/db_connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
    $user_id = $_SESSION['user_id'] ?? null;
    $feedback_type = $_POST['feedback_type'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO feedback (user_id, feedback_type, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $feedback_type, $message);
    $stmt->execute();
    $stmt->close();

    // Redirect to a thank you page or back to the main page
    header("Location: thank_you.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback/Complaint</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Feedback/Complaint</h1>
        <form method="POST" action="feedback.php">
            <label for="feedback_type">Type:</label>
            <select name="feedback_type" id="feedback_type" required>
                <option value="Feedback">Feedback</option>
                <option value="Complaint">Complaint</option>
            </select><br>
            <label for="message">Message:</label>
            <textarea name="message" id="message" required></textarea><br>
            <button type="submit" name="submit_feedback">Submit</button>
        </form>
    </div>
</body>
</html>
