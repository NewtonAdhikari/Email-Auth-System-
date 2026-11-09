<?php
require_once 'config/config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$message = '';
$messageType = '';

// Handle password reset request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/User.php';
    
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        $message = 'Email is required';
        $messageType = 'error';
    } else {
        $user = new User();
        $result = $user->requestPasswordReset($email);
        
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Email Authentication</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Forgot Password</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (empty($message) || $messageType === 'error'): ?>
                <form method="POST" action="forgot-password.php" id="forgotPasswordForm">
                    <p>Enter your email address and we'll send you a link to reset your password.</p>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required placeholder="Enter your email address">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Send Reset Link</button>
                </form>
            <?php endif; ?>
            
            <p class="form-footer">
                <a href="login.php">Back to Login</a>
            </p>
        </div>
    </div>
</body>
</html>
