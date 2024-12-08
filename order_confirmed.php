<?php
session_start();

if (!isset($_SESSION['order_total']) || !isset($_SESSION['points_earned'])) {
    header("Location: mobile_index.php");
    exit();
}

$order_total = $_SESSION['order_total'];
$points_earned = $_SESSION['points_earned'];

// Clear these session variables after displaying
unset($_SESSION['order_total']);
unset($_SESSION['points_earned']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Order Confirmed</h2>
        <p>Your order has been confirmed!</p>
        <p>Total Cost: $<?= number_format($order_total, 2); ?></p>
        <p>Points Earned: <?= $points_earned; ?></p>
        <p><a href="mobile_index.php">Go back to shop</a></p>
    </div>
</body>
</html>
