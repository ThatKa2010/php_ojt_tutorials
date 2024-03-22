<?php
$conn = new mysqli("localhost", "root", "", "tutorial-10", "3307");
if ($conn->connect_error) {
    die ("Connection failed: " . $conn->connect_error);
}
?>
