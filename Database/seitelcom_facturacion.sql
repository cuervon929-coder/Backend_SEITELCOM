-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-11-2026 a las 01:41:55
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
-- Base de datos: `seitelcom_facturacion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo_documento` varchar(20) NOT NULL,
  `numero_documento` varchar(30) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nombre`, `tipo_documento`, `numero_documento`, `correo`, `telefono`, `direccion`) VALUES
(1, 'Erika Rios', 'CC', '1001234567', 'erika@example.com', '3000000001', 'Calle 10 # 15-20'),
(2, 'Yesmi Gomez', 'CC', '1002345678', 'yesmi@example.com', '3000000002', 'Carrera 12 # 20-15'),
(3, 'Jose Cuervo', 'CC', '1003456789', 'jose@example.com', '3000000003', 'Calle 25 # 8-30'),
(4, 'Humberto Rios', 'CC', '1004567890', 'humberto@example.com', '3000000004', 'Carrera 18 # 12-40'),
(5, 'Maribel Arias', 'CC', '1005678901', 'maribel@example.com', '3000000005', 'Calle 30 # 10-25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_servicio`
--

CREATE TABLE `cliente_servicio` (
  `id_cliente_servicio` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `cliente_servicio`
--

INSERT INTO `cliente_servicio` (`id_cliente_servicio`, `id_cliente`, `id_servicio`, `fecha_inicio`, `estado`) VALUES
(8, 1, 1, '2026-11-01', 'activo'),
(9, 2, 1, '2026-11-01', 'activo'),
(10, 2, 3, '2026-11-01', 'activo'),
(11, 3, 2, '2026-11-01', 'activo'),
(12, 4, 2, '2026-11-01', 'activo'),
(13, 4, 4, '2026-11-01', 'activo'),
(14, 5, 1, '2026-11-01', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id_factura` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `monto_total` decimal(12,2) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`id_factura`, `id_cliente`, `id_usuario`, `fecha_emision`, `fecha_vencimiento`, `monto_total`, `estado`) VALUES
(2, 1, 1, '2026-11-01', '2026-11-30', 75000.00, 'pagada'),
(3, 2, 1, '2026-11-01', '2026-11-30', 110000.00, 'pagada'),
(4, 3, 1, '2026-11-01', '2026-11-30', 95000.00, 'pagada'),
(5, 4, 1, '2026-11-01', '2026-11-30', 140000.00, 'pendiente'),
(6, 5, 1, '2026-11-01', '2026-11-30', 75000.00, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_pagos`
--

CREATE TABLE `historial_pagos` (
  `id_historial` int(11) NOT NULL,
  `id_pago` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `historial_pagos`
--

INSERT INTO `historial_pagos` (`id_historial`, `id_pago`, `fecha_registro`, `descripcion`) VALUES
(1, 1, '2026-11-16 10:00:00', 'Pago aprobado mediante PSE por valor de $75.000'),
(2, 2, '2026-11-16 10:30:00', 'Pago aprobado mediante Nequi por valor de $110.000'),
(3, 3, '2026-11-16 11:00:00', 'Pago aprobado mediante Daviplata por valor de $95.000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion`
--

CREATE TABLE `notificacion` (
  `id_notificacion` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_pago` int(11) DEFAULT NULL,
  `mensaje` varchar(255) NOT NULL,
  `fecha_envio` datetime NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `notificacion`
--

INSERT INTO `notificacion` (`id_notificacion`, `id_cliente`, `id_pago`, `mensaje`, `fecha_envio`, `tipo`, `estado`) VALUES
(1, 1, 1, 'Tu pago de $75.000 fue aprobado correctamente mediante PSE.', '2026-11-16 10:05:00', 'pago', 'enviada'),
(2, 2, 2, 'Tu pago de $110.000 fue aprobado correctamente mediante Nequi.', '2026-11-16 10:35:00', 'pago', 'enviada'),
(3, 3, 3, 'Tu pago de $95.000 fue aprobado correctamente mediante Daviplata.', '2026-11-16 11:05:00', 'pago', 'enviada'),
(4, 4, NULL, 'Tienes una factura pendiente de $140.000 con vencimiento el 30 de noviembre de 2026.', '2026-11-16 11:30:00', 'recordatorio', 'pendiente'),
(5, 5, NULL, 'Tienes una factura pendiente de $75.000 con vencimiento el 30 de noviembre de 2026.', '2026-11-16 11:35:00', 'recordatorio', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_plataforma` int(11) DEFAULT NULL,
  `fecha_pago` datetime NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `metodo` varchar(50) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `pago`
--

INSERT INTO `pago` (`id_pago`, `id_factura`, `id_plataforma`, `fecha_pago`, `monto`, `metodo`, `estado`) VALUES
(1, 2, 1, '2026-11-16 10:00:00', 75000.00, 'PSE', 'aprobado'),
(2, 3, 2, '2026-11-16 10:30:00', 110000.00, 'Nequi', 'aprobado'),
(3, 4, 3, '2026-11-16 11:00:00', 95000.00, 'Daviplata', 'aprobado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plataforma_pago`
--

CREATE TABLE `plataforma_pago` (
  `id_plataforma` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `version` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `plataforma_pago`
--

INSERT INTO `plataforma_pago` (`id_plataforma`, `nombre`, `version`) VALUES
(1, 'PSE', '1.0'),
(2, 'Nequi', '1.0'),
(3, 'Daviplata', '1.0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `id_servicio` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `plan` varchar(100) NOT NULL,
  `precio` decimal(12,2) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'activo',
  `costo_mensual` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`id_servicio`, `tipo`, `plan`, `precio`, `estado`, `costo_mensual`) VALUES
(1, 'Internet', 'Internet Básico 100 Mbps', 75000.00, 'activo', 75000.00),
(2, 'Internet', 'Internet Premium 300 Mbps', 95000.00, 'activo', 95000.00),
(3, 'Televisión', 'Televisión Digital', 35000.00, 'activo', 35000.00),
(4, 'Televisión', 'Televisión Premium', 45000.00, 'activo', 45000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `usuario`, `contrasena_hash`) VALUES
(1, 'admin_seitelcom', '123456*');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `cliente_servicio`
--
ALTER TABLE `cliente_servicio`
  ADD PRIMARY KEY (`id_cliente_servicio`),
  ADD KEY `cliente_servicio_ibfk_1` (`id_servicio`),
  ADD KEY `fk_cliente_servicio_cliente` (`id_cliente`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `fk_factura_cliente` (`id_cliente`),
  ADD KEY `fk_factura_usuario` (`id_usuario`);

--
-- Indices de la tabla `historial_pagos`
--
ALTER TABLE `historial_pagos`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `fk_historial_pago` (`id_pago`);

--
-- Indices de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `fk_notificacion_cliente` (`id_cliente`),
  ADD KEY `fk_notificacion_pago` (`id_pago`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `fk_pago_factura` (`id_factura`),
  ADD KEY `fk_pago_plataforma` (`id_plataforma`);

--
-- Indices de la tabla `plataforma_pago`
--
ALTER TABLE `plataforma_pago`
  ADD PRIMARY KEY (`id_plataforma`);

--
-- Indices de la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`id_servicio`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `cliente_servicio`
--
ALTER TABLE `cliente_servicio`
  MODIFY `id_cliente_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `historial_pagos`
--
ALTER TABLE `historial_pagos`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `plataforma_pago`
--
ALTER TABLE `plataforma_pago`
  MODIFY `id_plataforma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente_servicio`
--
ALTER TABLE `cliente_servicio`
  ADD CONSTRAINT `cliente_servicio_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`),
  ADD CONSTRAINT `fk_cliente_servicio_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `fk_factura_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `fk_factura_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `historial_pagos`
--
ALTER TABLE `historial_pagos`
  ADD CONSTRAINT `fk_historial_pago` FOREIGN KEY (`id_pago`) REFERENCES `pago` (`id_pago`);

--
-- Filtros para la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD CONSTRAINT `fk_notificacion_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `fk_notificacion_pago` FOREIGN KEY (`id_pago`) REFERENCES `pago` (`id_pago`) ON DELETE SET NULL;

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `fk_pago_factura` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id_factura`),
  ADD CONSTRAINT `fk_pago_plataforma` FOREIGN KEY (`id_plataforma`) REFERENCES `plataforma_pago` (`id_plataforma`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
