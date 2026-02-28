<?php
session_start();
include 'includes/db.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $suggestion = trim($_POST['suggestion']);

    // Validation
    if (empty($name) || empty($email) || empty($suggestion)) {
        $error_message = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email format.';
    } else {
        try {
            $result = $suggestions_collection->insertOne([
                'name' => $name,
                'email' => $email,
                'suggestion' => $suggestion,
                'created_at' => new MongoDB\BSON\UTCDateTime(time() * 1000)
            ]);
            
            $success_message = 'Thank you for your suggestion! We appreciate your feedback.';
            // Clear form after successful submission
            $_POST = array();
        } catch (Exception $e) {
            $error_message = 'Error occurred during suggestion submission.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggest - Care Cloud</title>
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

    <!-- Suggestion Form -->
    <div class="container">
        <div class="form-container">
            <h2 class="page-title">Submit Your Suggestion</h2>
            
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : (isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : (isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="suggestion">Suggestion *</label>
                    <textarea id="suggestion" name="suggestion" rows="5" required><?php echo isset($_POST['suggestion']) ? htmlspecialchars($_POST['suggestion']) : ''; ?></textarea>
                </div>
                
                <button type="submit" class="btn">Submit Suggestion</button>
            </form>
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