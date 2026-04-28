<?php
require_once 'config/database.php';
$db = getDB();

try {
    // Adicionar coluna is_admin se não existir
    $db->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS is_admin TINYINT(1) NOT NULL DEFAULT 0");
    echo "<p style='color:green'>Coluna is_admin verificada.</p>";

    // Criar utilizador admin
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (nome, email, password, is_admin) VALUES (?, ?, ?, 1) ON DUPLICATE KEY UPDATE is_admin = 1, password = ?");
    $stmt->execute(['Administrador', 'admin@acusport.pt', $password, $password]);
    
    echo "<h2 style='color:green'>Admin criado com sucesso!</h2>";
    echo "<p>Email: <b>admin@acusport.pt</b></p>";
    echo "<p>Password: <b>admin123</b></p>";
    echo "<p><a href='/?page=login'>Ir para o login</a></p>";

} catch (Exception $e) {
    echo "<h2 style='color:red'>Erro: " . $e->getMessage() . "</h2>";
}
