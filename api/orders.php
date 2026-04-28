<?php
// API: Orders
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $required = ['nome', 'email', 'morada', 'codigo_postal', 'cidade'];
    foreach ($required as $field) {
        if (empty(trim($input[$field] ?? ''))) {
            jsonResponse(['success' => false, 'message' => 'Preencha todos os campos obrigatórios.'], 400);
        }
    }
    $result = createOrder($input);
    jsonResponse($result);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isLoggedIn()) jsonResponse(['success' => false, 'message' => 'Não autenticado'], 401);
    $orders = getUserOrders($_SESSION['user_id']);
    jsonResponse(['success' => true, 'orders' => $orders]);
} else {
    jsonResponse(['success' => false, 'message' => 'Método não permitido'], 405);
}
