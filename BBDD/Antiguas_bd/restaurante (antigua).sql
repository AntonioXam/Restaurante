-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-10-2024 a las 13:56:53
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `restaurante`



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedidos`
--

CREATE TABLE `detalle_pedidos` (
  `id` bigint(20) NOT NULL,
  `pedido_id` bigint(20) DEFAULT NULL,
  `producto_id` bigint(20) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` bigint(20) NOT NULL,
  `numero_mesa` int(11) DEFAULT NULL,
  `estado` text DEFAULT NULL,
  `comensales` int(11) DEFAULT NULL,
  `camarero_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` bigint(20) NOT NULL,
  `mesa_id` bigint(20) DEFAULT NULL,
  `camarero_id` bigint(20) DEFAULT NULL,
  `estado` text DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` bigint(20) NOT NULL,
  `nombre` text DEFAULT NULL,
  `categoria` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) NOT NULL,
  `nombre` text DEFAULT NULL,
  `apellidos` text DEFAULT NULL,
  `rol` text DEFAULT NULL,
  `usuario` text DEFAULT NULL,
  `contrasena` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `rol`, `usuario`, `contrasena`) VALUES
(1, 'Antonio', 'Ibáñez', 'encargado', 'master', 'pizza'),
(2, 'Perico', 'Salvador', 'encargado', 'periko_elmaki', '1234'),
(3, 'Fernando', 'Ureña', 'camarero', 'litolunar', 'bambu');

-- Inserciones para la tabla `mesas`
INSERT INTO `mesas` (`id`, `numero_mesa`, `estado`, `comensales`, `camarero_id`) VALUES
(1, 1, 'activa', 4, 3),
(2, 2, 'activa', 2, 3),
(3, 3, 'inactiva', 0, NULL);

-- Inserciones para la tabla `productos`
INSERT INTO `productos` (`id`, `nombre`, `categoria`, `precio`, `stock`) VALUES
(1, 'Pizza Margarita', 'Comida', 8.50, 10),
(2, 'Ensalada César', 'Comida', 6.00, 15),
(3, 'Coca Cola', 'Bebida', 2.50, 20);

-- Inserciones para la tabla `pedidos`
INSERT INTO `pedidos` (`id`, `mesa_id`, `camarero_id`, `estado`, `total`) VALUES
(1, 1, 3, 'pendiente', 0.00),
(2, 2, 3, 'pendiente', 0.00);

-- Inserciones para la tabla `detalle_pedidos`
INSERT INTO `detalle_pedidos` (`id`, `pedido_id`, `producto_id`, `cantidad`, `notas`) VALUES
(1, 1, 1, 2, 'Sin queso'),
(2, 1, 3, 1, 'Con hielo'),
(3, 2, 2, 1, 'Sin crutones');

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
  ADD UNIQUE KEY `numero_mesa` (`numero_mesa`),
  ADD KEY `camarero_id` (`camarero_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mesa_id` (`mesa_id`),
  ADD KEY `camarero_id` (`camarero_id`);

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
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
-- Filtros para la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD CONSTRAINT `detalle_pedidos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `detalle_pedidos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD CONSTRAINT `mesas_ibfk_1` FOREIGN KEY (`camarero_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`camarero_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
