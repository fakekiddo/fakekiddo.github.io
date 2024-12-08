<?php
include('Database/db_connect.php');
session_start();

$drinks_query = "SELECT * FROM drinks";
$drinks_result = $conn->query($drinks_query);
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
            <h1>Tea</h1><br>
        </div>

        <div class="auth-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <p>Welcome, <?= $_SESSION['username']; ?> | <a href="logout.php">Logout</a></p>
            <?php else: ?>
                <p><a href="login.php">Login</a> | <a href="register.php">Register</a></p>
            <?php endif; ?>
                <br>
        </div>

        <div>
            <button class="confirm-order-btn" onclick="confirmOrder()">Confirm Order</button>
        </div>
        
        <div class="left-menu" id="left-menu">
    <button class="close-btn" onclick="closeMenus()">✖</button>
    <h3>Menu Categories</h3>
    <ul>
        <li><a href="coffee.php">Coffee</a></li>
        <li><a href="tea.php">Tea</a></li>
        <li><a href="smoothie.php">Smoothies</a></li>
    </ul>
</div>

        <div class="main-drink">
            <img src="your-drink-image-url-here.jpg" alt="Main Drink" id="main-drink-image">
        </div>

        <h2>Select Your Drink</h2>
        <ul id="drinksList">
            <?php while ($drink = $drinks_result->fetch_assoc()) { ?>
                <li>
                    <h3><?php echo $drink['drinks_name']; ?></h3>
                    <p>Type: <?php echo $drink['drinks_type']; ?></p>
                    <p>Cost: $<?php echo $drink['drinks_cost']; ?></p>
                    <!-- Add additional ingredient options here if needed -->
                </li>
            <?php } ?>
        </ul>
        
<div class="right-menu" id="right-menu">
    <button class="close-btn" onclick="closeMenus()">✖</button>
    <h3>Additional Ingredients</h3>
    <form action="order.php" method="POST">
        <label>Crumbs</label><br>
        <label><input type="checkbox" name="ingredients[]" value="Chocolate Chips"> Chocolate Chips</label><br>
        <label><input type="checkbox" name="ingredients[]" value="Oreo Crumbs"> Oreo Crumbs</label><br>
        <label><input type="checkbox" name="ingredients[]" value="Rainbow Sprinkles"> Rainbow Sprinkles</label><br>
        <label>Whipped Cream</label><br>
        <label><input type="radio" name="whipped_cream" value="Whipped Cream"> Whipped Cream</label><br>
        <label><input type="radio" name="whipped_cream" value="No Whipped Cream"> No Whipped Cream</label><br>
        <label>Sugar Level</label><br>
        <label><input type="radio" name="sugar_level" value="Normal"> Normal</label><br>
        <label><input type="radio" name="sugar_level" value="Less"> Less</label><br>
        <label><input type="radio" name="sugar_level" value="More"> More</label><br>
        <label>Caffeine</label><br>
        <label><input type="radio" name="caffeine" value="Normal"> Normal</label><br>
        <label><input type="radio" name="caffeine" value="Extra"> Extra</label><br>
        <!-- Add more options as needed -->

        <?php if (!isset($_SESSION['user_id'])): ?>
            <h4>Guest Information</h4>
            <label for="guest_name">Name:</label>
            <input type="text" name="guest_name" required><br>
            <label for="guest_email">Email:</label>
            <input type="email" name="guest_email"><br>
        <?php endif; ?>
        <button onclick="addToOrder('Main Drink')">Order Drink</button>
    </form>
</div>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
