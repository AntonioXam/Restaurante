-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-11-2024 a las 20:23:36
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
(14, 3, 14, 1, ''),
(15, 3, 22, 1, ''),
(18, 5, 14, 2, ''),
(19, 5, 25, 1, ''),
(20, 5, 1, 1, ''),
(22, 6, 1, 1, 'sin tomatae'),
(23, 4, 3, 1, ''),
(24, 4, 14, 1, ''),
(25, 7, 14, 1, ''),
(26, 7, 17, 1, ''),
(27, 7, 16, 1, '');

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
(1, 1, 'activa', 4),
(2, 2, 'activa', 4),
(3, 3, 'inactiva', 0),
(4, 4, 'inactiva', 0),
(5, 5, 'inactiva', 0),
(6, 6, 'inactiva', 0),
(7, 7, 'inactiva', 0),
(8, 8, 'inactiva', 0),
(9, 9, 'inactiva', 0),
(10, 10, 'inactiva', 0);

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
(3, 1, 'enviado', '0.00'),
(4, 1, 'pendiente', '0.00'),
(5, 2, 'enviado', '0.00'),
(6, 2, 'enviado', '0.00'),
(7, 2, 'pendiente', '0.00');

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
(1, 'Pizza Margarita', 'pizzas', '8.50', 10),
(2, 'Ensalada César', 'ensalada', '6.00', 15),
(3, 'Coca Cola', 'Bebida', '2.50', 20),
(4, 'Pizza Pepperoni', 'pizzas', '9.00', 10),
(5, 'Pizza Cuatro Quesos', 'pizzas', '9.50', 10),
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
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`) USING HASH;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
