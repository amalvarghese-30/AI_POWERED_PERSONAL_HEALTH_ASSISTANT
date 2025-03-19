<!DOCTYPE php>
<php lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AI Health Assistant</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="symptom-checker.php">Symptom Checker</a></li>
                <li><a href="recommendations.php">Recommendations</a></li>
                <li><a href="reminders.php">Reminders</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="dashboard">
            <h1>Your Dashboard</h1>
            <div class="progress">
                <h3>Health Progress</h3>
                <p>Fitness: 70%</p>
                <p>Diet: 85%</p>
                <p>Mental Health: 60%</p>
            </div>
            <div class="reminders">
                <h3>Upcoming Reminders</h3>
                <p>Take Medication X at 10:00 AM</p>
                <p>Take Medication Y at 02:00 PM</p>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 AI Health Assistant. All rights reserved.</p>
    </footer>
</body>

</php>