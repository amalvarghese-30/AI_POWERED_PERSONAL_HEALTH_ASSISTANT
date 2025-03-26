<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login-signup.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="notification-system.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Health Assistant</title>
    <!-- Add Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
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

        .hero {
            background-image: url('assets/pexels-negativespace-48604.jpg');
            background-size: cover;
            background-position: center;
            height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
        }

        .hero-content {
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #4EA685;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background: #45a049;
        }

        .features {
            padding: 40px 20px;
            text-align: center;
        }

        .features h2 {
            margin-bottom: 20px;
        }

        .feature-cards {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .card {
            background: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
            width: 30%;
            margin: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .news-grid {
            padding: 40px 20px;
            text-align: center;
        }

        .news-grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .news-card {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s;
        }

        .news-card:hover {
            transform: translateY(-5px);
        }

        .news-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .news-card h3 {
            margin-bottom: 10px;
            color: #4EA685;
        }

        .news-card p {
            font-size: 0.9rem;
            color: #555;
        }

        .news-card a {
            color: #4EA685;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }

        .news-card a:hover {
            text-decoration: underline;
        }

        .testimonials {
            padding: 40px 20px;
            text-align: center;
        }

        .testimonial-cards {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .testimonial {
            background: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
            width: 45%;
            margin: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .cta {
            background: #4EA685;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .cta .btn {
            background: white;
            color: #4EA685;
        }

        .cta .btn:hover {
            background: #f1f1f1;
        }

        .newsletter {
            padding: 40px 20px;
            text-align: center;
            background: #f4f4f4;
        }

        .newsletter input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

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

        /* Floating Chatbot Icon */
        .chatbot-icon {
            position: fixed;
            bottom: 30%;
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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
            
            .feature-cards, .testimonial-cards {
                flex-direction: column;
            }
            
            .card, .testimonial {
                width: 100%;
                margin: 10px 0;
            }
            
            .hero {
                height: 300px;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .newsletter input {
                width: 100%;
                margin-right: 0;
                margin-bottom: 10px;
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
                        <a href="dashboard.php"><i class="fas fa-user"></i> My Profile</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Welcome to Your AI Health Assistant</h1>
                <p>Your personal guide to better health and wellness.</p>
                <a href="symptom-checker.php" class="btn">Check Symptoms</a>
                <a href="recommendations.php" class="btn">Get Recommendations</a>
            </div>
        </section>

        <!-- News/Blog Grid Section -->
        <section class="news-grid">
            <h2>Health News & Blogs</h2>
            <div class="news-grid-container">
                <!-- News cards will be dynamically inserted here -->
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="testimonials">
            <h2>What Our Users Say</h2>
            <div class="testimonial-cards">
                <div class="testimonial">
                    <p>"This AI health assistant is amazing! It helped me identify my symptoms and gave me great recommendations."</p>
                    <p><strong>- John Doe</strong></p>
                </div>
                <div class="testimonial">
                    <p>"The medication reminders are a lifesaver. I never miss a dose now!"</p>
                    <p><strong>- Jane Smith</strong></p>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta">
            <h2>Start Your Health Journey Today</h2>
            <p>Join thousands of users who are improving their health with our AI-powered assistant.</p>
            <a href="symptom-checker.php" class="btn">Get Started</a>
        </section>

        <!-- Newsletter Section -->
        <section class="newsletter">
            <h2>Subscribe to Our Newsletter</h2>
            <form id="newsletter-form">
                <input type="email" placeholder="Enter your email" required>
                <button type="submit" class="btn">Subscribe</button>
            </form>
        </section>
    </main>

    <!-- Floating Chatbot Icon -->
    <div class="chatbot-icon">
        <a href="/chatbot.php" style="color: white;"><i class="fas fa-robot"></i></a>
    </div>

    <footer>
        <p>&copy; 2023 AI Health Assistant. All rights reserved.</p>
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
        
        // Close dropdown if clicked outside
        window.onclick = function(e) {
            if (!e.target.matches('.user-icon') && !e.target.matches('.user-icon *')) {
                var dropdown = document.getElementById("userDropdown");
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }

        // News API functionality
        const apiKey = 'a9cfd1f1ce6544409b3a51efe9efa888';
        const newsContainer = document.querySelector('.news-grid-container');

        function fetchHealthNews() {
            fetch(`https://newsapi.org/v2/top-headlines?category=health&apiKey=${apiKey}`)
                .then(response => response.json())
                .then(data => {
                    if (data.articles) {
                        const articlesWithImages = data.articles.filter(article => article.urlToImage);
                        displayNews(articlesWithImages);
                    }
                })
                .catch(error => console.error('Error fetching news:', error));
        }

        function displayNews(articles) {
            newsContainer.innerHTML = '';
            articles.slice(0, 8).forEach(article => {
                const newsCard = document.createElement('div');
                newsCard.classList.add('news-card');
                newsCard.innerHTML = `
                    <img src="${article.urlToImage}" alt="${article.title}">
                    <h3>${article.title}</h3>
                    <p>${article.description || 'No description available.'}</p>
                    <a href="${article.url}" target="_blank">Read More</a>
                `;
                newsContainer.appendChild(newsCard);
            });
        }

        // Fetch news when the page loads
        fetchHealthNews();

        // Refresh news every 1 minute (60,000 milliseconds)
        setInterval(fetchHealthNews, 60000);

        // Newsletter form submission
        document.getElementById('newsletter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input').value;
            alert(`Thank you for subscribing with ${email}! You'll receive our newsletter soon.`);
            this.reset();
        });
    </script>
</body>
</html>