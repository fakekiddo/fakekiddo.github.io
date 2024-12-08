<?php
include('Database/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phoneno = $_POST['phoneno'];

    // Check if username already exists
    $check_query = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        echo "Username already exists. Please choose another.";
    } else {
        // Insert new user
        $insert_query = "INSERT INTO users (email, username, password_hash, phoneno) VALUES ('$email', '$username', '$password', '$phoneno')";
        if ($conn->query($insert_query) === TRUE) {
            echo "Registration successful. You can now <a href='login.php'>login</a>.";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <label for="phoneno">Phone Number:</label>
            <input type="text" name="phoneno" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>
