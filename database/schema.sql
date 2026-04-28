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
INSERT INTO `categories` VALUES (1,'Emagrecimento','emagrecimento',NULL,NULL,1),(2,'Energia','sistema-nervoso-cerebro-energia',NULL,NULL,2),(3,'Articula├º├Áes','ossos-articulacoes-musculos-e-tendoes',NULL,NULL,3),(4,'Fitoterapia','fitoterapia-suplementacao-alimentar',NULL,NULL,4);
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
INSERT INTO `products` VALUES (1,'Ponderal Fit 1','ponderal-fit-1',24.60,'Este suplemento, dispon├¡vel em embalagem de 60 c├ípsulas, foi desenvolvido para apoiar processos de e...','Este suplemento, dispon├¡vel em embalagem de 60 c├ípsulas, foi desenvolvido para apoiar processos de emagrecimento, ajudando na redu├º├úo da gordura corporal e localizada, no combate ├á celulite e no controlo eficaz do apetite. A sua f├│rmula completa promove uma sensa├º├úo de saciedade imediata e estimula o metabolismo atrav├®s de um efeito termog├®nico que evita a absor├º├úo de gorduras pelo organismo. Para al├®m de contribuir para a diminui├º├úo da reten├º├úo de l├¡quidos e facilitar o tr├ónsito intestinal, atua tamb├®m no equil├¡brio metab├│lico, ajudando a controlar os n├¡veis de glic├®mia e de colesterol. Gra├ºas ├á presen├ºa de vitaminas e minerais na sua composi├º├úo, auxilia ainda na redu├º├úo do cansa├ºo e da fadiga, proporcionando a energia necess├íria para o dia a dia. A toma geral recomendada ├® de duas c├ípsulas antes das duas refei├º├Áes principais, acompanhadas por um a dois copos de ├ígua. Relativamente a precau├º├Áes, o seu uso ├® contraindicado em caso de alergias, dificuldade de degluti├º├úo, e durante a gravidez ou amamenta├º├úo, exceto sob indica├º├úo m├®dica. Por fim, doentes diab├®ticos devem efetuar um controlo regular devido ├á a├º├úo hipoglicemiante natural do produto.',NULL,NULL,'ponderal-fit-1.jpg',1,1,100,'2026-04-21 13:54:12'),(2,'Ponderal Fit 2','ponderal-fit-2',22.50,'Este suplemento, dispon├¡vel em embalagem de 60 c├ípsulas, foi desenvolvido para auxiliar na perda de ...','Este suplemento, dispon├¡vel em embalagem de 60 c├ípsulas, foi desenvolvido para auxiliar na perda de gordura, sendo especialmente indicado para processos de emagrecimento associados a n├¡veis elevados de colesterol ou glicose no sangue. A sua f├│rmula combina o mineral Cr├│mio com extratos bot├ónicos (como Garcinia Cambogia, Gengibre, Alo├® Vera e Cascara Sagrada) para atuar de forma dupla no metabolismo e no sistema digestivo. Para al├®m de moderar o apetite e possuir uma a├º├úo termog├®nica que ajuda a reduzir a absor├º├úo de gorduras e hidratos de carbono, destaca-se por ser altamente eficaz no combate ├á obstipa├º├úo, estimulando a mobilidade intestinal, facilitando a evacua├º├úo e diminuindo a reten├º├úo de l├¡quidos. A toma recomendada ├® de um comprimido duas vezes ao dia, preferencialmente ap├│s as duas refei├º├Áes principais, sendo fundamental associar um consumo refor├ºado de ├ígua (cerca de 2 litros por dia). Embora seja seguro para atletas de alto rendimento e pacientes com patologias cr├│nicas ou diabetes, a sua utiliza├º├úo ├® estritamente contraindicada para menores de 12 anos, gr├ívidas, mulheres a amamentar ou pessoas com alergia aos seus constituintes. Em casos de obstipa├º├úo cr├│nica, o tratamento pode estender-se por um per├¡odo de 3 a 6 meses.',NULL,NULL,'ponderal-fit-2.jpg',1,1,100,'2026-04-21 13:54:13'),(3,'Neuro Mais','neuro-mais',29.66,'Este suplemento, dispon├¡vel numa embalagem de 20 ampolas, foi desenvolvido para combater ativamente ...','Este suplemento, dispon├¡vel numa embalagem de 20 ampolas, foi desenvolvido para combater ativamente a fadiga f├¡sica, mental e emocional, otimizando o rendimento cerebral, a mem├│ria e a capacidade de concentra├º├úo. A sua f├│rmula revitalizante atua no sistema nervoso e no metabolismo produtor de energia, combinando Vitamina C e vitaminas do complexo B (como B6, Niacina e Riboflavina) com extratos naturais estimulantes, onde se destacam o Guaran├í, Ginseng Coreano, Rhodiola Rosea e Gengibre. Em conjunto, estes ingredientes ajudam a combater o esgotamento psicol├│gico e a astenia, melhoram a recupera├º├úo muscular e a performance desportiva, e auxiliam no equil├¡brio das digest├Áes e no controlo do colesterol e da glic├®mia. A toma di├íria recomendada ├® de uma ampola dilu├¡da em 200 ml de ├ígua, preferencialmente de manh├ú. A dura├º├úo do tratamento varia consoante a necessidade: em casos agudos ou de sobrecarga intelectual, pode ser tomado at├® os sintomas desaparecerem; j├í em situa├º├Áes cr├│nicas, recomendam-se ciclos cont├¡nuos de 3 a 6 meses. O suplemento ├® seguro para atletas de alto rendimento e pessoas com patologias cr├│nicas. Contudo, devido ├á presen├ºa de cafe├¡na, n├úo deve ser consumido antes de dormir para evitar ins├│nias. Doentes diab├®ticos devem fazer um controlo regular da glic├®mia, e o uso ├® estritamente contraindicado para menores de 12 anos, gr├ívidas e mulheres a amamentar.',NULL,NULL,'neuro-mais.jpg',2,1,100,'2026-04-21 13:54:14'),(4,'Flexicalcium','flexicalcium',29.66,'Este suplemento, dispon├¡vel numa embalagem de 60 c├ípsulas, foi desenvolvido para apoiar o normal fun...','Este suplemento, dispon├¡vel numa embalagem de 60 c├ípsulas, foi desenvolvido para apoiar o normal funcionamento da estrutura ├│ssea, muscular e das cartilagens, destacando-se pela sua forte a├º├úo analg├®sica e anti-inflamat├│ria. A sua f├│rmula rigorosa combina vitaminas (C e D), minerais (como o Mangan├¬s) e componentes estruturais como a Glucosamina, Condroitina e MSM, potenciados por extratos naturais de Curcuma e Boswellia. Em conjunto, estes ingredientes ajudam a reduzir o incha├ºo articular e a rigidez, promovem a forma├º├úo de colag├®nio, atenuam a fadiga muscular e protegem as c├®lulas contra o stress oxidativo. ├ë especialmente indicado para casos agudos ou cr├│nicos de reumatismo, artroses, artrite, dores ci├íticas, fibromialgia, recupera├º├úo de fraturas ou les├Áes, e at├® para a melhoria da performance desportiva. A toma di├íria geral ├® de tr├¬s c├ípsulas, distribu├¡das pelas principais refei├º├Áes, podendo ser reduzida para duas c├ípsulas numa fase de manuten├º├úo. A dura├º├úo do tratamento pode variar desde a toma at├® ao desaparecimento de sintomas (casos agudos) at├® per├¡odos de 3 meses a 1 ano (casos cr├│nicos). Embora seja seguro para pessoas com patologias cr├│nicas, diabetes, hipertens├úo e desportistas, o produto ├® contraindicado para menores de 18 anos, gr├ívidas e mulheres a amamentar. Utilizadores de anticoagulantes ou com obstru├º├úo hepato-biliar devem consultar um m├®dico antes de iniciar a toma.',NULL,NULL,'flexicalcium.jpg',3,1,100,'2026-04-21 13:54:15'),(5,'F-44','f-44',28.79,'Ingredientes Extrato concentrado fitoativo: ├ügua, extrato l├¡quido de Ramo Cinnamomi (Cinnamomum cass...','Ingredientes Extrato concentrado fitoativo: ├ügua, extrato l├¡quido de Ramo Cinnamomi (Cinnamomum cassia), extrato l├¡quido de Raiz Paeoniae albae (Paeonia lactiflora), extrato l├¡quido de Rizoma Zingiberis exsiccatum (Zingiber officinale), extrato l├¡quido de Raiz Glycyrrhizae praeparata (Glycyrrhiza uralensis), extrato l├¡quido de Fruto Zizyphi jujubae (Ziziphus jujuba). Humidificante: glicerina vegetal. Conservante: sorbato de pot├íssio, benzoato de s├│dio, ascorbato de s├│dio. Modo De Utiliza├º├úo Em geral 50 gotas dilu├¡das em ├ígua 2 vezes ao dia. Avisos Os suplementos alimentares n├úo devem ser utilizados em substitui├º├úo de um regime alimentar variado. N├úo exceder as doses recomendadas.',NULL,NULL,'f-44.jpg',4,0,100,'2026-04-21 13:54:15'),(6,'F-47','f-47',28.79,'Ac├º├úo (Medicina Tradicional Chinesa): Retira calor no Qi. Purifica calor perverso (Pulm├úo/Est├┤mago)....','Ac├º├úo (Medicina Tradicional Chinesa): Retira calor no Qi. Purifica calor perverso (Pulm├úo/Est├┤mago). Regenera produ├º├úo l├¡quidos org├ónicos, acalma a sede. Indica├º├Áes / Sintomas: Doen├ºas infecciosas com sintomas de Forte Calor nas camadas superficiais. Sintomatologia 4 Grandes (sede, febre, transpira├º├úo e pulso) Sintomas Gerais: Irritabilidade, agita├º├úo, cefaleias, cansa├ºo, urinas escuras, rosto vermelho, tosse com expectora├º├úo de mucos viscoso e amarelo, hemoptise, epis├íxis, asma (Calor P); dor epigastro, apetite p/ alimentos frios, gengivas dolorosas, halitose (Calor E); abdom├®n inchado duro e doloroso, fezes duras revestidas de liquidos (Calor Intestinos). Ingredientes Extrato concentrado fitoativo: ├ügua, extrato l├¡quido de Fruto Mori (Morus alba), extrato l├¡quido de Erva Houttuyniae (Houttuyniae cordata), extrato l├¡quido de Rizoma Anemarrhenae (Anemarrhena asphodeloides), extrato l├¡quido de Raiz Glycyrrhizae praeparata (Glycyrrhiza uralensis). Humidificante: glicerina vegetal. Conservante: sorbato de pot├íssio, benzoato de s├│dio, ascorbato de s├│dio. Modo De Utiliza├º├úo Em geral 50 gotas dilu├¡das em ├ígua 2 vezes ao dia. Avisos Os suplementos alimentares n├úo devem ser utilizados em substitui├º├úo de um regime alimentar variado. N├úo exceder as doses recomendadas.',NULL,NULL,'f-47.jpg',4,0,100,'2026-04-21 13:54:15'),(7,'F-1','f-1',25.50,'Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natural. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato l├¡quido de Raiz Codonopsis (Codonopsis pilosula), extrato l├¡quido de Rizoma Atractylodis macrocephalae (Atractylodes macrocephala), extrato l├¡quido de Cogumelo Poriae albae (Poria cocus), extrato l├¡quido de Raiz Glycyrrhizae praeparata (Glycyrrhiza uralensis). Humidificante: glicerina vegetal. Conservante: sorbato de pot├íssio, benzoato de s├│dio, ascorbato de s├│dio. Modo De Utiliza├º├úo Tomar 50 gotas dilu├¡das em ├ígua 2 vezes ao dia, ou de acordo com a recomenda├º├úo de um terapeuta. Avisos Produto sujeito a dep├│sito. Agitar antes de tomar. Ap├│s abertura consumir no prazo de 3 meses. Devido ├á presen├ºa de extrato natural de plantas, o aspeto do produto poder├í variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma rea├º├úo, parar a utiliza├º├úo. Manter fora do alcance das crian├ºas. Se estiver gr├ívida, a amamentar, a tomar alguma especialidade farmac├¬utica ou se sofre de alguma doen├ºa, consulte o seu m├®dico ou t├®cnico de sa├║de antes de tomar este suplemento. N├úo exceder a dose di├íria recomendada. Conservar em local seco e fresco, sem exposi├º├úo direta ├á luz. Os suplementos alimentares n├úo devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saud├ível. Este produto n├úo pretende diagnosticar, tratar, curar ou prevenir nenhuma doen├ºa.',NULL,NULL,'f-1.jpg',4,0,100,'2026-04-21 13:54:16'),(8,'F-34 A','f-34-a',25.50,'Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natural. As suas ac├º├Áes tradicionais s├úo indicadas: - Astenia geral, profunda e cr├│nica, fadiga ao m├¡nimo esfor├ºo; - Debilidade seguida de uma doen├ºa cr├│nica; - Impot├¬ncia; - Dispneia de esfor├ºo; - Enurese noturna; - Fraqueza na regi├úo lombar e nos membros inferiores (joelhos); - Cura de rejuvenescimento (cabelos grisalhos, perda de cabelo, pele fl├ícida e seca, falta de energia). As suas ac├º├Áes energ├®ticas s├úo indicadas: - Grande tonifica├º├úo do Yin e do Yang, do Sangue e da Energia; - Fortalece todas as fun├º├Áes energ├®ticas do corpo, Yin e Yang, Xue e Qi, fun├º├Áes f├¡sicas e mentais; - Reduz as insufici├¬ncias no n├¡vel de todos os ├│rg├úos e v├¡sceras, especialmente as do rim Yang e, secundariamente, as do cora├º├úo, ba├ºo e pulm├úo. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato l├¡quido de Raiz Paeoniae rubrae (Paeonia lactiflora), extrato l├¡quido de Semente Zizyphi spinosae (Ziziphus jujuba), extrato l├¡quido de Semente Dolichoris album (Dolichos lablab), extrato l├¡quido de Fruto Arillus longan (Dimocarpus longan), extrato l├¡quido de Raiz Salviae miltiorrhizae (Salvia miltiorrhiza), extrato l├¡quido de Cogumelo Poriae albae (Poria cocus), Fruto Corni (Cornus officinalis), extrato l├¡quido de Raiz Codonopsis (Codonopsis pilosula), extrato l├¡quido de Raiz Paeoniae albae (Paeonia lactiflora), extrato l├¡quido de Fruto Mori (Morus alba), extrato l├¡quido de Caule Spatholobi (Spatholobus suberectus), extrato l├¡quido de Folha Mori (Morus alba), extrato l├¡quido de Raiz Puerariae (Pueraria lobata), extrato l├¡quido de Raiz Glycyrrhizae praeparata (Glycyrrhiza uralensis), extrato l├¡quido de Cortex Eucommiae (Eucommia ulmoides). Humidificante: glicerina vegetal. Conservante: sorbato de pot├íssio, benzoato de s├│dio, ascorbato de s├│dio. Modo De Utiliza├º├úo Tomar 50 gotas dilu├¡das em ├ígua 2 vezes ao dia, ou de acordo com a recomenda├º├úo de um terapeuta. Avisos Desaconselhado prescrever a pessoas do tipo ou constitui├º├úo do tipo Yang devido ├á sua atividade fortemente t├│nica em Yang. Produto sujeito a dep├│sito. Agitar antes de tomar. Ap├│s abertura consumir no prazo de 3 meses. Devido ├á presen├ºa de extrato natural de plantas, o aspeto do produto poder├í variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma rea├º├úo, parar a utiliza├º├úo. Manter fora do alcance das crian├ºas. Se estiver gr├ívida, a amamentar, a tomar alguma especialidade farmac├¬utica ou se sofre de alguma doen├ºa, consulte o seu m├®dico ou t├®cnico de sa├║de antes de tomar este suplemento. N├úo exceder a dose di├íria recomendada. Conservar em local seco e fresco, sem exposi├º├úo direta ├á luz. Os suplementos alimentares n├úo devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saud├ível. Este produto n├úo pretende diagnosticar, tratar, curar ou prevenir nenhuma doen├ºa.',NULL,NULL,'f-34-a.jpg',4,0,100,'2026-04-21 13:54:16'),(9,'F-25 B','f-25-b',25.50,'Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natural. Ac├º├úo tradicional/energ├®tica do complexo: Elimina Vento, frio e humidade; Tonifica o Rim; Refor├ºa os m├║sculos, tend├Áes e ossos; Ac├º├úo analg├®sica. Atua principalmente sobre: - Lombalgia cr├│nica, dores cr├│nicas das costas e fraqueza dos joelhos; - Lumbago cr├│nico com rigidez na zona dos rins; - Dor lombar. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato l├¡quido de Raiz Rehmanniae praeparata (Rehmannia glutinosa), extrato l├¡quido de Raiz Salviae miltiorrhizae (Salvia miltiorrhiza), extrato l├¡quido de Cortex Eucommiae (Eucommia ulmoides), extrato l├¡quido de Rizoma Dioscoreae oppositae (Dioscorea opposita), extrato l├¡quido de Fruto Corni (Cornus officinalis), extrato l├¡quido de Fruto Lycii (Lycium barbarum), extrato l├¡quido de Fruto Mori (Morus Alba), extrato l├¡quido de Caule Spatholobi (Spatholobus suberectus), extrato l├¡quido de Semente Cuscutae (Cuscuta chinensis), extrato l├¡quido de Cortex Cinnamomi (Cinnamomum cassia), extrato l├¡quido de Rizoma Zingiberis exsiccatum (Zingiber officinale). Humidificante: glicerina vegetal. Conservante: sorbato de pot├íssio, benzoato de s├│dio, ascorbato de s├│dio. Modo De Utiliza├º├úo Tomar 50 gotas dilu├¡das em ├ígua 2 vezes ao dia, ou de acordo com a recomenda├º├úo de um terapeuta. Avisos Produto sujeito a dep├│sito. Agitar antes de tomar. Ap├│s abertura consumir no prazo de 3 meses. Devido ├á presen├ºa de extrato natural de plantas, o aspeto do produto poder├í variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma rea├º├úo, parar a utiliza├º├úo. Manter fora do alcance das crian├ºas. Se estiver gr├ívida, a amamentar, a tomar alguma especialidade farmac├¬utica ou se sofre de alguma doen├ºa, consulte o seu m├®dico ou t├®cnico de sa├║de antes de tomar este suplemento. N├úo exceder a dose di├íria recomendada. Conservar em local seco e fresco, sem exposi├º├úo direta ├á luz. Os suplementos alimentares n├úo devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saud├ível. Este produto n├úo pretende diagnosticar, tratar, curar ou prevenir nenhuma doen├ºa.',NULL,NULL,'f-25-b.jpg',4,0,100,'2026-04-21 13:54:16'),(10,'F-54','f-54',25.50,'Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natural. Indica├º├Áes Terap├¬uticas: - Dispersa o Vento elimina o Calor; - T├│xico (Huo-Du, Re-Du) e a Humidade; - Calor nos intestinos e ao n├¡vel do Reaquecedor Inferior; - Hemost├ítico: p├íra as hemorragias; - Trata as hemorroidas; - Adstringente e hipotensivo. Sintomas: - Rectorragias, fezes sanguinolentas com uma mistura de sangue vermelho ou escuro. A cor do sangue tem import├óncia no diagn├│stico oriental: - Em caso de sangue vermelho claro, trata-se antes dum golpe do tipo Vento-Calor no intestino; - Em caso de sangue vermelho escuro ou viol├íceo, ser├í uma quest├úo de Humidade-Calor residente no intestino. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato l├¡quido de Rizoma Curcumae longae (Curcuma longa), extrato l├¡quido de Semente Persicae (Prunus persica), extrato l├¡quido de Fruto Aurantii (Citrus aurantium), extrato l├¡quido de Erva Schizonepetae (Schizonepeta tenuifolia). Humidificante: glicerina vegetal. Conservante: sorbato de pot├íssio, benzoato de s├│dio, ascorbato de s├│dio. Modo De Utiliza├º├úo Tomar 50 gotas dilu├¡das em ├ígua 2 vezes ao dia, ou de acordo com a recomenda├º├úo de um terapeuta. Avisos Produto sujeito a dep├│sito. Agitar antes de tomar. Ap├│s abertura consumir no prazo de 3 meses. Devido ├á presen├ºa de extrato natural de plantas, o aspeto do produto poder├í variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma rea├º├úo, parar a utiliza├º├úo. Manter fora do alcance das crian├ºas. Se estiver gr├ívida, a amamentar, a tomar alguma especialidade farmac├¬utica ou se sofre de alguma doen├ºa, consulte o seu m├®dico ou t├®cnico de sa├║de antes de tomar este suplemento. N├úo exceder a dose di├íria recomendada. Conservar em local seco e fresco, sem exposi├º├úo direta ├á luz. Os suplementos alimentares n├úo devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saud├ível. Este produto n├úo pretende diagnosticar, tratar, curar ou prevenir nenhuma doen├ºa.',NULL,NULL,'f-54.jpg',4,0,100,'2026-04-21 13:54:16'),(11,'F-41 B','f-41-b',25.50,'Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natural. As suas a├º├Áes tradicionais s├úo indicadas para: ÔÇô Hemorroidas; ÔÇô Dores epig├ístricas; ÔÇô Pris├úo de ventre; ÔÇô ├Ülceras da boca e l├¡ngua; ÔÇô Hemorragia nasal; ÔÇô Gengivorragias e dores de dentes; ÔÇô Cefaleias; ÔÇô Hemat├®mese; ÔÇô Falta de apetite; ÔÇô Sede de bebidas frias; ÔÇô Calor generalizado. As suas a├º├Áes energ├®ticas s├úo indicadas para: ÔÇô Nutre o Yin do Est├┤mago; ÔÇô Clarifica o Fogo profundo do Est├┤mago. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato l├¡quido de Fruto Mori (Morus alba), extrato l├¡quido de Raiz Rehmanniae praeparata (Rehmannia glutinosa), extrato l├¡quido de Raiz Ophiopogonis (Ophiopogon japonicus), extrato l├¡quido de Raiz Achyranthis bidentatae (Achyrantes bidentata), extrato l├¡quido de Rizoma Anemarrhenae (Anemarrhena asphodeloides). Humidificante: glicerina vegetal. Conservante: sorbato de pot├íssio, benzoato de s├│dio, ascorbato de s├│dio. Modo De Utiliza├º├úo Tomar 50 gotas dilu├¡das em ├ígua 2 vezes ao dia, ou de acordo com a recomenda├º├úo de um terapeuta. Avisos Produto sujeito a dep├│sito. Agitar antes de tomar. Ap├│s abertura consumir no prazo de 3 meses. Devido ├á presen├ºa de extrato natural de plantas, o aspeto do produto poder├í variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma rea├º├úo, parar a utiliza├º├úo. Manter fora do alcance das crian├ºas. Se estiver gr├ívida, a amamentar, a tomar alguma especialidade farmac├¬utica ou se sofre de alguma doen├ºa, consulte o seu m├®dico ou t├®cnico de sa├║de antes de tomar este suplemento. N├úo exceder a dose di├íria recomendada. Conservar em local seco e fresco, sem exposi├º├úo direta ├á luz. Os suplementos alimentares n├úo devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saud├ível. Este produto n├úo pretende diagnosticar, tratar, curar ou prevenir nenhuma doen├ºa.',NULL,NULL,'f-41-b.jpg',4,0,100,'2026-04-21 13:54:17'),(12,'F-34 B','f-34-b',25.50,'Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natural. As suas ac├º├Áes tradicionais s├úo indicadas: - Astenia, cansa├ºo; - Tonturas; - Dist├║rbios de mem├│ria e concentra├º├úo; - L├¡bido diminu├¡da, impot├¬ncia; - Senesc├¬ncia (envelhecimento dos tecidos do organismo) prematura; - Atraso de crescimento; - Lumbago. As suas ac├º├Áes energ├®ticas s├úo indicadas: - Tonifica o Yin e Yang em geral (sobretudo a n├¡vel do Rim), o Qi e o Sangue; - Refor├ºa o Jing renal e contraria a senilidade. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato l├¡quido de Fruto Mori (Morus alba), extrato l├¡quido de Raiz Astragali membranacei (Astragalus membranaceus), extrato l├¡quido de Semente Zizyphi spinosae (Ziziphus jujuba), extrato l├¡quido de Raiz Codonopsis (Codonopsis pilosula), extrato l├¡quido de Raiz Rehmanniae praeparata (Rehmannia glutinosa), extrato l├¡quido de Rizoma Dioscoreae oppositae (Dioscorea opposita), extrato l├¡quido de Semente Persicae (Prunus persica), extrato l├¡quido de Fruto Corni (Cornus officinalis), extrato l├¡quido de Cogumelo Poriae albae (Poria cocus), extrato l├¡quido de Fruto Lycii (Lycium barbarum), extrato l├¡quido de Raiz Angelicae sinensis (Angelica sinensis), extrato l├¡quido de Fruto Arillus longan (Dimocarpus longan), extrato l├¡quido de Semente Cuscutae (Cuscuta chinensis), extrato l├¡quido de Fruto Amomi villosi (Amomum villosum). Humidificante: glicerina vegetal. Conservante: sorbato de pot├íssio, benzoato de s├│dio, ascorbato de s├│dio. Modo De Utiliza├º├úo Tomar 50 gotas dilu├¡das em ├ígua 2 vezes ao dia, ou de acordo com a recomenda├º├úo de um terapeuta. Avisos Devido ├á sua ac├º├úo fortemente t├│nica sobre o Yang, ├® desaconselhado prescrever a pessoas de tipologia ou de constitui├º├úo igualmente muito Yang. Produto sujeito a dep├│sito. Agitar antes de tomar. Ap├│s abertura consumir no prazo de 3 meses. Devido ├á presen├ºa de extrato natural de plantas, o aspeto do produto poder├í variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma rea├º├úo, parar a utiliza├º├úo. Manter fora do alcance das crian├ºas. Se estiver gr├ívida, a amamentar, a tomar alguma especialidade farmac├¬utica ou se sofre de alguma doen├ºa, consulte o seu m├®dico ou t├®cnico de sa├║de antes de tomar este suplemento. N├úo exceder a dose di├íria recomendada. Conservar em local seco e fresco, sem exposi├º├úo direta ├á luz. Os suplementos alimentares n├úo devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saud├ível. Este produto n├úo pretende diagnosticar, tratar, curar ou prevenir nenhuma doen├ºa.',NULL,NULL,'f-34-b.jpg',4,0,100,'2026-04-21 13:54:17'),(13,'F-134 A','f-134-a',25.50,'Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natural. As suas ac├º├Áes tradicionais s├úo indicadas para: - m├í circula├º├úo; - varizes e varicoses; - preven├º├úo e tratamento de AVC e enfarte do mioc├írdio; - elimina a dor por estagna├º├úo de sangue como angina de peito, dor nos hipoc├┤ndrios, dismenorreia ou tromboflebites; - traumatismos e hematomas; - terap├¬utica complementar em carcinomas devidos a estagna├º├úo de sangue. As suas ac├º├Áes energ├®ticas s├úo indicadas para: - activa a circula├º├úo do Sangue e Energia; - retira a estase de Sangue; - elimina co├ígulos de sangue; - acalma a dor. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato l├¡quido de Raiz Salviae miltiorrhizae (Salvia miltiorrhiza), extrato l├¡quido de Raiz Rehmanniae recens (Rehmania glutinosa), extrato l├¡quido de Fruto Mori (Morus alba), extrato l├¡quido de Rizoma Curcumae longae (Curcuma longa), extrato l├¡quido de Erva Leonuri (Leonurus japonicus), extrato l├¡quido de Rizoma Ligustici chuanxiong (Ligusticum chuanxiong), extrato l├¡quido de Semente Persicae (Prunus persica), extrato l├¡quido de Fruto Aurantii (Citrus aurantium), extrato l├¡quido de Raiz Angelicae sinensis (Angelica sinensis), extrato l├¡quido de Rizoma Cyperi (Cyperus rotundus), extrato l├¡quido de Flor Carthami (Carthamus tinctorius), extrato l├¡quido de Resina Myrrhae (Commiphora myrrha), extrato l├¡quido de Raiz Glycyrrhizae (Glycyrrhiza uralensis), extrato l├¡quido de Cortex Cinnamomi (Cinnamomum cassia). Humidificante: glicerina vegetal. Conservante: sorbato de pot├íssio, benzoato de s├│dio, ascorbato de s├│dio. Modo De Utiliza├º├úo Tomar 50 gotas dilu├¡das em ├ígua 2 vezes ao dia, ou de acordo com a recomenda├º├úo de um terapeuta. Avisos Produto sujeito a dep├│sito. Agitar antes de tomar. Ap├│s abertura consumir no prazo de 3 meses. Devido ├á presen├ºa de extrato natural de plantas, o aspeto do produto poder├í variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma rea├º├úo, parar a utiliza├º├úo. Manter fora do alcance das crian├ºas. Se estiver gr├ívida, a amamentar, a tomar alguma especialidade farmac├¬utica ou se sofre de alguma doen├ºa, consulte o seu m├®dico ou t├®cnico de sa├║de antes de tomar este suplemento. N├úo exceder a dose di├íria recomendada. Conservar em local seco e fresco, sem exposi├º├úo direta ├á luz. Os suplementos alimentares n├úo devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saud├ível. Este produto n├úo pretende diagnosticar, tratar, curar ou prevenir nenhuma doen├ºa.',NULL,NULL,'f-134-a.jpg',4,0,100,'2026-04-21 13:54:17'),(14,'F-39 B','f-39-b',25.50,'Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natural. As suas a├º├Áes tradicionais: ÔÇô alivia a garganta inflamada; ÔÇô trata as dores de cabe├ºa; ÔÇô melhora os sintomas da constipa├º├úo; ÔÇô previne as dores articulares; ÔÇô combate a urtic├íria, eczemas e prurido. As suas a├º├Áes energ├®ticas s├úo indicadas para: ÔÇô dispersa o Vento ÔÇô Calor ÔÇô Humidade Ingredientes Extrato concentrado fitoativo: ├ügua, extrato l├¡quido de Semente Dolichoris album (Dolichos lablab), extrato l├¡quido de Erva Schizonepetae (Schizonepeta tenuifolia), extrato l├¡quido de Cortex Albizziae (Albizia julibrissin), extrato l├¡quido de Fruto Forsythiae (Forsythia suspensa), extrato l├¡quido de Cogumelo Poriae albae (Poria cocus), extrato l├¡quido de Semente Coicis (Coix lacrymajobi), extrato l├¡quido de Raiz Platycodi (Platycodon grandi florum), extrato l├¡quido de Rizoma Ligustici chuanxiong (Ligusticum chuanxiong), extrato l├¡quido de Rizoma Zingiberis exsiccatum (Zingiber officinale), extrato l├¡quido de Fruto Tribuli (Tribulus terrestris), extrato l├¡quido de Raiz Ledeburiellae (Saposhnikovia divaricata), extrato l├¡quido de Raiz Angelicae pubescentis (Angelica pubescens), extrato l├¡quido de Raiz Glycyrrhizae (Glycyrrhiza uralensis). Humidificante: glicerina vegetal. Conservante: sorbato de pot├íssio, benzoato de s├│dio, ascorbato de s├│dio. Modo De Utiliza├º├úo Tomar 50 gotas dilu├¡das em ├ígua 2 vezes ao dia, ou de acordo com a recomenda├º├úo de um terapeuta. Avisos Produto sujeito a dep├│sito. Agitar antes de tomar. Ap├│s abertura consumir no prazo de 3 meses. Devido ├á presen├ºa de extrato natural de plantas, o aspeto do produto poder├í variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma rea├º├úo, parar a utiliza├º├úo. Manter fora do alcance das crian├ºas. Se estiver gr├ívida, a amamentar, a tomar alguma especialidade farmac├¬utica ou se sofre de alguma doen├ºa, consulte o seu m├®dico ou t├®cnico de sa├║de antes de tomar este suplemento. N├úo exceder a dose di├íria recomendada. Conservar em local seco e fresco, sem exposi├º├úo direta ├á luz. Os suplementos alimentares n├úo devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saud├ível. Este produto n├úo pretende diagnosticar, tratar, curar ou prevenir nenhuma doen├ºa.',NULL,NULL,'f-39-b.jpg',4,0,100,'2026-04-21 13:54:17'),(15,'F-33 B','f-33-b',25.50,'Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natural. Ac├º├Áes principais: - Dispersa e diminui a energia patog├®nica no Pulm├úo - Elimina o Flegma - Calor - Acalma a Asma (Anti - asm├ítico), devido a Invas├úo de Vento - Frio com acumula├º├úo de Flegma - Calor no pulm├úo - Alivia a tosse devido a Invas├úo de Vento - Frio com acumula├º├úo de Flegma - Calor no pulm├úo - Respira├º├úo dif├¡cil e ruidosa, asma, tosse com expectora├º├úo viscosa desagrad├ível com laivos de sangue, respira├º├úo irregular. - Plenitude no peito, dor no peito, dificuldade em expectorar, expectora├º├úo com sangue, suores, dor de cabe├ºa, face vermelha, febre e arrepios, n├íuseas, pris├úo de ventre. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato l├¡quido de Fruto Mori (Morus alba), extrato l├¡quido de Ramo Cinnamomi (Cinnamomum cassia), extrato l├¡quido de Semente Persicae (Prunus persica), extrato l├¡quido de Semente Trichosanthis (Trichosanthes kirilowii), extrato l├¡quido de Fruto Perillae (Perilla frutescens), extrato l├¡quido de Semente Sinapis albae (Sinapis alba), extrato l├¡quido de Semente Armeniacae amarum (Prunus armeniaca), extrato l├¡quido de Raiz Scutellariae baicalensis (Scutellaria baicalensis), extrato l├¡quido de Raiz Glycyrrhizae (Glycyrrhiza uralensis). Humidificante: glicerina vegetal. Conservante: sorbato de pot├íssio, benzoato de s├│dio, ascorbato de s├│dio. Modo De Utiliza├º├úo Tomar 50 gotas dilu├¡das em ├ígua 2 vezes ao dia, ou de acordo com a recomenda├º├úo de um terapeuta. Avisos Produto sujeito a dep├│sito. Agitar antes de tomar. Ap├│s abertura consumir no prazo de 3 meses. Devido ├á presen├ºa de extrato natural de plantas, o aspeto do produto poder├í variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma rea├º├úo, parar a utiliza├º├úo. Manter fora do alcance das crian├ºas. Se estiver gr├ívida, a amamentar, a tomar alguma especialidade farmac├¬utica ou se sofre de alguma doen├ºa, consulte o seu m├®dico ou t├®cnico de sa├║de antes de tomar este suplemento. N├úo exceder a dose di├íria recomendada. Conservar em local seco e fresco, sem exposi├º├úo direta ├á luz. Os suplementos alimentares n├úo devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saud├ível. Este produto n├úo pretende diagnosticar, tratar, curar ou prevenir nenhuma doen├ºa.',NULL,NULL,'f-33-b.jpg',4,0,100,'2026-04-21 13:54:17'),(16,'F-31 B','f-31-b',25.50,'Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natura...','Suplemento alimentar fitoterap├¬utico, ├á base de plantas de medicina tradicional chinesa, 100% natural. As suas ac├º├Áes tradicionais s├úo indicadas para: - cefaleias de tens├úo; - vertigens, tonturas; - convuls├Áes, tremuras, movimentos n├úo controlados; - acessos de calor; - entorpecimento dos membros, boca desviada; - transpira├º├úo nocturna; - espasmos, - rigidez da nuca e das costas; - febre; - sede; - zumbidos; - palpita├º├Áes; - ins├│nia; - suspiros. As suas ac├º├Áes energ├®ticas: - acalma o F├¡gado; - apazigua o vento do F├¡gado; - alimenta o Yin. Ingredientes Extrato concentrado fitoativo: ├ügua, extrato l├¡quido de Raiz Paeoniae rubrae (Paeonia lactiflora), extrato l├¡quido de Raiz Achyranthis bidentatae (Achyrantes bidentata),extrato l├¡quido de Semente Persicae (Prunus persica), extrato l├¡quido de Raiz Paeoniae albae (Paeonia lactiflora), extrato l├¡quido de Flor Carthami (Carthamus tinctorius), extrato l├¡quido de Raiz Asparagi (Asparagus cochinchinensis), extrato l├¡quido de Raiz Scrophulariae (Scrophularia ning poensis), extrato l├¡quido de Raiz e rizoma Rhei (Rheum palmatum), extrato l├¡quido de Semente Dolichoris album (Dolichos lablab), extrato l├¡quido de Fruto Hordei germinatus (Hordeum vulgare), extrato l├¡quido de Raiz Glycyrrhizae (Glycyrrhiza uralensis). Humidificante: glicerina vegetal. Conservante: sorbato de pot├íssio, benzoato de s├│dio, ascorbato de s├│dio. Modo De Utiliza├º├úo Tomar 50 gotas dilu├¡das em ├ígua 2 vezes ao dia, ou de acordo com a recomenda├º├úo de um terapeuta. Avisos Produto sujeito a dep├│sito. Agitar antes de tomar. Ap├│s abertura consumir no prazo de 3 meses. Devido ├á presen├ºa de extrato natural de plantas, o aspeto do produto poder├í variar ligeiramente. Evite o produto em caso de alergia ou sensibilidade a algum dos ingredientes. Se ocorrer alguma rea├º├úo, parar a utiliza├º├úo. Manter fora do alcance das crian├ºas. Se estiver gr├ívida, a amamentar, a tomar alguma especialidade farmac├¬utica ou se sofre de alguma doen├ºa, consulte o seu m├®dico ou t├®cnico de sa├║de antes de tomar este suplemento. N├úo exceder a dose di├íria recomendada. Conservar em local seco e fresco, sem exposi├º├úo direta ├á luz. Os suplementos alimentares n├úo devem ser usados como substitutos de uma dieta equilibrada e variada e de um estilo de vida saud├ível. Este produto n├úo pretende diagnosticar, tratar, curar ou prevenir nenhuma doen├ºa.',NULL,NULL,'f-31-b.jpg',4,0,100,'2026-04-21 13:54:18');
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
