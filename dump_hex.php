<?php
require_once "config/database.php";
$pdo = getDB();
$cat = $pdo->query("SELECT nome FROM categories WHERE id=3")->fetchColumn();
echo "Hex: " . bin2hex($cat) . "\n";
echo "String: " . $cat;
