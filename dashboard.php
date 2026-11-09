<?php
require_once 'config/config.php';
require_once 'includes/User.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    $user = new User();
    $user->logout();
    header('Location: login.php');
    exit();
}

// Get user information
$user = new User();
$userInfo = $user->getUserById($_SESSION['user_id']);

if (!$userInfo) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Email Authentication</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-container">
            <h2 class="navbar-brand">Email Auth System</h2>
            <div class="navbar-menu">
                <span class="user-greeting">Welcome, <?php echo htmlspecialchars($userInfo['username']); ?>!</span>
                <a href="?logout" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="dashboard-container">
            <h1>Dashboard</h1>
            
            <div class="dashboard-content">
                <div class="info-card">
                    <h3>User Information</h3>
                    <div class="info-item">
                        <label>Username:</label>
                        <span><?php echo htmlspecialchars($userInfo['username']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Email:</label>
                        <span><?php echo htmlspecialchars($userInfo['email']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Member Since:</label>
                        <span><?php echo date('F j, Y', strtotime($userInfo['created_at'])); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Last Login:</label>
                        <span><?php echo $userInfo['last_login'] ? date('F j, Y \a\t H:i', strtotime($userInfo['last_login'])) : 'Never'; ?></span>
                    </div>
                </div>
                
                <div class="info-card">
                    <h3>Account Actions</h3>
                    <ul class="action-list">
                        <li><a href="#" onclick="alert('Profile update feature coming soon!')">Edit Profile</a></li>
                        <li><a href="#" onclick="alert('Change password feature coming soon!')">Change Password</a></li>
                        <li><a href="?logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Email Authentication System. All rights reserved.</p>
    </footer>
</body>
</html>
