SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Estructura de tabla para la tabla `empleados`
--

DROP TABLE IF EXISTS `empleado`;
CREATE TABLE IF NOT EXISTS `empleado` (
  `id_empleado` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `clave` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `nombre_empleado` varchar(50) NOT NULL,
  `estado` int(11) NOT NULL, /* 1 activo, 0 inactivo*/
  `fecha_registro` datetime NOT NULL,
  `fecha_ultimo_login` datetime NOT NULL,
   PRIMARY KEY (`id_empleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `empleado` VALUES (1, 'admin', '$2y$10$yiebVkIeNFUTqhk/wPsC..H.WAekqT3vBX0xpSagTQ/K9U95NYtk.', 5, 'Administrador', 1, STR_TO_DATE('24-06-2022', '%d-%m-%Y'), STR_TO_DATE('24-06-2022', '%d-%m-%Y'));
INSERT INTO `empleado` VALUES(2, 'GSinnott', '$2y$10$g/ph3u8epUbCEKg1Y4E5wO4aH1XrFCW7R197CWJnSQ50wprOM3Wha', 5, 'Gonzalo Sinnott', 1, STR_TO_DATE('24-06-2022', '%d-%m-%Y'), STR_TO_DATE('24-06-2022', '%d-%m-%Y'));
INSERT INTO `empleado` VALUES (3, 'bartender', '$2y$10$d7.vOISOLjIUkKH8DQLgA.9/KWLmbFD6oAn1yv7E1vs7aCRC3KKo6', 1,  'Empleado Bar', 1 , STR_TO_DATE('24-06-2022', '%d-%m-%Y'), STR_TO_DATE('24-06-2022', '%d-%m-%Y'));
INSERT INTO `empleado` VALUES (4, 'cervecero', '$2y$10$VivI370OEEH6qNtieR8YIua6UIl2/1ajUGs8QdYkuzsJI6KrZo9Ny', 2, 'Empleado Choperia', 1, STR_TO_DATE('24-06-2022', '%d-%m-%Y'), STR_TO_DATE('24-06-2022', '%d-%m-%Y'));
INSERT INTO `empleado` VALUES (5, 'cocinero', '$2y$10$zS9N5s9HTH6NcFKrujj26eU4b.snXZWQgLZg1w4dsi6tJBocOUrPW', 3, 'Empleado Cocina', 1, STR_TO_DATE('24-06-2022', '%d-%m-%Y'), STR_TO_DATE('24-06-2022', '%d-%m-%Y'));
INSERT INTO `empleado` VALUES (6, 'mozo', '$2y$10$LjQuwIIwzk9WAjXqSYw8hO5KQIP5XwB6t80pe9g4d.NeOgUmc3nW2', 4, 'Empleado Mozo', 1 , STR_TO_DATE('24-06-2022', '%d-%m-%Y'), STR_TO_DATE('24-06-2022', '%d-%m-%Y'));

--
-- Estructura de tabla para la tabla `tipo`
--

DROP TABLE IF EXISTS `tipo`;
CREATE TABLE IF NOT EXISTS `tipo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `tipo` VALUES (1, 'Bartender');
INSERT INTO `tipo` VALUES (2, 'Cervecero');
INSERT INTO `tipo` VALUES (3, 'Cocinero');
INSERT INTO `tipo` VALUES (4, 'Mozo');
INSERT INTO `tipo` VALUES (5, 'Socio');

--
-- Estructura de tabla para la tabla `sector`
--

DROP TABLE IF EXISTS `sector`;
CREATE TABLE IF NOT EXISTS `sector` (
  `id_sector` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_sector`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `sector` VALUES (1, 'Barra');
INSERT INTO `sector` VALUES (2, 'Choperia');
INSERT INTO `sector` VALUES (3, 'Cocina');
INSERT INTO `sector` VALUES (4, 'Candy Bar');


--
-- Estructura de tabla para la tabla `producto`
--

DROP TABLE IF EXISTS `producto`;
CREATE TABLE IF NOT EXISTS `producto` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `precio` int(11) NOT NULL,
  `id_sector` int(11) NOT NULL,
  `tiempo_preparacion` int(11) DEFAULT '1',
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `producto` VALUES (1, 'Pasta', 800,3, 25);
INSERT INTO `producto` VALUES (2, 'Pizza', 1000,3, 30);
INSERT INTO `producto` VALUES (3, 'Hamburguesa', 500,3, 20);
INSERT INTO `producto` VALUES (4, 'Ensalada', 600,3, 15);
INSERT INTO `producto` VALUES (5, 'Pollo', 700,3, 25);
INSERT INTO `producto` VALUES (6, 'Pescado', 900,3, 30);
INSERT INTO `producto` VALUES (7, 'Coca-Cola', 100,1, 5);
INSERT INTO `producto` VALUES (8, 'Fanta', 100,1, 5);
INSERT INTO `producto` VALUES (9, 'Sprite', 100,1, 5);
INSERT INTO `producto` VALUES (10, 'Agua', 100,1, 5);
INSERT INTO `producto` VALUES (11, 'Cerveza', 100,2, 5);
INSERT INTO `producto` VALUES (12, 'Gin Tonic', 100,1, 5);
INSERT INTO `producto` VALUES (13, 'Carne al horno', 1250, 3, 30);
INSERT INTO `producto` VALUES (14, 'Margarita', 450, 1, 5);
INSERT INTO `producto` VALUES (15, 'Cheesecake', 350, 4, 5);
INSERT INTO `producto` VALUES (16, 'Flan', 290, 4, 5);

--
-- Estructura de tabla para la tabla `mesa`
--

DROP TABLE IF EXISTS `mesa`;
CREATE TABLE `mesa` (
  `id_mesa` int(11) NOT NULL AUTO_INCREMENT,
  `estado_mesa` int(11) NOT NULL,
  `foto` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_mesa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `mesa` VALUES (1,1, NULL);
INSERT INTO `mesa` VALUES (2,2,NULL);
INSERT INTO `mesa` VALUES (3,3, NULL);

--
-- Estructura de tabla para la tabla `mesa`
--

DROP TABLE IF EXISTS `estados_mesa`;
CREATE TABLE `estados_mesa` (
  `id_estado_mesa` int(11) NOT NULL AUTO_INCREMENT,
  `estado_mesa` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_estado_mesa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `estados_mesa` VALUES (1, 'Cliente esperando');
INSERT INTO `estados_mesa` VALUES (2, 'Cliente comiendo');
INSERT INTO `estados_mesa` VALUES (3, 'Cliente pagando');
INSERT INTO `estados_mesa` VALUES (4, 'Mesa cerrada');

--
-- Estructura de tabla para la tabla `pedido`
--

DROP TABLE IF EXISTS `pedido`;
CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `estado_pedido` int(11) NOT NULL,
  `hora_inicial` time NOT NULL,
  `hora_entrega_estimada` time DEFAULT NULL,
  `hora_entrega_real` time DEFAULT NULL,
  `minutos_estimados` int(11) NOT NULL,
  `id_mozo` int(11) NOT NULL,
  `id_cocinero` int(11) NOT NULL,
  PRIMARY KEY (`id_pedido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Estructura de tabla para la tabla `estados pedido`
--

DROP TABLE IF EXISTS `estados_pedido`;
CREATE TABLE `estados_pedido` (
  `id_estado_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `estado_pedido` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_estado_pedido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `estados_pedido` VALUES (1, 'En preparacion');
INSERT INTO `estados_pedido` VALUES  (2, 'Listo para servir');

--
-- Estructura de tabla para la tabla `estados pedido`
--

DROP TABLE IF EXISTS `encuesta`;
CREATE TABLE `encuesta` (
  `id_encuesta` int(11) NOT NULL AUTO_INCREMENT,
  `rating_mesa` int(11) NOT NULL,
  `rating_restaurante` int(11) NOT NULL,
  `rating_mozo` int(11) NOT NULL,
  `rating_cocinero` int(11) NOT NULL,
  PRIMARY KEY (`id_encuesta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;