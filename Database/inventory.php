<?php   
$servername = "localhost";   
$username = "root"; 
$password= ""; 
$dbname="fypcffe"; 
  
 
$conn = mysqli_connect($servername, $username, $password, $dbname); 
if (!$conn) { 
    die("Connection failed: " . mysqli_connect_error()); 
} 
 
$sql = "CREATE TABLE inventory (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    item_cost FLOAT(50) NOT NULL
)"; 
if (mysqli_query($conn, $sql)) { 
    echo "user created successfully\n"; 
} else { 
    echo "Error creating table: " . $conn->error; 
} 
 mysqli_close($conn);
?>