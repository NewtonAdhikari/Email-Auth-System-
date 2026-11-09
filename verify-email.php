<?php
require_once 'config/config.php';
require_once 'includes/User.php';

$message = '';
$messageType = '';

// Handle email verification
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $user = new User();
    $result = $user->verifyEmail($token);
    
    $message = $result['message'];
    $messageType = $result['success'] ? 'success' : 'error';
}

// Redirect if already logged in
if (isset($_SESSION['user_id']) && empty($message)) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Email Authentication</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Email Verification</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
                
                <?php if ($messageType === 'success'): ?>
                    <p class="form-footer">
                        <a href="login.php" class="btn btn-primary" style="display: inline-block;">Go to Login</a>
                    </p>
                <?php else: ?>
                    <p class="form-footer">
                        <a href="register.php" class="btn btn-primary" style="display: inline-block;">Register Again</a>
                    </p>
                <?php endif; ?>
            <?php else: ?>
                <p>Please use the link from your email to verify your account.</p>
                <p class="form-footer">
                    <a href="login.php">Go to Login</a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
