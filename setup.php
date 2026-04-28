<?php
require_once 'config/database.php';
$db = getDB();

try {
    $db->exec("ALTER TABLE users ADD COLUMN plan VARCHAR(50) DEFAULT NULL");
    echo "<h2 style='color:green'>Coluna plan adicionada!</h2>";
    echo "<p><a href='/?page=admin'>Ir para o admin</a></p>";
} catch (Exception $e) {
    echo "<h2 style='color:red'>Erro: " . $e->getMessage() . "</h2>";
}
