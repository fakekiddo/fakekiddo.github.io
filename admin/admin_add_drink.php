<?php
session_start();
include('db_connect.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $drinks_name = $_POST['drinks_name'];
    $drinks_cost = $_POST['drinks_cost'];
    $drinks_type = $_POST['drinks_type'];

    // Insert new drink into the database
    $insert_query = "INSERT INTO drinks (drinks_name, drinks_cost, drinks_type) VALUES ('$drinks_name', '$drinks_cost', '$drinks_type')";
    if ($conn->query($insert_query) === TRUE) {
        $message = "Drink added successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Drink</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
    <div class="header">
        <h2>Add Drink</h2>
</div>
        <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
        <form action="admin_add_drink.php" method="POST">
            <label for="drinks_name">Drink Name:</label>
            <input type="text" name="drinks_name" required>
            <label for="drinks_cost">Drink Cost:</label>
            <input type="text" step="0.01" name="drinks_cost" required>
            <label for="drinks_type">Drink Type</label>
            <select name="drinks_type" required>
                            <option value="Coffee">Coffee</option>
                            <option value="Tea">Tea</option>
                            <option value="Other">Other</option>
                        </select></br>
            <button type="submit">Add Drink</button>
        </form>
        <div class="actions">
        <a href="admin_drinks.php">Drinks List</a>
        <a href="admin_dashboard.php">Back to Dashboard</a>
                </div>
    </div>
</body>
</html>
