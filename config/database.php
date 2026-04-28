<?php
// =============================================
// AcuSport - Configuração da Base de Dados
// Railway Environment Variables
// =============================================

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $url = getenv('MYSQL_URL');
            if ($url) {
                $pdo = new PDO($url, null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } else {
                $host = getenv('MYSQLHOST') ?: 'localhost';
                $port = getenv('MYSQLPORT') ?: '3306';
                $name = getenv('MYSQLDATABASE') ?: 'acusport_app';
                $user = getenv('MYSQLUSER') ?: 'root';
                $pass = getenv('MYSQLPASSWORD') ?: '';
                $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
                $pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['error' => 'Erro na conexão à base de dados: ' . $e->getMessage()]));
        }
    }
    return $pdo;
}

// URL base da app
define('BASE_URL', '');
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
