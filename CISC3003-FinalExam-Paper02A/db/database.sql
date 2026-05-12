CREATE DATABASE IF NOT EXISTS cisc3003_paper02a
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cisc3003_paper02a;

CREATE TABLE IF NOT EXISTS enquiries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    email VARCHAR(120) NOT NULL,
    phone VARCHAR(20) NULL,
    topic ENUM('php', 'mysql', 'security', 'frontend') NOT NULL,
    message TEXT NOT NULL,
    study_mode ENUM('online', 'onsite', 'hybrid') NOT NULL,
    skills VARCHAR(120) NULL,
    subscribe TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO enquiries (name, email, phone, topic, message, study_mode, skills, subscribe)
VALUES ('Demo Student', 'demo@example.com', '+853 6000 0000', 'php', 'This sample row demonstrates SQL INSERT INTO.', 'hybrid', 'html,css,php', 1);

INSERT IGNORE INTO users (name, email, password_hash)
VALUES ('Yang Hao', 'paper-a@example.com', '$2y$10$j1opHiLwXPVSSFEFy7IrbOilZl6U7HQjzz3tdPlDv2YM7Srj6qEXK');
