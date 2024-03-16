<?php
// Create connection
$conn = new mysqli("localhost", "root", "", 'tutorial-8', "3307");

// Check connection
if ($conn->connect_error) {
    die ("Connection failed: " . $conn->connect_error);
}
?>