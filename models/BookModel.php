<?php
require_once __DIR__ . '/../config/database.php';

class BookModel {
    private $conn;
    private $table = 'books';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (title, author, genre, isbn, year, stock, description)
                VALUES (:title, :author, :genre, :isbn, :year, :stock, :description)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':title'       => $data['title'],
            ':author'      => $data['author'],
            ':genre'       => $data['genre'],
            ':isbn'        => $data['isbn'],
            ':year'        => $data['year'],
            ':stock'       => $data['stock'],
            ':description' => $data['description'],
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table}
                SET title = :title, author = :author, genre = :genre,
                    isbn = :isbn, year = :year, stock = :stock, description = :description
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':title'       => $data['title'],
            ':author'      => $data['author'],
            ':genre'       => $data['genre'],
            ':isbn'        => $data['isbn'],
            ':year'        => $data['year'],
            ':stock'       => $data['stock'],
            ':description' => $data['description'],
            ':id'          => $id,
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function isbnExists($isbn, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE isbn = ?";
        $params = [$isbn];
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
}
