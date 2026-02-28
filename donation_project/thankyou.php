<?php
session_start();
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - Care Cloud</title>
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

    <!-- Thank You Content -->
    <div class="container">
        <div class="card" style="text-align: center; padding: 3rem;">
            <h2 class="page-title">Thank You for Your Donation!</h2>
            <p style="font-size: 1.2rem; margin-bottom: 2rem;">Your generosity makes a real difference in the lives of those in need.</p>
            
            <div style="font-size: 3rem; color: #27ae60; margin-bottom: 1rem;">&#128525;</div>
            
            <p>Your donation will help us continue our mission to support communities in need around the world.</p>
            
            <p>We appreciate your kindness and compassion. Together, we can create a brighter future for everyone.</p>
            
            <div style="margin: 2rem 0;">
                <a href="index.php" class="btn" style="margin-right: 1rem;">Back to Home</a>
                <a href="donate.php" class="btn">Make Another Donation</a>
            </div>
        </div>
        
        <div class="card">
            <h3>Ways to Stay Involved</h3>
            <ul>
                <li>Follow us on social media to see the impact of donations</li>
                <li>Subscribe to our newsletter for updates on projects</li>
                <li>Share our mission with friends and family</li>
                <li>Volunteer with our partner organizations</li>
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