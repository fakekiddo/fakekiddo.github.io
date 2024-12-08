<?php
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $adminid = $_POST['adminid'];

    // Check if admin ID already exists
    $check_query = "SELECT * FROM admins WHERE admin_id='$adminid'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        echo "Admin ID exists. Please choose another.";
    } else {
        // Insert new admin
        $insert_query = "INSERT INTO admins (email, username, password_hash, admin_id) VALUES ('$email', '$username', '$password', '$adminid')";
        if ($conn->query($insert_query) === TRUE) {
            echo "Registration successful.";
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
        <form action="add_admin.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <label for="phoneno">Admin ID:</label>
            <input type="text" name="adminid" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
