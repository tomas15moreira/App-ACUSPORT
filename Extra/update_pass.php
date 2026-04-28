<?php
require_once "config/database.php";
$pdo = getDB();
$password = "1234567";
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = 'admin123@gmail.com'");
$stmt->execute([$hash]);
echo "Password updated to: " . $hash . "\n";
