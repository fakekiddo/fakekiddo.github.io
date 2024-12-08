<?php
include('Database/db_connect.php');
session_start();

$drinks_query = "SELECT * FROM drinks";
$drinks_result = $conn->query($drinks_query);

if (!isset($_SESSION['order'])) {
    $_SESSION['order'] = [];
}

// Handle adding drinks to the session order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_order'])) {
    $drink_id = $_POST['drink_id'];
    $drink_name = $_POST['drink_name'];
    $whipped_cream = $_POST['whipped_cream'];
    $sugar_level = $_POST['sugar_level'];
    $caffeine = $_POST['caffeine'];

    // Store the drink details in session
    $_SESSION['order'][] = [
        'drink_id' => $drink_id,
        'drink_name' => $drink_name,
        'whipped_cream' => $whipped_cream,
        'sugar_level' => $sugar_level,
        'caffeine' => $caffeine
    ];

    // Redirect to the same page to avoid resubmission
    header("Location: mobile_index.php");
    exit();
}

// Handle confirming the order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    // Redirect to the order confirmation page
    header("Location: order_confirmation.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop Ordering System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <button class="menu-btn" onclick="openMenu('left')">☰</button>
            <h1>Coffee Shop</h1><br>
        </div>

        <div class="auth-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <p>Welcome, <?= $_SESSION['username']; ?> | <a href="logout.php">Logout</a></p>
            <?php else: ?>
                <p><a href="login.php">Login</a> | <a href="register.php">Register</a></p>
            <?php endif; ?>
        </div>

        <h2>Select Your Drink</h2>
        <ul id="drinksList">
            <?php while ($drink = $drinks_result->fetch_assoc()) { ?>
                <li onclick="selectDrink(<?php echo $drink['drinks_id']; ?>, '<?php echo addslashes($drink['drinks_name']); ?>')">
                    <h3><?php echo $drink['drinks_name']; ?></h3>
                    <p>Type: <?php echo $drink['drinks_type']; ?></p>
                    <p>Cost: $<?php echo $drink['drinks_cost']; ?></p>
                </li>
            <?php } ?>
        </ul>

        <!-- Drink Form -->
        <h2>Add Ingredients</h2>
        <form action="mobile_index.php" method="POST">
            <input type="hidden" name="drink_id" id="selected_drink_id" value="">
            <input type="hidden" name="drink_name" id="selected_drink_name" value="">
            
            <!-- Additional Ingredients -->
            <h3>Choose Ingredients</h3>
            <label><input type="checkbox" name="ingredients[]" value="Chocolate Chips"> Chocolate Chips</label><br>
            <label><input type="checkbox" name="ingredients[]" value="Oreo Crumbs"> Oreo Crumbs</label><br>
            <label><input type="checkbox" name="ingredients[]" value="Rainbow Sprinkles"> Rainbow Sprinkles</label><br>

            <h3>Whipped Cream</h3>
            <label><input type="radio" name="whipped_cream" value="Whipped Cream"> Whipped Cream</label><br>
            <label><input type="radio" name="whipped_cream" value="No Whipped Cream"> No Whipped Cream</label><br>

            <h3>Sugar Level</h3>
            <label><input type="radio" name="sugar_level" value="Normal"> Normal</label><br>
            <label><input type="radio" name="sugar_level" value="Less"> Less</label><br>
            <label><input type="radio" name="sugar_level" value="More"> More</label><br>

            <h3>Caffeine</h3>
            <label><input type="radio" name="caffeine" value="Normal"> Normal</label><br>
            <label><input type="radio" name="caffeine" value="Extra"> Extra</label><br>

            <button type="submit" name="add_to_order">Add Drink to Order</button>
        </form>

        <!-- Display the Current Order -->
        <h2>Your Order</h2>
        <ul>
            <?php if (isset($_SESSION['order'])): ?>
                <?php foreach ($_SESSION['order'] as $item): ?>
                    <li>
                        <strong><?= $item['drink_name']; ?></strong> (Ingredients: <?= $item['ingredients']; ?>, Whipped Cream: <?= $item['whipped_cream']; ?>, Sugar: <?= $item['sugar_level']; ?>, Caffeine: <?= $item['caffeine']; ?>)
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Your order is empty.</li>
            <?php endif; ?>
        </ul>

        <!-- Confirm Order -->
        <form action="mobile_index.php" method="POST">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <h4>Guest Information</h4>
                <label for="guest_name">Name:</label>
                <input type="text" name="guest_name" required><br>
                <label for="guest_email">Email:</label>
                <input type="email" name="guest_email" required><br>
            <?php endif; ?>
            <button type="submit" name="confirm_order">Confirm Order</button>
        </form>
    </div>