	
-- Volcando estructura para tabla blog-utn.articulos
CREATE TABLE IF NOT EXISTS `articulos` (
  `id` int(9) DEFAULT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `categoria_id` int(2) DEFAULT NULL,
  `preciocompra` varchar(50) DEFAULT NULL,
  `cantidad` varchar(50) DEFAULT NULL,
  `minimo` varchar(50) DEFAULT NULL,
  `maximo` varchar(50) DEFAULT NULL,
  `proveedor_id` int(2) DEFAULT NULL,
  `precioventa` varchar(50) DEFAULT NULL,
  `imagen` varchar(50) DEFAULT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos` CHANGE `id` `id` INT(9) NOT NULL AUTO_INCREMENT; 