<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'config/database.php';
require_once 'controllers/ApiController.php';
require_once 'controllers/AuthController.php';
require_once 'vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$database = new Database();
$db = $database->connect();

$resource = $_GET['resource'] ?? '';
$action = $_GET['action'] ?? '';
$data = json_decode(file_get_contents("php://input"), true);

// 1. Endpoint Autentikasi (Tidak perlu token)
if ($resource == 'auth') {
    $auth = new AuthController($db);
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action == 'register') {
        $auth->register($data);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action == 'login') {
        $auth->login($data);
    }
    exit();
}

// 2. MIDDLEWARE JWT UNTUK RESOURCE BUKU
$headers = apache_request_headers();
$token = '';
if (isset($headers['Authorization'])) {
    $matches = array();
    preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches);
    if(isset($matches[1])) {
        $token = $matches[1];
    }
}

if (!$token) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Akses ditolak. Token tidak ditemukan."]);
    exit();
}

try {
    $secret_key = "KIBAR_UTS_PABP_RAHASIA_SUPER_AMAN_2026_12345";
    $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
    
    // Jika token valid, lanjutkan ke CRUD Buku
    if ($resource == 'books') {
        $id = $_GET['id'] ?? null;
        $controller = new ApiController($db); // Sesuaikan dengan controller lama kamu
        $controller->handleRequest($resource, $id); 
    }
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Akses ditolak. Token tidak valid atau kadaluarsa."]);
    exit();
}
?>