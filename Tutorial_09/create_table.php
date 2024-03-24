<?php
include_once ("db_connection.php");
$create_sql = "CREATE TABLE posts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  is_published BOOLEAN DEFAULT NULL,
  created_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($create_sql) === TRUE) {
    echo "Table created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
$conn->close();
?>
