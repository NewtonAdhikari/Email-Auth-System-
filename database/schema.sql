-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email_verified INT DEFAULT 0,
    email_verified_at TIMESTAMP NULL,
    verification_token VARCHAR(255) UNIQUE,
    verification_token_expiry TIMESTAMP NULL,
    password_reset_token VARCHAR(255) UNIQUE,
    password_reset_token_expiry TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active INT DEFAULT 1
);

-- Activity Log Table
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create indexes for better performance
CREATE INDEX idx_email ON users(email);
CREATE INDEX idx_username ON users(username);
CREATE INDEX idx_verification_token ON users(verification_token);
CREATE INDEX idx_password_reset_token ON users(password_reset_token);
CREATE INDEX idx_user_activity ON activity_logs(user_id);
