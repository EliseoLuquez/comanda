-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-12-2020 a las 02:12:45
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
-- Base de datos: `comanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_empleado`
--

CREATE TABLE `estado_empleado` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `estado_empleado`
--

INSERT INTO `estado_empleado` (`id`, `descripcion`) VALUES
(1, 'activo'),
(2, 'suspendido'),
(3, 'baja');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_mesa`
--

CREATE TABLE `estado_mesa` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `estado_mesa`
--

INSERT INTO `estado_mesa` (`id`, `descripcion`) VALUES
(1, 'cerrada'),
(2, 'con cliente esperando pedido'),
(3, 'con clientes comiendo'),
(4, 'con clientes pagando');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_pedido`
--

CREATE TABLE `estado_pedido` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `estado_pedido`
--

INSERT INTO `estado_pedido` (`id`, `descripcion`) VALUES
(1, 'pendiente'),
(2, 'en preparacion'),
(3, 'finalizado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `codigo` int(5) NOT NULL,
  `estado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `codigo`, `estado_id`) VALUES
(1, 87304, 1),
(2, 60227, 1),
(3, 70635, 1),
(4, 93240, 1),
(5, 51580, 1),
(6, 59541, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden`
--

CREATE TABLE `orden` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `cantidad` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `precio` decimal(10,0) NOT NULL,
  `empleado_id` int(11) DEFAULT NULL,
  `sector_id` int(11) NOT NULL,
  `estado_id` int(11) NOT NULL,
  `demora` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `orden`
--

INSERT INTO `orden` (`id`, `descripcion`, `cantidad`, `pedido_id`, `precio`, `empleado_id`, `sector_id`, `estado_id`, `demora`) VALUES
(10, 'Cerveza', 2, 6, '500', 6, 2, 3, '2020-12-01 14:32:41'),
(11, 'Pizza', 2, 6, '1100', 4, 3, 2, '2020-12-01 14:32:13'),
(12, 'Empanadas', 6, 6, '270', 4, 3, 3, '2020-12-01 14:06:17'),
(13, 'Fatay', 6, 6, '270', 4, 3, 2, '2020-12-01 14:30:12'),
(14, 'Tarta', 1, 6, '150', 4, 3, 2, '2020-12-01 14:29:57'),
(15, 'vino', 1, 6, '700', 5, 1, 2, '2020-12-01 14:22:21'),
(16, 'torta', 1, 6, '250', NULL, 4, 1, NULL),
(17, 'trago', 1, 6, '250', 5, 1, 2, '2020-12-01 14:25:17'),
(18, 'trago', 1, 6, '250', 5, 1, 2, '2020-12-01 14:52:59'),
(19, 'trago', 1, 6, '250', 5, 1, 3, '2020-12-01 14:28:07'),
(20, 'trago', 1, 6, '250', 5, 1, 2, '2020-12-01 15:49:44'),
(21, 'trago', 1, 6, '250', 5, 1, 2, '2020-12-01 15:05:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `cliente` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `codigo` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `demora` datetime DEFAULT NULL,
  `descripcion` varchar(200) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estado_pedido_id` int(11) NOT NULL,
  `mesa_id` int(11) NOT NULL,
  `precio` decimal(10,0) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `cliente`, `codigo`, `demora`, `descripcion`, `foto`, `estado_pedido_id`, `mesa_id`, `precio`, `created_at`, `updated_at`, `deleted_at`) VALUES
(6, 3, 'Romina', 'fa430', '2020-12-01 15:49:44', 'pedido  cliente', NULL, 1, 6, NULL, '2020-12-01 12:46:44', '2020-12-01 14:59:44', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sectores`
--

CREATE TABLE `sectores` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `sectores`
--

INSERT INTO `sectores` (`id`, `descripcion`) VALUES
(1, 'Barra de tragos y vinos'),
(2, 'Barra de choperas y cerveza artesanal'),
(3, 'Cocina'),
(4, 'Candy bar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_empleado`
--

CREATE TABLE `tipo_empleado` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `tipo_empleado`
--

INSERT INTO `tipo_empleado` (`id`, `descripcion`) VALUES
(1, 'Cocinero'),
(2, 'Mozo'),
(3, 'Bartender'),
(4, 'Cervecero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `clave` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `estado_id` int(11) NOT NULL,
  `tipo_usuario` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo_empleado_id` int(11) DEFAULT NULL,
  `sector_id` int(11) DEFAULT NULL,
  `cantidad_operaciones` int(11) DEFAULT NULL,
  `create_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `nombre`, `clave`, `estado_id`, `tipo_usuario`, `tipo_empleado_id`, `sector_id`, `cantidad_operaciones`, `create_at`, `updated_at`, `deleted_at`) VALUES
(1, 'cheo', 'eliseo', '$2y$10$cGjwrrxCcrtGrvmjY2/zOurLXVJCqW3ihJnwUzH9/vZWtOxajXUxq', 1, 'admin', NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL),
(2, 'marito', 'mario', '$2y$10$z3.R7h5ioLgwUQjtLc2YKeXlVrY6b9xFe4WC5iSRrVKTiIuqiMxZq', 1, 'socio', NULL, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL),
(3, 'gonza', 'gonzalo', '$2y$10$ba5xuonFQU0stZq45iyVPuQAu0W0pbZn0A8fcvDhOMvhxecCgbmC2', 1, 'empleado', 2, NULL, NULL, '0000-00-00 00:00:00', NULL, NULL),
(4, 'santi', 'Santiago', '$2y$10$DHmkqxZ3zRXqlmMpBFbEUefrTlheHDu1h4fFYmY9/u0nutb9jHpAu', 1, 'empleado', 1, 3, NULL, '0000-00-00 00:00:00', NULL, NULL),
(5, 'marian', 'Mariano', '$2y$10$Kj8Itz3aLdQ/nvTYqW9qb.lPxKi3ikLyTMFZiWe049AomDx83OvYW', 1, 'empleado', 3, 1, NULL, '0000-00-00 00:00:00', NULL, NULL),
(6, 'nahue', 'Nahuel', '$2y$10$gtJWf6VTlleEANff/jsJEOLoNe8G9.igclkv4XFQHGMPdMtyd5Ylu', 1, 'empleado', 4, 2, NULL, '0000-00-00 00:00:00', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estado_empleado`
--
ALTER TABLE `estado_empleado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_mesa`
--
ALTER TABLE `estado_mesa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_pedido`
--
ALTER TABLE `estado_pedido`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orden`
--
ALTER TABLE `orden`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sectores`
--
ALTER TABLE `sectores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_empleado`
--
ALTER TABLE `tipo_empleado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estado_empleado`
--
ALTER TABLE `estado_empleado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estado_mesa`
--
ALTER TABLE `estado_mesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estado_pedido`
--
ALTER TABLE `estado_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `orden`
--
ALTER TABLE `orden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `sectores`
--
ALTER TABLE `sectores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipo_empleado`
--
ALTER TABLE `tipo_empleado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
