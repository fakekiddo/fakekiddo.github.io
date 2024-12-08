<?php   
$servername = "localhost";   
$username = "root"; 
$password= ""; 
$dbname="fypcffe"; 
  
 
$conn = mysqli_connect($servername, $username, $password, $dbname); 
if (!$conn) { 
    die("Connection failed: " . mysqli_connect_error()); 
} 
 
$sql = "CREATE TABLE feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    feedback_type VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
)";
if (mysqli_query($conn, $sql)) { 
    echo "feedback created successfully\n"; 
} else { 
    echo "Error creating table: " . $conn->error; 
} 
 mysqli_close($conn);
?>