# Email Authentication System - API Documentation

## Overview

This document describes the classes and methods available in the Email Authentication System.

## User Class

### Methods

#### register($username, $email, $password)
Register a new user with email verification.

**Parameters:**
- `$username` (string) - Username (required)
- `$email` (string) - Email address (required)
- `$password` (string) - Password, min 6 characters (required)

**Returns:**
```php
[
    'success' => bool,
    'message' => 'Registration message'
]
```

**Example:**
```php
$user = new User();
$result = $user->register('john_doe', 'john@example.com', 'password123');
if ($result['success']) {
    echo $result['message']; // Registration successful message
}
```

---

#### login($email, $password)
Authenticate user and create session.

**Parameters:**
- `$email` (string) - User email (required)
- `$password` (string) - User password (required)

**Returns:**
```php
[
    'success' => bool,
    'message' => 'Login message'
]
```

**Example:**
```php
$user = new User();
$result = $user->login('john@example.com', 'password123');
if ($result['success']) {
    // User logged in, redirect to dashboard
}
```

---

#### verifyEmail($token)
Verify user email with verification token.

**Parameters:**
- `$token` (string) - Verification token from email (required)

**Returns:**
```php
[
    'success' => bool,
    'message' => 'Verification message'
]
```

**Example:**
```php
$user = new User();
$result = $user->verifyEmail($_GET['token']);
```

---

#### requestPasswordReset($email)
Request password reset email.

**Parameters:**
- `$email` (string) - User email (required)

**Returns:**
```php
[
    'success' => bool,
    'message' => 'Email send status message'
]
```

**Example:**
```php
$user = new User();
$result = $user->requestPasswordReset('john@example.com');
```

---

#### resetPassword($token, $newPassword)
Reset password with verification token.

**Parameters:**
- `$token` (string) - Password reset token (required)
- `$newPassword` (string) - New password (required)

**Returns:**
```php
[
    'success' => bool,
    'message' => 'Reset message'
]
```

**Example:**
```php
$user = new User();
$result = $user->resetPassword($_POST['token'], $_POST['new_password']);
```

---

#### getUserById($id)
Get user information by ID.

**Parameters:**
- `$id` (int) - User ID (required)

**Returns:**
```php
[
    'id' => int,
    'username' => string,
    'email' => string,
    'created_at' => datetime,
    'last_login' => datetime
]
// or null if user not found
```

**Example:**
```php
$user = new User();
$userInfo = $user->getUserById($_SESSION['user_id']);
echo $userInfo['username'];
```

---

#### logout()
Logout user and destroy session.

**Parameters:**
None

**Returns:**
Void

**Example:**
```php
$user = new User();
$user->logout();
header('Location: login.php');
```

---

#### logActivity($userId, $action)
Log user activity (login/logout).

**Parameters:**
- `$userId` (int) - User ID
- `$action` (string) - Action name (login/logout/etc)

**Returns:**
bool

**Example:**
```php
$user->logActivity($_SESSION['user_id'], 'login');
```

## Database Class

### Methods

#### connect()
Connect to database.

**Returns:**
MySQLi connection object

#### getConnection()
Get database connection.

**Returns:**
MySQLi connection object

**Example:**
```php
$database = new Database();
$conn = $database->getConnection();
$result = $conn->query("SELECT * FROM users");
```

## Email Class

### Methods

#### sendVerificationEmail($email, $username, $verificationLink)
Send email verification message.

**Parameters:**
- `$email` (string) - Recipient email
- `$username` (string) - User username
- `$verificationLink` (string) - Verification link URL

**Returns:**
bool - true if sent successfully

**Example:**
```php
$email = new Email();
$sent = $email->sendVerificationEmail(
    'user@example.com',
    'username',
    'http://localhost/verify?token=abc123'
);
```

---

#### sendPasswordResetEmail($email, $username, $resetLink)
Send password reset email.

**Parameters:**
- `$email` (string) - Recipient email
- `$username` (string) - User username
- `$resetLink` (string) - Reset link URL

**Returns:**
bool - true if sent successfully

**Example:**
```php
$email = new Email();
$sent = $email->sendPasswordResetEmail(
    'user@example.com',
    'username',
    'http://localhost/reset?token=xyz789'
);
```

## Sessions

Session variables available after login:

```php
$_SESSION['user_id']   // User ID
$_SESSION['username']  // Username
$_SESSION['email']     // User email
```

## Error Handling

All methods return array with 'success' and 'message' keys:

```php
// Success
['success' => true, 'message' => 'Operation successful']

// Error
['success' => false, 'message' => 'Error description']
```

## Constants

Available in config.php:

```php
DB_HOST              // Database host
DB_USER              // Database user
DB_PASS              // Database password
DB_NAME              // Database name
SMTP_HOST            // SMTP server
SMTP_PORT            // SMTP port
SMTP_USER            // SMTP username
SMTP_PASS            // SMTP password
FROM_EMAIL           // Sender email
FROM_NAME            // Sender name
SITE_URL             // Site URL
SITE_NAME            // Site name
TOKEN_EXPIRY         // Token expiry (seconds)
PASSWORD_RESET_EXPIRY // Reset token expiry (seconds)
```

## Usage Examples

### Complete Registration Flow

```php
<?php
require_once 'includes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();
    $result = $user->register(
        $_POST['username'],
        $_POST['email'],
        $_POST['password']
    );
    
    if ($result['success']) {
        echo "Registration successful. Check your email.";
    } else {
        echo "Error: " . $result['message'];
    }
}
?>
```

### Complete Login Flow

```php
<?php
require_once 'includes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();
    $result = $user->login(
        $_POST['email'],
        $_POST['password']
    );
    
    if ($result['success']) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Error: " . $result['message'];
    }
}
?>
```

### Protect Pages

```php
<?php
require_once 'config/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// User is authenticated, proceed with page
?>
```

---

For more information, see README.md
