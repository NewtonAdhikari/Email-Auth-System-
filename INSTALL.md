# Installation Guide

## Quick Start

### 1. Database Setup

```bash
mysql -u root -p < database/schema.sql
```

Or manually:
1. Open phpMyAdmin
2. Create new database: `email_auth_db`
3. Import `database/schema.sql`

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Settings

Edit `config/config.php`:

**Database:**
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('DB_NAME', 'email_auth_db');
```

**Email (Gmail Example):**
```php
define('SMTP_USER', 'your_email@gmail.com');
define('SMTP_PASS', 'your_app_password');  // 16-char from Google
define('FROM_EMAIL', 'your_email@gmail.com');
```

### 4. Start Server

```bash
php -S localhost:8000
```

Visit: `http://localhost:8000`

## Gmail App Password Setup

1. Go to: https://myaccount.google.com/apppasswords
2. Select: Mail & Windows PC (or Mail & Linux)
3. Generate 16-character password
4. Copy to `SMTP_PASS` in config.php

## File Permissions

```bash
chmod 755 -R .
chmod 644 config/config.php
chmod 644 assets/css/style.css
```

## Verify Installation

- [ ] Database created and tables exist
- [ ] Dependencies installed (composer.json)
- [ ] config.php configured with credentials
- [ ] Email settings verified
- [ ] Server is running
- [ ] Can access http://localhost:8000

## Common Issues

**"Connection failed" error:**
- Check MySQL is running
- Verify database credentials
- Ensure database exists

**"Class not found" error:**
- Run `composer install`
- Or download phpmailer manually

**Email not sending:**
- Check SMTP credentials
- Verify port 587 is open
- Enable "Less secure app" in Gmail
- Check error logs

## Next Steps

1. Create test account
2. Verify email works
3. Test login/logout
4. Test password reset
5. Explore dashboard
