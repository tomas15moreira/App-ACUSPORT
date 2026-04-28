<?php
require_once 'config/database.php';
$pdo = getDB();
$cats = $pdo->query("SELECT id, nome FROM categories")->fetchAll();
$prod = $pdo->query("SELECT id, nome, descricao_curta FROM products WHERE id=4")->fetch();
header('Content-Type: text/html; charset=utf-8');
echo '<meta charset="utf-8">';
echo '<h2>Categorias:</h2>';
foreach($cats as $c) echo $c['id'] . ' - ' . htmlspecialchars($c['nome']) . '<br>';
echo '<h2>Produto 4 (Flexicalcium):</h2>';
echo htmlspecialchars($prod['nome']) . '<br>';
echo htmlspecialchars($prod['descricao_curta']);
