<?php
session_start();
require_once __DIR__ . '/controllers/BookController.php';

$controller = new BookController();
$action = $_GET['action'] ?? 'index';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'edit':
        if ($id) $controller->edit($id);
        else { header('Location: index.php'); exit; }
        break;
    case 'delete':
        if ($id) $controller->delete($id);
        else { header('Location: index.php'); exit; }
        break;
    default:
        $controller->index();
        break;
}
