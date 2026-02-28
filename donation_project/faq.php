<?php
session_start();
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Care Cloud</title>
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

    <!-- FAQ Content -->
    <div class="container">
        <h2 class="page-title">Frequently Asked Questions</h2>
        
        <div class="faq-item">
            <div class="faq-question">
                How can I donate?
                <span>+</span>
            </div>
            <div class="faq-answer">
                <p>You can donate through our website by clicking the "Donate" button in the navigation bar. Simply fill out the donation form with details about what you'd like to donate, including the type of item, description, and quantity. You can also choose to donate money by entering an amount in the donation form.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                What items can I donate?
                <span>+</span>
            </div>
            <div class="faq-answer">
                <p>We accept a wide variety of items including books, clothes, food, stationery, electronics, and furniture. All items should be in good condition and suitable for use by others. Please ensure that any electronic items are in working condition before donating.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                Is donation secure?
                <span>+</span>
            </div>
            <div class="faq-answer">
                <p>Yes, our donation process is completely secure. We use industry-standard security measures to protect your personal information. All financial transactions are processed securely, and we never share your personal information with third parties without your consent.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                Can I donate money instead of items?
                <span>+</span>
            </div>
            <div class="faq-answer">
                <p>Absolutely! We accept monetary donations in addition to physical items. During the donation process, you can specify an amount you'd like to donate in the "Donation Amount" field. Your monetary donation will be used to purchase items that are most needed by communities in our network.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                How are donated items distributed?
                <span>+</span>
            </div>
            <div class="faq-answer">
                <p>Donated items are carefully sorted and distributed to verified organizations and communities in need. We work with trusted partners to ensure that all items reach their intended recipients. You can view the impact of your donations on our success stories page.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                Are donations tax deductible?
                <span>+</span>
            </div>
            <div class="faq-answer">
                <p>Many of our donation categories qualify for tax deductions. Once you make a donation, you'll receive a receipt that can be used for tax purposes. Please consult with a tax professional to understand the specific deductions applicable to your situation.</p>
            </div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">
                How can I track my donation?
                <span>+</span>
            </div>
            <div class="faq-answer">
                <p>After making a donation, you can track its status by visiting the "Items Donated" page on our website. Here you'll find information about all donations made through our platform, including the status and distribution details.</p>
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
        // Script for FAQ accordion functionality
        document.addEventListener('DOMContentLoaded', function() {
            const faqQuestions = document.querySelectorAll('.faq-question');
            faqQuestions.forEach(question => {
                question.addEventListener('click', () => {
                    const answer = question.nextElementSibling;
                    const span = question.querySelector('span');
                    
                    if (answer.style.display === 'block') {
                        answer.style.display = 'none';
                        span.textContent = '+';
                    } else {
                        answer.style.display = 'block';
                        span.textContent = '-';
                    }
                });
            });
        });
    </script>
</body>
</html>