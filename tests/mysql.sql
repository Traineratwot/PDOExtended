-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               8.0.24 - MySQL Community Server - GPL
-- Операционная система:         Win64
-- HeidiSQL Версия:              12.0.0.6468
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE = @@TIME_ZONE */;
/*!40103 SET TIME_ZONE = '+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0 */;


-- Дамп структуры базы данных test
DROP DATABASE IF EXISTS `test`;
CREATE DATABASE IF NOT EXISTS `test` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `test`;

-- Дамп структуры для таблица test.test
DROP TABLE IF EXISTS `test`;
CREATE TABLE IF NOT EXISTS `test`
(
	`id`    INT UNSIGNED                                                 NOT NULL AUTO_INCREMENT,
	`value` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
	`int`   INT                                                                   DEFAULT '123',
	PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Дамп данных таблицы test.test: ~0 rows (приблизительно)

-- Дамп структуры для таблица test.test_link_master
DROP TABLE IF EXISTS `test_link_master`;
CREATE TABLE IF NOT EXISTS `test_link_master`
(
	`id`     INT NOT NULL AUTO_INCREMENT,
	`master` INT DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `FK_test_link_master_test_link_slave` (`master`),
	CONSTRAINT `FK_test_link_master_test_link_slave` FOREIGN KEY (`master`) REFERENCES `test_link_slave` (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 4
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

-- Дамп данных таблицы test.test_link_master: ~2 rows (приблизительно)
INSERT INTO
	`test_link_master` (`id`, `master`)
	VALUES
		(1, 1),
		(2, 1),
		(3, 1);

-- Дамп структуры для таблица test.test_link_slave
DROP TABLE IF EXISTS `test_link_slave`;
CREATE TABLE IF NOT EXISTS `test_link_slave`
(
	`id`    INT NOT NULL AUTO_INCREMENT,
	`slave` VARCHAR(50) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

-- Дамп данных таблицы test.test_link_slave: ~2 rows (приблизительно)
INSERT INTO
	`test_link_slave` (`id`, `slave`)
	VALUES
		(1, 'one'),
		(2, 'two');

/*!40103 SET TIME_ZONE = IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE = IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS = IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES = IFNULL(@OLD_SQL_NOTES, 1) */;
