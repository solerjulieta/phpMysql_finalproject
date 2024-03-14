-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-08-2023 a las 18:19:47
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dw3_soler_julieta`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caracteristicas`
--

CREATE TABLE `caracteristicas` (
  `caracteristicas_id` tinyint(3) UNSIGNED NOT NULL,
  `cualidad` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `caracteristicas`
--

INSERT INTO `caracteristicas` (`caracteristicas_id`, `cualidad`) VALUES
(1, 'Confección artesanal.'),
(2, 'Calabazas seleccionadas.'),
(3, 'Con cuero de primera calidad.'),
(4, 'Virola de acero inoxidable.'),
(5, 'Virola y guarda de ALPACA.'),
(6, 'Cerámica/acero inoxidable.'),
(7, 'No necesita curado.'),
(8, 'Incluye bombilla.'),
(9, 'Acero inoxidable.'),
(10, 'Mantiene 30 minutos tu mate caliente.'),
(11, '3 horas de frío.'),
(12, 'Aislamiento al vacío.'),
(13, 'Apto para lavavajillas.'),
(14, 'Virola de alpaca cincelada.'),
(15, 'Térmico - con cámara de aire.'),
(16, 'No genera hongos.'),
(17, 'Se puede lavar.'),
(18, 'Súper resistente.'),
(19, 'Caño 10mm.'),
(20, '100% alpaca.'),
(21, 'Pico tipo rey.'),
(22, 'Acero inoxidable.'),
(23, 'Fácil de lavar.'),
(24, 'Liviana.'),
(25, 'Pico curvo.'),
(26, 'Capacidad de 1 lt.'),
(27, 'Pico cebador.'),
(28, 'Pico cebador 360 grados.'),
(29, 'Capacidad de 739 ml.'),
(30, 'Tecnología Thermolock.'),
(31, 'Calidad por excelencia.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `carrito_id` int(10) UNSIGNED NOT NULL,
  `usuario_fk` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `categoria_id` tinyint(3) UNSIGNED NOT NULL,
  `categoria_nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`categoria_id`, `categoria_nombre`) VALUES
(2, 'Bombillas'),
(3, 'Mates'),
(4, 'Termos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_item_carrito`
--

CREATE TABLE `detalle_item_carrito` (
  `producto_fk` int(10) UNSIGNED NOT NULL,
  `carrito_fk` int(10) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden`
--

CREATE TABLE `orden` (
  `orden_id` int(10) UNSIGNED NOT NULL,
  `orden_estado_fk` tinyint(3) UNSIGNED NOT NULL,
  `usuario_fk` int(10) UNSIGNED NOT NULL,
  `fecha_pedido` datetime NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `orden`
--

INSERT INTO `orden` (`orden_id`, `orden_estado_fk`, `usuario_fk`, `fecha_pedido`, `total`) VALUES
(1, 2, 2, '2023-08-06 12:49:35', 4350.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_estado`
--

CREATE TABLE `orden_estado` (
  `orden_estado_id` tinyint(3) UNSIGNED NOT NULL,
  `estado_nombre` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `orden_estado`
--

INSERT INTO `orden_estado` (`orden_estado_id`, `estado_nombre`) VALUES
(1, 'Pendiente de pago'),
(2, 'En preparación'),
(3, 'Despachado'),
(4, 'Entregado'),
(5, 'Devuelto'),
(6, 'Anulado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_tiene_productos`
--

CREATE TABLE `orden_tiene_productos` (
  `producto_fk` int(10) UNSIGNED NOT NULL,
  `orden_fk` int(10) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `orden_tiene_productos`
--

INSERT INTO `orden_tiene_productos` (`producto_fk`, `orden_fk`, `cantidad`, `subtotal`) VALUES
(5, 1, 1, 2350.00),
(7, 1, 2, 2000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `producto_id` int(10) UNSIGNED NOT NULL,
  `categoria_fk` tinyint(3) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `precio` decimal(6,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `imagen_descripcion` varchar(255) DEFAULT NULL,
  `recomendado` bit(1) NOT NULL,
  `mostrar` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`producto_id`, `categoria_fk`, `usuario_id`, `nombre`, `descripcion`, `precio`, `imagen`, `imagen_descripcion`, `recomendado`, `mostrar`) VALUES
(1, 3, 1, 'Mate imperial', 'Mate uruguayo de calabaza forrada en cuero con virola  y guarda de alpaca cincelada a mano.', 6800.00, 'mate-imperial.jpg', 'Mate imperial', b'0', b'1'),
(2, 3, 1, 'Mate criollo', 'Mate de cerámica con bombilla y caja.', 2700.00, 'mate-criollo.jpg', 'Mate criollo', b'1', b'1'),
(3, 3, 1, 'Mate stanley', 'Mate de acero inoxidable.', 7700.00, 'mate-stanley.jpg', 'Mate stanley', b'1', b'1'),
(4, 3, 1, 'Mate torpedo', 'Mate con virola de alpaca cincelada.', 7700.00, 'mate-torpedo.jpg', 'Mate torpedo', b'0', b'1'),
(5, 3, 1, 'Mate pampa', 'Mate de PVC (policloruro de vinilo).', 2350.00, 'mate-pampa.jpg', 'Mate pampa', b'1', b'1'),
(6, 2, 1, 'Bombilla pico rey', 'Bombilla pico rey de alpaca.', 3300.00, 'bombilla-picorey.jpg', 'Bombilla pico rey', b'0', b'1'),
(7, 2, 1, 'Bombilla chata', 'Bombilla chata de acero con dije.', 1000.00, 'bombilla-chata.jpg', 'Bombilla chata', b'0', b'1'),
(8, 2, 1, 'Bombilla pico loro', 'Bombillón pico loro.', 3300.00, 'bombilla-picoloro.jpg', 'Bombilla pico loro', b'0', b'1'),
(9, 4, 1, 'Termo acero', 'Termo de acero.', 3100.00, 'termo-acero.jpg', 'Termo acero', b'0', b'1'),
(10, 4, 1, 'Termo contigo', 'Termo contigo, tecnología thermolock.', 8500.00, 'termo-contigo.jpg', 'Termo contigo', b'0', b'1'),
(11, 4, 1, 'Termo lumilagro', 'Termo lumilagro luminox.', 7900.00, 'termo-lumilagro.jpg', 'Termo lumilagro', b'0', b'1'),
(12, 3, 1, 'Mate camionero', 'Mate uruguayo de calabaza forrada en cuero con virola de acero inoxidable.', 3250.00, '20230806125603-mate-camionero.jpg', 'Mate camionero', b'0', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_tienen_caracteristicas`
--

CREATE TABLE `productos_tienen_caracteristicas` (
  `producto_id` int(10) UNSIGNED NOT NULL,
  `caracteristicas_id` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos_tienen_caracteristicas`
--

INSERT INTO `productos_tienen_caracteristicas` (`producto_id`, `caracteristicas_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 5),
(2, 6),
(2, 7),
(2, 8),
(3, 9),
(3, 10),
(3, 11),
(3, 12),
(3, 13),
(4, 1),
(4, 3),
(4, 14),
(5, 7),
(5, 15),
(5, 16),
(5, 17),
(5, 18),
(6, 19),
(6, 20),
(6, 21),
(7, 9),
(7, 23),
(7, 24),
(8, 19),
(8, 20),
(8, 25),
(9, 22),
(9, 26),
(9, 27),
(10, 28),
(10, 29),
(10, 30),
(11, 26),
(11, 28),
(11, 31),
(12, 1),
(12, 2),
(12, 3),
(12, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recuperar_contrasena`
--

CREATE TABLE `recuperar_contrasena` (
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `token` varchar(255) NOT NULL,
  `expiracion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `rol_id` tinyint(3) UNSIGNED NOT NULL,
  `rol_nombre` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`rol_id`, `rol_nombre`) VALUES
(1, 'Administrador'),
(2, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `rol_id` tinyint(3) UNSIGNED NOT NULL DEFAULT 2,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `rol_id`, `email`, `contrasena`, `nombre`, `apellido`) VALUES
(1, 1, 'admin@tu-mate.com', '$2y$10$gqLP/C.5o4G/JTFc3ch2HeE/F25m4sMDfkVL9gsnve2A6HQLQTfWK', 'Julieta', 'Soler'),
(2, 2, 'valen@mail.com', '$2y$10$2EL2J1nj4vPlYlWo2KMRfu25aNUlQ3xSFVhuWNUI8F0RxP3XmMMAq', 'Valentina', 'Moreno');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `caracteristicas`
--
ALTER TABLE `caracteristicas`
  ADD PRIMARY KEY (`caracteristicas_id`);

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`carrito_id`),
  ADD KEY `carrito_usuarios_fk_idx` (`usuario_fk`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`categoria_id`);

--
-- Indices de la tabla `detalle_item_carrito`
--
ALTER TABLE `detalle_item_carrito`
  ADD PRIMARY KEY (`producto_fk`,`carrito_fk`),
  ADD KEY `detalle_item_carrito_tiene_productos_idx` (`producto_fk`),
  ADD KEY `detalle_item_carrito_tiene_carrito_idx` (`carrito_fk`);

--
-- Indices de la tabla `orden`
--
ALTER TABLE `orden`
  ADD PRIMARY KEY (`orden_id`),
  ADD KEY `orden_usuario_fk_idx` (`usuario_fk`),
  ADD KEY `orden_estado_orden_fk_idx` (`orden_estado_fk`);

--
-- Indices de la tabla `orden_estado`
--
ALTER TABLE `orden_estado`
  ADD PRIMARY KEY (`orden_estado_id`);

--
-- Indices de la tabla `orden_tiene_productos`
--
ALTER TABLE `orden_tiene_productos`
  ADD PRIMARY KEY (`producto_fk`,`orden_fk`),
  ADD KEY `orden_orden_tiene_productos_fk_idx` (`orden_fk`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`producto_id`),
  ADD KEY `productos_usuarios_fk_idx` (`usuario_id`),
  ADD KEY `productos_categoria_fk_idx` (`categoria_fk`);

--
-- Indices de la tabla `productos_tienen_caracteristicas`
--
ALTER TABLE `productos_tienen_caracteristicas`
  ADD PRIMARY KEY (`producto_id`,`caracteristicas_id`),
  ADD KEY `productos_productos_tienen_caracteristicas_fk_idx` (`producto_id`),
  ADD KEY `caracteristicas_productos_tienen_caracteristicas_fk_idx` (`caracteristicas_id`);

--
-- Indices de la tabla `recuperar_contrasena`
--
ALTER TABLE `recuperar_contrasena`
  ADD PRIMARY KEY (`usuario_id`),
  ADD KEY `fk_recuperar_contrasena_usuarios1_idx` (`usuario_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`rol_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD KEY `fk_usuarios_roles1_idx` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `caracteristicas`
--
ALTER TABLE `caracteristicas`
  MODIFY `caracteristicas_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `carrito_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `categoria_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `orden`
--
ALTER TABLE `orden`
  MODIFY `orden_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `orden_estado`
--
ALTER TABLE `orden_estado`
  MODIFY `orden_estado_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `producto_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `rol_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_usuarios_fk` FOREIGN KEY (`usuario_fk`) REFERENCES `usuarios` (`usuario_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_item_carrito`
--
ALTER TABLE `detalle_item_carrito`
  ADD CONSTRAINT `detalle_item_carrito_tiene_carrito` FOREIGN KEY (`carrito_fk`) REFERENCES `carrito` (`carrito_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `detalle_item_carrito_tiene_productos` FOREIGN KEY (`producto_fk`) REFERENCES `productos` (`producto_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `orden`
--
ALTER TABLE `orden`
  ADD CONSTRAINT `orden_orden_estado_fk` FOREIGN KEY (`orden_estado_fk`) REFERENCES `orden_estado` (`orden_estado_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `orden_usuario_fk` FOREIGN KEY (`usuario_fk`) REFERENCES `usuarios` (`usuario_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `orden_tiene_productos`
--
ALTER TABLE `orden_tiene_productos`
  ADD CONSTRAINT `orden_orden_tiene_productos_fk` FOREIGN KEY (`orden_fk`) REFERENCES `orden` (`orden_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `productos_orden_tiene_productos_fk` FOREIGN KEY (`producto_fk`) REFERENCES `productos` (`producto_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_categoria_fk` FOREIGN KEY (`categoria_fk`) REFERENCES `categoria` (`categoria_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `productos_usuarios_fk` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `productos_tienen_caracteristicas`
--
ALTER TABLE `productos_tienen_caracteristicas`
  ADD CONSTRAINT `caracteristicas_productos_tienen_caracteristicas_fk` FOREIGN KEY (`caracteristicas_id`) REFERENCES `caracteristicas` (`caracteristicas_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `productos_productos_tienen_caracteristicas_fk` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`producto_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `recuperar_contrasena`
--
ALTER TABLE `recuperar_contrasena`
  ADD CONSTRAINT `fk_recuperar_contrasena_usuarios1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_roles1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`rol_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
