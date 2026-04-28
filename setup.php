<?php
require_once 'config/database.php';
$db = getDB();

try {
    $db->exec("UPDATE categories SET nome = 'Articulações' WHERE id = 3");
    echo "<h2 style='color:green'>Corrigido!</h2>";
    echo "<p><a href='/'>Ir para a app</a></p>";
} catch (Exception $e) {
    echo "<h2 style='color:red'>Erro: " . $e->getMessage() . "</h2>";
}
