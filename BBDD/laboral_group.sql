-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 15-03-2024 a las 22:17:31
-- Versión del servidor: 8.2.0
-- Versión de PHP: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `laboral_group`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `nId` int NOT NULL AUTO_INCREMENT,
  `sDni` varchar(9) NOT NULL,
  `sNombre` varchar(100) NOT NULL,
  `dFechaNacimiento` date NOT NULL,
  `sTelefono` varchar(100) DEFAULT NULL,
  `sEmail` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`nId`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf16;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`nId`, `sDni`, `sNombre`, `dFechaNacimiento`, `sTelefono`, `sEmail`) VALUES
(1, '12398700E', 'Antonio Martínez', '1950-10-10', '958256455', 'antoniomartinez@gmail.com'),
(2, '23409876A', 'Ana María Solís', '1985-07-22', '692467081', 'anasolis@gmail.com'),
(3, '34520987B', 'Carlos Esteban Quiroga', '1990-02-15', '693578182', 'carloseq@gmail.com'),
(4, '45632098C', 'Diana Carolina Reyes', '1982-11-30', '694689283', 'dianacr@gmail.com'),
(5, '56743109D', 'Eduardo José Linares', '1975-05-05', '695790384', 'eduardojl@gmail.com'),
(6, '67854210E', 'Fernanda Martínez Gómez', '1988-09-19', '696801485', 'fernandamg@gmail.com'),
(7, '78965321F', 'Gustavo Henríquez Solano', '1972-03-08', '697912586', 'gustavohs@gmail.com'),
(8, '89076432G', 'Helena Isabel Freites', '1995-12-24', '698023687', 'helenaf@gmail.com'),
(9, '90187543H', 'Igor Joaquín Navarro', '1983-06-17', '699134788', 'igorn@gmail.com'),
(10, '01298654I', 'Julia Karina López', '1978-04-02', '691245889', 'juliakl@gmail.com'),
(11, '12345678J', 'Luisa Fernanda Pérez', '1990-07-15', '692356900', 'luisafp@gmail.com'),
(12, '23456789K', 'Mariano José Ortega', '1982-11-30', NULL, 'marianojo@gmail.com'),
(13, '56789012N', 'Patricia Quintana', '1993-05-09', '696790344', 'patriciaq@gmail.com'),
(14, '78901234P', 'Sofía Margarita Vega', '1985-09-22', NULL, 'sofiamv@gmail.com'),
(15, '90123456R', 'Ursula Miriam Baez', '1986-01-29', NULL, 'ursulamb@gmail.com'),
(16, '10293847A', 'Ana Carolina Díaz', '1984-06-17', NULL, 'anacdiaz@example.com'),
(17, '82736455D', 'Javier Enrique Solís', '1985-12-09', '640506070', 'javieresol@example.com'),
(18, '73645566E', 'Mónica Lizeth Betancourt', '1990-09-21', NULL, 'monicalb@example.com'),
(19, '64827391F', 'Sofía Martínez', '1987-11-30', '661218349', 'sofiamartinez@example.com'),
(20, '36527364I', 'Carlos Estévez', '1980-07-19', NULL, 'carlosestevez@example.com'),
(21, '14345586K', 'Manuel Ortiz', '1991-09-16', NULL, 'manuelortiz@example.com'),
(22, '03254697L', 'Laura Giménez', '1986-03-25', '727874905', NULL),
(23, '83726154M', 'Elena Torres', '1992-07-14', NULL, 'elenatorres@example.com'),
(24, '72615483N', 'Miguel Ángel Ruiz', '1984-12-09', '749096127', NULL),
(25, '61549372O', 'Isabel Miranda', '1979-05-23', NULL, NULL),
(26, '12355700E', 'Sophia Pino', '1990-04-05', '958987455', 'sophiapino@gmail.com'),
(27, '84465498P', 'Mariano Rivera', '2009-01-14', '525854565', 'marianorivera@google.com');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
