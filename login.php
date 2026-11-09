<?php
require_once 'config/config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$message = '';
$messageType = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/User.php';
    
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $message = 'Email and password are required';
        $messageType = 'error';
    } else {
        $user = new User();
        $result = $user->login($email, $password);
        
        if ($result['success']) {
            header('Location: dashboard.php');
            exit();
        } else {
            $message = $result['message'];
            $messageType = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Email Authentication</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Login</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="login.php" id="loginForm">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="Enter email address">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter password">
                </div>
                
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            
            <p class="form-footer">
                Don't have an account? <a href="register.php">Register here</a>
            </p>
            <p class="form-footer">
                <a href="forgot-password.php">Forgot password?</a>
            </p>
        </div>
    </div>
</body>
</html>
