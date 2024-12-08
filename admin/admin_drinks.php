<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include('db_connect.php');

// Fetch all drinks
$drinks_query = "SELECT * FROM drinks";
$drinks_result = $conn->query($drinks_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_drink'])) {
        $drink_id = $_POST['drink_id'];
        
        // Use prepared statements to safely delete the drink
        $delete_query = "DELETE FROM drinks WHERE drinks_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $drink_id);
        $stmt->execute();
        $stmt->close();
        
        // Redirect to refresh the page and see the updated list of drinks
        header("Location: admin_drinks.php");
        exit();
    } elseif (isset($_POST['edit_drink'])) {
        $drink_id = $_POST['drink_id'];
        $drink_name = $_POST['drink_name'];
        $drink_cost = $_POST['drink_cost'];
        $drink_type = $_POST['drink_type'];
        
        // Use prepared statements to safely update the drink
        $update_query = "UPDATE drinks SET drinks_name = ?, drinks_cost = ?, drinks_type = ? WHERE drinks_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sdsi", $drink_name, $drink_cost, $drink_type, $drink_id);
        $stmt->execute();
        $stmt->close();
        
        // Redirect to refresh the page and see the updated list of drinks
        header("Location: admin_drinks.php");
        exit();
    }
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
            <h1>Admin Dashboard</h1>
        </div>
        <h2>Drinks</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Cost</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($drink = $drinks_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($drink['drinks_id']); ?></td>
                        <td><?php echo htmlspecialchars($drink['drinks_name']); ?></td>
                        <td><?php echo htmlspecialchars($drink['drinks_cost']); ?></td>
                        <td><?php echo htmlspecialchars($drink['drinks_type']); ?></td>
                        <td>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="drink_id" value="<?php echo htmlspecialchars($drink['drinks_id']); ?>">
                                <button type="submit" name="delete_drink">Delete</button>
                            </form>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="drink_id" value="<?php echo htmlspecialchars($drink['drinks_id']); ?>">
                                <input type="text" name="drink_name" value="<?php echo htmlspecialchars($drink['drinks_name']); ?>">
                                <input type="number" step="0.01" name="drink_cost" value="<?php echo htmlspecialchars($drink['drinks_cost']); ?>">
                                <input type="text" name="drink_type" value="<?php echo htmlspecialchars($drink['drinks_type']); ?>">
                                <button type="submit" name="edit_drink">Edit</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="actions">
            <a href="admin_add_drink.php">Add New Drink</a>
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
