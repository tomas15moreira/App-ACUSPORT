<?php
require_once 'config/database.php';

$db = getDB();

$sql = file_get_contents('database/schema.sql');

// Executar cada statement
$statements = array_filter(array_map('trim', explode(';', $sql)));

$errors = [];
$success = 0;

foreach ($statements as $statement) {
    if (empty($statement)) continue;
    try {
        $db->exec($statement);
        $success++;
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}

echo "<h2>Resultado:</h2>";
echo "<p>Statements executados: $success</p>";
if ($errors) {
    echo "<h3>Erros:</h3><ul>";
    foreach ($errors as $e) {
        echo "<li>$e</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color:green'>Base de dados criada com sucesso!</p>";
}
