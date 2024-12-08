<?php   
$servername = "localhost";   
$username = "root"; 
$password= ""; 
$dbname="fypcffe"; 
  
 
$conn = mysqli_connect($servername, $username, $password, $dbname); 
if (!$conn) { 
    die("Connection failed: " . mysqli_connect_error()); 
} 
 
$sql = "CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(100) NOT NULL,
    phoneno INT(10) NOT NULL
)"; 
if (mysqli_query($conn, $sql)) { 
    echo "user created successfully\n"; 
} else { 
    echo "Error creating table: " . $conn->error; 
} 
 mysqli_close($conn);
?>