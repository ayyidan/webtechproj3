-- MySQLShell dump 2.0.1  Distrib Ver 9.1.0 for Win64 on x86_64 - for MySQL 9.1.0 (MySQL Community Server (GPL)), for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: web_technology_class    Table: time_slots
-- ------------------------------------------------------
-- Server version	8.0.40

--
-- Table structure for table `time_slots`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `time_slots` (
  `slot_id` int NOT NULL AUTO_INCREMENT,
  `date_time` datetime NOT NULL,
  `available_seats` int NOT NULL,
  PRIMARY KEY (`slot_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
