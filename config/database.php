<?php
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $url = getenv('MYSQL_URL');
            if ($url) {
                $parts = parse_url($url);
                $host = $parts['host'];
                $port = $parts['port'] ?? 3306;
                $name = ltrim($parts['path'], '/');
                $user = $parts['user'];
                $pass = $parts['pass'];
            } else {
                $host = getenv('MYSQLHOST') ?: 'localhost';
                $port = getenv('MYSQLPORT') ?: '3306';
                $name = getenv('MYSQLDATABASE') ?: 'acusport_app';
                $user = getenv('MYSQLUSER') ?: 'root';
                $pass = getenv('MYSQLPASSWORD') ?: '';
            }

            $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            $pdo->exec("SET NAMES utf8mb4");
            $pdo->exec("SET CHARACTER SET utf8mb4");
            $pdo->exec("SET character_set_connection=utf8mb4");
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['error' => 'Erro na conexão à base de dados: ' . $e->getMessage()]));
        }
    }
    return $pdo;
}

define('BASE_URL', '');
define('ASSETS_URL', BASE_URL . '/assets');
define('UPLOADS_URL', BASE_URL . '/uploads');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart_session_id'])) {
    $_SESSION['cart_session_id'] = bin2hex(random_bytes(32));
}
