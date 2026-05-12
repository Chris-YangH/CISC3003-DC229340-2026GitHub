CREATE DATABASE IF NOT EXISTS cisc3003_paper02c
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cisc3003_paper02c;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 0,
    activation_hash VARCHAR(64) NULL,
    reset_hash VARCHAR(64) NULL,
    reset_expires_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, password_hash, is_active)
VALUES (
    'Demo Active User',
    'active@example.com',
    '$2y$10$j1opHiLwXPVSSFEFy7IrbOilZl6U7HQjzz3tdPlDv2YM7Srj6qEXK',
    1
);

-- Demo password for active@example.com is: Password123
