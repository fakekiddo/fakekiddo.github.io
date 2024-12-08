<?php   
$servername = "localhost";   
$username = "root"; 
$password= ""; 
$dbname="fypcffe"; 
  
 
$conn = mysqli_connect($servername, $username, $password, $dbname); 
if (!$conn) { 
    die("Connection failed: " . mysqli_connect_error()); 
} 

$sql = "CREATE TABLE orderdetails (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,  -- Will be NULL for guest orders
    drink_id INT NOT NULL,
    ingredients VARCHAR(255) NOT NULL,
    whipped_cream VARCHAR(100) NOT NULL,
    sugar_level VARCHAR(100) NOT NULL,
    caffeine VARCHAR(100) NOT NULL,
    guest_name VARCHAR(100) NULL,  -- Will be used for guest orders
    guest_email VARCHAR(100) NULL,  -- Will be used for guest orders
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),  -- If you have a user table
    FOREIGN KEY (drink_id) REFERENCES drinks(drinks_id)  -- Assuming drinks table exists
)";
if (mysqli_query($conn, $sql)) { 
    echo "orderdetails created successfully\n"; 
} else { 
    echo "Error creating table: " . $conn->error; 
} 
 mysqli_close($conn);
?>
