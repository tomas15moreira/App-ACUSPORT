<?php
require_once 'config/database.php';
$db = getDB();

try {
    $nome = "Articula\u{00E7}\u{00F5}es";
    $stmt = $db->prepare("UPDATE categories SET nome = ? WHERE id = 3");
    $stmt->execute([$nome]);
    
    // Verificar o que ficou guardado
    $check = $db->query("SELECT nome FROM categories WHERE id = 3")->fetch();
    echo "<h2>Resultado: " . htmlspecialchars($check['nome']) . "</h2>";
} catch (Exception $e) {
    echo "<h2 style='color:red'>Erro: " . $e->getMessage() . "</h2>";
}
