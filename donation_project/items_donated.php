<?php
session_start();
include 'includes/db.php';

$donations = [];

try {
    $cursor = $donations_collection->find([], ['sort' => ['created_at' => -1]]);
    foreach ($cursor as $donation) {
        $donations[] = $donation;
    }
} catch (Exception $e) {
    $error_message = 'Error fetching donations.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items Donated - Care Cloud</title>
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

    <!-- Items Donated Content -->
    <div class="container">
        <h2 class="page-title">Items Donated</h2>
        
        <?php if (count($donations) > 0): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Amount</th>
                            <th>Item Type</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($donations as $donation): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($donation['name']); ?></td>
                                <td><?php echo htmlspecialchars($donation['email']); ?></td>
                                <td><?php echo $donation['amount'] ? '$' . number_format($donation['amount'], 2) : 'N/A'; ?></td>
                                <td><?php echo htmlspecialchars($donation['item_type']); ?></td>
                                <td><?php echo htmlspecialchars(substr($donation['description'], 0, 50)) . (strlen($donation['description']) > 50 ? '...' : ''); ?></td>
                                <td><?php echo htmlspecialchars($donation['quantity']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($donation['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="card">
                <p>No donations have been made yet. Be the first to make a difference!</p>
                <a href="donate.php" class="btn">Make a Donation</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Care Cloud. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>