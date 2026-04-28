<?php
// API: Products
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        $category = $_GET['category'] ?? null;
        $search = $_GET['search'] ?? null;
        $limit = $_GET['limit'] ?? null;
        $category_id = null;
        if ($category) {
            $cat = getCategoryBySlug($category);
            if ($cat) $category_id = $cat['id'];
        }
        $products = getProducts($limit, $category_id, $search);
        jsonResponse(['success' => true, 'products' => $products]);
        break;

    case 'detail':
        $id = $_GET['id'] ?? null;
        if (!$id) jsonResponse(['success' => false, 'message' => 'ID inválido'], 400);
        $product = getProduct($id);
        if (!$product) jsonResponse(['success' => false, 'message' => 'Produto não encontrado'], 404);
        jsonResponse(['success' => true, 'product' => $product]);
        break;

    case 'categories':
        $categories = getCategories();
        jsonResponse(['success' => true, 'categories' => $categories]);
        break;

    default:
        jsonResponse(['success' => false, 'message' => 'Ação inválida'], 400);
}
