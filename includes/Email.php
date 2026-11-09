<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email {
    private $mail;
    
    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->configureSMTP();
    }
    
    /**
     * Configure SMTP settings
     */
    private function configureSMTP() {
        try {
            $this->mail->isSMTP();
            $this->mail->Host = SMTP_HOST;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = SMTP_USER;
            $this->mail->Password = SMTP_PASS;
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = SMTP_PORT;
            $this->mail->setFrom(FROM_EMAIL, FROM_NAME);
            $this->mail->isHTML(true);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Send verification email
     */
    public function sendVerificationEmail($email, $username, $verificationLink) {
        try {
            $this->mail->addAddress($email);
            $this->mail->Subject = 'Email Verification - ' . SITE_NAME;
            
            $body = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #4CAF50; color: white; padding: 20px; text-align: center; }
                    .content { padding: 20px; border: 1px solid #ddd; }
                    .button { background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; }
                    .footer { text-align: center; padding: 20px; color: #999; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Email Verification</h1>
                    </div>
                    <div class='content'>
                        <p>Hi $username,</p>
                        <p>Thank you for registering with us. Please verify your email address by clicking the link below:</p>
                        <p><a href='$verificationLink' class='button'>Verify Email</a></p>
                        <p>Or copy and paste this link in your browser:</p>
                        <p>$verificationLink</p>
                        <p>This link will expire in 1 hour.</p>
                        <p>If you did not register, please ignore this email.</p>
                    </div>
                    <div class='footer'>
                        <p>&copy; " . date('Y') . " " . SITE_NAME . ". All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>";
            
            $this->mail->Body = $body;
            return $this->mail->send();
        } catch (Exception $e) {
            error_log("Email error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send password reset email
     */
    public function sendPasswordResetEmail($email, $username, $resetLink) {
        try {
            // Reset the recipient list
            $this->mail->clearAddresses();
            
            $this->mail->addAddress($email);
            $this->mail->Subject = 'Password Reset - ' . SITE_NAME;
            
            $body = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #2196F3; color: white; padding: 20px; text-align: center; }
                    .content { padding: 20px; border: 1px solid #ddd; }
                    .button { background-color: #2196F3; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; }
                    .footer { text-align: center; padding: 20px; color: #999; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Password Reset Request</h1>
                    </div>
                    <div class='content'>
                        <p>Hi $username,</p>
                        <p>We received a request to reset your password. Click the link below to proceed:</p>
                        <p><a href='$resetLink' class='button'>Reset Password</a></p>
                        <p>Or copy and paste this link in your browser:</p>
                        <p>$resetLink</p>
                        <p>This link will expire in 1 hour.</p>
                        <p>If you did not request this, please ignore this email and your password will remain unchanged.</p>
                    </div>
                    <div class='footer'>
                        <p>&copy; " . date('Y') . " " . SITE_NAME . ". All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>";
            
            $this->mail->Body = $body;
            return $this->mail->send();
        } catch (Exception $e) {
            error_log("Email error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Test email configuration
     */
    public function testConfiguration() {
        try {
            // Create a fresh mailer instance for testing
            $testMail = new PHPMailer(true);
            $testMail->isSMTP();
            $testMail->Host = SMTP_HOST;
            $testMail->SMTPAuth = true;
            $testMail->Username = SMTP_USER;
            $testMail->Password = SMTP_PASS;
            $testMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $testMail->Port = SMTP_PORT;
            $testMail->setFrom(FROM_EMAIL, FROM_NAME);
            
            // Try to connect
            if ($testMail->smtpConnect()) {
                $testMail->smtpClose();
                return ['success' => true, 'message' => 'SMTP connection successful!'];
            } else {
                return ['success' => false, 'message' => 'SMTP connection failed'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
?>
