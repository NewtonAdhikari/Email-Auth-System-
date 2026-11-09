<?php
require_once 'config/config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$message = '';
$messageType = '';
$token = $_GET['token'] ?? '';

// Handle password reset
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/User.php';
    
    $token = trim($_POST['token'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($token) || empty($newPassword) || empty($confirmPassword)) {
        $message = 'All fields are required';
        $messageType = 'error';
    } elseif ($newPassword !== $confirmPassword) {
        $message = 'Passwords do not match';
        $messageType = 'error';
    } else {
        $user = new User();
        $result = $user->resetPassword($token, $newPassword);
        
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'error';
        
        if ($result['success']) {
            $token = '';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Email Authentication</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Reset Password</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (empty($token) && !$messageType): ?>
                <p>Please use the link from your email to reset your password.</p>
                <p class="form-footer">
                    <a href="login.php">Go to Login</a>
                </p>
            <?php elseif ($messageType === 'success'): ?>
                <p>Your password has been reset successfully.</p>
                <p class="form-footer">
                    <a href="login.php" class="btn btn-primary" style="display: inline-block;">Go to Login</a>
                </p>
            <?php else: ?>
                <form method="POST" action="reset-password.php" id="resetPasswordForm">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required placeholder="Enter new password (min 6 characters)">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm password">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </form>
            <?php endif; ?>
            
            <p class="form-footer">
                <a href="login.php">Back to Login</a>
            </p>
        </div>
    </div>
</body>
</html>
