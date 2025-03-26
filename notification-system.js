// notification-system.js
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

    // Start interval checking (every 30 seconds)
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
                    // Only show if not already shown in this session
                    const reminderKey = `reminder_${reminder.id}_${reminder.medication_name}_${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;
                    if (!sessionStorage.getItem(reminderKey)) {
                        showMedicationReminder(reminder);
                        sessionStorage.setItem(reminderKey, 'shown');
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
        const audio = new Audio('alert.mpr');
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
        setTimeout(() => {
            showMedicationReminder(reminder);
        }, 600000); // 10 minutes
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