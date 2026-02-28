<?php
session_start();
include 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $amount = !empty($_POST['amount']) ? floatval($_POST['amount']) : null;
    $item_type = trim($_POST['item_type']);
    $description = trim($_POST['description']);
    $quantity = intval($_POST['quantity']);
    $delivery_option = trim($_POST['delivery_option']);
    $address = trim($_POST['address']);

    // Validation
    if (empty($name) || empty($email) || empty($item_type) || empty($description) || empty($quantity)) {
        $error_message = 'All required fields must be filled.';
    } elseif (!empty($amount) && $amount <= 0) {
        $error_message = 'Amount must be greater than zero.';
    } elseif ($quantity <= 0) {
        $error_message = 'Quantity must be greater than zero.';
    } elseif ($delivery_option === 'Request Pick-up' && empty($address)) {
        $error_message = 'Address is required for pick-up delivery option.';
    } else {
        try {
            $user_id = $_SESSION['user_id'];
            
            $result = $donations_collection->insertOne([
                'user_id' => $user_id,
                'name' => $name,
                'email' => $email,
                'mobile' => $mobile,
                'amount' => $amount,
                'item_type' => $item_type,
                'description' => $description,
                'quantity' => $quantity,
                'delivery_option' => $delivery_option,
                'address' => $address,
                'created_at' => new MongoDB\BSON\UTCDateTime(time() * 1000)
            ]);
            
            $success_message = 'Donation submitted successfully!';
            // Clear form after successful submission
            $_POST = array();
        } catch (Exception $e) {
            $error_message = 'Error occurred during donation submission.';
        }
    }
}

// Get user details to prefill form
$user_details = null;
if (isset($_SESSION['user_id'])) {
    try {
        $objectId = new MongoDB\BSON\ObjectId($_SESSION['user_id']);
        $user_details = $users_collection->findOne(['_id' => $objectId]);
    } catch (Exception $e) {
        $error_message = 'Error retrieving user details.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate - Care Cloud</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="navbar-brand">Care Cloud</a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="donate.php">Donate</a></li>
                <li><a href="items_donated.php">Items Donated</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="suggest.php">Suggest</a></li>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="success_story.php">Success Story</a></li>
            </ul>
        </div>
    </nav>

    <!-- Donate Form -->
    <div class="container">
        <div class="form-container">
            <h2 class="page-title">Make a Donation</h2>
            
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
                <p><a href="thankyou.php" class="btn">Continue</a></p>
            <?php endif; ?>
            
            <form method="POST" action="" <?php echo $success_message ? 'style="display:none;"' : ''; ?>>
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" id="name" name="name" value="<?php echo $user_details ? htmlspecialchars($user_details['first_name'] . ' ' . $user_details['last_name']) : (isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="<?php echo $user_details ? htmlspecialchars($user_details['email']) : (isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="mobile">Mobile</label>
                    <input type="tel" id="mobile" name="mobile" value="<?php echo $user_details ? htmlspecialchars($user_details['mobile']) : (isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="amount">Donation Amount (in USD)</label>
                    <input type="number" id="amount" name="amount" min="0" step="0.01" value="<?php echo isset($_POST['amount']) ? htmlspecialchars($_POST['amount']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="item_type">Item Type *</label>
                    <select id="item_type" name="item_type" required>
                        <option value="">Select Item Type</option>
                        <option value="Books" <?php echo (isset($_POST['item_type']) && $_POST['item_type'] == 'Books') ? 'selected' : ''; ?>>Books</option>
                        <option value="Clothes" <?php echo (isset($_POST['item_type']) && $_POST['item_type'] == 'Clothes') ? 'selected' : ''; ?>>Clothes</option>
                        <option value="Food" <?php echo (isset($_POST['item_type']) && $_POST['item_type'] == 'Food') ? 'selected' : ''; ?>>Food</option>
                        <option value="Stationery" <?php echo (isset($_POST['item_type']) && $_POST['item_type'] == 'Stationery') ? 'selected' : ''; ?>>Stationery</option>
                        <option value="Electronics" <?php echo (isset($_POST['item_type']) && $_POST['item_type'] == 'Electronics') ? 'selected' : ''; ?>>Electronics</option>
                        <option value="Furniture" <?php echo (isset($_POST['item_type']) && $_POST['item_type'] == 'Furniture') ? 'selected' : ''; ?>>Furniture</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="description">Item Description *</label>
                    <textarea id="description" name="description" rows="3" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="quantity">Quantity *</label>
                    <input type="number" id="quantity" name="quantity" min="1" value="<?php echo isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : '1'; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="delivery_option">Delivery Option *</label>
                    <select id="delivery_option" name="delivery_option" required>
                        <option value="Drop-off" <?php echo (isset($_POST['delivery_option']) && $_POST['delivery_option'] == 'Drop-off') ? 'selected' : ''; ?>>Drop-off at Location</option>
                        <option value="Request Pick-up" <?php echo (isset($_POST['delivery_option']) && $_POST['delivery_option'] == 'Request Pick-up') ? 'selected' : ''; ?>>Request Pick-up</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="address">Address (required if requesting pick-up) *</label>
                    <textarea id="address" name="address" rows="3"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                </div>
                
                <button type="submit" class="btn">Submit Donation</button>
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