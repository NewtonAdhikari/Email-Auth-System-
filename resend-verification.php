<?php
require_once 'config/config.php';
require_once 'includes/User.php';
require_once 'includes/Email.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        $message = 'Please enter your email address';
        $messageType = 'error';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Invalid email format';
        $messageType = 'error';
    } else {
        // Find unverified users with this email
        require_once 'config/Database.php';
        $db = new Database();
        $conn = $db->getConnection();
        
        $query = "SELECT id, username, verification_token, email_verified FROM users WHERE email = ? AND email_verified = 0 LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            $message = 'No unverified account found with this email address';
            $messageType = 'error';
        } else {
            // Resend verification email
            $emailClass = new Email();
            $verificationLink = SITE_URL . 'verify-email.php?token=' . $user['verification_token'];
            $emailResult = $emailClass->sendVerificationEmail($email, $user['username'], $verificationLink);
            
            if ($emailResult) {
                $message = '✓ Verification email resent successfully! Check your email and click the verification link.';
                $messageType = 'success';
            } else {
                $message = '⚠ Email could not be sent. Please try again later or contact support.';
                $messageType = 'warning';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resend Verification Email - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .container { max-width: 400px; margin: 50px auto; padding: 20px; }
        .card { border: 1px solid #ddd; border-radius: 5px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="email"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 3px; }
        button { width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 3px; cursor: pointer; }
        button:hover { background-color: #45a049; }
        .message { margin-bottom: 20px; padding: 10px; border-radius: 3px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .back-link { text-align: center; margin-top: 15px; }
        .back-link a { color: #4CAF50; text-decoration: none; }
        .back-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Resend Verification Email</h2>
            
            <?php if ($message): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="email">Enter your email address:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit">Resend Verification Email</button>
            </form>
            
            <div class="back-link">
                <p>Remember your password? <a href="login.php">Back to Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
