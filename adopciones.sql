-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
CREATE DATABASE IF NOT EXISTS `adopciones` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-01-2024 a las 19:46:57
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `adopciones`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `carrito_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `mascota_id` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascotas`
--

CREATE TABLE `mascotas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `raza` varchar(100) NOT NULL,
  `edad` int(11) DEFAULT NULL,
  `id_dueño` int(11) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `foto` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mascotas`
--

INSERT INTO `mascotas` (`id`, `nombre`, `tipo`, `raza`, `edad`, `id_dueño`, `color`, `foto`) VALUES
(8, 'Donald', 'Pato', 'Salvaje', 1, 1, 'blanco', 'img/8.jpg'),
(87, 'Luna', 'Perro', 'Pastor Aleman', 4, 2, 'Marron', 'img/19.webp'),
(88, 'Firulais', 'Perro', 'Callejero', 3, 1, 'Negro', 'img/49.jpg'),
(89, 'Lisa', 'Perro', 'Golden Retriever', 5, 1, 'beige', 'img/18.jpg'),
(90, 'Peppa pig', 'Cerdo', 'vietnamita', 1, 2, 'negro', 'img/5.jpg'),
(91, 'Sebastian', 'Cangrejo', 'salvaje', 1, 1, 'rojo', 'img/45.jpg'),
(93, 'Rajah', 'Tigre', 'bengala', 6, 1, 'blanco y negro', 'img/10.jpg'),
(95, 'Pegaso', 'Caballo', 'Pura Sangre', 3, 2, 'blanco', 'img/51.jpg'),
(96, 'Messi', 'Mono', 'común', 6, 1, 'negro', 'img/52.jpg'),
(97, 'Sugar', 'Perro', 'Chihuahua', 1, 2, 'blanco', 'img/sugar.jpg'),
(98, 'Bad bunny', 'Conejo', 'Granjero', 2, 1, 'naranja', 'img/20.avif'),
(99, 'Flecha', 'Gato', 'Bosque de Noruega', 2, 2, 'marron', 'img/21.webp'),
(117, 'Rudolf', 'Reno', 'del Bosque', 1, 2, 'gris', 'img/reno.jpg'),
(119, 'Sparrow', 'Loro', 'exotico', 1, 1, 'rojo y verde', 'img/46.jpg'),
(120, 'Hitler', 'Gato', 'Aria', 4, 2, 'blanco', 'img/hitler.png'),
(133, 'Snaky', 'amazonica', 'cobra', 2, 2, 'gris', 'img/47.jpg'),
(134, 'Kunfi', 'Oso', 'Panda', 2, 1, 'blanco y negro', 'img/48.jpg'),
(151, 'Nieve', 'Perro', 'Husky', 4, 1, 'blanco y negro', 'img/2.jpeg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`id`, `titulo`, `contenido`, `fecha_publicacion`) VALUES
(1, 'Bienvenid@ a nuestro RSS', 'Iremos actualizando con las novedades de la protectora.', '2024-01-18 15:32:44'),
(2, 'Hola guerreros', 'como estan los makinah', '2024-01-25 17:07:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `razas`
--

CREATE TABLE `razas` (
  `raza_id` int(11) NOT NULL,
  `mascota_id` int(11) DEFAULT NULL,
  `nombre_raza` varchar(50) NOT NULL,
  `tamaño` enum('pequeño','mediano','grande') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `razas`
--

INSERT INTO `razas` (`raza_id`, `mascota_id`, `nombre_raza`, `tamaño`) VALUES
(8, 8, 'conquistadora', 'grande'),
(128, 87, 'Pastor Aleman', 'grande'),
(129, 88, 'Callejero', 'mediano'),
(130, 89, 'Golden Retriever', 'grande'),
(131, 90, 'vietnamita', 'pequeño'),
(132, 91, 'salvaje', 'pequeño'),
(134, 93, 'bengala', 'grande'),
(136, 95, 'Pura Sangre', 'grande'),
(137, 96, 'común', 'mediano'),
(138, 97, 'Chihuahua', 'pequeño'),
(139, 98, 'Granjero', 'mediano'),
(140, 99, 'Bosque de Noruega', 'mediano'),
(153, 117, 'del Bosque', 'grande'),
(155, 119, 'exotico', 'mediano'),
(156, 120, 'Aria', 'mediano'),
(167, 133, 'cobra', 'grande'),
(168, 134, 'Panda', 'grande'),
(170, 151, 'Husky', 'grande');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `identificador` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nombre_apellidos` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `calle` varchar(100) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `localidad` varchar(50) DEFAULT NULL,
  `provincia` varchar(50) DEFAULT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `identificador`, `password`, `nombre_apellidos`, `correo`, `calle`, `numero`, `localidad`, `provincia`, `role`) VALUES
(1, 'Amanda', '1234', 'Amanda Robles Ureña', 'amandaroblesurena@gmail.com', 'La Via', '29', 'Mazarron', 'Murcia', 'usuario'),
(2, 'Diego', '1234', 'Diego Lopez ', 'diego.l.araque@gmail.com', 'Animales por las calles', '15', 'Zoologico', 'Chupilandia', 'administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacunas`
--

CREATE TABLE `vacunas` (
  `id` int(11) NOT NULL,
  `id_mascota` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `laboratorio` varchar(50) DEFAULT NULL,
  `nombre_vacuna` varchar(50) DEFAULT NULL,
  `lote` varchar(50) DEFAULT NULL,
  `numero` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`carrito_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `mascota_id` (`mascota_id`);

--
-- Indices de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_dueño` (`id_dueño`);

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `razas`
--
ALTER TABLE `razas`
  ADD PRIMARY KEY (`raza_id`),
  ADD KEY `razas_ibfk_1` (`mascota_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identificador` (`identificador`);

--
-- Indices de la tabla `vacunas`
--
ALTER TABLE `vacunas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mascota` (`id_mascota`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `carrito_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=447;

--
-- AUTO_INCREMENT de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `razas`
--
ALTER TABLE `razas`
  MODIFY `raza_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `vacunas`
--
ALTER TABLE `vacunas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`mascota_id`) REFERENCES `mascotas` (`id`);

--
-- Filtros para la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD CONSTRAINT `mascotas_ibfk_1` FOREIGN KEY (`id_dueño`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `razas`
--
ALTER TABLE `razas`
  ADD CONSTRAINT `razas_ibfk_1` FOREIGN KEY (`mascota_id`) REFERENCES `mascotas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `vacunas`
--
ALTER TABLE `vacunas`
  ADD CONSTRAINT `vacunas_ibfk_1` FOREIGN KEY (`id_mascota`) REFERENCES `mascotas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
