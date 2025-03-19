<!DOCTYPE php>
<php lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Health Assistant</title>
    <!-- Add Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        }

        /* Floating Chatbot Icon */
        .chatbot-icon {
            position: fixed;
            bottom: 30%;
            /* Positioned 30% up from the bottom */
            right: 20px;
            /* Positioned on the right-hand side */
            background: #4EA685;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            /* Makes it circular */
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Adds a shadow */
            z-index: 1000;
            /* Ensures it stays on top of other content */
        }

        .chatbot-icon:hover {
            background: #45a049;
            /* Changes color on hover */
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
                <li><a href="contact.php">Contact</a></li>
                <li><a href="login-signup.php">LOGIN</a></li>
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

        <!-- Features Section -->
        <!-- <section class="features">
            <h2>Our Features</h2>
            <div class="feature-cards">
                <div class="card">
                    <h3>Symptom Checker</h3>
                    <p>Identify possible health conditions based on your symptoms.</p>
                </div>
                <div class="card">
                    <h3>Personalized Recommendations</h3>
                    <p>Get tailored diet, fitness, and mental health plans.</p>
                </div>
                <div class="card">
                    <h3>Medication Reminders</h3>
                    <p>Never miss a dose with our smart reminder system.</p>
                </div>
            </div>
        </section> -->

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
                    <p>"This AI health assistant is amazing! It helped me identify my symptoms and gave me great
                        recommendations."</p>
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
        <a href="/chatbot.php"><i class="fas fa-robot"></i></a> <!-- Font Awesome chatbot icon -->
    </div>

    <footer>
        <p>&copy; 2023 AI Health Assistant. All rights reserved.</p>
        <div class="social-links">
            <a href="#">Facebook</a>
            <a href="#">Twitter</a>
            <a href="#">Instagram</a>
        </div>
    </footer>

    <script>
        const apiKey = 'a9cfd1f1ce6544409b3a51efe9efa888'; // Replace with your NewsAPI key
        const newsContainer = document.querySelector('.news-grid-container');

        // Fetch health news from NewsAPI
        function fetchHealthNews() {
            fetch(`https://newsapi.org/v2/top-headlines?category=health&apiKey=${apiKey}`)
                .then(response => response.json())
                .then(data => {
                    if (data.articles) {
                        // Filter articles with valid image URLs
                        const articlesWithImages = data.articles.filter(article => article.urlToImage);
                        displayNews(articlesWithImages);
                    }
                })
                .catch(error => console.error('Error fetching news:', error));
        }

        // Display news in the grid
        function displayNews(articles) {
            newsContainer.innerphp = ''; // Clear existing content
            articles.slice(0, 8).forEach(article => { // Show only 6 articles
                const newsCard = document.createElement('div');
                newsCard.classList.add('news-card');

                // Add image, title, description, and link
                newsCard.innerphp = `
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
    </script>
</body>

</php>