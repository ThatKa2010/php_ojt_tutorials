<?php
include_once ("db.php");
$create_sql = "CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(255) NOT NULL,
  img VARCHAR(255) DEFAULT NULL,
  address TEXT NOT NULL,
  reset_token INT DEFAULT NULL,
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
