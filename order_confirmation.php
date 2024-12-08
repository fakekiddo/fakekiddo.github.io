<?php
session_start();

if (!isset($_SESSION['order']) || empty($_SESSION['order'])) {
    header("Location: mobile_index.php");
    exit();
}

include('Database/db_connect.php');

// Handle payment and order confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $guest_name = $_POST['guest_name'] ?? null;
    $guest_email = $_POST['guest_email'] ?? null;
    $order_total = 0;
    $points_earned = 0;

    foreach ($_SESSION['order'] as $order) {
        $stmt = $conn->prepare("INSERT INTO orderdetails (user_id, drink_id, whipped_cream, sugar_level, caffeine, guest_name, guest_email) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssss", $user_id, $order['drink_id'], $order['whipped_cream'], $order['sugar_level'], $order['caffeine'], $guest_name, $guest_email);
        $stmt->execute();
        
        // Calculate the order total
        $order_total += $order['drinks_cost'];
    }

    if ($user_id) {
        // Calculate points from the order total
        $points_earned = round($order_total);

        // Update user's points
        $update_points_stmt = $conn->prepare("UPDATE users SET points = points + ? WHERE user_id = ?");
        $update_points_stmt->bind_param("ii", $points_earned, $user_id);
        $update_points_stmt->execute();
    }

    // Clear the session order after confirmation
    $_SESSION['order'] = [];

    // Store points earned and order total in session for display on confirmation page
    $_SESSION['points_earned'] = $points_earned;
    $_SESSION['order_total'] = $order_total;

    // Redirect to the order confirmed page
    header("Location: order_confirmed.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Order Confirmation</h2>
        <h3>Your Order</h3>
        <ul>
            <?php foreach ($_SESSION['order'] as $order): ?>
                <?php
                // Fetch drink name using the drink_id
                $drink_id = $order['drink_id'];
                $drink_query = $conn->prepare("SELECT drinks_name FROM drinks WHERE drinks_id = ?");
                $drink_query->bind_param("i", $drink_id);
                $drink_query->execute();
                $drink_result = $drink_query->get_result();
                $drinks_name = $drink_result->fetch_assoc()['drinks_name'];
                ?>
                <li>
                    <p><b>Drink Name: </b><?= htmlspecialchars($drinks_name); ?></p>
                    <p><b>Whipped Cream: </b><?= htmlspecialchars($order['whipped_cream']); ?></p>
                    <p><b>Sugar Level: </b><?= htmlspecialchars($order['sugar_level']); ?></p>
                    <p><b>Caffeine: </b><?= htmlspecialchars($order['caffeine']); ?></p>
                    <p><b>Cost: </b>$<?= htmlspecialchars(number_format($order['drinks_cost'], 2)); ?></p><br>
                </li>
            <?php endforeach; ?>
        </ul>

        <form method="POST" action="order_confirmation.php">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <h4>Guest Information</h4>
                <label for="guest_name">Name:</label><?php
session_start();

if (!isset($_SESSION['order']) || empty($_SESSION['order'])) {
    header("Location: mobile_index.php");
    exit();
}

include('Database/db_connect.php');

// Handle payment and order confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $guest_name = $_POST['guest_name'] ?? null;
    $guest_email = $_POST['guest_email'] ?? null;
    $order_total = 0;
    $points_earned = 0;

    foreach ($_SESSION['order'] as $order) {
        $stmt = $conn->prepare("INSERT INTO orderdetails (user_id, drink_id, whipped_cream, sugar_level, caffeine, guest_name, guest_email) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssss", $user_id, $order['drink_id'], $order['whipped_cream'], $order['sugar_level'], $order['caffeine'], $guest_name, $guest_email);
        $stmt->execute();
        
        // Calculate the order total
        $order_total += $order['drinks_cost'];
    }

    if ($user_id) {
        // Calculate points from the order total
        $points_earned = round($order_total);

        // Update user's points
        $update_points_stmt = $conn->prepare("UPDATE users SET points = points + ? WHERE user_id = ?");
        $update_points_stmt->bind_param("ii", $points_earned, $user_id);
        $update_points_stmt->execute();
    }

    // Clear the session order after confirmation
    $_SESSION['order'] = [];

    // Store points earned and order total in session for display on confirmation page
    $_SESSION['points_earned'] = $points_earned;
    $_SESSION['order_total'] = $order_total;

    // Redirect to the order confirmed page
    header("Location: order_confirmed.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Order Confirmation</h2>
        <h3>Your Order</h3>
        <ul>
            <?php 
            $order_total = 0; // Initialize total cost variable
            foreach ($_SESSION['order'] as $order): 
                // Fetch drink name using the drink_id
                $drink_id = $order['drink_id'];
                $drink_query = $conn->prepare("SELECT drinks_name FROM drinks WHERE drinks_id = ?");
                $drink_query->bind_param("i", $drink_id);
                $drink_query->execute();
                $drink_result = $drink_query->get_result();
                $drinks_name = $drink_result->fetch_assoc()['drinks_name'];

                // Add the cost of the current item to the total
                $order_total += $order['drinks_cost'];
            ?>
                <li>
                    <p><b>Drink Name: </b><?= htmlspecialchars($drinks_name); ?></p>
                    <p><b>Whipped Cream: </b><?= htmlspecialchars($order['whipped_cream']); ?></p>
                    <p><b>Sugar Level: </b><?= htmlspecialchars($order['sugar_level']); ?></p>
                    <p><b>Caffeine: </b><?= htmlspecialchars($order['caffeine']); ?></p>
                    <p><b>Cost: </b>$<?= htmlspecialchars(number_format($order['drinks_cost'], 2)); ?></p><hr>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Display Total Cost -->
        <h4>Total Cost: $<?= htmlspecialchars(number_format($order_total, 2)); ?></h4>

        <form method="POST" action="order_confirmation.php">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <h4>Guest Information</h4>
                <label for="guest_name">Name:</label>
                <input type="text" name="guest_name" required><br>
                <label for="guest_email">Email:</label>
                <input type="email" name="guest_email" required><br>
            <?php endif; ?>
            <button type="submit" name="confirm_payment">Confirm Payment</button>
        </form>
    </div>
</body>
</html>

                <input type="text" name="guest_name" required><br>
                <label for="guest_email">Email:</label>
                <input type="email" name="guest_email" required><br>
            <?php endif; ?>
            <button type="submit" name="confirm_payment">Confirm Payment</button>
        </form>
    </div>
</body>
</html>
