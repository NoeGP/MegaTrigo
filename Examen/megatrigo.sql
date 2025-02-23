-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-02-2025 a las 21:45:40
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
-- Base de datos: `megatrigo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria_remision`
--

CREATE TABLE `auditoria_remision` (
  `id_auditoria` int(11) NOT NULL,
  `id_nota` int(11) NOT NULL,
  `accion` varchar(50) NOT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auditoria_remision`
--

INSERT INTO `auditoria_remision` (`id_auditoria`, `id_nota`, `accion`, `usuario`, `fecha`) VALUES
(19, 1, 'INSERT', 'Noe', '2025-02-23 05:02:38'),
(20, 2, 'INSERT', 'Noe', '2025-02-23 05:20:48'),
(21, 3, 'INSERT', 'Noe', '2025-02-23 05:35:52'),
(22, 4, 'INSERT', 'Noe', '2025-02-23 19:31:31'),
(29, 5, 'INSERT', 'Noe', '2025-02-23 20:41:26'),
(30, 5, 'DELETE', 'Noe', '2025-02-23 20:42:32'),
(31, 4, 'DELETE', 'Noe', '2025-02-23 20:42:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas_remision`
--

CREATE TABLE `notas_remision` (
  `id_nota` int(11) NOT NULL,
  `fecha_emision` date NOT NULL,
  `nombre_cliente` varchar(100) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT 0.00,
  `firma` varchar(255) DEFAULT NULL,
  `id_vendedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notas_remision`
--

INSERT INTO `notas_remision` (`id_nota`, `fecha_emision`, `nombre_cliente`, `direccion`, `total`, `firma`, `id_vendedor`) VALUES
(1, '2025-02-23', 'Richard', 'Tumbaco', 322.00, 'risaje', 1),
(2, '2025-02-23', 'Pedro', 'Tumbaco', 2.00, 'risaje', 1),
(3, '2025-02-23', 'Pedro', 'Quito', 16.00, 'risaje', 1);

--
-- Disparadores `notas_remision`
--
DELIMITER $$
CREATE TRIGGER `trg_after_delete_remision` AFTER DELETE ON `notas_remision` FOR EACH ROW BEGIN
    INSERT INTO auditoria_remision (id_nota, accion, usuario)
    VALUES (OLD.id_nota, 'DELETE', (SELECT nom_usu FROM usuarios WHERE id_usu = OLD.id_vendedor));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_after_insert_remision` AFTER INSERT ON `notas_remision` FOR EACH ROW BEGIN
    INSERT INTO auditoria_remision (id_nota, accion, usuario)
    VALUES (NEW.id_nota, 'INSERT', (SELECT nom_usu FROM usuarios WHERE id_usu = NEW.id_vendedor));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_pro` int(11) NOT NULL,
  `nom_pro` varchar(24) DEFAULT NULL,
  `pre_uni_pro` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_pro`, `nom_pro`, `pre_uni_pro`) VALUES
(1, 'Galletas', 1),
(2, 'Avena', 2),
(3, 'Arroz', 2.6),
(4, 'Frejol', 3.2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_remision`
--

CREATE TABLE `productos_remision` (
  `id_producto_remision` int(11) NOT NULL,
  `id_nota` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` int(11) NOT NULL,
  `importe` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_remision`
--

INSERT INTO `productos_remision` (`id_producto_remision`, `id_nota`, `cantidad`, `producto`, `importe`) VALUES
(1, 1, 121, 3, 314.60),
(2, 1, 2, 4, 6.40),
(3, 1, 1, 1, 1.00),
(4, 2, 1, 2, 2.00),
(5, 3, 2, 2, 4.00),
(6, 3, 4, 1, 12.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `des_rol` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `des_rol`) VALUES
(1, 'vendedor'),
(2, 'comprador'),
(3, 'administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usu` int(11) NOT NULL,
  `email_usu` varchar(24) DEFAULT NULL,
  `pass_usu` varchar(64) DEFAULT NULL,
  `nom_usu` varchar(24) DEFAULT NULL,
  `rol` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usu`, `email_usu`, `pass_usu`, `nom_usu`, `rol`) VALUES
(1, 'ngordon@ute.edu.ec', 'fb557d0fdf35f0fc3a635e4af0bf86eda8c93f5b40f3f04ef05094c142053cde', 'Noe', 1),
(2, 'rtenorio@ute.edu.ec', '917c8c30499d64e38c836fd9095ff62433df493243cb24f3650a8c68f9998a5d', 'Richard', 2),
(3, 'psuarez@ute.edu.ec', 'd69a53183e3c503ccc231a33671c3a1b67f67d6b8590f625adf779467430edbb', 'Pedro', 3);

--
-- Disparadores `usuarios`
--
DELIMITER $$
CREATE TRIGGER `before_insert_usuarios` BEFORE INSERT ON `usuarios` FOR EACH ROW BEGIN
    SET NEW.pass_usu = SHA2(NEW.pass_usu, 256);
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria_remision`
--
ALTER TABLE `auditoria_remision`
  ADD PRIMARY KEY (`id_auditoria`),
  ADD KEY `id_nota` (`id_nota`);

--
-- Indices de la tabla `notas_remision`
--
ALTER TABLE `notas_remision`
  ADD PRIMARY KEY (`id_nota`),
  ADD KEY `id_vendedor` (`id_vendedor`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_pro`);

--
-- Indices de la tabla `productos_remision`
--
ALTER TABLE `productos_remision`
  ADD PRIMARY KEY (`id_producto_remision`),
  ADD KEY `id_nota` (`id_nota`),
  ADD KEY `id_pro` (`producto`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usu`),
  ADD KEY `id_rol` (`rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria_remision`
--
ALTER TABLE `auditoria_remision`
  MODIFY `id_auditoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `notas_remision`
--
ALTER TABLE `notas_remision`
  MODIFY `id_nota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_pro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productos_remision`
--
ALTER TABLE `productos_remision`
  MODIFY `id_producto_remision` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `notas_remision`
--
ALTER TABLE `notas_remision`
  ADD CONSTRAINT `notas_remision_ibfk_1` FOREIGN KEY (`id_vendedor`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos_remision`
--
ALTER TABLE `productos_remision`
  ADD CONSTRAINT `id_pro` FOREIGN KEY (`producto`) REFERENCES `producto` (`id_pro`),
  ADD CONSTRAINT `productos_remision_ibfk_1` FOREIGN KEY (`id_nota`) REFERENCES `notas_remision` (`id_nota`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `id_rol` FOREIGN KEY (`rol`) REFERENCES `rol` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
