<?php
require_once dirname(__DIR__) . '/config/Database.php';
require_once dirname(__DIR__) . '/includes/Email.php';

class User {
    private $db;
    private $email;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->email = new Email();
    }
    
    /**
     * Register a new user
     */
    public function register($username, $email, $password) {
        // Validate inputs
        if (empty($username) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'All fields are required'];
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }
        
        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters'];
        }
        
        // Check if username already exists (email can be reused)
        $checkQuery = "SELECT id FROM users WHERE username = ?";
        $stmt = $this->db->prepare($checkQuery);
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'Username already exists'];
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Generate verification token
        $verificationToken = bin2hex(random_bytes(32));
        $tokenExpiry = date('Y-m-d H:i:s', strtotime('+' . TOKEN_EXPIRY . ' seconds'));
        
        // Insert user
        $insertQuery = "INSERT INTO users (username, email, password, verification_token, verification_token_expiry) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($insertQuery);
        
        if (!$stmt->execute([$username, $email, $hashedPassword, $verificationToken, $tokenExpiry])) {
            return ['success' => false, 'message' => 'Registration failed. Please try again.'];
        }
        
        // Send verification email
        $verificationLink = SITE_URL . 'verify-email.php?token=' . $verificationToken;
        $emailResult = $this->email->sendVerificationEmail($email, $username, $verificationLink);
        
        $message = "✓ Registration successful for username: <strong>$username</strong><br>";
        $message .= "📧 Verification email sent to: <strong>$email</strong><br>";
        $message .= "Next steps: Check your email and click the verification link to activate your account.<br>";
        $message .= "💡 If you don't receive the email within 5 minutes, check your spam folder or <a href='" . SITE_URL . "resend-verification.php'>resend verification link here</a>.";
        
        return ['success' => true, 'message' => $message, 'username' => $username, 'email' => $email];
    }
    
    /**
     * Verify email address
     */
    public function verifyEmail($token) {
        if (empty($token)) {
            return ['success' => false, 'message' => 'Invalid verification token'];
        }
        
        $query = "SELECT id, email, verification_token_expiry FROM users WHERE verification_token = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Invalid or expired verification token'];
        }
        
        // Check if token is expired
        if (strtotime($user['verification_token_expiry']) < time()) {
            return ['success' => false, 'message' => 'Verification token has expired'];
        }
        
        // Update user
        $updateQuery = "UPDATE users SET email_verified = 1, email_verified_at = NOW(), verification_token = NULL, verification_token_expiry = NULL WHERE id = ?";
        $stmt = $this->db->prepare($updateQuery);
        
        if ($stmt->execute([$user['id']])) {
            return ['success' => true, 'message' => 'Email verified successfully. You can now login.'];
        } else {
            return ['success' => false, 'message' => 'Verification failed. Please try again.'];
        }
    }
    
    /**
     * Login user
     */
    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email and password are required'];
        }
        
        $query = "SELECT id, username, email, password, email_verified FROM users WHERE email = ? AND is_active = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }
        
        // Check if email is verified
        if (!$user['email_verified']) {
            return ['success' => false, 'message' => 'Please verify your email first'];
        }
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }
        
        // Update last login
        $updateQuery = "UPDATE users SET last_login = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($updateQuery);
        $stmt->execute([$user['id']]);
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        
        // Log activity
        $this->logActivity($user['id'], 'login');
        
        return ['success' => true, 'message' => 'Login successful'];
    }
    
    /**
     * Request password reset
     */
    public function requestPasswordReset($email) {
        if (empty($email)) {
            return ['success' => false, 'message' => 'Email is required'];
        }
        
        $query = "SELECT id, username, email FROM users WHERE email = ? AND is_active = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Email not found'];
        }
        
        // Generate reset token
        $resetToken = bin2hex(random_bytes(32));
        $tokenExpiry = date('Y-m-d H:i:s', strtotime('+' . PASSWORD_RESET_EXPIRY . ' seconds'));
        
        // Update user with reset token
        $updateQuery = "UPDATE users SET password_reset_token = ?, password_reset_token_expiry = ? WHERE id = ?";
        $stmt = $this->db->prepare($updateQuery);
        
        if (!$stmt->execute([$resetToken, $tokenExpiry, $user['id']])) {
            return ['success' => false, 'message' => 'Failed to process request'];
        }
        
        // Send reset email
        $resetLink = SITE_URL . 'reset-password.php?token=' . $resetToken;
        $emailResult = $this->email->sendPasswordResetEmail($email, $user['username'], $resetLink);
        
        return ['success' => $emailResult, 'message' => $emailResult ? 'Password reset link sent to your email' : 'Failed to send email'];
    }
    
    /**
     * Reset password with token
     */
    public function resetPassword($token, $newPassword) {
        if (empty($token) || empty($newPassword)) {
            return ['success' => false, 'message' => 'All fields are required'];
        }
        
        if (strlen($newPassword) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters'];
        }
        
        $query = "SELECT id, password_reset_token_expiry FROM users WHERE password_reset_token = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Invalid reset token'];
        }
        
        // Check if token is expired
        if (strtotime($user['password_reset_token_expiry']) < time()) {
            return ['success' => false, 'message' => 'Reset token has expired'];
        }
        
        // Hash new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        // Update password
        $updateQuery = "UPDATE users SET password = ?, password_reset_token = NULL, password_reset_token_expiry = NULL WHERE id = ?";
        $stmt = $this->db->prepare($updateQuery);
        $stmt->execute([$hashedPassword, $user['id']]);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Password reset successful. You can now login with your new password.'];
        } else {
            return ['success' => false, 'message' => 'Failed to reset password'];
        }
    }
    
    /**
     * Get user by ID
     */
    public function getUserById($id) {
        $query = "SELECT id, username, email, created_at, last_login FROM users WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Log user activity
     */
    public function logActivity($userId, $action) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        
        $query = "INSERT INTO activity_logs (user_id, action, ip_address, user_agent) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$userId, $action, $ipAddress, $userAgent]);
    }
    
    /**
     * Logout user
     */
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $this->logActivity($_SESSION['user_id'], 'logout');
        }
        session_destroy();
    }
}
?>
