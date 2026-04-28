<?php
echo "MYSQLHOST: " . getenv('MYSQLHOST') . "<br>";
echo "MYSQLPORT: " . getenv('MYSQLPORT') . "<br>";
echo "MYSQLDATABASE: " . getenv('MYSQLDATABASE') . "<br>";
echo "MYSQLUSER: " . getenv('MYSQLUSER') . "<br>";
echo "MYSQL_URL: " . (getenv('MYSQL_URL') ? 'exists' : 'missing') . "<br>";

$host = getenv('MYSQLHOST');
$port = getenv('MYSQLPORT') ?: '3306';
$name = getenv('MYSQLDATABASE');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');

echo "<br>A tentar ligar...<br>";
try {
    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
    echo "DSN: " . $dsn . "<br>";
    $pdo = new PDO($dsn, $user, $pass);
    echo "LIGAÇÃO BEM SUCEDIDA!";
} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage();
}
