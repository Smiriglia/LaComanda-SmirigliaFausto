-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 20-02-2024 a las 18:29:31
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

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
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `codigoMesa` varchar(255) NOT NULL,
  `puntaje` int(11) NOT NULL CHECK (`puntaje` between 1 and 5),
  `comentario` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id`, `codigoMesa`, `puntaje`, `comentario`) VALUES
(1, 'XaA2TaX71RxyFla7', 5, 'muy buena comida'),
(2, 'AbFhwZAuFgvmP64l', 3, 'relativamente buena comida');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logtransacciones`
--

CREATE TABLE `logtransacciones` (
  `nroTransaccion` int(11) NOT NULL,
  `fecha` varchar(50) DEFAULT NULL,
  `idUsuario` int(11) DEFAULT NULL,
  `accion` varchar(50) NOT NULL,
  `code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `logtransacciones`
--

INSERT INTO `logtransacciones` (`nroTransaccion`, `fecha`, `idUsuario`, `accion`, `code`) VALUES
(25, '12/12/2023 19:31:24', 3, '/usuarios', 200),
(26, '12/12/2023 19:31:41', 3, '/sesion', 200),
(27, '12/12/2023 19:31:44', -1, '/usuarios', 400),
(28, '12/12/2023 19:41:06', -1, '/transacciones', 400),
(29, '12/12/2023 19:43:59', -1, '/transacciones', 200),
(30, '12/12/2023 19:44:02', -1, '/transacciones', 200),
(31, '12/12/2023 19:45:24', -1, '/transacciones', 400),
(32, '12/12/2023 19:46:51', -1, '/usuarios/id', 400),
(33, '12/12/2023 19:48:56', -1, '/sesion', 200),
(34, '12/12/2023 19:49:01', 3, '/transacciones', 200),
(35, '12/12/2023 19:49:20', 3, '/estadisticas/promedio', 200),
(37, '12/12/2023 23:20:13', 3, '/sesion', 200),
(38, '12/12/2023 23:21:17', 3, '/mesas/', 200),
(39, '12/12/2023 23:25:07', 3, '/pedidos', 200),
(40, '12/12/2023 23:27:59', 3, '/pedidos/por/sector', 200),
(41, '12/12/2023 23:28:23', 3, '/pedidos/sector/preparar/5', 200),
(42, '12/12/2023 23:28:36', 3, '/pedidos/sector/preparado/5', 200),
(43, '12/12/2023 23:29:11', 3, '/pedidos/entregar/pedido/5', 200),
(44, '12/12/2023 23:29:45', 3, '/mesas/', 200),
(45, '12/12/2023 23:29:58', 3, '/cobrar', 200),
(46, '12/12/2023 23:30:34', 3, '/pedidos/sector/preparar/6', 200),
(47, '12/12/2023 23:30:47', 3, '/pedidos/sector/preparado/6', 200),
(48, '12/12/2023 23:30:58', 3, '/pedidos/entregar/pedido/6', 200),
(49, '12/12/2023 23:31:03', 3, '/cobrar', 200),
(50, '12/12/2023 23:31:19', 3, '/mesas/', 200),
(51, '12/12/2023 23:31:27', 3, '/pedidos', 200),
(52, '12/12/2023 23:31:50', 3, '/pedidos/sector/preparar/7', 200),
(53, '12/12/2023 23:31:54', 3, '/pedidos/sector/preparado/7', 200),
(54, '12/12/2023 23:32:00', 3, '/pedidos/entregar/pedido/7', 200),
(55, '12/12/2023 23:32:13', 3, '/cobrar', 200),
(56, '12/12/2023 23:33:12', 3, '/archivos/cargarProductos', 200),
(57, '12/12/2023 23:33:32', 3, '/archivos/descargarPedidos', 200),
(58, '12/12/2023 23:34:01', 3, '/archivos/descargarUsuarios', 200),
(59, '12/12/2023 23:37:08', 3, '/transacciones', 200),
(60, '12/12/2023 23:37:35', 3, '/usuarios/id', 200),
(61, '13/02/2024 18:55:04', -1, '/admin', 200),
(62, '13/02/2024 18:55:09', -1, '/sesion', 200),
(63, '13/02/2024 18:55:22', 3, '/sesion', 200),
(64, '13/02/2024 19:00:59', -1, '/mesas/', 400),
(65, '13/02/2024 19:01:04', -1, '/sesion', 200),
(66, '13/02/2024 19:01:08', 3, '/mesas/', 200),
(67, '13/02/2024 19:01:41', 3, '/sesion', 200),
(68, '13/02/2024 19:01:46', -1, '/sesion', 200),
(69, '15/02/2024 20:30:49', -1, '/sesion', 200),
(70, '15/02/2024 20:32:05', 3, '/transacciones', 200),
(71, '15/02/2024 20:41:18', 3, '/pedidos/por/sector', 200),
(72, '15/02/2024 20:41:57', 3, '/sesion', 200),
(73, '15/02/2024 20:42:16', -1, '/sesion', 200),
(74, '15/02/2024 20:42:18', -1, '/sesion', 200),
(75, '15/02/2024 20:46:07', -1, '/sesion', 200),
(76, '15/02/2024 20:46:13', -1, '/sesion', 200),
(77, '15/02/2024 20:46:18', 2, '/pedidos', 400),
(78, '15/02/2024 20:46:24', 2, '/mesas/', 400),
(79, '15/02/2024 20:46:29', 2, '/sesion', 200),
(80, '15/02/2024 20:46:33', 3, '/mesas/id', 200),
(81, '15/02/2024 20:46:37', 3, '/mesas/', 200),
(82, '15/02/2024 20:46:44', 3, '/pedidos', 200),
(83, '15/02/2024 21:19:12', 3, '/productos/', 200),
(84, '20/02/2024 02:52:47', -1, '/sesion', 200),
(85, '20/02/2024 02:53:10', 3, '/comentar', 400),
(86, '20/02/2024 02:53:27', 3, '/comentar', 400),
(87, '20/02/2024 02:56:08', 3, '/comentar', 400),
(88, '20/02/2024 02:56:15', 3, '/comentar', 400),
(89, '20/02/2024 02:56:22', 3, '/comentar', 400),
(90, '20/02/2024 02:56:36', 3, '/comentar', 400),
(91, '20/02/2024 02:56:44', 3, '/comentar', 400),
(92, '20/02/2024 03:14:56', 3, '/comentar', 400),
(93, '20/02/2024 03:15:04', 3, '/comentar', 200),
(94, '20/02/2024 03:15:06', 3, '/comentar', 400),
(95, '20/02/2024 03:52:44', 3, '/sesion', 200),
(96, '20/02/2024 03:53:00', -1, '/sesion', 200),
(97, '20/02/2024 03:53:06', 2, '/comentar', 400),
(98, '20/02/2024 03:53:14', 2, '/sesion', 200),
(99, '20/02/2024 03:56:10', 3, '/mejores-comentarios', 400),
(100, '20/02/2024 03:56:18', 3, '/mejores-comentarios', 200),
(101, '20/02/2024 04:05:08', 3, '/mejores-comentarios', 200),
(102, '20/02/2024 04:05:55', 3, '/comentar', 400),
(103, '20/02/2024 04:06:05', 3, '/comentar', 400),
(104, '20/02/2024 04:06:17', 3, '/comentar', 200),
(105, '20/02/2024 04:06:21', 3, '/mejores-comentarios', 200),
(106, '20/02/2024 04:06:36', 3, '/mejores-comentarios', 200),
(107, '20/02/2024 04:06:45', 3, '/mejores-comentarios', 200),
(108, '20/02/2024 04:43:19', 3, '/pedidos/fuera-de-tiempo', 200),
(109, '20/02/2024 04:51:37', 3, '/pedidos/fuera-de-tiempo', 200),
(110, '20/02/2024 04:57:22', 3, '/pedidos/fuera-de-tiempo', 200),
(111, '20/02/2024 05:01:23', 3, '/pedidos/fuera-de-tiempo', 200),
(112, '20/02/2024 05:07:36', 3, '/pedidos/fuera-de-tiempo', 200),
(113, '20/02/2024 05:11:06', 3, '/pedidos/fuera-de-tiempo', 200),
(114, '20/02/2024 05:12:13', 3, '/pedidos/fuera-de-tiempo', 200),
(115, '20/02/2024 05:14:10', 3, '/pedidos/fuera-de-tiempo', 200),
(116, '20/02/2024 05:14:22', 3, '/pedidos/fuera-de-tiempo', 200),
(117, '20/02/2024 05:14:26', 3, '/pedidos/fuera-de-tiempo', 200),
(118, '20/02/2024 05:18:18', 3, '/pedidos/fuera-de-tiempo', 200),
(119, '20/02/2024 05:46:12', 3, '/exportar-logo', 400),
(120, '20/02/2024 05:48:19', 3, '/exportar-logo', 400),
(121, '20/02/2024 05:49:25', 3, '/exportar-logo', 400),
(122, '20/02/2024 05:50:54', 3, '/exportar-logo', 400),
(123, '20/02/2024 05:51:54', 3, '/exportar-logo', 400),
(124, '20/02/2024 05:56:07', 3, '/exportar-logo', 400),
(125, '20/02/2024 05:56:38', 3, '/exportar-logo', 200),
(126, '20/02/2024 06:09:19', 3, '/cantidad-operaciones', 400),
(127, '20/02/2024 06:09:33', 3, '/usuarios/cantidad-operaciones', 400),
(128, '20/02/2024 06:21:52', 3, '/usuarios/cantidad-operaciones', 200),
(129, '20/02/2024 06:22:59', 3, '/usuarios/cantidad-operaciones', 200),
(130, '20/02/2024 06:24:51', 3, '/usuarios/cantidad-operaciones', 200),
(131, '20/02/2024 06:39:23', 3, '/usuarios/cantidad-operaciones', 200),
(132, '20/02/2024 06:39:58', 3, '/sesion', 200),
(133, '20/02/2024 06:40:01', 4, '/pedidos/codigo', 200),
(134, '20/02/2024 06:40:05', 4, '/pedidos', 200),
(135, '20/02/2024 06:40:11', 4, '/pedidos/por/sector', 200),
(136, '20/02/2024 06:40:25', 4, '/sesion', 200),
(137, '20/02/2024 06:40:28', 3, '/usuarios/cantidad-operaciones', 200),
(138, '20/02/2024 07:20:32', 3, '/cantidad-operaciones-usuarios', 400),
(139, '20/02/2024 07:20:45', 3, '/usuarios/cantidad-operaciones-usuarios', 400),
(140, '20/02/2024 07:21:41', 3, '/usuarios/cantidad-operaciones-usuarios', 200),
(141, '20/02/2024 07:46:45', 3, '/productos/orden-mas-ventas', 200),
(142, '20/02/2024 07:47:17', 3, '/productos/orden-mas-ventas', 200),
(143, '20/02/2024 07:48:03', 3, '/productos/orden-mas-ventas', 200),
(144, '20/02/2024 07:48:54', 3, '/productos/orden-mas-ventas', 200),
(145, '20/02/2024 07:49:30', 3, '/usuarios/cantidad-operaciones-usuarios', 200),
(146, '20/02/2024 07:49:35', 3, '/usuarios/cantidad-operaciones', 200),
(147, '20/02/2024 07:51:54', 3, '/productos/orden-mas-ventas', 200),
(148, '20/02/2024 14:03:53', 3, '/sesion', 200),
(149, '20/02/2024 14:04:20', -1, '/sesion', 200),
(150, '20/02/2024 14:58:08', 3, '/sesion', 200),
(151, '20/02/2024 14:58:17', 3, '/sesion', 200),
(152, '20/02/2024 15:15:28', 4, '/usuarios/registro-login', 400),
(153, '20/02/2024 15:15:36', 4, '/sesion', 200),
(154, '20/02/2024 15:15:41', 3, '/usuarios/registro-login', 400),
(155, '20/02/2024 15:15:47', 3, '/usuarios/registro-login', 400),
(156, '20/02/2024 15:15:57', 3, '/usuarios/registro-login', 200),
(157, '20/02/2024 15:16:01', 3, '/usuarios/registro-login', 200),
(158, '20/02/2024 15:16:04', 3, '/usuarios/registro-login', 200),
(159, '20/02/2024 15:38:29', 3, '/cobrar', 200),
(160, '20/02/2024 15:38:44', 3, '/cobrar', 200),
(161, '20/02/2024 15:41:15', 3, '/cobrar', 200),
(162, '20/02/2024 15:41:28', 3, '/cobrar', 200),
(163, '20/02/2024 15:41:46', 3, '/cobrar', 200),
(164, '20/02/2024 15:42:09', 3, '/mesas/', 200),
(165, '20/02/2024 15:52:30', 3, '/usuarios/registro-login', 200),
(166, '20/02/2024 15:53:04', 3, '/mesas/orden-menor-factura', 200),
(167, '20/02/2024 15:53:32', 3, '/mesas/orden-menor-factura', 200),
(168, '20/02/2024 17:22:12', 3, '/mesas/facturacion-entre-fechas', 400),
(169, '20/02/2024 17:22:23', 3, '/mesas/facturacion-entre-fechas', 400),
(170, '20/02/2024 17:22:39', 3, '/mesas/facturacion-entre-fechas', 400),
(171, '20/02/2024 17:22:45', 3, '/mesas/facturacion-entre-fechas', 400),
(172, '20/02/2024 17:22:50', 3, '/mesas/facturacion-entre-fechas', 400),
(173, '20/02/2024 17:22:50', 3, '/mesas/facturacion-entre-fechas', 400),
(174, '20/02/2024 17:23:03', 3, '/mesas/facturacion-entre-fechas', 400),
(175, '20/02/2024 17:23:10', 3, '/mesas/facturacion-entre-fechas', 400),
(176, '20/02/2024 17:25:28', 3, '/mesas/facturacion-entre-fechas', 400),
(177, '20/02/2024 17:25:47', 3, '/mesas/facturacion-entre-fechas', 200),
(178, '20/02/2024 17:25:53', 3, '/mesas/facturacion-entre-fechas', 200),
(179, '20/02/2024 17:26:16', 3, '/mesas/facturacion-entre-fechas', 200),
(180, '20/02/2024 17:26:19', 3, '/mesas/facturacion-entre-fechas', 200),
(181, '20/02/2024 17:26:22', 3, '/mesas/facturacion-entre-fechas', 200),
(182, '20/02/2024 17:26:27', 3, '/mesas/facturacion-entre-fechas', 200),
(183, '20/02/2024 17:26:33', 3, '/mesas/facturacion-entre-fechas', 200),
(184, '20/02/2024 17:26:36', 3, '/mesas/facturacion-entre-fechas', 200),
(185, '20/02/2024 17:26:45', 3, '/mesas/facturacion-entre-fechas', 200);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `nombreMozo` varchar(255) DEFAULT NULL,
  `cobro` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `codigo`, `estado`, `nombreMozo`, `cobro`) VALUES
(1, 'XaA2TaX71RxyFla7', 'cerrada', 'admin', 3000),
(3, 'tKaeVsH4U9oPB2ph', 'cerrada', 'admin', 2000),
(4, 'AbFhwZAuFgvmP64l', 'cerrada', 'admin', 1000),
(5, 'jw1k0F5wWA7RZN5R', 'en uso', 'admin', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `codigoPedido` varchar(255) DEFAULT NULL,
  `idMesa` varchar(255) DEFAULT NULL,
  `idProducto` int(11) DEFAULT NULL,
  `nombreCliente` varchar(255) DEFAULT NULL,
  `sector` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `importe` decimal(10,2) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `fechaInicio` varchar(50) DEFAULT NULL,
  `fechaCierre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `codigoPedido`, `idMesa`, `idProducto`, `nombreCliente`, `sector`, `estado`, `importe`, `cantidad`, `fechaInicio`, `fechaCierre`) VALUES
(1, '96KUVzarMvqCyire', '0', 5, 'Pepita', 'cocina', 'pendiente', 2000.00, 2, '2023-12-12 15:28:15', NULL),
(2, '96KUVzarMvqCyire', '0', 2, 'Pepita', 'cocina', 'pendiente', 5600.00, 2, '2023-12-12 15:30:51', NULL),
(3, '96KUVzarMvqCyire', '0', 5, 'Pepita', 'cocina', 'pendiente', 2000.00, 2, '2023-12-12 15:45:07', NULL),
(4, '96KUVzarMvqCyire', '0', 5, 'Pepita', 'cocina', 'completado', 2000.00, 2, '2023-12-12 15:49:07', '2023-12-12 16:33:17'),
(5, '96KUVzarMvqCyire', 'XaA2TaX71RxyFla7', 5, 'Juanita', 'cocina', 'completado', 1000.00, 1, '2023-12-12 15:51:04', '2023-12-12 16:08:14'),
(6, 'xyU44XKL9SDEyMro', 'tKaeVsH4U9oPB2ph', 5, 'Pepita', 'cocina', 'completado', 2000.00, 2, '2023-12-12 23:25:07', '2023-12-12 23:56:17'),
(7, 'pyhKhBAL9WkagLMb', 'AbFhwZAuFgvmP64l', 5, 'Pepita', 'cocina', 'completado', 2000.00, 2, '2023-12-12 23:31:27', '2023-12-12 23:43:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  `tiempoPreparacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `tipo`, `precio`, `tiempoPreparacion`) VALUES
(2, 'Milanesa a caballo', 'comida', 2800, 45),
(3, 'Palta', 'comida', 10000, 1),
(4, 'nachos', 'comida', 1000, 15),
(5, 'Hamburguesa', 'comida', 1000, 20),
(6, 'Ribs', 'comida', 15000, 120),
(8, 'Coca-Cola', 'Bebida', 2, 11),
(9, 'Hamburguesa con Queso', 'Comida', 6, 12),
(10, 'Ensalada Caesar', 'Ensalada', 4, 13),
(11, 'Pizza Margarita', 'Comida', 9, 14),
(12, 'Helado de Vainilla', 'Postre', 4, 15),
(13, 'Papas Fritas', 'Aperitivo', 3, 10),
(14, 'Coca-Cola', 'Bebida', 2, 11),
(15, 'Hamburguesa con Queso', 'Comida', 6, 12),
(16, 'Ensalada Caesar', 'Ensalada', 4, 13),
(17, 'Pizza Margarita', 'Comida', 9, 14),
(18, 'Helado de Vainilla', 'Postre', 4, 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registrologin`
--

CREATE TABLE `registrologin` (
  `id` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `fechaConexion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registrologin`
--

INSERT INTO `registrologin` (`id`, `idUsuario`, `fechaConexion`) VALUES
(1, 3, '2024-02-20 14:58:08'),
(2, 4, '2024-02-20 14:58:17'),
(3, 3, '2024-02-20 15:15:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `clave` varchar(255) DEFAULT NULL,
  `rol` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `fechaInicio` varchar(50) DEFAULT NULL,
  `fechaFinalizacion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `clave`, `rol`, `estado`, `fechaInicio`, `fechaFinalizacion`) VALUES
(-1, 'desconocido', 'desconocido@gmail.com', '$2y$10$0Nk.StnCw/pbjeFYeNckqOr5S.IAp731pP6ptr8KVZYA9rakW/7KO', 'desconocido', 'activo', '2023-12-12', NULL),
(2, 'FaustoSmiriglia', 'ejemplo2@gmail.com', '$2y$10$MDCj.uxlQIK0NvS074ZUb.5jgFDZ8Pzyv6nRZ1cA55Xml1oYddliC', 'cocinero', 'activo', '2023-12-12', NULL),
(3, 'admin', 'ejemplo@gmail.com', '$2y$10$84793EpK3lqgPqxsn5687.6fOaAq5hwWpvSOLyYGus3MQU/E84Rqm', 'socio', 'activo', '2023-12-12', NULL),
(4, 'Hooper', 'ejemplo3@gmail.com', '$2y$10$QPFzTMk.x7/1BwroiEr0qO2E.Cr/FPaenPEAKIaymD4qZ4Q75QVk2', 'mozo', 'activo', '2023-12-12', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `logtransacciones`
--
ALTER TABLE `logtransacciones`
  ADD PRIMARY KEY (`nroTransaccion`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idProducto` (`idProducto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registrologin`
--
ALTER TABLE `registrologin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `logtransacciones`
--
ALTER TABLE `logtransacciones`
  MODIFY `nroTransaccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `registrologin`
--
ALTER TABLE `registrologin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `logtransacciones`
--
ALTER TABLE `logtransacciones`
  ADD CONSTRAINT `logtransacciones_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `registrologin`
--
ALTER TABLE `registrologin`
  ADD CONSTRAINT `registrologin_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
