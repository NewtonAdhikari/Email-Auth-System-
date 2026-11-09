<?php
/* Landing Page - Index */
require_once 'config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Authentication System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-container">
            <h2 class="navbar-brand">Email Auth System</h2>
            <div class="navbar-menu">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php" class="btn btn-primary">Dashboard</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">Login</a>
                    <a href="register.php" class="btn btn-secondary">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="hero">
        <div class="hero-content">
            <h1>Secure Email Authentication</h1>
            <p>A complete email authentication system with verification, login, and password reset features.</p>
            <div class="hero-buttons">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="register.php" class="btn btn-large">Get Started</a>
                    <a href="login.php" class="btn btn-large btn-secondary">Already a Member?</a>
                <?php else: ?>
                    <a href="dashboard.php" class="btn btn-large">Go to Dashboard</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="container">
        <section class="features">
            <h2>Features</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <h3>📧 Email Verification</h3>
                    <p>Verify user email addresses with secure verification tokens and time-based expiry.</p>
                </div>
                <div class="feature-card">
                    <h3>🔐 Secure Password</h3>
                    <p>Password hashing using bcrypt for maximum security. Passwords never stored in plain text.</p>
                </div>
                <div class="feature-card">
                    <h3>🔑 Password Reset</h3>
                    <p>Secure password reset functionality with email verification and token expiry.</p>
                </div>
                <div class="feature-card">
                    <h3>📝 User Dashboard</h3>
                    <p>Personal dashboard to manage account information and settings.</p>
                </div>
                <div class="feature-card">
                    <h3>📊 Activity Logging</h3>
                    <p>Track user activities like login/logout with IP address and user agent.</p>
                </div>
                <div class="feature-card">
                    <h3>⚠️ Input Validation</h3>
                    <p>Comprehensive input validation and sanitization to prevent security vulnerabilities.</p>
                </div>
            </div>
        </section>
    </div>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Email Authentication System. All rights reserved.</p>
    </footer>
</body>
</html>
