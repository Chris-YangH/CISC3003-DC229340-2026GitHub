CREATE DATABASE IF NOT EXISTS cisc3003_paper02b
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cisc3003_paper02b;

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    email VARCHAR(120) NOT NULL,
    subject VARCHAR(120) NOT NULL,
    message TEXT NOT NULL,
    mail_status ENUM('not_sent', 'sent', 'failed') NOT NULL DEFAULT 'not_sent',
    debug_info TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO contact_messages (name, email, subject, message, mail_status, debug_info)
VALUES ('Demo Sender', 'demo@example.com', 'Sample contact', 'This row demonstrates saving contact form data.', 'not_sent', 'Demo only.');

INSERT IGNORE INTO users (name, email, password_hash)
VALUES ('Yang Hao', 'paper-b@example.com', '$2y$10$j1opHiLwXPVSSFEFy7IrbOilZl6U7HQjzz3tdPlDv2YM7Srj6qEXK');
