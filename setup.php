<?php
require_once 'config/database.php';
$db = getDB();

try {
    // Adicionar coluna is_admin
    $db->exec("ALTER TABLE users ADD COLUMN is_admin TINYINT(1) NOT NULL DEFAULT 0");
    echo "<p style='color:green'>Coluna is_admin adicionada.</p>";
} catch (Exception $e) {
    echo "<p style='color:orange'>is_admin: " . $e->getMessage() . "</p>";
}

try {
    // Adicionar coluna plan se não existir
    $db->exec("ALTER TABLE users ADD COLUMN plan VARCHAR(50) DEFAULT NULL");
    echo "<p style='color:green'>Coluna plan adicionada.</p>";
} catch (Exception $e) {
    echo "<p style='color:orange'>plan: " . $e->getMessage() . "</p>";
}

try {
    // Recriar admin
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $db = getDB();
    $db->exec("DELETE FROM users WHERE email = 'admin@acusport.pt'");
    $stmt = $db->prepare("INSERT INTO users (nome, email, password, is_admin) VALUES ('Administrador', 'admin@acusport.pt', ?, 1)");
    $stmt->execute([$password]);
    echo "<h2 style='color:green'>Admin criado!</h2>";
    echo "<p>Email: <b>admin@acusport.pt</b></p>";
    echo "<p>Password: <b>admin123</b></p>";
    echo "<p><a href='/?page=login'>Ir para o login</a></p>";
} catch (Exception $e) {
    echo "<h2 style='color:red'>Erro admin: " . $e->getMessage() . "</h2>";
}
