<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_index.php");
    exit();
}

include('db_connect.php');

// Fetch all drinks and their inventory
$inventory_query = "SELECT * FROM drinks";
$inventory_result = $conn->query($inventory_query);

// Handle stock update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $drink_id = $_POST['drink_id'];
    $new_stock = $_POST['new_stock'];

    // Use prepared statements to safely update the stock
    $update_query = "UPDATE drinks SET stock_quantity = ? WHERE drinks_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $new_stock, $drink_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to refresh the page and see the updated inventory
    header("Location: admin_inventory.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Inventory</title>
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
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_drinks.php">Drinks</a>
            <a href="admin_feedback.php">Feedback/Complaints</a>
        </div>
        <h2>Inventory</h2>
        <table>
            <thead>
                <tr>
                    <th>Drink ID</th>
                    <th>Drink Name</th>
                    <th>Cost</th>
                    <th>Stock Quantity</th>
                    <th>Update Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($drink = $inventory_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($drink['drinks_id']); ?></td>
                        <td><?= htmlspecialchars($drink['drinks_name']); ?></td>
                        <td><?= htmlspecialchars(number_format($drink['drinks_cost'], 2)); ?></td>
                        <td><?= htmlspecialchars($drink['stock_quantity']); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="drink_id" value="<?= htmlspecialchars($drink['drinks_id']); ?>">
                                <input type="number" name="new_stock" value="<?= htmlspecialchars($drink['stock_quantity']); ?>" required>
                                <button type="submit" name="update_stock">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
