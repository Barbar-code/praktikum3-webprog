<?php
// Application Configuration
define('APP_NAME', 'LibraryX');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost/praktikum/');
define('BASE_PATH', dirname(__DIR__) . '/');
define('UPLOAD_PATH', BASE_PATH . 'public/uploads/posters/');
define('UPLOAD_URL', BASE_URL . 'public/uploads/posters/');

// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'library_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Session
define('SESSION_LIFETIME', 3600 * 24); // 24 hours

// Pagination
define('ITEMS_PER_PAGE', 10);

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
