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
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$reminderId = isset($data['reminder_id']) ? intval($data['reminder_id']) : 0;

if ($reminderId <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid reminder ID']);
    exit();
}

try {
    // Verify the reminder belongs to the user
    $stmt = $conn->prepare("SELECT id FROM medication_reminders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $reminderId, $_SESSION['user_id']);
    $stmt->execute();
    
    if (!$stmt->get_result()->fetch_assoc()) {
        echo json_encode(['success' => false, 'error' => 'Reminder not found']);
        exit();
    }

    // Record that the medication was taken
    $stmt = $conn->prepare("INSERT INTO medication_logs (reminder_id, taken_at) VALUES (?, NOW())");
    $stmt->bind_param("i", $reminderId);
    $stmt->execute();
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    error_log('Mark taken error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>