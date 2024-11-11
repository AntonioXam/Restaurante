-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-11-2024 a las 20:08:28
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `restaurante`
--
CREATE DATABASE IF NOT EXISTS `restaurante` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `restaurante`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta`
--

DROP TABLE IF EXISTS `cuenta`;
CREATE TABLE `cuenta` (
  `id` bigint(20) NOT NULL,
  `mesa_id` bigint(20) NOT NULL,
  `producto_id` bigint(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_pagadas`
--

DROP TABLE IF EXISTS `cuentas_pagadas`;
CREATE TABLE `cuentas_pagadas` (
  `id` bigint(20) NOT NULL,
  `mesa_id` bigint(20) NOT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp(),
  `producto` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cuentas_pagadas`
--

INSERT INTO `cuentas_pagadas` (`id`, `mesa_id`, `fecha_hora`, `producto`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 1, '2024-11-08 17:19:32', 'Fanta Naranja', 5, '2.50', '12.50'),
(2, 1, '2024-11-08 17:54:39', 'Ensalada Caprese', 1, '7.00', '7.00'),
(3, 1, '2024-11-08 17:54:39', 'Pizza Hawaiana', 2, '9.00', '18.00'),
(4, 1, '2024-11-08 17:54:39', 'Pizza Diavola', 2, '9.50', '19.00'),
(5, 1, '2024-11-08 17:54:39', 'Agua Mineral', 2, '1.50', '3.00'),
(6, 1, '2024-11-08 17:54:39', 'Entrecot de Ternera', 1, '15.00', '15.00'),
(7, 1, '2024-11-08 17:54:39', 'Vino Tinto', 1, '4.00', '4.00'),
(8, 1, '2024-11-08 17:54:39', 'Pasta Carbonara', 1, '8.00', '8.00'),
(9, 5, '2024-11-09 18:01:52', 'Vino Tinto', 1, '4.00', '4.00'),
(10, 5, '2024-11-09 18:01:52', 'Solomillo de Cerdo', 2, '14.00', '28.00'),
(11, 5, '2024-11-09 18:01:52', 'Bacalao al Pil Pil', 1, '13.00', '13.00'),
(12, 5, '2024-11-09 18:01:52', 'Cerveza', 3, '3.00', '9.00'),
(13, 1, '2024-11-09 18:35:59', 'Sprite', 3, '2.50', '7.50'),
(14, 1, '2024-11-09 18:35:59', 'Coca Cola', 2, '2.50', '5.00'),
(15, 1, '2024-11-09 18:35:59', 'Pizza Napolitana', 1, '9.00', '9.00'),
(16, 1, '2024-11-11 15:00:20', 'Sprite', 5, '2.50', '12.50'),
(17, 1, '2024-11-11 15:00:20', 'Coca Cola', 5, '2.50', '12.50'),
(18, 1, '2024-11-11 15:00:20', 'Bacalao al Pil Pil', 5, '13.00', '65.00'),
(19, 1, '2024-11-11 15:22:12', 'Coca Cola', 1, '2.50', '2.50'),
(20, 1, '2024-11-11 15:22:12', 'Sprite', 1, '2.50', '2.50'),
(21, 1, '2024-11-11 15:22:12', 'Sprite', 1, '2.50', '2.50'),
(22, 1, '2024-11-11 15:22:12', 'Coca Cola', 1, '2.50', '2.50'),
(23, 1, '2024-11-11 15:22:12', 'Bacalao al Pil Pil', 1, '13.00', '13.00'),
(24, 2, '2024-11-11 15:42:28', 'Ensalada Griega', 2, '6.50', '13.00'),
(25, 1, '2024-11-11 15:45:33', 'Vino Blanco', 1, '4.00', '4.00'),
(26, 1, '2024-11-11 15:45:33', 'Agua Mineral', 1, '1.50', '1.50'),
(27, 1, '2024-11-11 15:45:33', 'Sprite', 2, '2.50', '5.00'),
(28, 1, '2024-11-11 15:45:33', 'Ensalada de Pollo', 1, '7.50', '7.50'),
(29, 1, '2024-11-11 17:06:08', 'Entrecot de Ternera', 1, '15.00', '15.00'),
(30, 1, '2024-11-11 17:06:08', 'Fanta Naranja', 1, '2.50', '2.50'),
(31, 1, '2024-11-11 17:06:08', 'Agua Mineral', 1, '1.50', '1.50'),
(32, 1, '2024-11-11 17:06:08', 'Ensalada Griega', 1, '6.50', '6.50'),
(33, 1, '2024-11-11 17:06:08', 'Pasta Carbonara', 1, '8.00', '8.00'),
(34, 1, '2024-11-11 17:06:08', 'Cerveza', 5, '3.00', '15.00'),
(35, 1, '2024-11-11 17:29:52', 'Cerveza', 3, '3.00', '9.00'),
(36, 3, '2024-11-11 17:32:36', 'Fanta Limón', 1, '2.50', '2.50'),
(37, 1, '2024-11-11 17:36:27', 'Cerveza', 1, '3.00', '3.00'),
(38, 1, '2024-11-11 17:39:29', 'Cerveza', 1, '3.00', '3.00'),
(39, 6, '2024-11-11 19:51:53', 'Coca Cola', 2, '2.50', '5.00'),
(40, 6, '2024-11-11 19:51:53', 'Fanta Naranja', 1, '2.50', '2.50'),
(41, 6, '2024-11-11 19:51:53', 'Cerveza', 1, '3.00', '3.00'),
(42, 6, '2024-11-11 19:51:53', 'Pizza 4 Quesos', 1, '9.50', '9.50'),
(43, 6, '2024-11-11 19:51:53', 'Pizza Diavola', 1, '9.50', '9.50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedidos`
--

DROP TABLE IF EXISTS `detalle_pedidos`;
CREATE TABLE `detalle_pedidos` (
  `id` bigint(20) NOT NULL,
  `pedido_id` bigint(20) DEFAULT NULL,
  `producto_id` bigint(20) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle_pedidos`
--

INSERT INTO `detalle_pedidos` (`id`, `pedido_id`, `producto_id`, `cantidad`, `notas`) VALUES
(1, 1, 14, 5, ''),
(2, 2, 6, 2, ''),
(3, 2, 10, 2, ''),
(4, 2, 17, 2, ''),
(5, 2, 28, 1, 'al punto\r\n'),
(6, 2, 19, 1, ''),
(7, 2, 22, 1, 'extra de queso'),
(8, 4, 12, 1, ''),
(9, 6, 19, 1, ''),
(10, 6, 29, 1, ''),
(11, 6, 26, 1, ''),
(12, 6, 18, 3, ''),
(13, 7, 28, 4, ''),
(14, 7, 23, 2, ''),
(15, 5, 1, 1, ''),
(16, 5, 4, 1, ''),
(17, 9, 1, 1, ''),
(18, 9, 16, 1, ''),
(26, 15, 11, 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

DROP TABLE IF EXISTS `mesas`;
CREATE TABLE `mesas` (
  `id` bigint(20) NOT NULL,
  `numero_mesa` int(11) DEFAULT NULL,
  `estado` text DEFAULT NULL,
  `comensales` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `numero_mesa`, `estado`, `comensales`) VALUES
(1, 1, 'inactiva', NULL),
(2, 2, 'inactiva', NULL),
(3, 3, 'inactiva', NULL),
(4, 4, 'inactiva', NULL),
(5, 5, 'inactiva', NULL),
(6, 6, 'inactiva', NULL),
(7, 7, 'inactiva', NULL),
(8, 8, 'inactiva', NULL),
(9, 9, 'inactiva', NULL),
(10, 10, 'inactiva', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE `pedidos` (
  `id` bigint(20) NOT NULL,
  `mesa_id` bigint(20) DEFAULT NULL,
  `estado` text DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `mesa_id`, `estado`, `total`) VALUES
(1, 1, 'enviado', '0.00'),
(2, 1, 'enviado', '0.00'),
(3, 1, 'enviado', '0.00'),
(4, 1, 'enviado', '0.00'),
(5, 1, 'enviado', '0.00'),
(6, 5, 'enviado', '0.00'),
(7, 5, 'pendiente', '0.00'),
(8, 1, 'enviado', '0.00'),
(9, 1, 'enviado', '0.00'),
(15, 2, 'pendiente', '0.00'),
(16, 1, 'completado', '0.00'),
(17, 1, 'completado', '0.00'),
(18, 1, 'completado', '0.00'),
(19, 1, 'completado', '0.00'),
(20, 1, 'completado', '0.00'),
(21, 1, 'completado', '0.00'),
(22, 1, 'completado', '0.00'),
(23, 1, 'completado', '0.00'),
(24, 10, 'completado', '0.00'),
(25, 1, 'completado', '0.00'),
(26, 3, 'completado', '0.00'),
(27, 1, 'completado', '0.00'),
(28, 1, 'completado', '0.00'),
(29, 6, 'completado', '0.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `id` bigint(20) NOT NULL,
  `nombre` text DEFAULT NULL,
  `categoria` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `categoria`, `precio`, `stock`) VALUES
(1, 'Coca Cola', 'Bebida', '2.50', 20),
(2, 'Pizza Margarita', 'pizzas', '8.00', 10),
(3, 'Pizza 4 Quesos', 'pizzas', '9.50', 10),
(4, 'Pizza Napolitana', 'pizzas', '9.00', 10),
(5, 'Pizza Pepperoni', 'pizzas', '9.50', 10),
(6, 'Pizza Hawaiana', 'pizzas', '9.00', 10),
(7, 'Pizza Vegetariana', 'pizzas', '8.50', 10),
(8, 'Pizza Barbacoa', 'pizzas', '10.00', 10),
(9, 'Pizza Prosciutto', 'pizzas', '9.00', 10),
(10, 'Pizza Diavola', 'pizzas', '9.50', 10),
(11, 'Ensalada Griega', 'ensalada', '6.50', 15),
(12, 'Ensalada Caprese', 'ensalada', '7.00', 15),
(13, 'Ensalada de Pollo', 'ensalada', '7.50', 15),
(14, 'Fanta Naranja', 'Bebida', '2.50', 20),
(15, 'Fanta Limón', 'Bebida', '2.50', 20),
(16, 'Sprite', 'Bebida', '2.50', 20),
(17, 'Agua Mineral', 'Bebida', '1.50', 30),
(18, 'Cerveza', 'Bebida', '3.00', 20),
(19, 'Vino Tinto', 'vino', '4.00', 15),
(20, 'Vino Blanco', 'vino', '4.00', 15),
(21, 'Vino Rosado', 'vino', '4.00', 15),
(22, 'Pasta Carbonara', 'pasta', '8.00', 10),
(23, 'Pasta Boloñesa', 'pasta', '8.50', 10),
(24, 'Pasta Alfredo', 'pasta', '9.00', 10),
(25, 'Salmón a la Plancha', 'pescado', '12.00', 10),
(26, 'Bacalao al Pil Pil', 'pescado', '13.00', 10),
(27, 'Merluza a la Romana', 'pescado', '11.00', 10),
(28, 'Entrecot de Ternera', 'carne', '15.00', 10),
(29, 'Solomillo de Cerdo', 'carne', '14.00', 10),
(30, 'Pollo Asado', 'carne', '10.00', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temp_ticket`
--

DROP TABLE IF EXISTS `temp_ticket`;
CREATE TABLE `temp_ticket` (
  `id` bigint(20) NOT NULL,
  `mesa_id` bigint(20) NOT NULL,
  `producto` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` bigint(20) NOT NULL,
  `nombre` text DEFAULT NULL,
  `apellidos` text DEFAULT NULL,
  `dni` text NOT NULL,
  `rol` text DEFAULT NULL,
  `usuario` text DEFAULT NULL,
  `contrasena` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `dni`, `rol`, `usuario`, `contrasena`) VALUES
(1, 'Antonio', 'Ibáñez', '48576585L', 'encargado', 'master', 'pizza'),
(3, 'Fernando', 'Ureña', '23445667F', 'camarero', 'litolunar', 'bambu');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mesa_id` (`mesa_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `cuentas_pagadas`
--
ALTER TABLE `cuentas_pagadas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mesa_id` (`mesa_id`);

--
-- Indices de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_mesa` (`numero_mesa`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mesa_id` (`mesa_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `temp_ticket`
--
ALTER TABLE `temp_ticket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mesa_id` (`mesa_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`) USING HASH;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de la tabla `cuentas_pagadas`
--
ALTER TABLE `cuentas_pagadas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `temp_ticket`
--
ALTER TABLE `temp_ticket`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuenta`
--
ALTER TABLE `cuenta`
  ADD CONSTRAINT `cuenta_ibfk_1` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`),
  ADD CONSTRAINT `cuenta_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `cuentas_pagadas`
--
ALTER TABLE `cuentas_pagadas`
  ADD CONSTRAINT `cuentas_pagadas_ibfk_1` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`);

--
-- Filtros para la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD CONSTRAINT `detalle_pedidos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `detalle_pedidos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`);

--
-- Filtros para la tabla `temp_ticket`
--
ALTER TABLE `temp_ticket`
  ADD CONSTRAINT `temp_ticket_ibfk_1` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
