<?php
require_once __DIR__ . '/../models/BookModel.php';

class BookController {
    private $model;

    public function __construct() {
        $this->model = new BookModel();
    }

    public function index() {
        $books = $this->model->getAll();
        require_once __DIR__ . '/../views/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitizeInput($_POST);
            $errors = $this->validate($data);

            if ($this->model->isbnExists($data['isbn'])) {
                $errors['isbn'] = 'ISBN already exists.';
            }

            if (empty($errors)) {
                if ($this->model->create($data)) {
                    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Book added successfully!'];
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Failed to add book.'];
                }
                header('Location: index.php');
                exit;
            }
            // Return with errors
            require_once __DIR__ . '/../views/create.php';
        } else {
            require_once __DIR__ . '/../views/create.php';
        }
    }

    public function edit($id) {
        $book = $this->model->getById($id);
        if (!$book) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Book not found.'];
            header('Location: index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitizeInput($_POST);
            $errors = $this->validate($data);

            if ($this->model->isbnExists($data['isbn'], $id)) {
                $errors['isbn'] = 'ISBN already exists.';
            }

            if (empty($errors)) {
                if ($this->model->update($id, $data)) {
                    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Book updated successfully!'];
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Failed to update book.'];
                }
                header('Location: index.php');
                exit;
            }
            require_once __DIR__ . '/../views/edit.php';
        } else {
            require_once __DIR__ . '/../views/edit.php';
        }
    }

    public function delete($id) {
        if ($this->model->delete($id)) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Book deleted successfully!'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Failed to delete book.'];
        }
        header('Location: index.php');
        exit;
    }

    private function sanitizeInput($data) {
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

    private function validate($data) {
        $errors = [];
        if (empty($data['title']))  $errors['title']  = 'Title is required.';
        if (empty($data['author'])) $errors['author'] = 'Author is required.';
        if (empty($data['genre']))  $errors['genre']  = 'Genre is required.';
        if (empty($data['isbn']))   $errors['isbn']   = 'ISBN is required.';
        if ($data['year'] < 1000 || $data['year'] > (int)date('Y')) {
            $errors['year'] = 'Enter a valid year.';
        }
        if ($data['stock'] < 0) $errors['stock'] = 'Stock cannot be negative.';
        return $errors;
    }
}
