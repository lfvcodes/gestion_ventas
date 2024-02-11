-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.17 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para gestion_ventas
CREATE DATABASE IF NOT EXISTS `gestion_ventas` /*!40100 DEFAULT CHARACTER SET utf8 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `gestion_ventas`;

-- Volcando estructura para tabla gestion_ventas.pro_1categoria
CREATE TABLE IF NOT EXISTS `pro_1categoria` (
  `cod_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nom_categoria` varchar(256) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cod_categoria`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla gestion_ventas.pro_1categoria: ~4 rows (aproximadamente)
INSERT IGNORE INTO `pro_1categoria` (`cod_categoria`, `nom_categoria`) VALUES
	(1, 'Bolsas Plasticas'),
	(2, 'Productos Quimicos'),
	(3, 'Higiénicos y Servilletas'),
	(4, 'Productos Varios');

-- Volcando estructura para tabla gestion_ventas.pro_1cliente
CREATE TABLE IF NOT EXISTS `pro_1cliente` (
  `sig_idcliente` enum('V','E','J') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'V',
  `id_cliente` varchar(11) NOT NULL,
  `nombre_cliente` varchar(116) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dir_cliente` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `email_cliente` varchar(116) DEFAULT NULL,
  `telf_cliente` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id_cliente`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla gestion_ventas.pro_1cliente: ~0 rows (aproximadamente)

-- Volcando estructura para tabla gestion_ventas.pro_1vendedor
CREATE TABLE IF NOT EXISTS `pro_1vendedor` (
  `sig_idvendedor` enum('V','E') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'V',
  `id_vendedor` varchar(20) NOT NULL,
  `nombre_vendedor` varchar(116) NOT NULL,
  `apellido_vendedor` varchar(116) NOT NULL,
  `dir_vendedor` text,
  `email_vendedor` varchar(320) DEFAULT NULL,
  `telf_vendedor` varchar(16) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_vendedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla gestion_ventas.pro_1vendedor: ~0 rows (aproximadamente)

-- Volcando estructura para tabla gestion_ventas.pro_2producto
CREATE TABLE IF NOT EXISTS `pro_2producto` (
  `cod_producto` int(11) NOT NULL AUTO_INCREMENT,
  `nom_producto` varchar(256) NOT NULL,
  `cod_categoria` int(11) NOT NULL DEFAULT '0',
  `p_base` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`cod_producto`),
  KEY `id_grupo` (`cod_categoria`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla gestion_ventas.pro_2producto: ~0 rows (aproximadamente)

-- Volcando estructura para tabla gestion_ventas.pro_2usuario
CREATE TABLE IF NOT EXISTS `pro_2usuario` (
  `id_usuario` int(11) NOT NULL,
  `log_user` varchar(64) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `nom_usuario` varchar(128) NOT NULL DEFAULT '',
  `psw` varchar(256) NOT NULL,
  `nivel` int(11) NOT NULL DEFAULT '0',
  `activo` enum('S','N') DEFAULT 'S',
  `registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla gestion_ventas.pro_2usuario: ~1 rows (aproximadamente)
INSERT IGNORE INTO `pro_2usuario` (`id_usuario`, `log_user`, `email`, `nom_usuario`, `psw`, `nivel`, `activo`, `registro`) VALUES
	(99999999, 'admin', 'prueba@gmail.com', 'LFVasquez', '$2y$10$Mm5lOJ244h2A0JV6Txaa6.xGJfiBY9rwYHMJ.xOeJAt2mV2AsgHLC', 1, 'S', '2024-02-08 13:33:41');

-- Volcando estructura para tabla gestion_ventas.pro_2venta
CREATE TABLE IF NOT EXISTS `pro_2venta` (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_venta` date NOT NULL,
  `id_cliente` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `id_vendedor` varchar(20) NOT NULL DEFAULT '',
  `descripcion` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id_venta`),
  KEY `FK_pro_2venta_pro_1vendedor` (`id_vendedor`),
  KEY `FK_pro_2venta_pro_1cliente` (`id_cliente`),
  CONSTRAINT `FK_pro_2venta_pro_1cliente` FOREIGN KEY (`id_cliente`) REFERENCES `pro_1cliente` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_pro_2venta_pro_1vendedor` FOREIGN KEY (`id_vendedor`) REFERENCES `pro_1vendedor` (`id_vendedor`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla gestion_ventas.pro_2venta: ~0 rows (aproximadamente)

-- Volcando estructura para tabla gestion_ventas.pro_3dventa
CREATE TABLE IF NOT EXISTS `pro_3dventa` (
  `id_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `id_venta` int(11) NOT NULL,
  `cod_producto` int(11) NOT NULL DEFAULT (0),
  `cant` int(11) NOT NULL,
  `monto` double NOT NULL,
  PRIMARY KEY (`id_detalle`),
  KEY `cod_producto` (`cod_producto`),
  KEY `id_venta` (`id_venta`),
  CONSTRAINT `FK_pro_3dventa_pro_2producto` FOREIGN KEY (`cod_producto`) REFERENCES `pro_2producto` (`cod_producto`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `FK_pro_3dventa_pro_2venta` FOREIGN KEY (`id_venta`) REFERENCES `pro_2venta` (`id_venta`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla gestion_ventas.pro_3dventa: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
