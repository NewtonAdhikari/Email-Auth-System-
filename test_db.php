<?php
require 'config/config.php';
require 'config/Database.php';
require 'includes/Email.php';

echo "=== Email Auth System - Configuration Test ===\n\n";

// Test Database
echo "1. Database Connection Test:\n";
try {
    $db = new Database();
    $conn = $db->getConnection();
    echo "   ✓ Connected to: " . DB_NAME . " on " . DB_HOST . "\n";
    echo "   ✓ Database user: " . DB_USER . "\n\n";
} catch (Exception $e) {
    echo "   ✗ Database connection failed: " . $e->getMessage() . "\n\n";
}

// Test Email Configuration
echo "2. Email Configuration Test:\n";
echo "   Host: " . SMTP_HOST . "\n";
echo "   Port: " . SMTP_PORT . "\n";
echo "   User: " . SMTP_USER . "\n";
echo "   From: " . FROM_EMAIL . "\n";
echo "   Name: " . FROM_NAME . "\n\n";

echo "3. Testing SMTP Connection:\n";
$email = new Email();
$testResult = $email->testConfiguration();

if ($testResult['success']) {
    echo "   ✓ " . $testResult['message'] . "\n";
    echo "   ✓ Email system is working correctly!\n\n";
} else {
    echo "   ✗ " . $testResult['message'] . "\n\n";
    echo "   Troubleshooting:\n";
    echo "   - Verify SMTP credentials in config.php\n";
    echo "   - Check firewall/network settings\n";
    echo "   - Verify port 587 is not blocked\n";
    echo "   - Try with TLS encryption enabled\n\n";
}

echo "=== Configuration Summary ===\n";
echo "Database: " . DB_NAME . "@" . DB_HOST . "\n";
echo "Database User: " . DB_USER . "\n";
echo "SMTP: " . SMTP_USER . "@" . SMTP_HOST . ":" . SMTP_PORT . "\n";
echo "Site URL: " . SITE_URL . "\n";
echo "Token Expiry: " . TOKEN_EXPIRY . " seconds\n";
echo "Password Reset Expiry: " . PASSWORD_RESET_EXPIRY . " seconds\n";

// Check logs directory
if (!is_dir('logs')) {
    echo "\n⚠ Creating logs directory...\n";
    mkdir('logs', 0755);
    echo "✓ Logs directory created\n";
}

echo "\n✓ Configuration test completed!\n";


