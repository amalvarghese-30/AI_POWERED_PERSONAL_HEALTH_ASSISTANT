<?php
// includes/db_connection.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'health_assistant');

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $test = $pdo->query("SELECT 1 FROM users LIMIT 1");
    // Create tables if they don't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS medication_reminders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        medication VARCHAR(100) NOT NULL,
        dosage VARCHAR(50) NOT NULL,
        schedule_type ENUM('daily', 'weekly', 'custom') NOT NULL,
        schedule_data TEXT NOT NULL,
        start_date DATE NOT NULL,
        end_date DATE,
        notes TEXT,
        last_sent DATETIME NULL,  -- NEW COLUMN ADDED
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        reminder_id INT NOT NULL,
        user_id INT NOT NULL,
        notification_time DATETIME NOT NULL,
        status ENUM('pending', 'sent', 'dismissed', 'completed') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (reminder_id) REFERENCES medication_reminders(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX (notification_time, status)
    )");
      $pdo->exec("ALTER TABLE medication_reminders ADD COLUMN IF NOT EXISTS last_sent DATETIME NULL");
    
    return $pdo;
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}