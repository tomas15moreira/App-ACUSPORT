<?php
// =============================================
// AcuSport - Funções Auxiliares
// =============================================

require_once __DIR__ . '/../config/database.php';

// ---- PRODUTOS ----

function getProducts($limit = null, $category_id = null, $search = null, $destaque = null) {
    $db = getDB();
    $sql = "SELECT p.*, c.nome as categoria_nome, c.slug as categoria_slug 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            WHERE 1=1";
    $params = [];

    if ($category_id) {
        $sql .= " AND p.category_id = :category_id";
        $params[':category_id'] = $category_id;
    }
    if ($search) {
        $sql .= " AND (p.nome LIKE :search OR p.descricao_curta LIKE :search2)";
        $params[':search'] = "%$search%";
        $params[':search2'] = "%$search%";
    }
    if ($destaque !== null) {
        $sql .= " AND p.destaque = :destaque";
        $params[':destaque'] = $destaque;
    }

    $sql .= " ORDER BY p.destaque DESC, p.created_at DESC";

    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getProduct($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT p.*, c.nome as categoria_nome, c.slug as categoria_slug 
                          FROM products p 
                          JOIN categories c ON p.category_id = c.id 
                          WHERE p.id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

function getProductBySlug($slug) {
    $db = getDB();
    $stmt = $db->prepare("SELECT p.*, c.nome as categoria_nome, c.slug as categoria_slug 
                          FROM products p 
                          JOIN categories c ON p.category_id = c.id 
                          WHERE p.slug = :slug");
    $stmt->execute([':slug' => $slug]);
    return $stmt->fetch();
}

// ---- CATEGORIAS ----

function getCategories() {
    $db = getDB();
    $stmt = $db->query("SELECT c.*, COUNT(p.id) as total_produtos 
                        FROM categories c 
                        LEFT JOIN products p ON c.id = p.category_id 
                        GROUP BY c.id 
                        ORDER BY c.ordem ASC");
    return $stmt->fetchAll();
}

function getCategoryBySlug($slug) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM categories WHERE slug = :slug");
    $stmt->execute([':slug' => $slug]);
    return $stmt->fetch();
}

// ---- CARRINHO ----

function getCartIdentifier() {
    if (isset($_SESSION['user_id'])) {
        return ['user_id' => $_SESSION['user_id'], 'session_id' => null];
    }
    return ['user_id' => null, 'session_id' => $_SESSION['cart_session_id']];
}

function getCartItems() {
    $db = getDB();
    $cart = getCartIdentifier();
    
    if ($cart['user_id']) {
        $sql = "SELECT ci.*, p.nome, p.preco, p.imagem, p.slug 
                FROM cart_items ci 
                JOIN products p ON ci.product_id = p.id 
                WHERE ci.user_id = :user_id";
        $params = [':user_id' => $cart['user_id']];
    } else {
        $sql = "SELECT ci.*, p.nome, p.preco, p.imagem, p.slug 
                FROM cart_items ci 
                JOIN products p ON ci.product_id = p.id 
                WHERE ci.session_id = :session_id";
        $params = [':session_id' => $cart['session_id']];
    }
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getCartCount() {
    $db = getDB();
    $cart = getCartIdentifier();
    
    if ($cart['user_id']) {
        $stmt = $db->prepare("SELECT COALESCE(SUM(quantidade), 0) as total FROM cart_items WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $cart['user_id']]);
    } else {
        $stmt = $db->prepare("SELECT COALESCE(SUM(quantidade), 0) as total FROM cart_items WHERE session_id = :session_id");
        $stmt->execute([':session_id' => $cart['session_id']]);
    }
    
    return (int) $stmt->fetch()['total'];
}

function addToCart($product_id, $quantidade = 1) {
    $db = getDB();
    $cart = getCartIdentifier();
    
    // Verificar se já existe no carrinho
    if ($cart['user_id']) {
        $stmt = $db->prepare("SELECT id, quantidade FROM cart_items WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute([':user_id' => $cart['user_id'], ':product_id' => $product_id]);
    } else {
        $stmt = $db->prepare("SELECT id, quantidade FROM cart_items WHERE session_id = :session_id AND product_id = :product_id");
        $stmt->execute([':session_id' => $cart['session_id'], ':product_id' => $product_id]);
    }
    
    $existing = $stmt->fetch();
    
    if ($existing) {
        $stmt = $db->prepare("UPDATE cart_items SET quantidade = quantidade + :qty WHERE id = :id");
        $stmt->execute([':qty' => $quantidade, ':id' => $existing['id']]);
    } else {
        $stmt = $db->prepare("INSERT INTO cart_items (session_id, user_id, product_id, quantidade) VALUES (:session_id, :user_id, :product_id, :qty)");
        $stmt->execute([
            ':session_id' => $cart['session_id'],
            ':user_id' => $cart['user_id'],
            ':product_id' => $product_id,
            ':qty' => $quantidade
        ]);
    }
    
    return getCartCount();
}

function updateCartItem($item_id, $quantidade) {
    $db = getDB();
    if ($quantidade <= 0) {
        return removeCartItem($item_id);
    }
    $stmt = $db->prepare("UPDATE cart_items SET quantidade = :qty WHERE id = :id");
    $stmt->execute([':qty' => $quantidade, ':id' => $item_id]);
    return true;
}

function removeCartItem($item_id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM cart_items WHERE id = :id");
    $stmt->execute([':id' => $item_id]);
    return true;
}

function clearCart() {
    $db = getDB();
    $cart = getCartIdentifier();
    
    if ($cart['user_id']) {
        $stmt = $db->prepare("DELETE FROM cart_items WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $cart['user_id']]);
    } else {
        $stmt = $db->prepare("DELETE FROM cart_items WHERE session_id = :session_id");
        $stmt->execute([':session_id' => $cart['session_id']]);
    }
    return true;
}

function getPlanLimits() {
    return ['essencia' => 1, 'vitalidade' => 2, 'mestre' => 4];
}

function getFreeFormulasAvailable() {
    if (!isset($_SESSION['user_plan'])) return 0;
    
    $plan = strtolower($_SESSION['user_plan']);
    $limits = getPlanLimits();
    $limit = $limits[$plan] ?? 0;
    if ($limit == 0) return 0;
    
    // Gerir reset mensal
    $current_month = date('Y-m');
    if (!isset($_SESSION['plan_month']) || $_SESSION['plan_month'] !== $current_month) {
        $_SESSION['plan_month'] = $current_month;
        $_SESSION['free_formulas_used'] = 0;
    }
    
    $used = $_SESSION['free_formulas_used'] ?? 0;
    return max(0, $limit - $used);
}

function calculateCartTotals() {
    $items = getCartItems();
    $subtotal = 0;
    $free_formulas_available = getFreeFormulasAvailable();
    $free_formulas_used = 0;
    $free_discount_amount = 0;
    
    foreach ($items as $item) {
        $qty = $item['quantidade'];
        $price = $item['preco'];
        
        while ($qty > 0 && $free_formulas_used < $free_formulas_available) {
            $free_discount_amount += $price;
            $free_formulas_used++;
            $qty--;
        }
        
        $subtotal += $item['preco'] * $item['quantidade'];
    }
    
    $user_plan = isset($_SESSION['user_plan']) ? strtolower($_SESSION['user_plan']) : null;
    $plan_discount_pct = 0;
    if ($user_plan === 'vitalidade') $plan_discount_pct = 0.15;
    if ($user_plan === 'mestre') $plan_discount_pct = 0.25;
    
    $amount_subject_to_pct_discount = max(0, $subtotal - $free_discount_amount);
    $pct_discount_amount = $amount_subject_to_pct_discount * $plan_discount_pct;
    
    $total_discount = $free_discount_amount + $pct_discount_amount;
    $subtotal_after_discount = max(0, $subtotal - $total_discount);
    
    $shipping_threshold = 60;
    $shipping_cost = 4.90;
    $free_shipping = ($subtotal_after_discount >= $shipping_threshold) || ($user_plan !== null);
    
    // Evitar encomendas de 0€ quando usam as fórmulas grátis
    if ($free_formulas_used > 0 && $subtotal_after_discount == 0) {
        $free_shipping = false;
        $shipping_cost = 10.00;
    }
    
    $total = $free_shipping ? $subtotal_after_discount : $subtotal_after_discount + $shipping_cost;
    
    return [
        'subtotal' => $subtotal,
        'free_formulas_used' => $free_formulas_used,
        'free_discount_amount' => $free_discount_amount,
        'pct_discount_amount' => $pct_discount_amount,
        'total_discount' => $total_discount,
        'subtotal_after_discount' => $subtotal_after_discount,
        'free_shipping' => $free_shipping,
        'shipping_cost' => $free_shipping ? 0 : $shipping_cost,
        'total' => $total,
        'shipping_threshold' => $shipping_threshold
    ];
}

function getCartTotal() {
    $totals = calculateCartTotals();
    return $totals['total'];
}

// ---- AUTENTICAÇÃO ----

function registerUser($nome, $email, $password, $telefone = null) {
    $db = getDB();
    
    // Verificar se email já existe
    $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Este email já está registado.'];
    }
    
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $is_admin = (strtolower(trim($nome)) === 'admin') ? 1 : 0;
    $stmt = $db->prepare("INSERT INTO users (nome, email, password, telefone, is_admin) VALUES (:nome, :email, :password, :telefone, :is_admin)");
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':password' => $hash,
        ':telefone' => $telefone,
        ':is_admin' => $is_admin
    ]);
    
    $user_id = $db->lastInsertId();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_nome'] = $nome;
    $_SESSION['user_email'] = $email;
    $_SESSION['is_admin'] = (bool)$is_admin;
    
    // Migrar carrinho da sessão para o user
    migrateCart($user_id);
    
    return ['success' => true, 'user_id' => $user_id];
}

function loginUser($email, $password) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();
    
    if (!$user || !password_verify($password, $user['password'])) {
        return ['success' => false, 'message' => 'Email ou password incorretos.'];
    }
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_nome'] = $user['nome'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['is_admin'] = (bool)$user['is_admin'];
    
    if (!empty($user['plan'])) {
        $_SESSION['user_plan'] = $user['plan'];
    }
    
    migrateCart($user['id']);
    
    return ['success' => true, 'user' => $user];
}

function migrateCart($user_id) {
    $db = getDB();
    $session_id = $_SESSION['cart_session_id'] ?? null;
    if ($session_id) {
        $stmt = $db->prepare("UPDATE cart_items SET user_id = :user_id, session_id = NULL WHERE session_id = :session_id");
        $stmt->execute([':user_id' => $user_id, ':session_id' => $session_id]);
    }
}

function logoutUser() {
    unset($_SESSION['user_id'], $_SESSION['user_nome'], $_SESSION['user_email'], $_SESSION['is_admin'], $_SESSION['user_plan'], $_SESSION['plan_month'], $_SESSION['free_formulas_used']);
    return true;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

function getCurrentUser() {
    if (!isLoggedIn()) return null;
    $db = getDB();
    $stmt = $db->prepare("SELECT id, nome, email, telefone, morada, codigo_postal, cidade, is_admin FROM users WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['user_id']]);
    return $stmt->fetch();
}

// ---- ENCOMENDAS ----

function createOrder($data) {
    $db = getDB();
    $items = getCartItems();
    
    if (empty($items)) {
        return ['success' => false, 'message' => 'O carrinho está vazio.'];
    }
    
    $totals = calculateCartTotals();
    $total = $totals['total'];
    
    $stmt = $db->prepare("INSERT INTO orders (user_id, total, nome_envio, email_envio, telefone_envio, morada_envio, codigo_postal_envio, cidade_envio, metodo_pagamento, notas) 
                          VALUES (:user_id, :total, :nome, :email, :telefone, :morada, :cp, :cidade, :pagamento, :notas)");
    $stmt->execute([
        ':user_id' => $_SESSION['user_id'] ?? null,
        ':total' => $total,
        ':nome' => $data['nome'],
        ':email' => $data['email'],
        ':telefone' => $data['telefone'] ?? null,
        ':morada' => $data['morada'],
        ':cp' => $data['codigo_postal'],
        ':cidade' => $data['cidade'],
        ':pagamento' => $data['metodo_pagamento'] ?? 'cartao',
        ':notas' => $data['notas'] ?? null
    ]);
    
    $order_id = $db->lastInsertId();
    
    // Inserir items da encomenda
    $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, quantidade, preco_unitario) VALUES (:order_id, :product_id, :qty, :preco)");
    foreach ($items as $item) {
        $stmt->execute([
            ':order_id' => $order_id,
            ':product_id' => $item['product_id'],
            ':qty' => $item['quantidade'],
            ':preco' => $item['preco']
        ]);
    }
    
    clearCart();
    
    // Descontar as fórmulas grátis usadas
    if ($totals['free_formulas_used'] > 0) {
        if (!isset($_SESSION['free_formulas_used'])) $_SESSION['free_formulas_used'] = 0;
        $_SESSION['free_formulas_used'] += $totals['free_formulas_used'];
    }
    
    // Send order confirmation email
    $to = $data['email'];
    $subject = "Confirmação de Encomenda #$order_id - AcuSport";
    $body = "Olá " . $data['nome'] . ",\n\n";
    $body .= "Recebemos a sua encomenda com sucesso!\n";
    $body .= "Referência da Encomenda: #$order_id\n";
    $body .= "Total: " . formatPrice($total) . "\n\n";
    if (($data['metodo_pagamento'] ?? '') === 'multibanco') {
        $body .= "Pode consultar os dados de pagamento na sua área de cliente.\n\n";
    }
    $body .= "Avisaremos assim que a encomenda for expedida.\n\n";
    $body .= "Obrigado por escolher a AcuSport!";
    
    $headers = "From: no-reply@acusport.pt\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    @mail($to, $subject, $body, $headers);
    
    // Log for dev
    $log = "=================================================\n";
    $log .= "Date: " . date('Y-m-d H:i:s') . "\n";
    $log .= "To: $to\n";
    $log .= "Subject: $subject\n";
    $log .= "Body:\n$body\n\n";
    file_put_contents(__DIR__ . '/../emails_log.txt', $log, FILE_APPEND);
    
    return ['success' => true, 'order_id' => $order_id];
}

function getUserOrders($user_id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT o.*, 
                          (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as total_items
                          FROM orders o 
                          WHERE o.user_id = :user_id 
                          ORDER BY o.created_at DESC");
    $stmt->execute([':user_id' => $user_id]);
    return $stmt->fetchAll();
}

function getOrderDetails($order_id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT o.* FROM orders o WHERE o.id = :id");
    $stmt->execute([':id' => $order_id]);
    $order = $stmt->fetch();
    
    if ($order) {
        $stmt = $db->prepare("SELECT oi.*, p.nome, p.imagem, p.slug 
                              FROM order_items oi 
                              JOIN products p ON oi.product_id = p.id 
                              WHERE oi.order_id = :order_id");
        $stmt->execute([':order_id' => $order_id]);
        $order['items'] = $stmt->fetchAll();
    }
    
    return $order;
}

// ---- UTILIDADES ----

function formatPrice($price) {
    return number_format($price, 2, ',', '.') . ' €';
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function jsonResponse($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function getProductImageUrl($imagem) {
    if (!$imagem) return ASSETS_URL . '/images/product-placeholder.svg';
    if (file_exists(__DIR__ . '/../assets/images/products/' . $imagem)) {
        return ASSETS_URL . '/images/products/' . $imagem;
    }
    return ASSETS_URL . '/images/product-placeholder.svg';
}

// ---- ADMIN ----

function getAllOrders() {
    $db = getDB();
    $stmt = $db->query("SELECT o.*, u.nome as user_nome, u.email as user_email,
                        (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as total_items
                        FROM orders o 
                        LEFT JOIN users u ON o.user_id = u.id 
                        ORDER BY o.created_at DESC");
    return $stmt->fetchAll();
}

function updateOrderStatus($order_id, $status) {
    $db = getDB();
    $allowed = ['pendente', 'processando', 'enviado', 'entregue', 'cancelado'];
    if (!in_array($status, $allowed)) return false;
    
    // Verificar estado atual para evitar dupla dedução de stock
    $stmt = $db->prepare("SELECT estado FROM orders WHERE id = :id");
    $stmt->execute([':id' => $order_id]);
    $current = $stmt->fetchColumn();
    
    // Atualizar estado
    $stmt = $db->prepare("UPDATE orders SET estado = :estado WHERE id = :id");
    $stmt->execute([':estado' => $status, ':id' => $order_id]);
    
    // Deduzir stock quando marcado como entregue (e não era já entregue)
    if ($status === 'entregue' && $current !== 'entregue') {
        $items = $db->prepare("SELECT product_id, quantidade FROM order_items WHERE order_id = :oid");
        $items->execute([':oid' => $order_id]);
        $update = $db->prepare("UPDATE products SET stock = GREATEST(stock - :qty, 0) WHERE id = :pid");
        foreach ($items->fetchAll() as $item) {
            $update->execute([':qty' => $item['quantidade'], ':pid' => $item['product_id']]);
        }
    }
    
    // Enviar email se foi marcado como enviado e não estava
    if ($status === 'enviado' && $current !== 'enviado') {
        $stmtEmail = $db->prepare("SELECT nome_envio, email_envio FROM orders WHERE id = :id");
        $stmtEmail->execute([':id' => $order_id]);
        $orderInfo = $stmtEmail->fetch();
        if ($orderInfo && !empty($orderInfo['email_envio'])) {
            $to = $orderInfo['email_envio'];
            $subject = "A sua encomenda #$order_id foi expedida - AcuSport";
            $body = "Olá " . $orderInfo['nome_envio'] . ",\n\n";
            $body .= "Boas notícias! A sua encomenda #$order_id acabou de ser expedida.\n";
            $body .= "Deverá recebê-la nas próximas 24h a 48h úteis.\n\n";
            $body .= "Obrigado pela sua confiança!\n";
            
            $headers = "From: no-reply@acusport.pt\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            
            @mail($to, $subject, $body, $headers);
            
            // Log for dev
            $log = "=================================================\n";
            $log .= "Date: " . date('Y-m-d H:i:s') . "\n";
            $log .= "To: $to\n";
            $log .= "Subject: $subject\n";
            $log .= "Body:\n$body\n\n";
            file_put_contents(__DIR__ . '/../emails_log.txt', $log, FILE_APPEND);
        }
    }
    
    return true;
}

function getAllUsers() {
    $db = getDB();
    $stmt = $db->query("SELECT u.id, u.nome, u.email, u.telefone, u.cidade, u.is_admin, u.created_at, u.plan,
                        (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as total_orders,
                        (SELECT COALESCE(SUM(total), 0) FROM orders WHERE user_id = u.id) as total_spent
                        FROM users u ORDER BY u.created_at DESC");
    return $stmt->fetchAll();
}

function adminUpdateProduct($id, $data) {
    $db = getDB();
    $stmt = $db->prepare("UPDATE products SET nome = :nome, preco = :preco, descricao_curta = :desc_curta, 
                          descricao_mtc = :desc_mtc, modo_utilizacao = :modo, restricoes = :restricoes, 
                          category_id = :cat_id, destaque = :destaque, stock = :stock 
                          WHERE id = :id");
    return $stmt->execute([
        ':nome' => $data['nome'], ':preco' => $data['preco'],
        ':desc_curta' => $data['descricao_curta'] ?? null, ':desc_mtc' => $data['descricao_mtc'] ?? null,
        ':modo' => $data['modo_utilizacao'] ?? null, ':restricoes' => $data['restricoes'] ?? null,
        ':cat_id' => $data['category_id'], ':destaque' => $data['destaque'] ?? 0,
        ':stock' => $data['stock'] ?? 100, ':id' => $id
    ]);
}

function adminCreateProduct($data) {
    $db = getDB();
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['nome'])));
    $stmt = $db->prepare("INSERT INTO products (nome, slug, preco, descricao_curta, descricao_mtc, modo_utilizacao, restricoes, imagem, category_id, destaque, stock) 
                          VALUES (:nome, :slug, :preco, :desc_curta, :desc_mtc, :modo, :restricoes, :imagem, :cat_id, :destaque, :stock)");
    $stmt->execute([
        ':nome' => $data['nome'], ':slug' => $slug, ':preco' => $data['preco'],
        ':desc_curta' => $data['descricao_curta'] ?? null, ':desc_mtc' => $data['descricao_mtc'] ?? null,
        ':modo' => $data['modo_utilizacao'] ?? null, ':restricoes' => $data['restricoes'] ?? null,
        ':imagem' => $data['imagem'] ?? null, ':cat_id' => $data['category_id'],
        ':destaque' => $data['destaque'] ?? 0, ':stock' => $data['stock'] ?? 100
    ]);
    return $db->lastInsertId();
}

function adminDeleteProduct($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM products WHERE id = :id");
    return $stmt->execute([':id' => $id]);
}

function adminDeleteUser($id) {
    $db = getDB();
    // Não permitir apagar admins
    $stmt = $db->prepare("SELECT is_admin FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);
    if ($stmt->fetchColumn()) return false;
    $stmt = $db->prepare("DELETE FROM users WHERE id = :id AND is_admin = 0");
    return $stmt->execute([':id' => $id]);
}

function getOrderItemsAdmin($order_id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT oi.*, p.nome, p.imagem, p.slug 
                          FROM order_items oi 
                          JOIN products p ON oi.product_id = p.id 
                          WHERE oi.order_id = :order_id");
    $stmt->execute([':order_id' => $order_id]);
    return $stmt->fetchAll();
}

function getAdminStats() {
    $db = getDB();
    $stats = [];
    $stats['total_orders'] = (int) $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $stats['total_revenue'] = (float) $db->query("SELECT COALESCE(SUM(total), 0) FROM orders")->fetchColumn();
    $stats['total_users'] = (int) $db->query("SELECT COUNT(*) FROM users WHERE is_admin = 0")->fetchColumn();
    $stats['vip_users'] = (int) $db->query("SELECT COUNT(*) FROM users WHERE plan IS NOT NULL AND plan != ''")->fetchColumn();
    $stats['total_products'] = (int) $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $stats['pending_orders'] = (int) $db->query("SELECT COUNT(*) FROM orders WHERE estado = 'pendente'")->fetchColumn();
    $stats['recent_orders'] = $db->query("SELECT o.*, u.nome as user_nome FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5")->fetchAll();
    return $stats;
}
