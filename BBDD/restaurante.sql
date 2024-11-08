-- Configuración inicial
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Crear base de datos
DROP DATABASE IF EXISTS `restaurante`;
CREATE DATABASE `restaurante` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `restaurante`;

-- Crear tablas en orden (primero las independientes)
-- 1. Tabla mesas
CREATE TABLE `mesas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `numero_mesa` int(11) DEFAULT NULL,
  `estado` text DEFAULT NULL,
  `comensales` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_mesa` (`numero_mesa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabla productos
CREATE TABLE `productos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` text DEFAULT NULL,
  `categoria` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabla usuarios
CREATE TABLE `usuarios` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` text DEFAULT NULL,
  `apellidos` text DEFAULT NULL,
  `dni` text NOT NULL,
  `rol` text DEFAULT NULL,
  `usuario` text DEFAULT NULL,
  `contrasena` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tabla pedidos (depende de mesas)
CREATE TABLE `pedidos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mesa_id` bigint(20) DEFAULT NULL,
  `estado` text DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mesa_id` (`mesa_id`),
  CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Tabla detalle_pedidos (depende de pedidos y productos)
CREATE TABLE `detalle_pedidos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pedido_id` bigint(20) DEFAULT NULL,
  `producto_id` bigint(20) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `notas` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `detalle_pedidos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  CONSTRAINT `detalle_pedidos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Tabla historial_pedidos (depende de mesas y productos)
CREATE TABLE `historial_pedidos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mesa_id` bigint(20) NOT NULL,
  `fecha_hora` datetime DEFAULT CURRENT_TIMESTAMP,
  `producto_id` bigint(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `notas` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mesa_id` (`mesa_id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `historial_pedidos_ibfk_1` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`),
  CONSTRAINT `historial_pedidos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla para la cuenta
CREATE TABLE `cuenta` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mesa_id` bigint(20) NOT NULL,
  `producto_id` bigint(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `fecha_hora` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `mesa_id` (`mesa_id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `cuenta_ibfk_1` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`),
  CONSTRAINT `cuenta_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla temporal para tickets
CREATE TABLE `temp_ticket` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mesa_id` bigint(20) NOT NULL,
  `producto` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `fecha_hora` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `mesa_id` (`mesa_id`),
  CONSTRAINT `temp_ticket_ibfk_1` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar datos iniciales
-- 1. Insertar mesas
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

-- 2. Insertar productos
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

-- 3. Insertar usuarios
INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `dni`, `rol`, `usuario`, `contrasena`) VALUES
(1, 'Antonio', 'Ibáñez', '48576585L', 'encargado', 'master', 'pizza'),
(3, 'Fernando', 'Ureña', '23445667F', 'camarero', 'litolunar', 'bambu');

COMMIT;