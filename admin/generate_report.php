<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_index.php");
    exit();
}

include('db_connect.php');

// Fetch sales report data
$report_query = "SELECT d.drinks_name, COUNT(o.order_id) AS total_orders, SUM(d.drinks_cost) AS total_sales
                 FROM orderdetails o
                 LEFT JOIN drinks d ON o.drink_id = d.drinks_id
                 GROUP BY d.drinks_name";
$report_result = $conn->query($report_query);

// Get the current date and time for the report generation
$report_date = date("Y-m-d H:i:s");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Sales Report</h1>
        <p>Report generated on: <?= htmlspecialchars($report_date); ?></p>
        <table>
            <thead>
                <tr>
                    <th>Drink Name</th>
                    <th>Total Orders</th>
                    <th>Total Sales ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($report = $report_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($report['drinks_name']); ?></td>
                        <td><?= htmlspecialchars($report['total_orders']); ?></td>
                        <td><?= htmlspecialchars(number_format($report['total_sales'], 2)); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
