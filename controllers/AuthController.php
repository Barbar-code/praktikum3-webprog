<?php
require_once 'vendor/autoload.php';
use \Firebase\JWT\JWT;

class AuthController {
    private $conn;
    private $secret_key = "KIBAR_UTS_PABP_RAHASIA_SUPER_AMAN_2026_12345";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($data) {
        if(empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Data tidak lengkap"]);
            return;
        }

        $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->conn->prepare($query);
        $password_hash = password_hash($data['password'], PASSWORD_BCRYPT); // Best practice hashing

        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $password_hash);

        if($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["success" => true, "message" => "User berhasil didaftarkan"]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Gagal mendaftar"]);
        }
    }

    public function login($data) {
        $query = "SELECT id, name, password FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $data['email']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($data['password'], $user['password'])) {
            $issued_at = time();
            $expiration_time = $issued_at + (60 * 60); // Token valid 1 jam
            $payload = [
                "iat" => $issued_at,
                "exp" => $expiration_time,
                "data" => ["id" => $user['id'], "name" => $user['name']]
            ];

            $jwt = JWT::encode($payload, $this->secret_key, 'HS256');
            http_response_code(200);
            echo json_encode(["success" => true, "message" => "Login sukses", "token" => $jwt]);
        } else {
            http_response_code(401);
            echo json_encode(["success" => false, "message" => "Email atau password salah"]);
        }
    }
}
?>