<?php
// create_symptom_checks_table.php

// Database connection details for XAMPP
$servername = "localhost";
$username = "root";       // Default XAMPP username
$password = "";           // Default XAMPP password (empty)
$dbname = "health_assistant"; // Your database name

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // SQL to create table
    $sql = "CREATE TABLE IF NOT EXISTS `symptom_checks` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `age` int(11) NOT NULL,
              `gender` varchar(10) NOT NULL,
              `symptoms` text NOT NULL,
              `response` text NOT NULL,
              `created_at` datetime NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    // Execute query
    if ($conn->query($sql) === TRUE) {
        echo "Table 'symptom_checks' created successfully or already exists.";
    } else {
        throw new Exception("Error creating table: " . $conn->error);
    }
    
    // Close connection
    $conn->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>