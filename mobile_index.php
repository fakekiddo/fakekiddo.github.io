<?php
include('Database/db_connect.php');
session_start();

$drinks_query = "SELECT * FROM drinks";
$drinks_result = $conn->query($drinks_query);

// Fetch user points if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Prepare and execute the query to fetch points
    $stmt = $conn->prepare("SELECT points FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($points);
    $stmt->fetch();
    $stmt->close();
}

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
    $drinks_cost = $_POST['drinks_cost'];

    // Store the drink details in session
    $_SESSION['order'][] = [
        'drink_id' => $drink_id,
        'drink_name' => $drink_name,
        'whipped_cream' => $whipped_cream,
        'sugar_level' => $sugar_level,
        'caffeine' => $caffeine,
        'drinks_cost' => $drinks_cost
    ];

    // Redirect to the same page to avoid resubmission
    header("Location: mobile_index.php");
    exit();
}

// Handle redeeming points for a drink
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['redeem_points'])) {
    $drink_id = $_POST['drink_id'];
    $drink_name = $_POST['drink_name'];
    $required_points = $_POST['required_points'];

    if ($points >= $required_points) {
        // Deduct points and add drink to the order
        $points -= $required_points;

        $stmt = $conn->prepare("UPDATE users SET points = ? WHERE user_id = ?");
        $stmt->bind_param("ii", $points, $user_id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['order'][] = [
            'drink_id' => $drink_id,
            'drink_name' => $drink_name,
            'whipped_cream' => 'None',
            'sugar_level' => 'Normal',
            'caffeine' => 'Normal',
            'drinks_cost' => 0 // Free drink
        ];

        header("Location: mobile_index.php");
        exit();
    } else {
        $_SESSION['error'] = "Not enough points to redeem this drink.";
    }
}

// Handle deleting an item from the order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $index = $_POST['index'];
    unset($_SESSION['order'][$index]);
    $_SESSION['order'] = array_values($_SESSION['order']); // Reindex the array

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
            <h1>Mengupi Bah Cafe</h1><br>
        </div>
        
        <div class="left-menu" id="left-menu">
    <button class="close-btn" onclick="closeMenus()">✖</button>
    <h3>User Profile</h3>
<?php if (isset($_SESSION['user_id'])): ?>
    <p>Username: <?= htmlspecialchars($_SESSION['username']); ?></p>
    <p>Points: <?= htmlspecialchars($points); ?></p> <!-- Display points here -->
    <p><a href="feedback.php">Feedback/Complaint</a></p>
    <p><a href="logout.php">Logout</a></p>
<?php else: ?>
    <p><a href="login.php">Login</a></p>
    <p><a href="register.php">Register</a></p>
<?php endif; ?>

</div>

        <h2>Select Your Drink</h2>
<ul id="drinksList">
    <?php while ($drink = $drinks_result->fetch_assoc()) { ?>
        <li class="drink-item">
            <div class="drink-info">
                <h3><?php echo htmlspecialchars($drink['drinks_name']); ?></h3>
                <p>Type: <?php echo htmlspecialchars($drink['drinks_type']); ?></p>
                <p>Cost: $<?php echo htmlspecialchars($drink['drinks_cost']); ?></p>
            </div>
            <form method="POST" action="mobile_index.php">
                <input type="hidden" name="drink_id" value="<?php echo htmlspecialchars($drink['drinks_id']); ?>">
                <input type="hidden" name="drink_name" value="<?php echo htmlspecialchars($drink['drinks_name']); ?>">
                <input type="hidden" name="drinks_cost" value="<?= htmlspecialchars($drink['drinks_cost']); ?>">
                <label for="whipped_cream">Whipped Cream:</label>
                <select name="whipped_cream" required>
                    <option value="Whipped Cream">Whipped Cream</option>
                    <option value="No Whipped Cream">No Whipped Cream</option>
                </select><br>
                <label for="sugar_level">Sugar Level:</label>
                <select name="sugar_level" required>
                    <option value="Normal">Normal</option>
                    <option value="Less">Less</option>
                    <option value="More">More%</option>
                </select><br>
                <label for="caffeine">Caffeine:</label>
                <select name="caffeine" required>
                    <option value="Normal">Normal</option>
                    <option value="Extra">Extra</option>
                </select><br>
                <button type="submit" name="add_to_order">Add to Order</button>
            </form>

            <?php if ($drink['drinks_cost'] <= 5): ?>
                <form method="POST" action="mobile_index.php">
                    <input type="hidden" name="drink_id" value="<?php echo htmlspecialchars($drink['drinks_id']); ?>">
                    <input type="hidden" name="drink_name" value="<?php echo htmlspecialchars($drink['drinks_name']); ?>">
                    <input type="hidden" name="required_points" value="50"><br> <!-- Adjust the points as needed -->
                    <button type="submit" name="redeem_points">Redeem for Free (50 Points)</button>
                </form>
            <?php endif; ?>
        </li>
    <?php } ?>
</ul>


        <div class="right-menu" id="right-menu">
            <h2>Your Order</h2>
            <ul>
                <?php if (!empty($_SESSION['order'])): ?>
                    <?php foreach ($_SESSION['order'] as $index => $item): ?>
                        <li>
                            <strong><?= $item['drink_name']; ?></strong> (Whipped Cream: <?= $item['whipped_cream']; ?>, Sugar: <?= $item['sugar_level']; ?>, Caffeine: <?= $item['caffeine']; ?>)
                            <form method="POST" action="mobile_index.php" style="display:inline;">
                                <input type="hidden" name="index" value="<?= $index; ?>">
                                <button type="submit" name="delete_item">Delete</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Your order is empty.</li>
                <?php endif; ?>
            </ul>

            <form id="confirmOrderForm" action="mobile_index.php" method="POST">
                <button type="submit" name="confirm_order">Confirm Order</button>
            </form>
        </div>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
