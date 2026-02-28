<?php
session_start();
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Care Cloud</title>
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

    <!-- About Content -->
    <div class="container">
        <h2 class="page-title">About Us</h2>
        
        <div class="card about-section">
            <h3>Our Mission</h3>
            <p>At Care Cloud, our mission is to bridge the gap between those who have and those who need. We believe that everyone deserves access to basic necessities, educational resources, and opportunities to improve their lives. Through our platform, we connect generous donors with communities in need, ensuring that every contribution makes a meaningful impact.</p>
        </div>
        
        <div class="card about-section">
            <h3>Our Story</h3>
            <p>Care Cloud was founded in 2020 by a group of passionate individuals who recognized the need for a more efficient and transparent way to facilitate donations. What started as a small initiative among friends has grown into a platform that serves thousands of people across the globe.</p>
            <p>We've learned that even the smallest acts of kindness can create ripples of positive change. From books that open new worlds for children to clothing that provides warmth and dignity, every donation matters. Our goal is to make the donation process as seamless as possible while ensuring that every gift reaches those who need it most.</p>
        </div>
        
        <div class="card about-section">
            <h3>Join Our Cause</h3>
            <p>Donation is not just about giving money or items – it's about giving hope. When you donate through Care Cloud, you're not just contributing to a cause; you're becoming part of a community that believes in the power of collective action.</p>
            <p>Whether you're donating your time, money, or resources, every contribution counts. Together, we can create a world where everyone has the opportunity to thrive. Join us today and be part of the solution.</p>
        </div>
        
        <div class="card about-section">
            <h3>Our Impact</h3>
            <p>Since our inception, we have:</p>
            <ul>
                <li>Facilitated over 10,000 donations</li>
                <li>Supported 50+ communities worldwide</li>
                <li>Provided educational resources to 5,000+ children</li>
                <li>Supplied essential items to families in need</li>
                <li>Created sustainable change in underserved areas</li>
            </ul>
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