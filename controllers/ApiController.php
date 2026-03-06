<?php
require_once __DIR__ . '/../models/BookModel.php';

class ApiController {
    private $model;
    private $requestMethod;
    private $requestData;

    public function __construct() {
        $this->model = new BookModel();
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        
        // Set JSON response header
        header('Content-Type: application/json');
        
        // Enable CORS for testing
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        
        // Handle preflight OPTIONS request
        if ($this->requestMethod === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
        
        // Get request data
        $this->requestData = $this->getRequestData();
    }

    private function getRequestData() {
        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);
        
        // Merge with $_POST for form-data
        if ($this->requestMethod === 'POST' && !empty($_POST)) {
            $data = array_merge($data ?? [], $_POST);
        }
        
        return $data;
    }

    public function handleRequest($resource, $id = null) {
        switch ($resource) {
            case 'books':
                $this->handleBooks($id);
                break;
            default:
                $this->sendResponse(404, ['error' => 'Resource not found']);
                break;
        }
    }

    private function handleBooks($id) {
        switch ($this->requestMethod) {
            case 'GET':
                if ($id) {
                    $this->getBook($id);
                } else {
                    $this->getAllBooks();
                }
                break;
            case 'POST':
                $this->createBook();
                break;
            case 'PUT':
                if ($id) {
                    $this->updateBook($id);
                } else {
                    $this->sendResponse(400, ['error' => 'ID is required for update']);
                }
                break;
            case 'DELETE':
                if ($id) {
                    $this->deleteBook($id);
                } else {
                    $this->sendResponse(400, ['error' => 'ID is required for deletion']);
                }
                break;
            default:
                $this->sendResponse(405, ['error' => 'Method not allowed']);
                break;
        }
    }

    // GET all books
    private function getAllBooks() {
        try {
            $books = $this->model->getAll();
            $this->sendResponse(200, [
                'success' => true,
                'data' => $books,
                'count' => count($books)
            ]);
        } catch (Exception $e) {
            $this->sendResponse(500, ['error' => $e->getMessage()]);
        }
    }

    // GET single book
    private function getBook($id) {
        try {
            $book = $this->model->getById($id);
            if ($book) {
                $this->sendResponse(200, [
                    'success' => true,
                    'data' => $book
                ]);
            } else {
                $this->sendResponse(404, [
                    'success' => false,
                    'error' => 'Book not found'
                ]);
            }
        } catch (Exception $e) {
            $this->sendResponse(500, ['error' => $e->getMessage()]);
        }
    }

    // POST create book
    private function createBook() {
        try {
            $errors = $this->validateBookData($this->requestData);
            
            if (!empty($errors)) {
                $this->sendResponse(400, [
                    'success' => false,
                    'errors' => $errors
                ]);
                return;
            }

            // Check ISBN exists
            if ($this->model->isbnExists($this->requestData['isbn'])) {
                $this->sendResponse(409, [
                    'success' => false,
                    'error' => 'ISBN already exists'
                ]);
                return;
            }

            // Sanitize and create
            $data = $this->sanitizeBookData($this->requestData);
            if ($this->model->create($data)) {
                $this->sendResponse(201, [
                    'success' => true,
                    'message' => 'Book created successfully',
                    'data' => $data
                ]);
            } else {
                $this->sendResponse(500, [
                    'success' => false,
                    'error' => 'Failed to create book'
                ]);
            }
        } catch (Exception $e) {
            $this->sendResponse(500, ['error' => $e->getMessage()]);
        }
    }

    // PUT update book
    private function updateBook($id) {
        try {
            // Check if book exists
            $existingBook = $this->model->getById($id);
            if (!$existingBook) {
                $this->sendResponse(404, [
                    'success' => false,
                    'error' => 'Book not found'
                ]);
                return;
            }

            $errors = $this->validateBookData($this->requestData);
            
            if (!empty($errors)) {
                $this->sendResponse(400, [
                    'success' => false,
                    'errors' => $errors
                ]);
                return;
            }

            // Check ISBN exists (exclude current book)
            if ($this->model->isbnExists($this->requestData['isbn'], $id)) {
                $this->sendResponse(409, [
                    'success' => false,
                    'error' => 'ISBN already exists'
                ]);
                return;
            }

            // Sanitize and update
            $data = $this->sanitizeBookData($this->requestData);
            if ($this->model->update($id, $data)) {
                $this->sendResponse(200, [
                    'success' => true,
                    'message' => 'Book updated successfully',
                    'data' => array_merge(['id' => $id], $data)
                ]);
            } else {
                $this->sendResponse(500, [
                    'success' => false,
                    'error' => 'Failed to update book'
                ]);
            }
        } catch (Exception $e) {
            $this->sendResponse(500, ['error' => $e->getMessage()]);
        }
    }

    // DELETE book
    private function deleteBook($id) {
        try {
            // Check if book exists
            $existingBook = $this->model->getById($id);
            if (!$existingBook) {
                $this->sendResponse(404, [
                    'success' => false,
                    'error' => 'Book not found'
                ]);
                return;
            }

            if ($this->model->delete($id)) {
                $this->sendResponse(200, [
                    'success' => true,
                    'message' => 'Book deleted successfully'
                ]);
            } else {
                $this->sendResponse(500, [
                    'success' => false,
                    'error' => 'Failed to delete book'
                ]);
            }
        } catch (Exception $e) {
            $this->sendResponse(500, ['error' => $e->getMessage()]);
        }
    }

    private function validateBookData($data) {
        $errors = [];
        
        if (empty($data['title'])) {
            $errors['title'] = 'Title is required';
        }
        
        if (empty($data['author'])) {
            $errors['author'] = 'Author is required';
        }
        
        if (empty($data['genre'])) {
            $errors['genre'] = 'Genre is required';
        }
        
        if (empty($data['isbn'])) {
            $errors['isbn'] = 'ISBN is required';
        }
        
        $year = $data['year'] ?? 0;
        if ($year < 1000 || $year > (int)date('Y')) {
            $errors['year'] = 'Invalid year';
        }
        
        if (!isset($data['stock']) || $data['stock'] < 0) {
            $errors['stock'] = 'Stock must be a non-negative number';
        }
        
        return $errors;
    }

    private function sanitizeBookData($data) {
        return [
            'title'       => trim(htmlspecialchars($data['title'] ?? '')),
            'author'      => trim(htmlspecialchars($data['author'] ?? '')),
            'genre'       => trim(htmlspecialchars($data['genre'] ?? '')),
            'isbn'        => trim(htmlspecialchars($data['isbn'] ?? '')),
            'year'        => (int)($data['year'] ?? date('Y')),
            'stock'       => (int)($data['stock'] ?? 0),
            'description' => trim(htmlspecialchars($data['description'] ?? '')),
        ];
    }

    private function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit();
    }
}
