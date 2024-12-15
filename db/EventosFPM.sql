CREATE DATABASE  IF NOT EXISTS `eventosacademicos` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `eventosacademicos`;
-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: eventosacademicos
-- ------------------------------------------------------
-- Server version	8.2.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `certificados`
--

DROP TABLE IF EXISTS `certificados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `certificados` (
  `CertificadoId` int NOT NULL AUTO_INCREMENT,
  `InscricaoId` int DEFAULT NULL,
  `ArquivoCertificado` longblob NOT NULL,
  `DataEmissaoCertificado` datetime NOT NULL,
  PRIMARY KEY (`CertificadoId`),
  KEY `InscricaoId` (`InscricaoId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `certificados`
--

LOCK TABLES `certificados` WRITE;
/*!40000 ALTER TABLE `certificados` DISABLE KEYS */;
/*!40000 ALTER TABLE `certificados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cursos`
--

DROP TABLE IF EXISTS `cursos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cursos` (
  `CursoId` int NOT NULL AUTO_INCREMENT,
  `NomeCurso` varchar(100) NOT NULL,
  PRIMARY KEY (`CursoId`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cursos`
--

LOCK TABLES `cursos` WRITE;
/*!40000 ALTER TABLE `cursos` DISABLE KEYS */;
INSERT INTO `cursos` VALUES (1,'GTI');
/*!40000 ALTER TABLE `cursos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cursosdepartamentos`
--

DROP TABLE IF EXISTS `cursosdepartamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cursosdepartamentos` (
  `CursoDepartamentoId` int NOT NULL AUTO_INCREMENT,
  `CursoId` int DEFAULT NULL,
  `DepartamentoId` int DEFAULT NULL,
  PRIMARY KEY (`CursoDepartamentoId`),
  KEY `CursoId` (`CursoId`),
  KEY `DepartamentoId` (`DepartamentoId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cursosdepartamentos`
--

LOCK TABLES `cursosdepartamentos` WRITE;
/*!40000 ALTER TABLE `cursosdepartamentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `cursosdepartamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cursosparticipantes`
--

DROP TABLE IF EXISTS `cursosparticipantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cursosparticipantes` (
  `CursoParticipanteId` int NOT NULL AUTO_INCREMENT,
  `CursoId` int DEFAULT NULL,
  `ParticipanteId` int DEFAULT NULL,
  PRIMARY KEY (`CursoParticipanteId`),
  KEY `CursoId` (`CursoId`),
  KEY `ParticipanteId` (`ParticipanteId`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cursosparticipantes`
--

LOCK TABLES `cursosparticipantes` WRITE;
/*!40000 ALTER TABLE `cursosparticipantes` DISABLE KEYS */;
INSERT INTO `cursosparticipantes` VALUES (1,1,1);
/*!40000 ALTER TABLE `cursosparticipantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departamentos`
--

DROP TABLE IF EXISTS `departamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departamentos` (
  `DepartamentoId` int NOT NULL AUTO_INCREMENT,
  `NomeDepartamento` varchar(50) NOT NULL,
  PRIMARY KEY (`DepartamentoId`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departamentos`
--

LOCK TABLES `departamentos` WRITE;
/*!40000 ALTER TABLE `departamentos` DISABLE KEYS */;
INSERT INTO `departamentos` VALUES (1,'GTI'),(2,'GTI');
/*!40000 ALTER TABLE `departamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eventos`
--

DROP TABLE IF EXISTS `eventos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `eventos` (
  `EventoId` int NOT NULL AUTO_INCREMENT,
  `NomeEvento` varchar(100) NOT NULL,
  `DataInicioEvento` datetime NOT NULL,
  `DataFimEvento` datetime NOT NULL,
  `HorarioInicio` time NOT NULL,
  `HorarioTermino` time NOT NULL,
  `LocalEvento` varchar(255) NOT NULL,
  `CargaHoraria` int NOT NULL,
  `DescricaoEvento` text,
  `ImagemEvento` varchar(255) DEFAULT NULL,
  `VagasDisponiveis` int NOT NULL DEFAULT '0',
  `TipoEvento` enum('Interno','Externo') NOT NULL,
  `DepartamentoEventoId` int DEFAULT NULL,
  `ResponsavelEventoId` int DEFAULT NULL,
  `InstituicaoParceira` varchar(255) DEFAULT NULL,
  `PalestranteId` int DEFAULT NULL,
  PRIMARY KEY (`EventoId`),
  KEY `DepartamentoEventoId` (`DepartamentoEventoId`),
  KEY `ResponsavelEventoId` (`ResponsavelEventoId`),
  KEY `FK_Palestrante` (`PalestranteId`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eventos`
--

LOCK TABLES `eventos` WRITE;
/*!40000 ALTER TABLE `eventos` DISABLE KEYS */;
INSERT INTO `eventos` VALUES (1,'Informática Básica','2024-12-15 00:00:00','2024-12-15 00:00:00','20:00:00','21:00:00','FPM-LAB',0,'Evento para Iniciantes/teste edição','/Eventosfaculdade/public/uploads/evento_675ed3716bb6a3.76421098.png',1,'Interno',1,NULL,NULL,5),(5,'Curso de informática avançada','2024-12-15 00:00:00','2024-12-15 00:00:00','09:15:00','10:15:00','FPM-LAB',5,'TESTE',NULL,0,'Interno',1,NULL,NULL,5),(6,'Curso de EXEL , básico','2024-12-15 00:00:00','2024-12-15 00:00:00','10:18:00','11:18:00','FPM-LAB',5,'Teste',NULL,0,'Interno',1,NULL,NULL,5),(7,'Curso de EXEL , Avançado','2024-12-15 00:00:00','2024-12-15 00:00:00','10:18:00','11:18:00','FPM-LAB',5,'Teste','/Eventosfaculdade/public/uploads/evento_675ec99a875b94.09997267.png',0,'Interno',1,NULL,NULL,5),(8,'Excel e Ruim','2024-12-15 00:00:00','2024-12-15 00:00:00','12:43:00','15:43:00','FPM-LAB',5,'Para Leigos','/Eventosfaculdade/public/uploads/evento_675ecf0c0a21f1.43099985.png',2,'Interno',1,NULL,NULL,5);
/*!40000 ALTER TABLE `eventos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inscricoes`
--

DROP TABLE IF EXISTS `inscricoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inscricoes` (
  `InscricaoId` int NOT NULL AUTO_INCREMENT,
  `EventoId` int DEFAULT NULL,
  `ParticipanteId` int DEFAULT NULL,
  `DataInscricao` datetime DEFAULT CURRENT_TIMESTAMP,
  `Compareceu` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`InscricaoId`),
  KEY `EventoId` (`EventoId`),
  KEY `ParticipanteId` (`ParticipanteId`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inscricoes`
--

LOCK TABLES `inscricoes` WRITE;
/*!40000 ALTER TABLE `inscricoes` DISABLE KEYS */;
INSERT INTO `inscricoes` VALUES (1,1,1,'0000-00-00 00:00:00',1),(2,1,2,'0000-00-00 00:00:00',1),(3,1,2,'2024-12-14 10:32:22',1),(34,8,6,'2024-12-15 09:53:58',0);
/*!40000 ALTER TABLE `inscricoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participantes`
--

DROP TABLE IF EXISTS `participantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `participantes` (
  `ParticipanteId` int NOT NULL AUTO_INCREMENT,
  `NomeParticipante` varchar(100) NOT NULL,
  `EmailParticipante` varchar(100) NOT NULL,
  `TipoParticipante` enum('Interno','Externo','Admin') NOT NULL,
  `NumeroMatricula` varchar(20) DEFAULT NULL,
  `CPF` varchar(11) DEFAULT NULL,
  `SenhaParticipante` varchar(255) NOT NULL,
  `DepartamentoParticipanteId` int DEFAULT NULL,
  PRIMARY KEY (`ParticipanteId`),
  UNIQUE KEY `EmailParticipante` (`EmailParticipante`,`CPF`),
  KEY `DepartamentoParticipanteId` (`DepartamentoParticipanteId`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participantes`
--

LOCK TABLES `participantes` WRITE;
/*!40000 ALTER TABLE `participantes` DISABLE KEYS */;
INSERT INTO `participantes` VALUES (1,'jose','jose@gmail.com','Interno','202310000433',NULL,'$2y$10$iASW1SgKqGVrfQNeJ82si.GOwcpjCqP5YA3y2NCwjM1W1QZN/5mKG',NULL),(2,'diogo alves noguiera','nogueira@gamil.com','Interno','202310000422',NULL,'$2y$10$CTPS/tX2ETTPg17JQVdmu.zwh85p42mjPbSYBDHAV43wGleia61rC',NULL),(3,'diogo alves noguiera','nogueira@gamil.com','Interno','202310000422',NULL,'$2y$10$RZvakHUWIi1UHki0qowl3.pIJ4QbcnxST.gJ1HiqKyvp8lXuu8Ley',NULL),(4,'diogo alves noguiera','nogueira@gmail.com','Externo',NULL,'05036507142','$2y$10$iLkXzgwJVTenUMGMwX98ZOCxxoG66uSXx0.LeLPuIFXOBAKJOKoOO',NULL),(5,'adm','admin@example.com','Admin',NULL,NULL,'$2y$10$2QFrf34hdkuteETYYF1sgeNWuZcAKTGxFhqdO0Rj.D55XmBRCacAG',NULL),(6,'p','p@gmail.com','Interno','20231000041',NULL,'$2y$10$FvlfXWC0i0cKgsKHWoqHjeAIk7mell/cHuClCQ4GiKuEd.yWBJj6O',NULL),(7,'Jota','jota@gmail.com','Interno','1232134',NULL,'$2y$10$euWsz1AlsnssUWg9jaSVseOlPgruoSqFVi0Zqtc8mRM9DXh5C4R3.',NULL),(8,'teste','teste@gmail.com','Externo',NULL,'05347524542','$2y$10$dTwlnZJLxNPXwmNrVdBziOoT4nT7nYvc11VnXQY8xJHlau4zCQpeS',NULL),(9,'Add','add@gmail.com','Admin',NULL,NULL,'$2y$10$d65yUeKXwj6JH/TnChWT2.LvsOk4yiMB.4tjoaOu3Pnr4nl9JfXUi',NULL);
/*!40000 ALTER TABLE `participantes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-15 10:17:56
