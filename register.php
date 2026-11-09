<?php
require_once 'config/config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$message = '';
$messageType = '';

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/User.php';
    
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $message = 'All fields are required';
        $messageType = 'error';
    } elseif ($password !== $confirmPassword) {
        $message = 'Passwords do not match';
        $messageType = 'error';
    } else {
        $user = new User();
        $result = $user->register($username, $email, $password);
        
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
    <title>Register - Email Authentication</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Create Account</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="register.php" id="registerForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required placeholder="Enter username">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="Enter email address">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter password (min 6 characters)">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm password">
                </div>
                
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
            
            <p class="form-footer">
                Already have an account? <a href="login.php">Login here</a>
            </p>
        </div>
    </div>
</body>
</html>
