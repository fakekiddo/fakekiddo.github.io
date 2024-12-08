<?php   
$servername = "localhost";   
$username = "root"; 
$password= ""; 
$dbname="fypcffe"; 
  
 
$conn = mysqli_connect($servername, $username, $password, $dbname); 
if (!$conn) { 
    die("Connection failed: " . mysqli_connect_error()); 
} 
 
$sql = "CREATE TABLE drinks (
    drinks_id INT AUTO_INCREMENT PRIMARY KEY,
    drinks_name VARCHAR(100) NOT NULL,
    drinks_cost FLOAT(50) NOT NULL,
    drinks_type VARCHAR(100) NOT NULL
)"; 
if (mysqli_query($conn, $sql)) { 
    echo "drinks created successfully\n"; 
} else { 
    echo "Error creating table: " . $conn->error; 
} 
 mysqli_close($conn);
?>