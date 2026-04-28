-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: acusport_app
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `session_id` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `product_id` int NOT NULL,
  `quantidade` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icone` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `ordem` int DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Emagrecimento','emagrecimento',NULL,NULL,1),(2,'Energia','sistema-nervoso-cerebro-energia',NULL,NULL,2),(3,'Articulações','ossos-articulacoes-musculos-e-tendoes',NULL,NULL,3),(4,'Fitoterapia','fitoterapia-suplementacao-alimentar',NULL,NULL,4);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantidade` int NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendente','processando','enviado','entregue','cancelado') COLLATE utf8mb4_unicode_ci DEFAULT 'pendente',
  `nome_envio` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_envio` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone_envio` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `morada_envio` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_postal_envio` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cidade_envio` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `metodo_pagamento` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'cartao',
  `notas` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `descricao_curta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descricao_mtc` text COLLATE utf8mb4_unicode_ci,
  `modo_utilizacao` text COLLATE utf8mb4_unicode_ci,
  `restricoes` text COLLATE utf8mb4_unicode_ci,
  `imagem` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int NOT NULL,
  `destaque` tinyint(1) DEFAULT '0',
  `stock` int DEFAULT '100',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Ponderal Fit 1','ponderal-fit-1',24.60,'Este suplemento, disponível em embalagem de 60 cápsulas, foi desenvolvido para apoiar processos de e...','Este suplemento, disponível em embalagem de 60 cápsulas, foi desenvolvido para apoiar processos de emagrecimento, ajudando na redução da gordura corporal e localizada, no combate à celulite e no controlo eficaz do apetite. A sua fórmula completa promove uma sensação de saciedade imediata e estimula o metabolismo através de um efeito termogénico que evita a absorção de gorduras pelo organismo. Para além de contribuir para a diminuição da retenção de líquidos e facilitar o trânsito intestinal, atua também no equilíbrio metabólico, ajudando a controlar os níveis de glicémia e de colesterol. Graças à presença de vitaminas e minerais na sua composição, auxilia ainda na redução do cansaço e da fadiga, proporcionando a energia necessária para o dia a dia. A toma geral recomendada é de duas cápsulas antes das duas refeições principais, acompanhadas por um a dois copos de água. Relativamente a precauções, o seu uso é contraindicado em caso de alergias, dificuldade de deglutição, e durante a gravidez ou amamentação, exceto sob indicação médica. Por fim, doentes diabéticos devem efetuar um controlo regular devido à ação hipoglicemiante natural do produto.',NULL,NULL,'ponderal-fit-1.jpg',1,1,100,'2026-04-21 13:54:12'),(2,'Ponderal Fit 2','ponderal-fit-2',22.50,'Este suplemento, disponível em embalagem de 60 cápsulas, foi desenvolvido para auxiliar na perda de ...','Este suplemento, disponível em embalagem de 60 cápsulas, foi desenvolvido para auxiliar na perda de gordura, sendo especialmente indicado para processos de emagrecimento associados a níveis elevados de colesterol ou glicose no sangue. A sua fórmula combina o mineral Crómio com extratos botânicos (como Garcinia Cambogia, Gengibre, Aloé Vera e Cascara Sagrada) para atuar de forma dupla no metabolismo e no sistema digestivo. Para além de moderar o apetite e possuir uma ação termogénica que ajuda a reduzir a absorção de gorduras e hidratos de carbono, destaca-se por ser altamente eficaz no combate à obstipação, estimulando a mobilidade intestinal, facilitando a evacuação e diminuindo a retenção de líquidos. A toma recomendada é de um comprimido duas vezes ao dia, preferencialmente após as duas refeições principais, sendo fundamental associar um consumo reforçado de água (cerca de 2 litros por dia). Embora seja seguro para atletas de alto rendimento e pacientes com patologias crónicas ou diabetes, a sua utilização é estritamente contraindicada para menores de 12 anos, grávidas, mulheres a amamentar ou pessoas com alergia aos seus constituintes. Em casos de obstipação crónica, o tratamento pode estender-se por um período de 3 a 6 meses.',NULL,NULL,'ponderal-fit-2.jpg',1,1,100,'2026-04-21 13:54:13'),(3,'Neuro Mais','neuro-mais',29.66,'Este suplemento, disponível numa embalagem de 20 ampolas, foi desenvolvido para combater ativamente ...','Este suplemento, disponível numa embalagem de 20 ampolas, foi desenvolvido para combater ativamente a fadiga física, mental e emocional, otimizando o rendimento cerebral, a memória e a capacidade de concentração. A sua fórmula revitalizante atua no sistema nervoso e no metabolismo produtor de energia, combinando Vitamina C e vitaminas do complexo B (como B6, Niacina e Riboflavina) com extratos naturais estimulantes, onde se destacam o Guaraná, Ginseng Coreano, Rhodiola Rosea e Gengibre. Em conjunto, estes ingredientes ajudam a combater o esgotamento psicológico e a astenia, melhoram a recuperação muscular e a performance desportiva, e auxiliam no equilíbrio das digestões e no controlo do colesterol e da glicémia. A toma diária recomendada é de uma ampola diluída em 200 ml de água, preferencialmente de manhã. A duração do tratamento varia consoante a necessidade: em casos agudos ou de sobrecarga intelectual, pode ser tomado até os sintomas desaparecerem; já em situações crónicas, recomendam-se ciclos contínuos de 3 a 6 meses. O suplemento é seguro para atletas de alto rendimento e pessoas com patologias crónicas. Contudo, devido à presença de cafeína, não deve ser consumido antes de dormir para evitar insónias. Doentes diabéticos devem fazer um controlo regular da glicémia, e o uso é estritamente contraindicado para menores de 12 anos, grávidas e mulheres a amamentar.',NULL,NULL,'neuro-mais.jpg',2,1,100,'2026-04-21 13:54:14'),(4,'Flexicalcium','flexicalcium',29.66,'Este suplemento, disponível numa embalagem de 60 cápsulas, foi desenvolvido para apoiar o normal fun...','Este suplemento, disponível numa embalagem de 60 cápsulas, foi desenvolvido para apoiar o normal funcionamento da estrutura óssea, muscular e das cartilagens, destacando-se pela sua forte ação analgésica e anti-inflamatória. A sua fórmula rigorosa combina vitaminas (C e D), minerais (como o Manganês) e componentes estruturais como a Glucosamina, Condroitina e MSM, potenciados por extratos naturais de Curcuma e Boswellia. Em conjunto, estes ingredientes ajudam a reduzir o inchaço articular e a rigidez, promovem a formação de colagénio, atenuam a fadiga muscular e protegem as células contra o stress oxidativo. É especialmente indicado para casos agudos ou crónicos de reumatismo, artroses, artrite, dores ciáticas, fibromialgia, recuperação de fraturas ou lesões, e até para a melhoria da performance desportiva. A toma diária geral é de três cápsulas, distribuídas pelas principais refeições, podendo ser reduzida para duas cápsulas numa fase de manutenção. A duração do tratamento pode variar desde a toma até ao desaparecimento de sintomas (casos agudos) até períodos de 3 meses a 1 ano (casos crónicos). Embora seja seguro para pessoas com patologias crónicas, diabetes, hipertensão e desportistas, o produto é contraindicado para menores de 18 anos, grávidas e mulheres a amamentar. Utilizadores de anticoagulantes ou com obstrução hepato-biliar devem consultar um médico antes de iniciar a toma.',NULL,NULL,'flexicalcium.jpg',3,1,100,'2026-04-21 13:54:15'),(5,'F-44','f-44',28.79,'Ingredientes Extrato concentrado fitoativo: ├ügua, extrato líquido de Ramo Cinnamomi (Cinnamomum cass...','Ingredientes Extrato concentrado fitoativo: ├ügua, extrato líquido de Ramo Cinnamomi (Cinnamomum cassia), extrato líquido de Raiz Paeoniae albae (Paeonia lactiflora), extrato líquido de Rizoma Zingiberis exsiccatum (Zingiber officinale), extrato líquido de Raiz Glycyrrhizae praeparata (Glycyrrhiza uralensis), extrato líquido de Fruto Zizyphi jujubae (Ziziphus jujuba). Humidificante: glicerina vegetal. Conservante: sorbato de potássio, benzoato de sódio, ascorbato de sódio. Modo De Utilização Em geral 50 gotas diluídas em água 2 vezes ao dia. Avisos Os suplementos alimentares não devem ser utilizados em substituição de um regime alimentar variado. Não exceder as doses recomendadas.',NULL,NULL,'f-44.jpg',4,0,100,'2026-04-21 13:54:15'),(6,'F-47','f-47',28.79,'Acção (Medicina Tradicional Chinesa): Retira calor no Qi. Purifica calor perverso (Pulmão/Estômago)....','Acção (Medicina Tradicional Chinesa): Retira calor no Qi. Purifica calor perverso (Pulmão/Estômago). Regenera produção líquidos orgânicos, acalma a sede. Indicações / Sintomas: Doenças infecciosas com sintomas de Forte Calor nas camadas superficiais. Sintomatologia 4 Grandes (sede, febre, transpiração e pulso) Sintomas Gerais: Irritabilidade, agitação, cefaleias, cansaço, urinas escuras, rosto vermelho, tosse com expectoração de mucos viscoso e amarelo, hemoptise, episáxis, asma (Calor P); dor epigastro, apetite p/ alimentos frios, gengivas dolorosas, halitose (Calor E); abdomén inchado duro e doloroso, fezes duras revestidas de liquidos (Calor Intestinos). Ingredientes Extrato concentrado fitoativo: ├ügua, extrato líquido de Fruto Mori (Morus alba), extrato líquido de Erva Houttuyniae (Houttuyniae cordata), extrato líquido de Rizoma Anemarrhenae (Anemarrhena asphodeloides), extrato líquido de Raiz Glycyrrhizae praeparata (Glycyrrhiza uralensis). Humidificante: glicerina vegetal. Conservante: sorbato de potássio, benzoato de sódio, ascorbato de sódio. Modo De Utilização Em geral 50 gotas diluídas em água 2 vezes ao dia. Avisos Os suplementos alimentares não devem ser utilizados em substituição de um regime alimentar variado. Não exceder as doses recomendadas.',NULL,NULL,'f-47.jpg',4,0,100,'2026-04-21 13:54:15'),(7,'F-1','f-1',25.50,'Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natural. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato líquido de Raiz Codonopsis (Codonopsis pilosula), extrato líquido de Rizoma Atractylodis macrocephalae (Atractylodes macrocephala), extrato líquido de Cogumelo Poriae albae (Poria cocus), extrato líquido de Raiz Glycyrrhizae praeparata (Glycyrrhiza uralensis). Humidificante: glicerina vegetal. Conservante: sorbato de potássio, benzoato de sódio, ascorbato de sódio. Modo De Utilização Tomar 50 gotas diluídas em água 2 vezes ao dia, ou de acordo com a recomendação de um terapeuta. Avisos Produto sujeito a depósito. Agitar antes de tomar. Após abertura consumir no prazo de 3 meses. Devido à presença de extrato natural de plantas, o aspeto do produto poderá variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma reação, parar a utilização. Manter fora do alcance das crianças. Se estiver grávida, a amamentar, a tomar alguma especialidade farmacêutica ou se sofre de alguma doença, consulte o seu médico ou técnico de saúde antes de tomar este suplemento. Não exceder a dose diária recomendada. Conservar em local seco e fresco, sem exposição direta à luz. Os suplementos alimentares não devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saudável. Este produto não pretende diagnosticar, tratar, curar ou prevenir nenhuma doença.',NULL,NULL,'f-1.jpg',4,0,100,'2026-04-21 13:54:16'),(8,'F-34 A','f-34-a',25.50,'Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natural. As suas acções tradicionais são indicadas: - Astenia geral, profunda e crónica, fadiga ao mínimo esforço; - Debilidade seguida de uma doença crónica; - Impotência; - Dispneia de esforço; - Enurese noturna; - Fraqueza na região lombar e nos membros inferiores (joelhos); - Cura de rejuvenescimento (cabelos grisalhos, perda de cabelo, pele flácida e seca, falta de energia). As suas acções energéticas são indicadas: - Grande tonificação do Yin e do Yang, do Sangue e da Energia; - Fortalece todas as funções energéticas do corpo, Yin e Yang, Xue e Qi, funções físicas e mentais; - Reduz as insuficiências no nível de todos os órgãos e vísceras, especialmente as do rim Yang e, secundariamente, as do coração, baço e pulmão. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato líquido de Raiz Paeoniae rubrae (Paeonia lactiflora), extrato líquido de Semente Zizyphi spinosae (Ziziphus jujuba), extrato líquido de Semente Dolichoris album (Dolichos lablab), extrato líquido de Fruto Arillus longan (Dimocarpus longan), extrato líquido de Raiz Salviae miltiorrhizae (Salvia miltiorrhiza), extrato líquido de Cogumelo Poriae albae (Poria cocus), Fruto Corni (Cornus officinalis), extrato líquido de Raiz Codonopsis (Codonopsis pilosula), extrato líquido de Raiz Paeoniae albae (Paeonia lactiflora), extrato líquido de Fruto Mori (Morus alba), extrato líquido de Caule Spatholobi (Spatholobus suberectus), extrato líquido de Folha Mori (Morus alba), extrato líquido de Raiz Puerariae (Pueraria lobata), extrato líquido de Raiz Glycyrrhizae praeparata (Glycyrrhiza uralensis), extrato líquido de Cortex Eucommiae (Eucommia ulmoides). Humidificante: glicerina vegetal. Conservante: sorbato de potássio, benzoato de sódio, ascorbato de sódio. Modo De Utilização Tomar 50 gotas diluídas em água 2 vezes ao dia, ou de acordo com a recomendação de um terapeuta. Avisos Desaconselhado prescrever a pessoas do tipo ou constituição do tipo Yang devido à sua atividade fortemente tónica em Yang. Produto sujeito a depósito. Agitar antes de tomar. Após abertura consumir no prazo de 3 meses. Devido à presença de extrato natural de plantas, o aspeto do produto poderá variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma reação, parar a utilização. Manter fora do alcance das crianças. Se estiver grávida, a amamentar, a tomar alguma especialidade farmacêutica ou se sofre de alguma doença, consulte o seu médico ou técnico de saúde antes de tomar este suplemento. Não exceder a dose diária recomendada. Conservar em local seco e fresco, sem exposição direta à luz. Os suplementos alimentares não devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saudável. Este produto não pretende diagnosticar, tratar, curar ou prevenir nenhuma doença.',NULL,NULL,'f-34-a.jpg',4,0,100,'2026-04-21 13:54:16'),(9,'F-25 B','f-25-b',25.50,'Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natural. Acção tradicional/energética do complexo: Elimina Vento, frio e humidade; Tonifica o Rim; Reforça os músculos, tendões e ossos; Acção analgésica. Atua principalmente sobre: - Lombalgia crónica, dores crónicas das costas e fraqueza dos joelhos; - Lumbago crónico com rigidez na zona dos rins; - Dor lombar. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato líquido de Raiz Rehmanniae praeparata (Rehmannia glutinosa), extrato líquido de Raiz Salviae miltiorrhizae (Salvia miltiorrhiza), extrato líquido de Cortex Eucommiae (Eucommia ulmoides), extrato líquido de Rizoma Dioscoreae oppositae (Dioscorea opposita), extrato líquido de Fruto Corni (Cornus officinalis), extrato líquido de Fruto Lycii (Lycium barbarum), extrato líquido de Fruto Mori (Morus Alba), extrato líquido de Caule Spatholobi (Spatholobus suberectus), extrato líquido de Semente Cuscutae (Cuscuta chinensis), extrato líquido de Cortex Cinnamomi (Cinnamomum cassia), extrato líquido de Rizoma Zingiberis exsiccatum (Zingiber officinale). Humidificante: glicerina vegetal. Conservante: sorbato de potássio, benzoato de sódio, ascorbato de sódio. Modo De Utilização Tomar 50 gotas diluídas em água 2 vezes ao dia, ou de acordo com a recomendação de um terapeuta. Avisos Produto sujeito a depósito. Agitar antes de tomar. Após abertura consumir no prazo de 3 meses. Devido à presença de extrato natural de plantas, o aspeto do produto poderá variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma reação, parar a utilização. Manter fora do alcance das crianças. Se estiver grávida, a amamentar, a tomar alguma especialidade farmacêutica ou se sofre de alguma doença, consulte o seu médico ou técnico de saúde antes de tomar este suplemento. Não exceder a dose diária recomendada. Conservar em local seco e fresco, sem exposição direta à luz. Os suplementos alimentares não devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saudável. Este produto não pretende diagnosticar, tratar, curar ou prevenir nenhuma doença.',NULL,NULL,'f-25-b.jpg',4,0,100,'2026-04-21 13:54:16'),(10,'F-54','f-54',25.50,'Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natural. Indicações Terapêuticas: - Dispersa o Vento elimina o Calor; - Tóxico (Huo-Du, Re-Du) e a Humidade; - Calor nos intestinos e ao nível do Reaquecedor Inferior; - Hemostático: pára as hemorragias; - Trata as hemorroidas; - Adstringente e hipotensivo. Sintomas: - Rectorragias, fezes sanguinolentas com uma mistura de sangue vermelho ou escuro. A cor do sangue tem importância no diagnóstico oriental: - Em caso de sangue vermelho claro, trata-se antes dum golpe do tipo Vento-Calor no intestino; - Em caso de sangue vermelho escuro ou violáceo, será uma questão de Humidade-Calor residente no intestino. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato líquido de Rizoma Curcumae longae (Curcuma longa), extrato líquido de Semente Persicae (Prunus persica), extrato líquido de Fruto Aurantii (Citrus aurantium), extrato líquido de Erva Schizonepetae (Schizonepeta tenuifolia). Humidificante: glicerina vegetal. Conservante: sorbato de potássio, benzoato de sódio, ascorbato de sódio. Modo De Utilização Tomar 50 gotas diluídas em água 2 vezes ao dia, ou de acordo com a recomendação de um terapeuta. Avisos Produto sujeito a depósito. Agitar antes de tomar. Após abertura consumir no prazo de 3 meses. Devido à presença de extrato natural de plantas, o aspeto do produto poderá variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma reação, parar a utilização. Manter fora do alcance das crianças. Se estiver grávida, a amamentar, a tomar alguma especialidade farmacêutica ou se sofre de alguma doença, consulte o seu médico ou técnico de saúde antes de tomar este suplemento. Não exceder a dose diária recomendada. Conservar em local seco e fresco, sem exposição direta à luz. Os suplementos alimentares não devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saudável. Este produto não pretende diagnosticar, tratar, curar ou prevenir nenhuma doença.',NULL,NULL,'f-54.jpg',4,0,100,'2026-04-21 13:54:16'),(11,'F-41 B','f-41-b',25.50,'Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natural. As suas ações tradicionais são indicadas para: ÔÇô Hemorroidas; ÔÇô Dores epigástricas; ÔÇô Prisão de ventre; ÔÇô ├Ülceras da boca e língua; ÔÇô Hemorragia nasal; ÔÇô Gengivorragias e dores de dentes; ÔÇô Cefaleias; ÔÇô Hematémese; ÔÇô Falta de apetite; ÔÇô Sede de bebidas frias; ÔÇô Calor generalizado. As suas ações energéticas são indicadas para: ÔÇô Nutre o Yin do Estômago; ÔÇô Clarifica o Fogo profundo do Estômago. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato líquido de Fruto Mori (Morus alba), extrato líquido de Raiz Rehmanniae praeparata (Rehmannia glutinosa), extrato líquido de Raiz Ophiopogonis (Ophiopogon japonicus), extrato líquido de Raiz Achyranthis bidentatae (Achyrantes bidentata), extrato líquido de Rizoma Anemarrhenae (Anemarrhena asphodeloides). Humidificante: glicerina vegetal. Conservante: sorbato de potássio, benzoato de sódio, ascorbato de sódio. Modo De Utilização Tomar 50 gotas diluídas em água 2 vezes ao dia, ou de acordo com a recomendação de um terapeuta. Avisos Produto sujeito a depósito. Agitar antes de tomar. Após abertura consumir no prazo de 3 meses. Devido à presença de extrato natural de plantas, o aspeto do produto poderá variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma reação, parar a utilização. Manter fora do alcance das crianças. Se estiver grávida, a amamentar, a tomar alguma especialidade farmacêutica ou se sofre de alguma doença, consulte o seu médico ou técnico de saúde antes de tomar este suplemento. Não exceder a dose diária recomendada. Conservar em local seco e fresco, sem exposição direta à luz. Os suplementos alimentares não devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saudável. Este produto não pretende diagnosticar, tratar, curar ou prevenir nenhuma doença.',NULL,NULL,'f-41-b.jpg',4,0,100,'2026-04-21 13:54:17'),(12,'F-34 B','f-34-b',25.50,'Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natural. As suas acções tradicionais são indicadas: - Astenia, cansaço; - Tonturas; - Distúrbios de memória e concentração; - Líbido diminuída, impotência; - Senescência (envelhecimento dos tecidos do organismo) prematura; - Atraso de crescimento; - Lumbago. As suas acções energéticas são indicadas: - Tonifica o Yin e Yang em geral (sobretudo a nível do Rim), o Qi e o Sangue; - Reforça o Jing renal e contraria a senilidade. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato líquido de Fruto Mori (Morus alba), extrato líquido de Raiz Astragali membranacei (Astragalus membranaceus), extrato líquido de Semente Zizyphi spinosae (Ziziphus jujuba), extrato líquido de Raiz Codonopsis (Codonopsis pilosula), extrato líquido de Raiz Rehmanniae praeparata (Rehmannia glutinosa), extrato líquido de Rizoma Dioscoreae oppositae (Dioscorea opposita), extrato líquido de Semente Persicae (Prunus persica), extrato líquido de Fruto Corni (Cornus officinalis), extrato líquido de Cogumelo Poriae albae (Poria cocus), extrato líquido de Fruto Lycii (Lycium barbarum), extrato líquido de Raiz Angelicae sinensis (Angelica sinensis), extrato líquido de Fruto Arillus longan (Dimocarpus longan), extrato líquido de Semente Cuscutae (Cuscuta chinensis), extrato líquido de Fruto Amomi villosi (Amomum villosum). Humidificante: glicerina vegetal. Conservante: sorbato de potássio, benzoato de sódio, ascorbato de sódio. Modo De Utilização Tomar 50 gotas diluídas em água 2 vezes ao dia, ou de acordo com a recomendação de um terapeuta. Avisos Devido à sua acção fortemente tónica sobre o Yang, é desaconselhado prescrever a pessoas de tipologia ou de constituição igualmente muito Yang. Produto sujeito a depósito. Agitar antes de tomar. Após abertura consumir no prazo de 3 meses. Devido à presença de extrato natural de plantas, o aspeto do produto poderá variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma reação, parar a utilização. Manter fora do alcance das crianças. Se estiver grávida, a amamentar, a tomar alguma especialidade farmacêutica ou se sofre de alguma doença, consulte o seu médico ou técnico de saúde antes de tomar este suplemento. Não exceder a dose diária recomendada. Conservar em local seco e fresco, sem exposição direta à luz. Os suplementos alimentares não devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saudável. Este produto não pretende diagnosticar, tratar, curar ou prevenir nenhuma doença.',NULL,NULL,'f-34-b.jpg',4,0,100,'2026-04-21 13:54:17'),(13,'F-134 A','f-134-a',25.50,'Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natural. As suas acções tradicionais são indicadas para: - má circulação; - varizes e varicoses; - prevenção e tratamento de AVC e enfarte do miocárdio; - elimina a dor por estagnação de sangue como angina de peito, dor nos hipocôndrios, dismenorreia ou tromboflebites; - traumatismos e hematomas; - terapêutica complementar em carcinomas devidos a estagnação de sangue. As suas acções energéticas são indicadas para: - activa a circulação do Sangue e Energia; - retira a estase de Sangue; - elimina coágulos de sangue; - acalma a dor. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato líquido de Raiz Salviae miltiorrhizae (Salvia miltiorrhiza), extrato líquido de Raiz Rehmanniae recens (Rehmania glutinosa), extrato líquido de Fruto Mori (Morus alba), extrato líquido de Rizoma Curcumae longae (Curcuma longa), extrato líquido de Erva Leonuri (Leonurus japonicus), extrato líquido de Rizoma Ligustici chuanxiong (Ligusticum chuanxiong), extrato líquido de Semente Persicae (Prunus persica), extrato líquido de Fruto Aurantii (Citrus aurantium), extrato líquido de Raiz Angelicae sinensis (Angelica sinensis), extrato líquido de Rizoma Cyperi (Cyperus rotundus), extrato líquido de Flor Carthami (Carthamus tinctorius), extrato líquido de Resina Myrrhae (Commiphora myrrha), extrato líquido de Raiz Glycyrrhizae (Glycyrrhiza uralensis), extrato líquido de Cortex Cinnamomi (Cinnamomum cassia). Humidificante: glicerina vegetal. Conservante: sorbato de potássio, benzoato de sódio, ascorbato de sódio. Modo De Utilização Tomar 50 gotas diluídas em água 2 vezes ao dia, ou de acordo com a recomendação de um terapeuta. Avisos Produto sujeito a depósito. Agitar antes de tomar. Após abertura consumir no prazo de 3 meses. Devido à presença de extrato natural de plantas, o aspeto do produto poderá variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma reação, parar a utilização. Manter fora do alcance das crianças. Se estiver grávida, a amamentar, a tomar alguma especialidade farmacêutica ou se sofre de alguma doença, consulte o seu médico ou técnico de saúde antes de tomar este suplemento. Não exceder a dose diária recomendada. Conservar em local seco e fresco, sem exposição direta à luz. Os suplementos alimentares não devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saudável. Este produto não pretende diagnosticar, tratar, curar ou prevenir nenhuma doença.',NULL,NULL,'f-134-a.jpg',4,0,100,'2026-04-21 13:54:17'),(14,'F-39 B','f-39-b',25.50,'Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natural. As suas ações tradicionais: ÔÇô alivia a garganta inflamada; ÔÇô trata as dores de cabeça; ÔÇô melhora os sintomas da constipação; ÔÇô previne as dores articulares; ÔÇô combate a urticária, eczemas e prurido. As suas ações energéticas são indicadas para: ÔÇô dispersa o Vento ÔÇô Calor ÔÇô Humidade Ingredientes Extrato concentrado fitoativo: ├ügua, extrato líquido de Semente Dolichoris album (Dolichos lablab), extrato líquido de Erva Schizonepetae (Schizonepeta tenuifolia), extrato líquido de Cortex Albizziae (Albizia julibrissin), extrato líquido de Fruto Forsythiae (Forsythia suspensa), extrato líquido de Cogumelo Poriae albae (Poria cocus), extrato líquido de Semente Coicis (Coix lacrymajobi), extrato líquido de Raiz Platycodi (Platycodon grandi florum), extrato líquido de Rizoma Ligustici chuanxiong (Ligusticum chuanxiong), extrato líquido de Rizoma Zingiberis exsiccatum (Zingiber officinale), extrato líquido de Fruto Tribuli (Tribulus terrestris), extrato líquido de Raiz Ledeburiellae (Saposhnikovia divaricata), extrato líquido de Raiz Angelicae pubescentis (Angelica pubescens), extrato líquido de Raiz Glycyrrhizae (Glycyrrhiza uralensis). Humidificante: glicerina vegetal. Conservante: sorbato de potássio, benzoato de sódio, ascorbato de sódio. Modo De Utilização Tomar 50 gotas diluídas em água 2 vezes ao dia, ou de acordo com a recomendação de um terapeuta. Avisos Produto sujeito a depósito. Agitar antes de tomar. Após abertura consumir no prazo de 3 meses. Devido à presença de extrato natural de plantas, o aspeto do produto poderá variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma reação, parar a utilização. Manter fora do alcance das crianças. Se estiver grávida, a amamentar, a tomar alguma especialidade farmacêutica ou se sofre de alguma doença, consulte o seu médico ou técnico de saúde antes de tomar este suplemento. Não exceder a dose diária recomendada. Conservar em local seco e fresco, sem exposição direta à luz. Os suplementos alimentares não devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saudável. Este produto não pretende diagnosticar, tratar, curar ou prevenir nenhuma doença.',NULL,NULL,'f-39-b.jpg',4,0,100,'2026-04-21 13:54:17'),(15,'F-33 B','f-33-b',25.50,'Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natural. Acções principais: - Dispersa e diminui a energia patogénica no Pulmão - Elimina o Flegma - Calor - Acalma a Asma (Anti - asmático), devido a Invasão de Vento - Frio com acumulação de Flegma - Calor no pulmão - Alivia a tosse devido a Invasão de Vento - Frio com acumulação de Flegma - Calor no pulmão - Respiração difícil e ruidosa, asma, tosse com expectoração viscosa desagradável com laivos de sangue, respiração irregular. - Plenitude no peito, dor no peito, dificuldade em expectorar, expectoração com sangue, suores, dor de cabeça, face vermelha, febre e arrepios, náuseas, prisão de ventre. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato líquido de Fruto Mori (Morus alba), extrato líquido de Ramo Cinnamomi (Cinnamomum cassia), extrato líquido de Semente Persicae (Prunus persica), extrato líquido de Semente Trichosanthis (Trichosanthes kirilowii), extrato líquido de Fruto Perillae (Perilla frutescens), extrato líquido de Semente Sinapis albae (Sinapis alba), extrato líquido de Semente Armeniacae amarum (Prunus armeniaca), extrato líquido de Raiz Scutellariae baicalensis (Scutellaria baicalensis), extrato líquido de Raiz Glycyrrhizae (Glycyrrhiza uralensis). Humidificante: glicerina vegetal. Conservante: sorbato de potássio, benzoato de sódio, ascorbato de sódio. Modo De Utilização Tomar 50 gotas diluídas em água 2 vezes ao dia, ou de acordo com a recomendação de um terapeuta. Avisos Produto sujeito a depósito. Agitar antes de tomar. Após abertura consumir no prazo de 3 meses. Devido à presença de extrato natural de plantas, o aspeto do produto poderá variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma reação, parar a utilização. Manter fora do alcance das crianças. Se estiver grávida, a amamentar, a tomar alguma especialidade farmacêutica ou se sofre de alguma doença, consulte o seu médico ou técnico de saúde antes de tomar este suplemento. Não exceder a dose diária recomendada. Conservar em local seco e fresco, sem exposição direta à luz. Os suplementos alimentares não devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saudável. Este produto não pretende diagnosticar, tratar, curar ou prevenir nenhuma doença.',NULL,NULL,'f-33-b.jpg',4,0,100,'2026-04-21 13:54:17'),(16,'F-31 B','f-31-b',25.50,'Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterapêutico, à base de plantas de medicina tradicional chinesa, 100% natural. As suas acções tradicionais são indicadas para: - cefaleias de tensão; - vertigens, tonturas; - convulsões, tremuras, movimentos não controlados; - acessos de calor; - entorpecimento dos membros, boca desviada; - transpiração nocturna; - espasmos, - rigidez da nuca e das costas; - febre; - sede; - zumbidos; - palpitações; - insónia; - suspiros. As suas acções energéticas: - acalma o Fígado; - apazigua o vento do Fígado; - alimenta o Yin. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato líquido de Raiz Paeoniae rubrae (Paeonia lactiflora), extrato líquido de Raiz Achyranthis bidentatae (Achyrantes bidentata),extrato líquido de Semente Persicae (Prunus persica), extrato líquido de Raiz Paeoniae albae (Paeonia lactiflora), extrato líquido de Flor Carthami (Carthamus tinctorius), extrato líquido de Raiz Asparagi (Asparagus cochinchinensis), extrato líquido de Raiz Scrophulariae (Scrophularia ning poensis), extrato líquido de Raiz e rizoma Rhei (Rheum palmatum), extrato líquido de Semente Dolichoris album (Dolichos lablab), extrato líquido de Fruto Hordei germinatus (Hordeum vulgare), extrato líquido de Raiz Glycyrrhizae (Glycyrrhiza uralensis). Humidificante: glicerina vegetal. Conservante: sorbato de potássio, benzoato de sódio, ascorbato de sódio. Modo De Utilização Tomar 50 gotas diluídas em água 2 vezes ao dia, ou de acordo com a recomendação de um terapeuta. Avisos Produto sujeito a depósito. Agitar antes de tomar. Após abertura consumir no prazo de 3 meses. Devido à presença de extrato natural de plantas, o aspeto do produto poderá variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma reação, parar a utilização. Manter fora do alcance das crianças. Se estiver grávida, a amamentar, a tomar alguma especialidade farmacêutica ou se sofre de alguma doença, consulte o seu médico ou técnico de saúde antes de tomar este suplemento. Não exceder a dose diária recomendada. Conservar em local seco e fresco, sem exposição direta à luz. Os suplementos alimentares não devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saudável. Este produto não pretende diagnosticar, tratar, curar ou prevenir nenhuma doença.',NULL,NULL,'f-31-b.jpg',4,0,100,'2026-04-21 13:54:18');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `morada` text COLLATE utf8mb4_unicode_ci,
  `codigo_postal` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-21 16:32:33
