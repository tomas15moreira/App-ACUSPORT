<?php
// API: Cart Operations
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

switch ($action) {
    case 'add':
        $product_id = (int)($input['product_id'] ?? 0);
        $qty = (int)($input['quantidade'] ?? 1);
        if (!$product_id) jsonResponse(['success' => false, 'message' => 'Produto inválido'], 400);
        $count = addToCart($product_id, $qty);
        jsonResponse(['success' => true, 'cart_count' => $count]);
        break;

    case 'update':
        $item_id = (int)($input['item_id'] ?? 0);
        $qty = (int)($input['quantidade'] ?? 1);
        updateCartItem($item_id, $qty);
        jsonResponse(['success' => true, 'cart_count' => getCartCount()]);
        break;

    case 'remove':
        $item_id = (int)($input['item_id'] ?? 0);
        removeCartItem($item_id);
        jsonResponse(['success' => true, 'cart_count' => getCartCount()]);
        break;

    case 'clear':
        clearCart();
        jsonResponse(['success' => true, 'cart_count' => 0]);
        break;

    case 'count':
        jsonResponse(['success' => true, 'cart_count' => getCartCount()]);
        break;

    default:
        jsonResponse(['success' => false, 'message' => 'Ação inválida'], 400);
}
