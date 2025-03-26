<?php
$servername = "localhost";
$username = "root";
$password = ""; // Your database password (empty for XAMPP default)
$dbname = "health_assistant";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>