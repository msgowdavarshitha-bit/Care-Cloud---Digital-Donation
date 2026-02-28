<?php
session_start();
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success Stories - Care Cloud</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="navbar-brand">Care Cloud</a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php">Logout</a></li>
                    <li><a href="donate.php">Donate</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="signup.php">Sign Up</a></li>
                <?php endif; ?>
                <li><a href="donate.php">Donate</a></li>
                <li><a href="items_donated.php">Items Donated</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="suggest.php">Suggest</a></li>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="success_story.php">Success Story</a></li>
            </ul>
        </div>
    </nav>

    <!-- Success Stories Content -->
    <div class="container">
        <h2 class="page-title">Success Stories</h2>
        
        <div class="success-story">
            <h3>Building Libraries in Rural Communities</h3>
            <p>In partnership with local organizations, we've helped establish 15 mini-libraries in rural communities across three countries. These libraries were built using donated books and educational materials from our generous supporters.</p>
            <p>Over 2,000 children now have access to educational resources they didn't have before. Local teachers report improved reading scores and increased enthusiasm for learning among students.</p>
            <p><strong>Impact:</strong> 2,000+ children受益, 15 libraries established, 10,000+ books distributed</p>
        </div>
        
        <div class="success-story">
            <h3>Winter Clothing Drive</h3>
            <p>During the harsh winter of 2023, we launched a clothing drive to help families in need. Thanks to the generosity of our donors, we collected over 3,000 items of warm clothing including coats, gloves, scarves, and boots.</p>
            <p>The clothing was distributed to homeless shelters and community centers in cold-weather regions. Recipients expressed heartfelt gratitude for the warm clothing that helped them survive the winter months.</p>
            <p><strong>Impact:</strong> 3,000+ clothing items distributed, 500+ families helped, 12 community centers supported</p>
        </div>
        
        <div class="success-story">
            <h3>Technology for Education</h3>
            <p>Recognizing the growing need for digital literacy, we initiated a program to provide computers and tablets to schools in underserved areas. Donated electronics were refurbished and distributed to classrooms.</p>
            <p>Students who had never used a computer before are now learning digital skills that will help them in their future careers. Teachers have reported increased engagement and interest in subjects like math and science.</p>
            <p><strong>Impact:</strong> 150+ devices distributed, 10 schools equipped, 800+ students gaining digital skills</p>
        </div>
        
        <div class="card">
            <h3>Images Gallery</h3>
            <p>Below are some images showing the impact of donations:</p>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <div class="card" style="text-align: center; padding: 1rem;">
                    <div style="background-color: #eee; height: 150px; display: flex; align-items: center; justify-content: center; margin-bottom: 0.5rem;">
                        <span>Library Image</span>
                    </div>
                    <p>Children enjoying their new library</p>
                </div>
                <div class="card" style="text-align: center; padding: 1rem;">
                    <div style="background-color: #eee; height: 150px; display: flex; align-items: center; justify-content: center; margin-bottom: 0.5rem;">
                        <span>Clothing Distribution</span>
                    </div>
                    <p>Families receiving winter clothing</p>
                </div>
                <div class="card" style="text-align: center; padding: 1rem;">
                    <div style="background-color: #eee; height: 150px; display: flex; align-items: center; justify-content: center; margin-bottom: 0.5rem;">
                        <span>Technology Class</span>
                    </div>
                    <p>Students learning on donated computers</p>
                </div>
            </div>
        </div>
        
        <div class="card" style="margin-top: 2rem;">
            <h3>Your Impact Matters</h3>
            <p>Every donation, no matter how small, contributes to these success stories. When you give through Care Cloud, you become part of a movement that transforms lives and builds stronger communities.</p>
            <p><a href="donate.php" class="btn">Make a Difference Today</a></p>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Care Cloud. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>