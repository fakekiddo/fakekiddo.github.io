<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_index.php");
    exit();
}

include('db_connect.php');

// Fetch all orders
$orders_query = "SELECT o.*, d.drinks_name, d.drinks_cost, u.username FROM orderdetails o
                 LEFT JOIN drinks d ON o.drink_id = d.drinks_id
                 LEFT JOIN users u ON o.user_id = u.user_id";
$orders_result = $conn->query($orders_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];
    
    // Use prepared statements to safely delete the order
    $delete_query = "DELETE FROM orderdetails WHERE order_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();
    
    // Redirect to refresh the page and see the updated list of orders
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            <a href="admin_feedback.php">Feedback/Complaints</a>
            <a href="admin_inventory.php">Inventory</a>
        </div>
        <h2>Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Drink</th>
                    <th>Drink Cost</th>
                    <th>Customer</th>
                    <th>Guest Name</th>
                    <th>Guest Email</th>
                    <th>Order Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $orders_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']); ?></td>
                        <td><?= htmlspecialchars($order['drinks_name']); ?></td>
                        <td><?= htmlspecialchars(number_format($order['drinks_cost'], 2)); ?></td>
                        <td><?= htmlspecialchars($order['username']); ?></td>
                        <td><?= htmlspecialchars($order['guest_name']); ?></td>
                        <td><?= htmlspecialchars($order['guest_email']); ?></td>
                        <td><?= htmlspecialchars($order['order_date']); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']); ?>">
                                <button type="submit" name="delete_order">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <form action="generate_report.php" method="GET">
            <button type="submit">Generate Sales Report</button>
        </form>
    </div>
</body>
</html>
