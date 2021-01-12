-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-01-2021 a las 08:29:19
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `estacionamiento`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajones`
--

CREATE TABLE `cajones` (
  `id` int(11) NOT NULL,
  `area` varchar(255) DEFAULT NULL,
  `situacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cajones`
--

INSERT INTO `cajones` (`id`, `area`, `situacion`) VALUES
(1, '1', 'Ocupado'),
(2, '1', 'Ocupado'),
(3, '1', 'Disponible'),
(4, '1', 'Disponible'),
(5, '1', 'Disponible'),
(6, '1', 'Disponible'),
(7, '2', 'Disponible'),
(8, '2', 'Disponible'),
(9, '2', 'Disponible'),
(10, '3', 'Disponible'),
(11, '3', 'Disponible'),
(12, '3', 'Disponible'),
(13, '3', 'Disponible'),
(14, '3', 'Disponible'),
(15, '3', 'Disponible'),
(16, '3', 'Disponible'),
(17, '3', 'Disponible'),
(18, '3', 'Disponible'),
(19, '4', 'Disponible'),
(20, '4', 'Disponible'),
(21, '4', 'Disponible'),
(22, '4', 'Disponible'),
(23, '4', 'Disponible'),
(24, '4', 'Disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resguardos`
--

CREATE TABLE `resguardos` (
  `id_cajon` int(11) NOT NULL,
  `id_vehiculo` int(11) NOT NULL,
  `hora_llegada` varchar(255) NOT NULL,
  `hora_salida` varchar(255) DEFAULT NULL,
  `pago` varchar(255) DEFAULT NULL,
  `fecha` varchar(255) DEFAULT NULL,
  `estatus` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `resguardos`
--

INSERT INTO `resguardos` (`id_cajon`, `id_vehiculo`, `hora_llegada`, `hora_salida`, `pago`, `fecha`, `estatus`) VALUES
(1, 1, '00:07:05', '', '', '12/01/2021', 'Activo'),
(2, 2, '00:07:34', '', '', '12/01/2021', 'Activo'),
(3, 4, '00:18:52', '00:21:53', '0.6', '12/01/2021', 'Pagado'),
(3, 5, '00:38:23', '00:40:13', '0.36', '12/01/2021', 'Pagado'),
(3, 7, '01:01:11', '01:03:21', '0.48', '12/01/2021', 'Pagado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` varchar(255) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellidos` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `contrasenia` varchar(255) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `telefono`, `correo`, `contrasenia`, `tipo`) VALUES
('1', 'pedro', 'Torres Salgado', '7471363758', 'nortonextrem@gmail.com', '123', 'admin'),
('2', 'admin', 'Torres Salgado', '7471363758', 'pedo@gmail.com', '123', 'admin'),
('1', 'pedro', 'Torres Salgado', '7471363758', 'pedro@gmail.com', '123', 'admin'),
('3', 'juan', 'nava', '7472334567', 'juan@gmail.com', '123', 'cajero'),
('4', 'Paola', 'Arroyo', '7471006868', 'pao@gmail.com', '123', 'conductor'),
('9', 'Victor', 'Santos', '7172989700', 'Vic@gamil.com', '123', 'cajero'),
('6', 'Beto', 'Meneces', '7472563475', 'Betito@gmail.com', '123', 'conductor'),
('11', 'Jesus', 'Hernandez', '7475676879', 'jesus@gmail.com', '123', 'conductor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id` int(11) NOT NULL,
  `placas` varchar(255) DEFAULT NULL,
  `marca` varchar(255) DEFAULT NULL,
  `modelo` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `tamanio` varchar(255) DEFAULT NULL,
  `cliente` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id`, `placas`, `marca`, `modelo`, `color`, `tamanio`, `cliente`) VALUES
(1, 'PXT-99-00', 'Chevrolet', 'Chevi', 'rojo', 'Chico', '4'),
(2, 'PYC-89-87', 'Nisan', 'Tida', 'blanco', 'Chico', '4'),
(3, 'PTG-87-99', 'Nissan', 'Bora', 'verde', 'Chico', '6'),
(4, 'PYT-90-09', 'Nisaan', 'Martch', 'blanco', 'Chico', '6'),
(5, 'PTX-98-97', 'Nissan', 'Tida', 'blanco', 'Chico', '6'),
(6, 'RTE-09-87', 'Nissan', 'bora', 'blanco', 'Chico', '11'),
(7, 'TRY-0908', 'Nissan', 'Tida', 'blanco', 'Chico', '6');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cajones`
--
ALTER TABLE `cajones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `resguardos`
--
ALTER TABLE `resguardos`
  ADD PRIMARY KEY (`id_cajon`,`id_vehiculo`,`hora_llegada`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
