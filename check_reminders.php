<?php
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "root", "", "health_assistant");
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

date_default_timezone_set('Asia/Kolkata');
$currentTime = date('H:i');
$currentDay = strtolower(date('D'));
$userId = $_SESSION['user_id'];

try {
    $query = "SELECT id, medication_name, dosage, frequency, reminder_times, specific_days 
              FROM medication_reminders 
              WHERE user_id = ? 
              AND start_date <= CURDATE()
              AND (end_date IS NULL OR end_date >= CURDATE())";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $activeReminders = [];
    while ($reminder = $result->fetch_assoc()) {
        $times = json_decode($reminder['reminder_times'], true) ?? [];
        $days = json_decode($reminder['specific_days'], true) ?? [];
        
        // Check if current time matches any reminder time (with 5-minute window)
        $timeMatch = false;
        foreach ($times as $time) {
            $timeWindowStart = date('H:i', strtotime('-5 minutes', strtotime($time)));
            $timeWindowEnd = date('H:i', strtotime('+5 minutes', strtotime($time)));
            
            if ($currentTime >= $timeWindowStart && $currentTime <= $timeWindowEnd) {
                $timeMatch = true;
                break;
            }
        }
        
        // Check day for weekly reminders
        $dayMatch = ($reminder['frequency'] !== 'weekly') || in_array($currentDay, $days);
        
        if ($timeMatch && $dayMatch) {
            $activeReminders[] = $reminder;
        }
    }

    echo json_encode($activeReminders);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>