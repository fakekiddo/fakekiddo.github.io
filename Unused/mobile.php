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
            'ingredients' => $ingredients,
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
        // Get the user ID if logged in, otherwise set guest info
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $guest_name = $_POST['guest_name'] ?? null;
        $guest_email = $_POST['guest_email'] ?? null;

        // Loop through the order stored in the session and insert each drink into the database
        foreach ($_SESSION['order'] as $order) {
            $stmt = $conn->prepare("INSERT INTO orderdetails (user_id, drink_id, ingredients, whipped_cream, sugar_level, caffeine, guest_name, guest_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iissssss", $user_id, $order['drink_id'], $order['ingredients'], $order['whipped_cream'], $order['sugar_level'], $order['caffeine'], $guest_name, $guest_email);
            $stmt->execute();
        }

        // Clear the session order after confirmation
        $_SESSION['order'] = [];

        // Redirect to a success page or refresh
        header("Location: mobile_index.php");
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
                    <br>
            </div>

            <div>
                <button class="confirm-order-btn" onclick="confirmOrder()">Confirm Order</button> <!--might change -->
            </div>
            
            <div class="left-menu" id="left-menu">
        <button class="close-btn" onclick="closeMenus()">✖</button>
        <h3>Menu Categories</h3>
        <ul>
            <li><a href="coffee.php">Coffee</a></li> <!--not finished -->
            <li><a href="tea.php">Tea</a></li>
            <li><a href="smoothie.php">Smoothies</a></li>
        </ul>
    </div>

            <div class="main-drink">
                <img src="your-drink-image-url-here.jpg" alt="Main Drink" id="main-drink-image">
            </div>

            <h2>Select Your Drink</h2> <!--fix this part -->
            <ul id="drinksList">
    <?php while ($drink = $drinks_result->fetch_assoc()) { ?>
        <li class="drink-item" onclick="selectDrink(<?php echo $drink['drinks_id']; ?>, '<?php echo addslashes($drink['drinks_name']); ?>')">
            <div class="drink-info">
                <h3><?php echo $drink['drinks_name']; ?></h3>
                <p>Type: <?php echo $drink['drinks_type']; ?></p>
                <p>Cost: $<?php echo $drink['drinks_cost']; ?></p>
            </div>
            <!-- Adding a visible button for better UI -->
            <button class="select-btn" onclick="selectDrink(<?php echo $drink['drinks_id']; ?>, '<?php echo addslashes($drink['drinks_name']); ?>')">Select Drink</button>
        </li>
    <?php } ?>
</ul>
            
    <div class="right-menu" id="right-menu"> <!--might change -->
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
            <button type="submit" name="addtoorder">Add to order</button>
        </form>
    </div>

        </div>
        <script src="scripts.js"></script>
    </body>
    </html>
