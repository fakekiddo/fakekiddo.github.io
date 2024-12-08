<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include('db_connect.php');

// Fetch all feedback with user email
$feedback_query = "SELECT f.*, u.username, u.email FROM feedback f
                   LEFT JOIN users u ON f.user_id = u.user_id";
$feedback_result = $conn->query($feedback_query);

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_feedback'])) {
    $feedback_id = $_POST['feedback_id'];
    $user_email = $_POST['user_email'];

    // Predefined message and subject
    $subject = urlencode("Response to Your Feedback");
    $message = urlencode("Dear Customer,\n\nThank you for your feedback.\n\nBest Regards,\nCoffee Shop Admin");

    // Gmail redirect URL with prefilled details
    $mailto_link = "https://mail.google.com/mail/?view=cm&fs=1&to=" . urlencode($user_email) . "&su=" . $subject . "&body=" . $message;
    
    // Redirect to Gmail
    header("Location: " . $mailto_link);
    exit(); // Prevent further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Feedback</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Admin Dashboard</h1><br>
            <p>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</p>
            <a href="logout.php">Log Out</a>
        </div>
        <div class="actions">
            <a href="admin_add_drink.php">Add New Drink</a>
            <a href="admin_drinks.php">Drinks</a>
            <a href="admin_dashboard.php">Dashboard</a>
        </div>
        <h2>Feedback/Complaints</h2>
        <table>
            <thead>
                <tr>
                    <th>Feedback ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                    <th>Reply</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($feedback = $feedback_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($feedback['feedback_id']); ?></td>
                        <td><?= htmlspecialchars($feedback['username']); ?></td>
                        <td><?= htmlspecialchars($feedback['email']); ?></td>
                        <td><?= htmlspecialchars($feedback['feedback_type']); ?></td>
                        <td><?= htmlspecialchars($feedback['message']); ?></td>
                        <td><?= htmlspecialchars($feedback['created_at']); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="feedback_id" value="<?= htmlspecialchars($feedback['feedback_id']); ?>">
                                <input type="hidden" name="user_email" value="<?= htmlspecialchars($feedback['email']); ?>">
                                <button type="submit" name="reply_feedback">Reply via Gmail</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
