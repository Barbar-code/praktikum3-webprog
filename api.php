<?php
// API Entry Point
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/ApiController.php';

// Get resource and ID from URL
$resource = $_GET['resource'] ?? null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Initialize API controller
$api = new ApiController();

// Handle the request
if ($resource) {
    $api->handleRequest($resource, $id);
} else {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode([
        'message' => 'Library REST API',
        'version' => '1.0',
        'endpoints' => [
            'GET /api.php?resource=books' => 'Get all books',
            'GET /api.php?resource=books&id={id}' => 'Get single book',
            'POST /api.php?resource=books' => 'Create new book',
            'PUT /api.php?resource=books&id={id}' => 'Update book',
            'DELETE /api.php?resource=books&id={id}' => 'Delete book'
        ]
    ], JSON_PRETTY_PRINT);
}
