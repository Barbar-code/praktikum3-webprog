-- ============================================
-- Library Database Setup
-- Run this script in phpMyAdmin or MySQL CLI
-- ============================================

CREATE DATABASE IF NOT EXISTS library_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE library_db;

CREATE TABLE IF NOT EXISTS books (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255)    NOT NULL,
    author      VARCHAR(150)    NOT NULL,
    genre       VARCHAR(100)    NOT NULL,
    isbn        VARCHAR(20)     UNIQUE NOT NULL,
    year        YEAR            NOT NULL,
    stock       INT             NOT NULL DEFAULT 1,
    description TEXT,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sample data
INSERT INTO books (title, author, genre, isbn, year, stock, description) VALUES
('Clean Code', 'Robert C. Martin', 'Technology', '9780132350884', 2008, 5, 'A handbook of agile software craftsmanship.'),
('The Pragmatic Programmer', 'David Thomas', 'Technology', '9780135957059', 2019, 3, 'Your journey to mastery in software development.'),
('Atomic Habits', 'James Clear', 'Self-Help', '9780735211292', 2018, 7, 'Tiny changes, remarkable results.'),
('Sapiens', 'Yuval Noah Harari', 'History', '9780062316097', 2011, 4, 'A brief history of humankind.'),
('Dune', 'Frank Herbert', 'Science Fiction', '9780441172719', 1965, 2, 'The greatest science fiction novel of all time.');
