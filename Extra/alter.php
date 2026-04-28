<?php
require 'config/database.php';
$db = getDB();
try {
    $db->exec('ALTER TABLE users ADD COLUMN plan VARCHAR(50) DEFAULT NULL;');
    echo "Column added.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
