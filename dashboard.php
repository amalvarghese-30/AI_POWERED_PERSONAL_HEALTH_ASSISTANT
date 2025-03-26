<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login-signup.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "health_assistant");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get user data
$userId = $_SESSION['user_id'];
$reminders = $conn->query("SELECT * FROM medication_reminders WHERE user_id = $userId ORDER BY start_date LIMIT 3");
$logs = $conn->query("SELECT l.*, r.medication_name FROM medication_logs l 
                     JOIN medication_reminders r ON l.reminder_id = r.id 
                     WHERE r.user_id = $userId ORDER BY l.taken_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AI Health Assistant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="notification-system.js"></script>
      <style>
        /* General Styles */
       /* style.css - Navbar and Footer only */
/* General Styles */
body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    line-height: 1.6;
    color: #333;
}

header {
    background: #4EA685;
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
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
}

header nav ul li a:hover {
    text-decoration: underline;
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

/* Footer styles */
footer {
    background: #333;
    color: white;
    text-align: center;
    padding: 20px 0;
}

.social-links a {
    color: white;
    margin: 0 10px;
    text-decoration: none;
    font-size: 1.2rem;
}

.social-links a:hover {
    color: #4EA685;
}

/* Chatbot Icon */
.chatbot-icon {
    position: fixed;
    bottom: 30px;
    right: 20px;
    background: #4EA685;
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.5rem;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    z-index: 1000;
    transition: all 0.3s;
}

.chatbot-icon:hover {
    background: #45a049;
    transform: scale(1.1);
}

/* Responsive styles */
@media (max-width: 768px) {
    header {
        flex-direction: column;
        padding: 10px;
    }
    
    header nav ul {
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 10px;
    }
    
    header nav ul li {
        margin: 5px 10px;
    }
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
                        <a href="profile.php"><i class="fas fa-user"></i> My Profile</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-main">
        <div class="welcome-message">
            <h1>Welcome Back, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            <p>Your health overview and quick actions</p>
        </div>
        
        <div class="dashboard-grid">
            <!-- Health Summary Card -->
            <div class="dashboard-card">
                <h2 class="card-title"><i class="fas fa-heartbeat"></i> Health Summary</h2>
                <div class="metric-item">
                    <div>Medication Adherence <span>75%</span></div>
                    <div class="metric-bar"><div class="metric-fill" style="width:75%"></div></div>
                </div>
                <div class="metric-item">
                    <div>Activity Level <span>60%</span></div>
                    <div class="metric-bar"><div class="metric-fill" style="width:60%"></div></div>
                </div>
                <div class="metric-item">
                    <div>Sleep Quality <span>90%</span></div>
                    <div class="metric-bar"><div class="metric-fill" style="width:90%"></div></div>
                </div>
            </div>
            
            <!-- Upcoming Reminders -->
            <div class="dashboard-card">
                <h2 class="card-title"><i class="fas fa-bell"></i> Upcoming Reminders</h2>
                <?php if ($reminders->num_rows > 0): ?>
                    <?php while($reminder = $reminders->fetch_assoc()): 
                        $times = json_decode($reminder['reminder_times']);
                    ?>
                    <div class="reminder-item">
                        <strong><?php echo htmlspecialchars($reminder['medication_name']); ?></strong>
                        <p><?php echo htmlspecialchars($reminder['dosage']); ?> at <?php echo $times[0]; ?></p>
                        <form method="POST" action="mark_taken.php" style="display:inline;">
                            <input type="hidden" name="reminder_id" value="<?php echo $reminder['id']; ?>">
                            <button type="submit" class="btn">Mark Taken</button>
                        </form>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No upcoming reminders. <a href="reminders.php">Add some?</a></p>
                <?php endif; ?>
            </div>
            
            <!-- Recent Activity -->
            <div class="dashboard-card">
                <h2 class="card-title"><i class="fas fa-history"></i> Recent Activity</h2>
                <?php if ($logs->num_rows > 0): ?>
                    <?php while($log = $logs->fetch_assoc()): ?>
                    <div class="activity-item">
                        <p><strong><?php echo htmlspecialchars($log['medication_name']); ?></strong></p>
                        <small><?php echo date('M j, g:i a', strtotime($log['taken_at'])); ?></small>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No recent activity recorded.</p>
                <?php endif; ?>
            </div>
            
            <!-- Quick Actions -->
            <div class="dashboard-card">
                <h2 class="card-title"><i class="fas fa-bolt"></i> Quick Actions</h2>
                <div class="quick-actions">
                    <a href="symptom-checker.php" class="quick-btn">
                        <i class="fas fa-stethoscope"></i> Symptom Check
                    </a>
                    <a href="log-health.php" class="quick-btn">
                        <i class="fas fa-plus"></i> Log Health Data
                    </a>
                    <a href="reminders.php" class="quick-btn">
                        <i class="fas fa-bell"></i> Add Reminder
                    </a>
                    <a href="appointments.php" class="quick-btn">
                        <i class="fas fa-calendar"></i> Appointments
                    </a>
                </div>
            </div>
            
            <!-- Health Tips -->
            <div class="dashboard-card">
                <h2 class="card-title"><i class="fas fa-lightbulb"></i> Health Tips</h2>
                <div class="activity-item">
                    <p><i class="fas fa-glass-water"></i> Drink 8 glasses of water today</p>
                </div>
                <div class="activity-item">
                    <p><i class="fas fa-walking"></i> Take a 30-minute walk</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Floating Chatbot Icon -->
    <div class="chatbot-icon">
        <a href="/chatbot.php" style="color: white;"><i class="fas fa-robot"></i></a>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> AI Health Assistant. All rights reserved.</p>
        <div class="social-links">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
    </footer>

    <script>
        // Dropdown functionality
        function toggleDropdown() {
            document.getElementById("userDropdown").classList.toggle("show");
        }
        
        window.onclick = function(e) {
            if (!e.target.matches('.user-icon') && !e.target.matches('.user-icon *')) {
                var dropdown = document.getElementById("userDropdown");
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }

        // Animate metric bars on page load
        document.addEventListener('DOMContentLoaded', function() {
            const bars = document.querySelectorAll('.metric-fill');
            bars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
        });
    </script>
</body>
</html>