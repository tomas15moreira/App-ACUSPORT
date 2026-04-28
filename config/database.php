<?php
// =============================================
// AcuSport - Configuração da Base de Dados
// Conexão MySQL via Laragon
// =============================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'acusport_app');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Conexão PDO
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['error' => 'Erro na conexão à base de dados: ' . $e->getMessage()]));
        }
    }
    return $pdo;
}

// URL base da app
define('BASE_URL', '/App-Web');
define('ASSETS_URL', BASE_URL . '/assets');
define('UPLOADS_URL', BASE_URL . '/uploads');

// Sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Gerar session_id para carrinho de visitantes
if (!isset($_SESSION['cart_session_id'])) {
    $_SESSION['cart_session_id'] = bin2hex(random_bytes(32));
}
