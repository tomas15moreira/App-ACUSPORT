<?php
require_once 'config/database.php';
$db = getDB();

try {
    // Criar tabelas
    $db->exec("CREATE TABLE IF NOT EXISTS `categories` (`id` int NOT NULL AUTO_INCREMENT, `nome` varchar(100) NOT NULL, `slug` varchar(100) NOT NULL, `icone` varchar(10) DEFAULT NULL, `descricao` text, `ordem` int DEFAULT 0, PRIMARY KEY (`id`), UNIQUE KEY `slug` (`slug`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $db->exec("CREATE TABLE IF NOT EXISTS `users` (`id` int NOT NULL AUTO_INCREMENT, `nome` varchar(100) NOT NULL, `email` varchar(150) NOT NULL, `password` varchar(255) NOT NULL, `telefone` varchar(20) DEFAULT NULL, `morada` text, `codigo_postal` varchar(10) DEFAULT NULL, `cidade` varchar(100) DEFAULT NULL, `is_admin` tinyint(1) NOT NULL DEFAULT 0, `created_at` timestamp DEFAULT CURRENT_TIMESTAMP, `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`), UNIQUE KEY `email` (`email`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $db->exec("CREATE TABLE IF NOT EXISTS `products` (`id` int NOT NULL AUTO_INCREMENT, `nome` varchar(150) NOT NULL, `slug` varchar(150) NOT NULL, `preco` decimal(10,2) NOT NULL, `descricao_curta` varchar(255) DEFAULT NULL, `descricao_mtc` text, `modo_utilizacao` text, `restricoes` text, `imagem` varchar(255) DEFAULT NULL, `category_id` int NOT NULL, `destaque` tinyint(1) DEFAULT 0, `stock` int DEFAULT 100, `created_at` timestamp DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`), UNIQUE KEY `slug` (`slug`), KEY `category_id` (`category_id`), CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $db->exec("CREATE TABLE IF NOT EXISTS `orders` (`id` int NOT NULL AUTO_INCREMENT, `user_id` int DEFAULT NULL, `total` decimal(10,2) NOT NULL, `estado` enum('pendente','processando','enviado','entregue','cancelado') DEFAULT 'pendente', `nome_envio` varchar(150) NOT NULL, `email_envio` varchar(150) NOT NULL, `telefone_envio` varchar(20) DEFAULT NULL, `morada_envio` text NOT NULL, `codigo_postal_envio` varchar(10) NOT NULL, `cidade_envio` varchar(100) NOT NULL, `metodo_pagamento` varchar(50) DEFAULT 'cartao', `notas` text, `created_at` timestamp DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`), KEY `user_id` (`user_id`), CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $db->exec("CREATE TABLE IF NOT EXISTS `order_items` (`id` int NOT NULL AUTO_INCREMENT, `order_id` int NOT NULL, `product_id` int NOT NULL, `quantidade` int NOT NULL, `preco_unitario` decimal(10,2) NOT NULL, PRIMARY KEY (`id`), KEY `order_id` (`order_id`), KEY `product_id` (`product_id`), CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE, CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $db->exec("CREATE TABLE IF NOT EXISTS `cart_items` (`id` int NOT NULL AUTO_INCREMENT, `session_id` varchar(128) DEFAULT NULL, `user_id` int DEFAULT NULL, `product_id` int NOT NULL, `quantidade` int NOT NULL DEFAULT 1, `created_at` timestamp DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`), KEY `user_id` (`user_id`), KEY `product_id` (`product_id`), CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE, CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Categorias
    $db->exec("INSERT IGNORE INTO `categories` VALUES (1,'Emagrecimento','emagrecimento',NULL,NULL,1),(2,'Energia','sistema-nervoso-cerebro-energia',NULL,NULL,2),(3,'Articulações','ossos-articulacoes-musculos-e-tendoes',NULL,NULL,3),(4,'Fitoterapia','fitoterapia-suplementacao-alimentar',NULL,NULL,4)");

    // Produtos
    $stmt = $db->prepare("INSERT IGNORE INTO `products` (id, nome, slug, preco, descricao_curta, imagem, category_id, destaque, stock) VALUES (?,?,?,?,?,?,?,?,?)");
    
    $produtos = [
        [1,'Ponderal Fit 1','ponderal-fit-1',24.60,'Suplemento para apoiar processos de emagrecimento.','ponderal-fit-1.jpg',1,1,100],
        [2,'Ponderal Fit 2','ponderal-fit-2',22.50,'Suplemento para auxiliar na perda de gordura.','ponderal-fit-2.jpg',1,1,100],
        [3,'Neuro Mais','neuro-mais',29.66,'Suplemento para combater fadiga física e mental.','neuro-mais.jpg',2,1,100],
        [4,'Flexicalcium','flexicalcium',29.66,'Suplemento para apoio ósseo, muscular e cartilagens.','flexicalcium.jpg',3,1,100],
        [5,'F-44','f-44',28.79,'Suplemento fitoterápico de Medicina Tradicional Chinesa.','f-44.jpg',4,0,100],
        [6,'F-47','f-47',28.79,'Suplemento fitoterápico de Medicina Tradicional Chinesa.','f-47.jpg',4,0,100],
        [7,'F-1','f-1',25.50,'Suplemento fitoterápico 100% natural.','f-1.jpg',4,0,100],
        [8,'F-34 A','f-34-a',25.50,'Suplemento fitoterápico 100% natural.','f-34-a.jpg',4,0,100],
        [9,'F-25 B','f-25-b',25.50,'Suplemento fitoterápico 100% natural.','f-25-b.jpg',4,0,100],
        [10,'F-54','f-54',25.50,'Suplemento fitoterápico 100% natural.','f-54.jpg',4,0,100],
        [11,'F-41 B','f-41-b',25.50,'Suplemento fitoterápico 100% natural.','f-41-b.jpg',4,0,100],
        [12,'F-34 B','f-34-b',25.50,'Suplemento fitoterápico 100% natural.','f-34-b.jpg',4,0,100],
        [13,'F-134 A','f-134-a',25.50,'Suplemento fitoterápico 100% natural.','f-134-a.jpg',4,0,100],
        [14,'F-39 B','f-39-b',25.50,'Suplemento fitoterápico 100% natural.','f-39-b.jpg',4,0,100],
        [15,'F-33 B','f-33-b',25.50,'Suplemento fitoterápico 100% natural.','f-33-b.jpg',4,0,100],
        [16,'F-31 B','f-31-b',25.50,'Suplemento fitoterápico 100% natural.','f-31-b.jpg',4,0,100],
    ];

    foreach ($produtos as $p) {
        $stmt->execute($p);
    }

    echo "<h2 style='color:green'>Base de dados criada com sucesso!</h2>";
    echo "<p>Tabelas criadas e " . count($produtos) . " produtos inseridos.</p>";
    echo "<p><a href='/'>Ir para a app</a></p>";

} catch (Exception $e) {
    echo "<h2 style='color:red'>Erro: " . $e->getMessage() . "</h2>";
}
