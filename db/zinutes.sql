-- MySQL dump 10.13  Distrib 8.4.13, for Linux (x86_64)
--
-- Host: localhost    Database: stud
-- ------------------------------------------------------
-- Server version	8.4.13

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
-- Current Database: `stud`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `stud` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `stud`;

--
-- Table structure for table `pasiruosimas`
--

DROP TABLE IF EXISTS `pasiruosimas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pasiruosimas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vardas` varchar(20) NOT NULL,
  `epastas` varchar(50) NOT NULL,
  `kam` varchar(20) NOT NULL,
  `lytis` varchar(10) NOT NULL,
  `data` datetime NOT NULL,
  `zinute` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pasiruosimas`
--

LOCK TABLES `pasiruosimas` WRITE;
/*!40000 ALTER TABLE `pasiruosimas` DISABLE KEYS */;
/*!40000 ALTER TABLE `pasiruosimas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `titasstuopis`
--

DROP TABLE IF EXISTS `titasstuopis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `titasstuopis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vardas` varchar(20) NOT NULL,
  `epastas` varchar(50) NOT NULL,
  `kam` varchar(20) NOT NULL,
  `data` datetime NOT NULL,
  `zinute` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `titasstuopis`
--

LOCK TABLES `titasstuopis` WRITE;
/*!40000 ALTER TABLE `titasstuopis` DISABLE KEYS */;
INSERT INTO `titasstuopis` VALUES (1,'testas','testas@email.com','testuolis','2026-11-18 12:47:10','grazuolis'),(2,'testas','testas@email.com','testuolis','2026-11-18 12:48:12','grazuolis'),(3,'testas','testas@email.com','testuolis','2026-11-18 12:48:15','grazuolis'),(4,'testas','testas@email.com','testuolis','2026-11-18 12:51:20','grazuolis');
/*!40000 ALTER TABLE `titasstuopis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zinutes`
--

DROP TABLE IF EXISTS `zinutes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zinutes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vardas` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_lithuanian_ci NOT NULL,
  `epastas` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_lithuanian_ci NOT NULL,
  `kam` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_lithuanian_ci NOT NULL,
  `data` datetime NOT NULL,
  `ip` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_lithuanian_ci NOT NULL,
  `zinute` text CHARACTER SET utf8mb3 COLLATE utf8mb3_lithuanian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_lithuanian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zinutes`
--

LOCK TABLES `zinutes` WRITE;
/*!40000 ALTER TABLE `zinutes` DISABLE KEYS */;
/*!40000 ALTER TABLE `zinutes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed
