<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "health_assistant");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login-signup.php");
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_reminder'])) {
        // Add new reminder
        $medication = htmlspecialchars($_POST['medication']);
        $dosage = htmlspecialchars($_POST['dosage']);
        $frequency = $_POST['frequency'];
        $times = json_encode($_POST['times']);
        $days = isset($_POST['days']) ? json_encode($_POST['days']) : '[]';
        $startDate = $_POST['start_date'];
        $endDate = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
        $refillDate = !empty($_POST['refill_date']) ? $_POST['refill_date'] : null;
        
        $stmt = $conn->prepare("INSERT INTO medication_reminders 
                              (user_id, medication_name, dosage, frequency, reminder_times, specific_days, start_date, end_date, refill_date) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", $_SESSION['user_id'], $medication, $dosage, $frequency, $times, $days, $startDate, $endDate, $refillDate);
        $stmt->execute();
        
        $_SESSION['success'] = "Reminder added successfully!";
        header("Location: reminders.php");
        exit();
    } elseif (isset($_POST['mark_taken'])) {
        // Mark medication as taken
        $reminderId = $_POST['reminder_id'];
        $stmt = $conn->prepare("INSERT INTO medication_logs (reminder_id, taken_at) VALUES (?, NOW())");
        $stmt->bind_param("i", $reminderId);
        $stmt->execute();
        
        $_SESSION['success'] = "Medication marked as taken!";
        header("Location: reminders.php");
        exit();
    }
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM medication_reminders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
    $stmt->execute();
    
    $_SESSION['success'] = "Reminder deleted successfully!";
    header("Location: reminders.php");
    exit();
}

// Get user's reminders
$userId = $_SESSION['user_id'];
$reminders = $conn->query("SELECT * FROM medication_reminders WHERE user_id = $userId ORDER BY start_date");
$logs = $conn->query("SELECT l.*, r.medication_name FROM medication_logs l 
                     JOIN medication_reminders r ON l.reminder_id = r.id 
                     WHERE r.user_id = $userId ORDER BY l.taken_at DESC LIMIT 10");

// Get common medications for quick add
$commonMeds = $conn->query("SELECT medication_name, dosage FROM medication_reminders 
                           WHERE user_id = $userId GROUP BY medication_name, dosage 
                           ORDER BY COUNT(*) DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medication Reminders - AI Health Assistant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: #333;
            background-color: #f5f7fa;
        }

        header {
            background: #4EA685;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        header nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
        }

        header nav ul li {
            margin: 0 15px;
            position: relative;
        }

        header nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: opacity 0.3s;
        }

        header nav ul li a:hover {
            opacity: 0.8;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        /* User dropdown styles */
        .user-menu {
            position: relative;
            display: inline-block;
        }
        
        .user-icon {
            cursor: pointer;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            color: white;
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 5px;
            padding: 10px;
        }
        
        .dropdown-content p {
            color: #333;
            padding: 5px;
            margin: 0;
            font-weight: bold;
            border-bottom: 1px solid #eee;
        }
        
        .dropdown-content a {
            color: #333;
            padding: 8px 5px;
            display: block;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .dropdown-content a:hover {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .show {
            display: block;
        }

        /* Main content styles */
        main {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            color: #4EA685;
            border-bottom: 2px solid #4EA685;
            padding-bottom: 10px;
            margin-top: 30px;
        }

        /* Reminder form styles */
        .reminder-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .time-input {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .time-input input {
            flex: 1;
            margin-right: 10px;
        }

        .day-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .day-options label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #4EA685;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background: #3d8a6a;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        /* Reminders list styles */
        .reminders-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .reminders-container {
                grid-template-columns: 1fr;
            }
        }

        .reminders-list, .logs-list {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .reminder-card {
            border-left: 4px solid #4EA685;
            padding: 15px;
            margin-bottom: 15px;
            background: #f9f9f9;
            border-radius: 4px;
            position: relative;
        }

        .reminder-card h3 {
            margin-top: 0;
            color: #4EA685;
        }

        .reminder-card .actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .log-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }

        /* Calendar styles */
        #calendar {
            margin: 30px 0;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* Quick add styles */
        .quick-add {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .quick-add-btn {
            padding: 5px 10px;
            background: #e9ecef;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .quick-add-btn:hover {
            background: #d1d7dc;
        }

        /* Success message */
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        /* Notification popup styles */
        .notification-popup {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4EA685;
            color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            z-index: 10000;
            max-width: 300px;
            animation: fadeIn 0.5s;
        }

        .notification-popup h3 {
            margin-top: 0;
            color: white;
        }

        .notification-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .notification-buttons button {
            flex: 1;
            border: none;
            padding: 8px;
            border-radius: 4px;
            cursor: pointer;
        }

        .notification-taken {
            background: white;
            color: #4EA685;
        }

        .notification-snooze {
            background: #f8f9fa;
            color: #6c757d;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-20px); }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">AI Health Assistant</div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="symptom-checker.php">Symptom Checker</a></li>
                <li><a href="recommendations.php">Recommendations</a></li>
                <li><a href="reminders.php">Reminders</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="about.php">About Us</a></li>
                <li class="user-menu">
                    <div class="user-icon" onclick="toggleDropdown()">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="dropdown-content" id="userDropdown">
                        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                        <a href="dashboard.php"><i class="fas fa-user"></i> My Profile</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <h1 class="section-title">Medication Reminders</h1>

        <div class="reminder-form">
            <h2>Add New Reminder</h2>
            
            <!-- Quick Add Section -->
            <?php if ($commonMeds->num_rows > 0): ?>
            <div class="quick-add">
                <p>Quick add:</p>
                <?php while($med = $commonMeds->fetch_assoc()): ?>
                    <button class="quick-add-btn" onclick="quickAdd('<?= htmlspecialchars($med['medication_name']) ?>', '<?= htmlspecialchars($med['dosage']) ?>')">
                        <?= htmlspecialchars($med['medication_name']) ?> (<?= htmlspecialchars($med['dosage']) ?>)
                    </button>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="add_reminder" value="1">
                
                <div class="form-group">
                    <label for="medication">Medication Name:</label>
                    <input type="text" id="medication" name="medication" required>
                </div>
                
                <div class="form-group">
                    <label for="dosage">Dosage:</label>
                    <input type="text" id="dosage" name="dosage" required>
                </div>
                
                <div class="form-group">
                    <label for="frequency">Frequency:</label>
                    <select name="frequency" id="frequency" required>
                        <option value="daily">Daily</option>
                        <option value="weekly">Specific Days</option>
                        <option value="custom">Custom Schedule</option>
                    </select>
                </div>
                
                <div class="form-group" id="days-container" style="display:none;">
                    <label>Days of Week:</label>
                    <div class="day-options">
                        <label><input type="checkbox" name="days[]" value="mon"> Monday</label>
                        <label><input type="checkbox" name="days[]" value="tue"> Tuesday</label>
                        <label><input type="checkbox" name="days[]" value="wed"> Wednesday</label>
                        <label><input type="checkbox" name="days[]" value="thu"> Thursday</label>
                        <label><input type="checkbox" name="days[]" value="fri"> Friday</label>
                        <label><input type="checkbox" name="days[]" value="sat"> Saturday</label>
                        <label><input type="checkbox" name="days[]" value="sun"> Sunday</label>
                    </div>
                </div>
                
                <div class="form-group" id="times-container">
                    <label>Reminder Times:</label>
                    <div class="time-input">
                        <input type="time" name="times[]" required>
                        <button type="button" class="btn btn-secondary" onclick="addTimeInput()">+ Add Time</button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" required>
                </div>
                
                <div class="form-group">
                    <label for="end_date">End Date (optional):</label>
                    <input type="date" id="end_date" name="end_date">
                </div>
                
                <div class="form-group">
                    <label for="refill_date">Refill Reminder Date (optional):</label>
                    <input type="date" id="refill_date" name="refill_date">
                    <small>We'll remind you when it's time to refill</small>
                </div>
                
                <button type="submit" class="btn">Save Reminder</button>
            </form>
        </div>

        <div class="reminders-container">
            <div class="reminders-list">
                <h2 class="section-title">Your Current Reminders</h2>
                
                <?php if ($reminders->num_rows > 0): ?>
                    <?php while($reminder = $reminders->fetch_assoc()): 
                        $times = json_decode($reminder['reminder_times']);
                        $days = json_decode($reminder['specific_days']);
                    ?>
                        <div class="reminder-card">
                            <h3><?= htmlspecialchars($reminder['medication_name']) ?></h3>
                            <p><strong>Dosage:</strong> <?= htmlspecialchars($reminder['dosage']) ?></p>
                            <p><strong>Schedule:</strong> 
                                <?= ucfirst($reminder['frequency']) ?>
                                <?php if ($reminder['frequency'] === 'weekly' && !empty($days)): ?>
                                    on <?= implode(', ', array_map(function($d) { 
                                        return date('l', strtotime($d . ' this week')); 
                                    }, $days)) ?>
                                <?php endif; ?>
                                at <?= implode(', ', $times) ?>
                            </p>
                            <p><strong>Duration:</strong> 
                                <?= date('M j, Y', strtotime($reminder['start_date'])) ?>
                                <?php if ($reminder['end_date']): ?>
                                    to <?= date('M j, Y', strtotime($reminder['end_date'])) ?>
                                <?php else: ?>
                                    (No end date)
                                <?php endif; ?>
                            </p>
                            
                            <?php if ($reminder['refill_date']): ?>
                                <p><strong>Refill by:</strong> <?= date('M j, Y', strtotime($reminder['refill_date'])) ?></p>
                            <?php endif; ?>
                            
                            <div class="actions">
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="mark_taken" value="1">
                                    <input type="hidden" name="reminder_id" value="<?= $reminder['id'] ?>">
                                    <button type="submit" class="btn">Mark as Taken</button>
                                </form>
                                <a href="reminders.php?delete=<?= $reminder['id'] ?>" class="btn btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this reminder?')">Delete</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No reminders set up yet. Add your first reminder above!</p>
                <?php endif; ?>
            </div>
            
            <div class="logs-list">
                <h2 class="section-title">Recent Medication Logs</h2>
                
                <?php if ($logs->num_rows > 0): ?>
                    <?php while($log = $logs->fetch_assoc()): ?>
                        <div class="log-item">
                            <span><?= htmlspecialchars($log['medication_name']) ?></span>
                            <span><?= date('M j, g:i a', strtotime($log['taken_at'])) ?></span>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No medication logs yet. Mark your medications as taken when you take them.</p>
                <?php endif; ?>
            </div>
        </div>

        <h2 class="section-title">Medication Calendar</h2>
        <div id="calendar"></div>
    </main>

    <footer>
        <p>&copy; 2023 AI Health Assistant. All rights reserved.</p>
    </footer>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    
    <script>
        // Dropdown functionality
        function toggleDropdown() {
            document.getElementById("userDropdown").classList.toggle("show");
        }
        
        // Close dropdown if clicked outside
        window.onclick = function(e) {
            if (!e.target.matches('.user-icon') && !e.target.matches('.user-icon *')) {
                var dropdown = document.getElementById("userDropdown");
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }

        // Frequency selection logic
        document.getElementById('frequency').addEventListener('change', function() {
            const daysContainer = document.getElementById('days-container');
            daysContainer.style.display = this.value === 'weekly' ? 'block' : 'none';
        });

        // Add time input
        function addTimeInput() {
            const container = document.getElementById('times-container');
            const newInput = document.createElement('div');
            newInput.className = 'time-input';
            newInput.innerHTML = `
                <input type="time" name="times[]" required>
                <button type="button" class="btn btn-secondary" onclick="this.parentNode.remove()">Remove</button>
            `;
            container.appendChild(newInput);
        }

        // Quick add function
        function quickAdd(medication, dosage) {
            document.getElementById('medication').value = medication;
            document.getElementById('dosage').value = dosage;
        }

        // Initialize calendar
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    <?php 
                    $reminders->data_seek(0);
                    while($reminder = $reminders->fetch_assoc()): 
                        $times = json_decode($reminder['reminder_times']);
                        foreach ($times as $time):
                    ?>
                    {
                        title: '<?= addslashes($reminder['medication_name']) ?> (<?= addslashes($reminder['dosage']) ?>)',
                        start: '<?= $reminder['start_date'] ?>T<?= $time ?>',
                        allDay: false,
                        color: '#4EA685'
                    },
                    <?php 
                        endforeach;
                    endwhile;
                    
                    $reminders->data_seek(0);
                    while($reminder = $reminders->fetch_assoc()): 
                        if ($reminder['refill_date']):
                    ?>
                    {
                        title: 'Refill <?= addslashes($reminder['medication_name']) ?>',
                        start: '<?= $reminder['refill_date'] ?>',
                        allDay: true,
                        color: '#dc3545'
                    },
                    <?php 
                        endif;
                    endwhile;
                    ?>
                ]
            });
            calendar.render();
        });

        // Enhanced notification system
        let reminderCheckInterval;

        function initializeReminderSystem() {
            // Request notification permission
            if (Notification.permission !== "granted") {
                Notification.requestPermission().then(permission => {
                    console.log("Notification permission:", permission);
                });
            }

            // Initial check
            checkReminders();
            
            // Start interval checking (every 30 seconds for better responsiveness)
            reminderCheckInterval = setInterval(checkReminders, 30000);
            
            // Also check when tab becomes visible
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) checkReminders();
            });
        }

        function checkReminders() {
            console.log("Checking for reminders at:", new Date().toLocaleTimeString());
            
            fetch('check_reminders.php')
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        console.error("Server error:", data.error);
                        return;
                    }
                    
                    if (Array.isArray(data)) {
                        console.log("Found", data.length, "active reminders");
                        data.forEach(reminder => {
                            // Only show if not already shown
                            const reminderKey = `reminder_${reminder.id}_${new Date().toISOString().split('T')[0]}`;
                            if (!sessionStorage.getItem(reminderKey)) {
                                showMedicationReminder(reminder);
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error("Error checking reminders:", error);
                });
        }

        function showMedicationReminder(reminder) {
            console.log("Showing reminder for:", reminder.medication_name);
            
            // Create popup
            const popup = document.createElement('div');
            popup.className = 'notification-popup';
            popup.innerHTML = `
                <h3>Medication Reminder</h3>
                <p><strong>${reminder.medication_name}</strong> (${reminder.dosage})</p>
                <p>Time to take your medication!</p>
                <div class="notification-buttons">
                    <button class="notification-taken" id="markTakenBtn">Mark as Taken</button>
                    <button class="notification-snooze" id="snoozeBtn">Snooze (10 min)</button>
                </div>
            `;
            
            document.body.appendChild(popup);
            
            // Play sound
            playNotificationSound();
            
            // Browser notification
            showBrowserNotification(reminder);
            
            // Setup buttons
            setupReminderButtons(popup, reminder);
            
            // Auto-close after 1 minute
            setTimeout(() => {
                popup.style.animation = 'fadeOut 0.5s';
                setTimeout(() => popup.remove(), 500);
            }, 60000);
        }

        function playNotificationSound() {
            try {
                const audio = new Audio('alert.mp3');
                audio.play().catch(e => console.log("Audio play blocked:", e));
            } catch (e) {
                console.error("Sound error:", e);
            }
        }

        function showBrowserNotification(reminder) {
            if (Notification.permission === "granted") {
                new Notification(`Time to take ${reminder.medication_name}`, {
                    body: `Dosage: ${reminder.dosage}`,
                    icon: 'assets/medication-icon.png'
                });
            }
        }

        function setupReminderButtons(popup, reminder) {
            // Mark as taken
            popup.querySelector('#markTakenBtn').addEventListener('click', () => {
                markAsTaken(reminder.id);
                popup.remove();
            });
            
            // Snooze
            popup.querySelector('#snoozeBtn').addEventListener('click', () => {
                popup.remove();
                setTimeout(() => showMedicationReminder(reminder), 600000); // 10 minutes
            });
        }

        function markAsTaken(reminderId) {
            fetch('mark_taken.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ reminder_id: reminderId })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) console.error("Failed to mark as taken");
            })
            .catch(error => console.error("Error:", error));
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', initializeReminderSystem);
    </script>
</body>
</html>