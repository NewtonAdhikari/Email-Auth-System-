# Email Authentication System

**A secure and comprehensive email-based authentication system built with PHP and MySQL.**

[![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/Database-MySQL-blue?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Composer](https://img.shields.io/badge/Dependencies-Composer-885630?logo=composer&logoColor=white)](https://getcomposer.org/)
[![Authentication](https://img.shields.io/badge/Authentication-Email%20Based-success)](https://github.com/)

---

## Overview

The Email Authentication System is a PHP-based web application designed to provide secure user authentication and account management through email verification.

The system provides a complete authentication workflow, including user registration, email verification, secure login, password recovery, and personalized user dashboards.

Built using PHP and MySQL, the application implements password hashing, token-based authentication, session management, and activity logging to support secure user account operations.

The modular architecture separates database connectivity, user management, email delivery, and application configuration, making the system easier to maintain and extend.

## Features

The system provides the following authentication and account management capabilities:

- **User Registration** – Create new user accounts with username, email, and password validation.
- **Email Verification** – Verify registered email addresses using secure, time-limited verification tokens.
- **Secure Login** – Authenticate users using email and password credentials.
- **Password Recovery** – Request password reset links through registered email addresses.
- **Password Reset** – Securely update account passwords using time-limited reset tokens.
- **User Dashboard** – Display personal account information and authentication details.
- **Session Management** – Maintain authenticated user sessions.
- **Activity Logging** – Record login and logout activities for account monitoring.
- **Password Hashing** – Protect stored passwords using the bcrypt hashing algorithm.
- **Token-Based Security** – Generate verification and password reset tokens with expiration.
- **Input Validation** – Validate user-submitted information before processing.
- **SQL Injection Prevention** – Use prepared statements and parameter binding.
- **XSS Protection** – Encode output to reduce cross-site scripting risks.

## Project Structure

The project follows a modular directory structure to separate application configuration, database operations, business logic, and frontend resources.

```text
Email_auth/
│
├── config/
│   ├── config.php
│   └── Database.php
│
├── includes/
│   ├── User.php
│   └── Email.php
│
├── database/
│   └── schema.sql
│
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│
├── register.php
├── verify-email.php
├── login.php
├── forgot-password.php
├── reset-password.php
├── dashboard.php
├── index.php
│
├── composer.json
└── README.md
```

## Installation

### Prerequisites

Ensure that the following software is installed before setting up the application.

| Component | Requirement |
|---|---|
| PHP | 7.4 or higher |
| Database | MySQL 5.7 or higher |
| Dependency Manager | Composer |
| Web Server | Apache or Nginx |
| PHP Extension | MySQLi |
| Email Service | SMTP-compatible email provider |

### Installation Steps

**1. Clone or Download the Repository**

Clone the repository and navigate to the project directory.

```bash
git clone <repository-url>
cd Email_auth
```

**2. Install PHP Dependencies**

Install the required Composer packages.

```bash
composer install
```

**3. Create the Database**

Import the provided SQL schema to create the database and required tables.

Using the MySQL command line:

```bash
mysql -u root -p < database/schema.sql
```

Alternatively, import `database/schema.sql` through phpMyAdmin.

**4. Configure Database Credentials**

Open `config/config.php` and update the database configuration.

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('DB_NAME', 'email_auth_db');
```

Replace the example credentials with your actual database configuration.

**5. Configure SMTP Email Settings**

Update the email configuration in `config/config.php`.

```php
define('SMTP_USER', 'your_email@gmail.com');
define('SMTP_PASS', 'your_app_password');
define('FROM_EMAIL', 'your_email@gmail.com');
define('FROM_NAME', 'Email Auth System');
```

For Gmail SMTP configuration:

1. Enable two-factor authentication on your Google account.
2. Generate an application password.
3. Configure the generated password as the SMTP password.
4. Verify the SMTP host, port, and encryption settings in your email configuration.

Generate a Gmail application password:

https://myaccount.google.com/apppasswords

**Important:** Never use your regular Gmail account password as an SMTP application password.

**6. Configure File Permissions**

For Linux-based environments, configure appropriate permissions:

```bash
chmod 755 -R .
chmod 644 config/config.php
```

For production environments, restrict access to configuration files and sensitive credentials. Avoid granting unnecessary write permissions.

**7. Start the Application**

Using Apache, place the project inside the web server document root.

Alternatively, use PHP's built-in development server:

```bash
php -S localhost:8000
```

Open the application in your browser:

```text
http://localhost:8000
```

For an Apache installation using a project folder named `Email_auth`, the application can also be accessed through:

```text
http://localhost/Email_auth/
```

---

## Usage

### 1. User Registration

The registration process allows new users to create an account.

1. Open the registration page.
2. Enter a username, email address, and password.
3. Submit the registration form.
4. Check the registered email inbox.
5. Open the verification link.
6. Complete email verification to activate the account.

### 2. User Login

Registered and verified users can access the application through the login interface.

1. Navigate to the login page.
2. Enter the registered email address.
3. Enter the account password.
4. Submit the login form.
5. Access the user dashboard after successful authentication.

### 3. Password Recovery

The password recovery functionality allows users to regain account access.

1. Navigate to the login page.
2. Select the Forgot Password option.
3. Enter the registered email address.
4. Check the email inbox for the password reset link.
5. Open the reset link.
6. Enter and confirm a new password.

### 4. User Dashboard

The dashboard provides access to personal account information and account-related activities.

Available information includes:

- User account details.
- Membership or registration date.
- Last login timestamp.
- Account-related actions.

---

## Database Schema

The application uses MySQL to store user account information and authentication activity records.

### 1. Users Table

The users table stores account information and authentication-related data.

| Column | Description |
|---|---|
| `id` | Unique user identifier (Primary Key). |
| `username` | Registered username (Unique). |
| `email` | Registered email address (Unique). |
| `password` | Securely hashed password. |
| `email_verified` | Email verification status. |
| `verification_token` | Email verification token. |
| `password_reset_token` | Password recovery token. |
| `is_active` | Account activation status. |
| `created_at` | Account registration timestamp. |
| `last_login` | Most recent login timestamp. |

### 2. Activity Logs Table

The activity logs table records user authentication activities.

| Column | Description |
|---|---|
| `id` | Unique activity log identifier. |
| `user_id` | Associated user identifier (Foreign Key). |
| `action` | Activity type, such as login or logout. |
| `ip_address` | IP address associated with the activity. |
| `user_agent` | Browser and client information. |
| `created_at` | Activity timestamp. |

---

## Application Components

### 1. Configuration Module

**File:** `config/config.php`

Contains the application's global configuration settings, including:

- Database connection credentials.
- SMTP email configuration.
- Website URL and application name.
- Authentication token expiration settings.
- Timezone configuration.

### 2. Database Module

**File:** `config/Database.php`

Provides database connectivity using MySQLi.

Responsible for establishing and managing database connections used throughout the application.

### 3. User Management Module

**File:** `includes/User.php`

Contains the primary user authentication and account management operations.

| Method | Description |
|---|---|
| `register()` | Creates a new user account. |
| `login()` | Authenticates a registered user. |
| `verifyEmail()` | Verifies a user's email address. |
| `requestPasswordReset()` | Processes password reset requests. |
| `resetPassword()` | Updates a password using a valid reset token. |
| `getUserById()` | Retrieves user information using an ID. |
| `logActivity()` | Records user authentication activities. |

### 4. Email Module

**File:** `includes/Email.php`

Handles email delivery using PHPMailer and SMTP configuration.

| Method | Description |
|---|---|
| `sendVerificationEmail()` | Sends account verification emails. |
| `sendPasswordResetEmail()` | Sends password recovery emails. |

---

## Security Implementation

Security is an important component of the authentication workflow.

The application incorporates the following security mechanisms:

| Security Feature | Implementation |
|---|---|
| Password Protection | Bcrypt password hashing. |
| Email Verification | Token-based email verification. |
| Password Recovery | Time-limited password reset tokens. |
| SQL Injection Prevention | Prepared statements and parameter binding. |
| XSS Protection | HTML output encoding. |
| Session Management | PHP session-based authentication. |
| Input Validation | User input validation. |
| CSRF Protection | Token-based protection is identified as a security enhancement. |
| Activity Monitoring | Login and logout activity records. |

**Security Recommendation:** For production deployment, enable CSRF validation on state-changing forms, regenerate session IDs after authentication, use HTTPS, securely store SMTP credentials, and store only hashed verification and reset tokens where supported by the implementation.

---

## Dependencies

The application uses the following libraries and technologies:

- **PHPMailer** – Email delivery through SMTP.
- **MySQLi** – MySQL database connectivity.
- **Composer** – PHP dependency management.
- **PHP** – Server-side application development.

Install dependencies using:

```bash
composer install
```

---

## Configuration Options

Application settings can be customized through `config/config.php`.

### Token Expiration

The following configuration defines token expiration periods in seconds.

```php
define('TOKEN_EXPIRY', 3600);
define('PASSWORD_RESET_EXPIRY', 3600);
```

Both values represent a one-hour expiration period.

### Email Configuration

```php
define('FROM_NAME', 'Email Auth System');
```

### Application Configuration

```php
define('SITE_URL', 'http://localhost/Email_auth/');
define('SITE_NAME', 'Email Authentication System');
```

Update the application URL according to your local or production environment.

---

## Troubleshooting

### Email Delivery Issues

If verification or password reset emails are not received:

- Verify SMTP credentials.
- Check the configured SMTP host and port.
- Confirm the SMTP encryption settings.
- Verify that the email provider permits SMTP access.
- Review PHP and server error logs.
- Check spam and junk folders.

### Database Connection Errors

If the application cannot connect to MySQL:

- Confirm that the MySQL service is running.
- Verify the database hostname.
- Check database username and password.
- Ensure that the database exists.
- Confirm that the database user has the required privileges.

### Email Verification Issues

If an email verification link is expired or invalid:

- Check the configured token expiration period.
- Confirm that the token matches the stored verification record.
- Generate a new verification link through an appropriate application workflow.

### Password Reset Issues

If password recovery does not work:

- Verify SMTP email delivery.
- Check the reset token and its expiration.
- Confirm the database schema.
- Ensure that the password update operation completes successfully.

---

## Future Enhancements

Potential improvements for future versions include:

- Two-factor authentication (2FA).
- Social authentication through Google, Facebook, and GitHub.
- Remember Me functionality.
- User profile picture uploads.
- Email address change verification.
- Role-based access control.
- Account deletion and recovery.
- User data export.
- Enhanced security monitoring.

---

## License

This project is available for educational and commercial use under the license selected for the repository.

Refer to the `LICENSE` file for the applicable licensing terms.

## Support

For technical issues or configuration questions:

1. Review the installation instructions.
2. Check the troubleshooting section.
3. Verify database and SMTP configuration.
4. Review application and server error logs.
5. Check the relevant PHP and MySQL documentation.

---

**Email Authentication System — Secure Account Access Through Email Verification.**

*Always protect database credentials, SMTP passwords, secret keys, and other sensitive configuration data. Never commit real credentials or production secrets to version control.*