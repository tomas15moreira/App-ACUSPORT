<?php
require_once 'config/database.php';
$db = getDB();

try {
    // Ver o que está na tabela users
    $users = $db->query("SELECT id, nome, email, is_admin, LEFT(password,20) as pass_preview FROM users")->fetchAll();
    echo "<h3>Utilizadores na base de dados:</h3>";
    foreach ($users as $u) {
        echo "<p>ID:{$u['id']} | {$u['nome']} | {$u['email']} | admin:{$u['is_admin']} | pass:{$u['pass_preview']}</p>";
    }

    // Recriar admin com password nova
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Apagar e recriar
    $db->exec("DELETE FROM users WHERE email = 'admin@acusport.pt'");
    $stmt = $db->prepare("INSERT INTO users (nome, email, password, is_admin) VALUES ('Administrador', 'admin@acusport.pt', ?, 1)");
    $stmt->execute([$password]);
    
    echo "<h2 style='color:green'>Admin recriado!</h2>";
    echo "<p>Email: <b>admin@acusport.pt</b></p>";
    echo "<p>Password: <b>admin123</b></p>";
    echo "<p><a href='/?page=login'>Ir para o login</a></p>";

} catch (Exception $e) {
    echo "<h2 style='color:red'>Erro: " . $e->getMessage() . "</h2>";
}
