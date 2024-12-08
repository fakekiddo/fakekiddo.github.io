<?php   
$servername = "localhost";   
$username = "root"; 
$password= ""; 
$dbname="fypcffe"; 
  
 
$conn = mysqli_connect($servername, $username, $password, $dbname); 
if (!$conn) { 
    die("Connection failed: " . mysqli_connect_error()); 
} 
 
$sql = "CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL,
    password_hash VARCHAR(100) NOT NULL
)"; 
if (mysqli_query($conn, $sql)) { 
    echo "Admin created successfully\n"; 
} else { 
    echo "Error creating table: " . $conn->error; 
} 
 mysqli_close($conn);
?>