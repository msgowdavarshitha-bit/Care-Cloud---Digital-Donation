<?php
session_start();
include 'includes/db.php';

// Ensure user details are available on the homepage
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Cloud - Empowering a Brighter Tomorrow</title>
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Welcome to Care Cloud</h1>
            <p>Empowering a Brighter Tomorrow, One Donation at a Time.</p>
            <a href="donate.php" class="btn">Donate Now</a>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <div class="page-content">
            <h2 class="page-title">About Our Mission</h2>
            <div class="card">
                <p>Care Cloud is dedicated to connecting generous donors with communities in need. We believe that small acts of kindness can create big changes in the world. Through our platform, you can easily donate money or items to support various causes and make a difference in someone's life.</p>
                
                <p>Our mission is to make the donation process simple, secure, and impactful. We ensure that every donation reaches the right hands and creates positive change in communities around the world.</p>
            </div>
            
            <div class="card">
                <h3>Why Donate With Us?</h3>
                <ul>
                    <li>Secure and transparent donation process</li>
                    <li>Direct impact on communities in need</li>
                    <li>Tax-deductible donations</li>
                    <li>Real-time updates on donation impact</li>
                    <li>Multiple donation options to suit your preferences</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Care Cloud. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Simple script for FAQ functionality
        document.addEventListener('DOMContentLoaded', function() {
            const faqQuestions = document.querySelectorAll('.faq-question');
            faqQuestions.forEach(question => {
                question.addEventListener('click', () => {
                    const answer = question.nextElementSibling;
                    answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
                });
            });
        });
    </script>
</body>
</html>