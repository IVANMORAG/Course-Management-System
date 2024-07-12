/*
 Navicat Premium Data Transfer

 Source Server         : BiblioTesji
 Source Server Type    : MySQL
 Source Server Version : 50130 (5.1.30-community)
 Source Host           : localhost:3306
 Source Schema         : ranofweb

 Target Server Type    : MySQL
 Target Server Version : 50130 (5.1.30-community)
 File Encoding         : 65001

 Date: 27/06/2024 06:05:12
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for camiones
-- ----------------------------
DROP TABLE IF EXISTS `camiones`;
CREATE TABLE `camiones`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDTransportista` int(11) NULL DEFAULT NULL,
  `Placa` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `Marca` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `Modelo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `Año` int(4) NULL DEFAULT NULL,
  `Color` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `IDEstadoCamion` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  INDEX `IDTransportista`(`IDTransportista`) USING BTREE,
  INDEX `FK_Camiones_Estados`(`IDEstadoCamion`) USING BTREE,
  CONSTRAINT `FK_Camiones_Estados` FOREIGN KEY (`IDEstadoCamion`) REFERENCES `estados_camiones` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_camiones_transportistas` FOREIGN KEY (`IDTransportista`) REFERENCES `transportistas` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of camiones
-- ----------------------------
INSERT INTO `camiones` VALUES (16, 2, 'ABC123', 'Volvo', 'FH12', 2018, 'Blanco', 1);
INSERT INTO `camiones` VALUES (17, 5, 'DEF456', 'Scania', 'R500', 2019, 'Azul', 2);

-- ----------------------------
-- Table structure for certificados
-- ----------------------------
DROP TABLE IF EXISTS `certificados`;
CREATE TABLE `certificados`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDTransportista` int(11) NULL DEFAULT NULL,
  `IDCurso` int(11) NULL DEFAULT NULL,
  `FechaObtencion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Certificado` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  INDEX `IDTransportista`(`IDTransportista`) USING BTREE,
  INDEX `IDCurso`(`IDCurso`) USING BTREE,
  CONSTRAINT `certificados_ibfk_1` FOREIGN KEY (`IDTransportista`) REFERENCES `transportistas` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `certificados_ibfk_2` FOREIGN KEY (`IDCurso`) REFERENCES `cursos` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of certificados
-- ----------------------------
INSERT INTO `certificados` VALUES (6, 5, 1, '2024-06-27 04:35:14', 'Certificados/certificado_5_1.pdf');

-- ----------------------------
-- Table structure for cursos
-- ----------------------------
DROP TABLE IF EXISTS `cursos`;
CREATE TABLE `cursos`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `Descripcion` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `Estado` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `FechaActualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `CertificadoRequerido` tinyint(1) NULL DEFAULT NULL,
  `IDAdmin` int(11) NULL DEFAULT NULL,
  `MiniaturaCurso` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  INDEX `fk_admin`(`IDAdmin`) USING BTREE,
  CONSTRAINT `fk_admin` FOREIGN KEY (`IDAdmin`) REFERENCES `transportistas` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cursos
-- ----------------------------
INSERT INTO `cursos` VALUES (1, 'Salud y seguridad del conductor', 'Curso que aborda temas relacionados con la seguridad vial y salud del conductor.', 'Activo', '2024-06-18 04:31:40', 1, 5, 'Cursos\\01_SaludYSeguridad\\1.png');
INSERT INTO `cursos` VALUES (2, 'Mantenimiento y cuidado del vehículo', 'Curso sobre técnicas de mantenimiento preventivo y cuidado de vehículos.', 'Activo', '2024-06-18 04:31:47', 1, 5, 'Cursos\\02_mantenimiento\\1.png');
INSERT INTO `cursos` VALUES (3, 'Normativas y regulaciones de tráfico', 'Curso que explica las normativas y regulaciones de tráfico vigentes.', 'Activo', '2024-06-18 04:32:12', 1, 5, 'Cursos\\03_trafico\\1.png');
INSERT INTO `cursos` VALUES (4, 'Comunicación y Gestión de Riesgos', 'Curso sobre comunicación y gestión de riesgos en carretera.', 'Activo', '2024-06-18 04:33:38', 1, 5, 'Cursos\\04_señales\\1.png');
INSERT INTO `cursos` VALUES (5, 'Desarrollo Profesional y Ética Laboral', 'Curso sobre desarrollo profesional y ética laboral para conductores.', 'Activo', '2024-06-18 04:34:01', 1, 5, 'Cursos\\05_crecimientoLaboral\\1.png');
INSERT INTO `cursos` VALUES (6, 'Tecnología y Seguridad en la Carretera', 'Curso sobre el uso de tecnología y seguridad en la carretera.', 'Activo', '2024-06-18 04:34:11', 1, 5, 'Cursos\\06_tecnologia\\1.png');
INSERT INTO `cursos` VALUES (7, 'Gestión Ambiental y Sostenibilidad en el Transporte', 'Curso sobre gestión ambiental y sostenibilidad en el transporte.', 'Activo', '2024-06-26 03:14:20', 1, 5, 'Cursos\\07_ambiente\\1.png');

-- ----------------------------
-- Table structure for cursos_favoritos
-- ----------------------------
DROP TABLE IF EXISTS `cursos_favoritos`;
CREATE TABLE `cursos_favoritos`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDTransportista` int(11) NULL DEFAULT NULL,
  `IDCurso` int(11) NULL DEFAULT NULL,
  `FechaGuardado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`) USING BTREE,
  INDEX `IDTransportista`(`IDTransportista`) USING BTREE,
  INDEX `IDCurso`(`IDCurso`) USING BTREE,
  CONSTRAINT `cursos_favoritos_ibfk_1` FOREIGN KEY (`IDTransportista`) REFERENCES `transportistas` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `cursos_favoritos_ibfk_2` FOREIGN KEY (`IDCurso`) REFERENCES `cursos` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cursos_favoritos
-- ----------------------------

-- ----------------------------
-- Table structure for estados_camiones
-- ----------------------------
DROP TABLE IF EXISTS `estados_camiones`;
CREATE TABLE `estados_camiones`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of estados_camiones
-- ----------------------------
INSERT INTO `estados_camiones` VALUES (1, 'Buen estado');
INSERT INTO `estados_camiones` VALUES (2, 'Necesita revisión');
INSERT INTO `estados_camiones` VALUES (3, 'Requiere reparación');

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CorreoElectronico` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Expiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`) USING BTREE,
  INDEX `idx_correo`(`CorreoElectronico`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of password_resets
-- ----------------------------
INSERT INTO `password_resets` VALUES (1, 'moragarcia012@gmail.com', '72a13e0aa13c94cba958e69f1f99dc8ab15f913b2d6469ba1e41e42588436dcab1193d7d7adc57b33a03d0b3c16008d5cbef', '2024-06-18 06:34:02');
INSERT INTO `password_resets` VALUES (2, 'moragarcia012@gmail.com', '0cd2d5e6af51312614740874b9edf10d730e5ccf2ceba67db5ee215c1b4bab509d6b01113622d7ddb6d04b82c2b9eac4cc6e', '2024-06-18 05:49:10');
INSERT INTO `password_resets` VALUES (3, 'moragarcia012@gmail.com', 'dc969af44e481a82c7439b9ccb5cbe845b4cc6ebc245ecabd5640ee8921802a6ff01550a933e829334a9a46841824dd26328', '2024-06-19 09:54:47');
INSERT INTO `password_resets` VALUES (5, 'moragarcia012@gmail.com', '8e876b76e2a238bf7e971719720436bf6003d89e3273ca897781b88e2bd930f8523365aeb78e9360d90841199ee467522622', '2024-06-22 12:41:22');
INSERT INTO `password_resets` VALUES (6, 'moragarcia012@gmail.com', 'b4b748da8e8f84969077ddd0610f2e6adfb1d3d945d891ae44c3adabe5efa8ceadc2df567bb9a36330e1c77d39e1987d361f', '2024-06-22 12:46:10');
INSERT INTO `password_resets` VALUES (7, 'moragarcia012@gmail.com', 'd7124b91cba88a48174c16254a7edae6d1d1d036222bca6dfd93a33dd4bc4d504d6f00ab954faafc00f43d6cac374efbdc01', '2024-06-22 12:47:38');
INSERT INTO `password_resets` VALUES (10, 'moragarcia012@gmail.com', '2081cac387cc3b8ff540b90b2d0e119bbfc3612f73283ac5f33ae668d56b5c7f5e47befdda5689e36fd8212c528a16c356a2', '2024-06-22 13:00:00');
INSERT INTO `password_resets` VALUES (12, 'moragarcia012@gmail.com', 'e87fe84faf4cd09c93448c2d86608e20d67d50c1d8d3c7d23e8135f92256f3132bfe0ca0b24dd3cc984458d3638431a71ce7', '2024-06-25 14:50:31');

-- ----------------------------
-- Table structure for preguntas
-- ----------------------------
DROP TABLE IF EXISTS `preguntas`;
CREATE TABLE `preguntas`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDCurso` int(11) NOT NULL,
  `IDSubtema` int(11) NOT NULL,
  `Pregunta` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `OpcionA` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `OpcionB` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `OpcionC` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `RespuestaCorrecta` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  INDEX `IDCurso`(`IDCurso`) USING BTREE,
  INDEX `IDSubtema`(`IDSubtema`) USING BTREE,
  CONSTRAINT `fk_curso_preguntas` FOREIGN KEY (`IDCurso`) REFERENCES `cursos` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_subtema_preguntas` FOREIGN KEY (`IDSubtema`) REFERENCES `subtemas` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 142 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of preguntas
-- ----------------------------
INSERT INTO `preguntas` VALUES (17, 1, 1, '¿Por qué es importante que los conductores realicen descansos regulares durante viajes largos?', 'Para ahorrar tiempo en el trayecto.', 'Para evitar el tráfico pesado.', 'Para combatir la fatiga y mantener la concentración.', 'C');
INSERT INTO `preguntas` VALUES (18, 1, 1, '¿Cuáles son algunas técnicas efectivas para combatir la somnolencia mientras se conduce?', 'Aumentar la velocidad del vehículo.', 'Consumir bebidas energéticas.', 'Tomar pausas cortas para caminar y estirarse.', 'C');
INSERT INTO `preguntas` VALUES (19, 1, 1, '¿Cómo afecta la fatiga a la toma de decisiones de los conductores?', 'Mejora la capacidad de respuesta.', 'No tiene efecto en la toma de decisiones.', 'Reduce la capacidad para tomar decisiones rápidas y precisas.', 'C');
INSERT INTO `preguntas` VALUES (20, 1, 1, '¿Qué estrategias pueden implementarse para prevenir la fatiga durante largos trayectos?', 'Conducir durante la noche para evitar el tráfico.', 'Tomar siestas cortas antes de continuar conduciendo.', 'Consumir alimentos pesados antes de conducir.', 'B');
INSERT INTO `preguntas` VALUES (21, 1, 2, '¿Qué medidas de ergonomía son importantes para reducir el riesgo de lesiones en conductores?', 'Ajustar el asiento y los espejos correctamente.', 'No usar el cinturón de seguridad.', 'No realizar pausas durante el viaje.', 'A');
INSERT INTO `preguntas` VALUES (22, 1, 2, '¿Por qué es crucial el uso adecuado del cinturón de seguridad y otros dispositivos de seguridad?', 'Para evitar multas de tráfico.', 'Para protegerse en caso de accidentes y reducir lesiones.', 'Porque es obligatorio por ley.', 'B');
INSERT INTO `preguntas` VALUES (23, 1, 2, '¿Cuáles son algunos ejercicios de estiramiento recomendados para conductores?', 'Saltar y correr antes de conducir.', 'Estirar los brazos y las piernas regularmente.', 'No realizar ningún ejercicio de estiramiento.', 'B');
INSERT INTO `preguntas` VALUES (24, 1, 2, '¿Qué impacto puede tener una postura incorrecta en el puesto de conducción?', 'No tiene impacto en la seguridad.', 'Aumenta la concentración.', 'Puede causar fatiga y dolor crónico.', 'C');
INSERT INTO `preguntas` VALUES (25, 1, 3, '¿Por qué es importante que los conductores manejen adecuadamente el estrés en la carretera?', 'Para aumentar la velocidad del vehículo.', 'Para mejorar la experiencia de conducción.', 'Para evitar accidentes y promover la seguridad.', 'C');
INSERT INTO `preguntas` VALUES (26, 1, 3, '¿Qué recursos de apoyo pueden utilizar los conductores para problemas de salud mental?', 'No existen recursos disponibles.', 'Servicios de asistencia telefónica y psicológica.', 'Consultas médicas presenciales únicamente.', 'B');
INSERT INTO `preguntas` VALUES (27, 1, 3, '¿Cómo puede fomentarse un ambiente de trabajo positivo entre los conductores?', 'No es necesario fomentar un ambiente positivo.', 'Reconociendo logros individuales y promoviendo la comunicación.', 'Limitando la interacción entre conductores.', 'B');
INSERT INTO `preguntas` VALUES (28, 1, 3, '¿Cuáles son algunas estrategias efectivas para gestionar el estrés antes de iniciar un viaje largo?', 'Consumir bebidas alcohólicas.', 'Realizar ejercicios de respiración y relajación.', 'No realizar ninguna preparación previa.', 'B');
INSERT INTO `preguntas` VALUES (29, 1, 4, '¿Por qué es importante que los conductores mantengan una alimentación balanceada?', 'Para reducir el costo de la comida en la carretera.', 'Para mantener niveles adecuados de energía y concentración.', 'Porque es una recomendación opcional.', 'B');
INSERT INTO `preguntas` VALUES (30, 1, 4, '¿Qué alternativas saludables pueden elegirse en lugar de la comida rápida durante viajes largos?', 'Comida rápida exclusivamente.', 'Frutas frescas, barras de granola y sándwiches de alimentos integrales.', 'No hay alternativas saludables disponibles.', 'B');
INSERT INTO `preguntas` VALUES (31, 1, 4, '¿Por qué es crucial mantenerse adecuadamente hidratado durante largos viajes?', 'Porque es obligatorio por ley.', 'Para evitar visitas a estaciones de servicio.', 'Para mantener la función cognitiva y física óptima.', 'C');
INSERT INTO `preguntas` VALUES (32, 1, 4, '¿Cuáles son los riesgos asociados con la deshidratación en conductores?', 'Aumento de la concentración.', 'Fatiga y disminución de la capacidad de respuesta.', 'Incremento de la velocidad del vehículo.', 'B');
INSERT INTO `preguntas` VALUES (33, 2, 5, '¿Cuáles son los procedimientos recomendados para realizar una inspección exhaustiva antes de iniciar un viaje?', 'No es necesario hacer inspecciones antes de viajar.', 'Revisar solo los frenos.', 'Revisar neumáticos, luces, frenos, niveles de líquidos, y asegurarse de que todas las luces exteriores e interiores funcionen correctamente.', 'C');
INSERT INTO `preguntas` VALUES (34, 2, 5, '¿Por qué es importante identificar problemas comunes antes de iniciar un viaje largo?', 'Para tener una excusa para no viajar.', 'Para evitar que el vehículo se deteriore.', 'Para asegurar la seguridad y evitar problemas mecánicos en el camino.', 'C');
INSERT INTO `preguntas` VALUES (35, 2, 5, '¿Qué tipo de mantenimiento preventivo se recomienda realizar regularmente en un vehículo?', 'No es necesario realizar mantenimiento preventivo.', 'Cambio de aceite cada 50,000 kilómetros.', 'Seguir el programa de mantenimiento del fabricante para cambiar filtros, líquidos y otros componentes según lo recomendado.', 'C');
INSERT INTO `preguntas` VALUES (36, 2, 5, '¿Cuál es uno de los errores comunes al realizar una inspección previa al viaje?', 'No verificar la presión de los neumáticos.', 'No revisar las luces exteriores.', 'No llenar el tanque de combustible.', 'A');
INSERT INTO `preguntas` VALUES (37, 2, 6, '¿Por qué es importante mantener la presión adecuada de los neumáticos?', 'No tiene ningún efecto en el manejo del vehículo.', 'Para reducir el desgaste irregular de los neumáticos y mejorar la eficiencia del combustible.', 'Solo es importante en climas fríos.', 'B');
INSERT INTO `preguntas` VALUES (38, 2, 6, '¿Qué se debe inspeccionar en los neumáticos para identificar desgaste y daño?', 'Nada, los neumáticos no necesitan inspección.', 'Profundidad del dibujo y presencia de objetos incrustados.', 'Solo la presión de los neumáticos.', 'B');
INSERT INTO `preguntas` VALUES (39, 2, 6, '¿Cuándo se debe cambiar y rotar los neumáticos según las recomendaciones del fabricante?', 'No es necesario cambiar los neumáticos.', 'Cada 100,000 kilómetros.', 'Según el desgaste y las especificaciones del fabricante para mantener un desgaste uniforme y prolongar la vida útil de los neumáticos.', 'C');
INSERT INTO `preguntas` VALUES (40, 2, 6, '¿Qué herramienta se utiliza para medir la profundidad del dibujo en los neumáticos?', 'Un manómetro.', 'Un cronómetro.', 'Un calibrador de profundidad de banda de rodadura.', 'C');
INSERT INTO `preguntas` VALUES (41, 2, 7, '¿Cómo funcionan y cómo se debe mantener el sistema de frenos de un vehículo?', 'No es necesario entender el funcionamiento de los frenos.', 'Inspeccionar regularmente discos y pastillas de freno.', 'Solo revisar el líquido de frenos.', 'B');
INSERT INTO `preguntas` VALUES (42, 2, 7, '¿Cuáles son las señales comunes que indican problemas en el sistema de frenado?', 'Sonido fuerte de la radio.', 'Vibración en el volante al girar.', 'Pérdida de eficiencia al frenar, luces de advertencia encendidas.', 'C');
INSERT INTO `preguntas` VALUES (43, 2, 7, '¿Qué pruebas de frenado se deben realizar regularmente y por qué son importantes?', 'No es necesario realizar pruebas de frenado.', 'Para verificar la potencia de frenado y ajustar según sea necesario.', 'Solo en casos de emergencia.', 'B');
INSERT INTO `preguntas` VALUES (44, 2, 7, '¿Qué líquido es crucial para el funcionamiento adecuado del sistema de frenos?', 'Aceite de motor.', 'Líquido de transmisión.', 'Líquido de frenos.', 'C');
INSERT INTO `preguntas` VALUES (45, 2, 8, '¿Qué incluye un adecuado cambio de aceite y filtros según el programa de mantenimiento?', 'Solo cambiar el aceite.', 'Cambiar el aceite y los filtros según el kilometraje recomendado o el tiempo de uso.', 'No es necesario cambiar los filtros.', 'B');
INSERT INTO `preguntas` VALUES (46, 2, 8, '¿Por qué es importante revisar regularmente los niveles de líquidos y fluidos del motor?', 'No es necesario revisar los niveles de líquidos.', 'Para prevenir el sobrecalentamiento y daños en el motor.', 'Solo si se detecta una fuga.', 'B');
INSERT INTO `preguntas` VALUES (47, 2, 8, '¿Cómo se pueden identificar y resolver problemas comunes del motor antes de que se conviertan en grandes fallas?', 'Ignorar cualquier ruido o vibración.', 'Inspeccionar regularmente el motor y realizar mantenimiento preventivo.', 'Esperar hasta que ocurra una avería completa.', 'B');
INSERT INTO `preguntas` VALUES (48, 2, 8, '¿Qué puede causar un consumo elevado de aceite en un motor?', 'Filtros de aceite nuevos.', 'Juntas de motor dañadas.', 'Niveles bajos de combustible.', 'B');
INSERT INTO `preguntas` VALUES (49, 3, 9, '¿Cuáles son los derechos y responsabilidades de los conductores según la ley mexicana?', 'No tienen derechos específicos.', 'Respetar las señales de tráfico únicamente.', 'Conducir de manera segura y respetar las normativas de tránsito establecidas.', 'C');
INSERT INTO `preguntas` VALUES (50, 3, 9, '¿Qué regulaciones de velocidad y adelantamiento deben seguir los conductores en México?', 'No hay regulaciones específicas.', 'Seguir los límites de velocidad establecidos y adelantar de manera segura y legal.', 'Conducir a la velocidad que consideren segura.', 'B');
INSERT INTO `preguntas` VALUES (51, 3, 9, '¿Por qué es crucial respetar las normas de tráfico tanto en áreas urbanas como rurales?', 'Porque solo hay multas en áreas urbanas.', 'Para evitar accidentes y garantizar la seguridad de todos los usuarios de la vía.', 'Las normas de tráfico no aplican en áreas rurales.', 'B');
INSERT INTO `preguntas` VALUES (52, 3, 9, '¿Cuál es una de las sanciones comunes por violar las leyes de tránsito en México?', 'No hay sanciones.', 'Multas, puntos en la licencia y posible pérdida de la misma.', 'Solo una advertencia verbal.', 'B');
INSERT INTO `preguntas` VALUES (53, 3, 10, '¿Por qué es importante reconocer las señales de tráfico comunes?', 'No tienen ningún propósito.', 'Para mejorar la navegación y seguridad en las vías.', 'Solo para evitar multas.', 'B');
INSERT INTO `preguntas` VALUES (54, 3, 10, '¿Cómo se deben interpretar las señales de advertencia y regulación en las carreteras?', 'No es necesario interpretarlas.', 'Seguir las indicaciones sin considerar las señales.', 'Atender a las advertencias y regulaciones para ajustar el comportamiento de conducción.', 'C');
INSERT INTO `preguntas` VALUES (55, 3, 10, '¿Qué importancia tiene seguir las indicaciones de las señales en carretera?', 'No es necesario prestar atención a las señales.', 'Para evitar multas únicamente.', 'Para garantizar la seguridad y fluidez del tráfico.', 'C');
INSERT INTO `preguntas` VALUES (56, 3, 10, '¿Cuál es el color comúnmente utilizado para las señales de advertencia en México?', 'Verde.', 'Amarillo.', 'Rojo.', 'B');
INSERT INTO `preguntas` VALUES (57, 3, 11, '¿Cuáles son los procedimientos legales y de seguridad en la carga y descarga de mercancías?', 'No existen procedimientos específicos.', 'Asegurarse de que la carga esté bien sujeta y utilizar equipos de protección adecuados.', 'No importa cómo se maneje la carga y descarga.', 'B');
INSERT INTO `preguntas` VALUES (58, 3, 11, '¿Qué restricciones de peso y dimensiones deben cumplir los vehículos de carga en México?', 'No hay restricciones específicas.', 'Seguir las especificaciones del fabricante únicamente.', 'Cumplir con las regulaciones locales y nacionales sobre peso y dimensiones.', 'C');
INSERT INTO `preguntas` VALUES (59, 3, 11, '¿Qué documentación es necesaria para el transporte de mercancías en México?', 'Ninguna documentación es requerida.', 'Documentos de identidad personal únicamente.', 'Documentación legal como la guía de carga, factura comercial y permisos de transporte.', 'C');
INSERT INTO `preguntas` VALUES (60, 3, 11, '¿Qué riesgos puede haber si la carga no está correctamente asegurada durante el transporte?', 'No hay riesgos.', 'Daños a la mercancía y peligro para otros conductores.', 'Solo multas por violación de regulaciones de carga.', 'B');
INSERT INTO `preguntas` VALUES (61, 3, 12, '¿Por qué es importante cumplir con las normativas de emisiones contaminantes en los vehículos?', 'No hay razones para preocuparse por las emisiones.', 'Para proteger el medio ambiente y la salud pública.', 'Solo para evitar inspecciones técnicas vehiculares.', 'B');
INSERT INTO `preguntas` VALUES (62, 3, 12, '¿Qué tecnologías ecoamigables pueden utilizar los vehículos comerciales para reducir su impacto ambiental?', 'No hay tecnologías ecoamigables disponibles.', 'Uso de combustibles fósiles exclusivamente.', 'Tecnologías como vehículos eléctricos, híbridos y motores más eficientes.', 'C');
INSERT INTO `preguntas` VALUES (63, 3, 12, '¿Por qué es importante crear conciencia sobre el impacto ambiental de la industria del transporte?', 'No es necesario preocuparse por el impacto ambiental.', 'Para promover prácticas sostenibles y reducir la huella ecológica.', 'Solo para cumplir con regulaciones gubernamentales.', 'B');
INSERT INTO `preguntas` VALUES (64, 3, 12, '¿Qué puede hacer un conductor para reducir el consumo de combustible y las emisiones contaminantes?', 'Acelerar bruscamente y frenar rápidamente.', 'Mantener el vehículo en ralentí durante largos períodos.', 'Mantener una velocidad constante y evitar cargas innecesarias.', 'C');
INSERT INTO `preguntas` VALUES (65, 4, 13, '¿Por qué es importante la comunicación clara y precisa entre conductores y supervisores?', 'Para aumentar la velocidad del vehículo.', 'Para mejorar la experiencia de conducción.', 'Para garantizar la seguridad y eficiencia en las operaciones.', 'C');
INSERT INTO `preguntas` VALUES (66, 4, 13, '¿Cuál es el uso adecuado de dispositivos de comunicación en la carretera?', 'No usar dispositivos de comunicación.', 'Utilizar dispositivos solo para escuchar música.', 'Mantener la comunicación con otros conductores y autoridades relevantes.', 'C');
INSERT INTO `preguntas` VALUES (67, 4, 13, '¿Qué protocolos deben seguirse en situaciones de emergencia en la carretera?', 'No hay necesidad de protocolos.', 'Comunicar la emergencia a través de redes sociales.', 'Seguir los protocolos establecidos para garantizar una respuesta efectiva y segura.', 'C');
INSERT INTO `preguntas` VALUES (68, 4, 13, '¿Por qué es importante usar dispositivos de comunicación en situaciones de emergencia?', 'Para hacer llamadas personales.', 'Para coordinar la asistencia y la ayuda necesaria.', 'No es necesario usar dispositivos de comunicación.', 'B');
INSERT INTO `preguntas` VALUES (69, 4, 14, '¿Por qué es importante identificar peligros potenciales en la ruta antes de viajar?', 'No es necesario identificarlos.', 'Para aumentar la velocidad del vehículo.', 'Para tomar medidas preventivas y evitar accidentes.', 'C');
INSERT INTO `preguntas` VALUES (70, 4, 14, '¿Cómo se puede planificar rutas seguras y alternativas en caso de incidentes en la carretera?', 'No es necesario planificar rutas alternativas.', 'Seguir siempre la misma ruta independientemente de las condiciones.', 'Evaluar las condiciones de la carretera y planificar rutas alternativas en caso de obstrucciones o incidentes.', 'C');
INSERT INTO `preguntas` VALUES (71, 4, 14, '¿Qué deben hacer los conductores ante un accidente o emergencia en la carretera?', 'No es necesario responder ante emergencias.', 'Seguir conduciendo sin detenerse.', 'Seguir los protocolos de respuesta establecidos y contactar a las autoridades relevantes.', 'C');
INSERT INTO `preguntas` VALUES (72, 4, 14, '¿Cuáles son algunas medidas de seguridad adicionales que se pueden tomar para minimizar los riesgos en la carretera?', 'No es necesario tomar medidas adicionales.', 'Mantener una distancia segura con otros vehículos.', 'Ignorar las señales de advertencia.', 'B');
INSERT INTO `preguntas` VALUES (73, 4, 15, '¿Cuáles son los métodos efectivos de sujeción de la carga para evitar desplazamientos peligrosos?', 'No es necesario asegurar la carga.', 'Utilizar correas y amarres adecuados para mantener la carga segura y estable.', 'No hay métodos efectivos para asegurar la carga.', 'B');
INSERT INTO `preguntas` VALUES (74, 4, 15, '¿Por qué es importante distribuir equitativamente el peso de la carga en el vehículo?', 'Para aumentar la velocidad del vehículo.', 'Para facilitar la descarga de la carga.', 'Para mantener la estabilidad y control del vehículo durante el transporte.', 'C');
INSERT INTO `preguntas` VALUES (75, 4, 15, '¿Qué se debe inspeccionar durante el transporte para prevenir movimientos peligrosos de la carga?', 'No es necesario inspeccionar la carga.', 'La documentación únicamente.', 'La carga física para asegurar que esté adecuadamente asegurada.', 'C');
INSERT INTO `preguntas` VALUES (76, 4, 15, '¿Por qué es importante revisar periódicamente la sujeción de la carga durante el viaje?', 'No es importante revisar la sujeción.', 'Para asegurar que la carga permanezca estable y segura.', 'La sujeción de la carga no afecta la seguridad.', 'B');
INSERT INTO `preguntas` VALUES (77, 4, 16, '¿Qué medidas de seguridad pueden implementarse para proteger la carga y el vehículo?', 'No es necesario implementar medidas de seguridad.', 'Utilizar sistemas de seguridad como candados y GPS.', 'Dependiendo solo de la vigilancia pública.', 'B');
INSERT INTO `preguntas` VALUES (78, 4, 16, '¿Cómo se puede reconocer zonas de alto riesgo y qué precauciones deben tomarse?', 'No es necesario reconocer zonas de riesgo.', 'A través del uso de aplicaciones móviles de mapas.', 'A través de la experiencia y la información proporcionada por autoridades locales.', 'C');
INSERT INTO `preguntas` VALUES (79, 4, 16, '¿Por qué es importante colaborar con autoridades locales para reportar incidentes y prevenir la delincuencia?', 'No es necesario colaborar con autoridades.', 'Para evitar multas por no colaborar.', 'Para mejorar la seguridad pública y prevenir futuros incidentes.', 'C');
INSERT INTO `preguntas` VALUES (80, 4, 16, '¿Qué medidas de seguridad adicionales pueden tomarse para proteger la carga y el vehículo en zonas de alto riesgo?', 'No es necesario tomar medidas adicionales.', 'Aumentar la velocidad del vehículo.', 'Reforzar la seguridad con guardias adicionales y sistemas de monitoreo.', 'C');
INSERT INTO `preguntas` VALUES (81, 4, 13, '¿Por qué es importante la comunicación clara y precisa entre conductores y supervisores?', 'Para aumentar la velocidad del vehículo.', 'Para mejorar la experiencia de conducción.', 'Para garantizar la seguridad y eficiencia en las operaciones.', 'C');
INSERT INTO `preguntas` VALUES (82, 4, 13, '¿Cuál es el uso adecuado de dispositivos de comunicación en la carretera?', 'No usar dispositivos de comunicación.', 'Utilizar dispositivos solo para escuchar música.', 'Mantener la comunicación con otros conductores y autoridades relevantes.', 'C');
INSERT INTO `preguntas` VALUES (83, 4, 13, '¿Qué protocolos deben seguirse en situaciones de emergencia en la carretera?', 'No hay necesidad de protocolos.', 'Comunicar la emergencia a través de redes sociales.', 'Seguir los protocolos establecidos para garantizar una respuesta efectiva y segura.', 'C');
INSERT INTO `preguntas` VALUES (84, 4, 13, '¿Por qué es importante usar dispositivos de comunicación en situaciones de emergencia?', 'Para hacer llamadas personales.', 'Para coordinar la asistencia y la ayuda necesaria.', 'No es necesario usar dispositivos de comunicación.', 'B');
INSERT INTO `preguntas` VALUES (85, 4, 14, '¿Por qué es importante identificar peligros potenciales en la ruta antes de viajar?', 'No es necesario identificarlos.', 'Para aumentar la velocidad del vehículo.', 'Para tomar medidas preventivas y evitar accidentes.', 'C');
INSERT INTO `preguntas` VALUES (86, 4, 14, '¿Cómo se puede planificar rutas seguras y alternativas en caso de incidentes en la carretera?', 'No es necesario planificar rutas alternativas.', 'Seguir siempre la misma ruta independientemente de las condiciones.', 'Evaluar las condiciones de la carretera y planificar rutas alternativas en caso de obstrucciones o incidentes.', 'C');
INSERT INTO `preguntas` VALUES (87, 4, 14, '¿Qué deben hacer los conductores ante un accidente o emergencia en la carretera?', 'No es necesario responder ante emergencias.', 'Seguir conduciendo sin detenerse.', 'Seguir los protocolos de respuesta establecidos y contactar a las autoridades relevantes.', 'C');
INSERT INTO `preguntas` VALUES (88, 4, 14, '¿Cuáles son algunas medidas de seguridad adicionales que se pueden tomar para minimizar los riesgos en la carretera?', 'No es necesario tomar medidas adicionales.', 'Mantener una distancia segura con otros vehículos.', 'Ignorar las señales de advertencia.', 'B');
INSERT INTO `preguntas` VALUES (89, 4, 15, '¿Cuáles son los métodos efectivos de sujeción de la carga para evitar desplazamientos peligrosos?', 'No es necesario asegurar la carga.', 'Utilizar correas y amarres adecuados para mantener la carga segura y estable.', 'No hay métodos efectivos para asegurar la carga.', 'B');
INSERT INTO `preguntas` VALUES (90, 4, 15, '¿Por qué es importante distribuir equitativamente el peso de la carga en el vehículo?', 'Para aumentar la velocidad del vehículo.', 'Para facilitar la descarga de la carga.', 'Para mantener la estabilidad y control del vehículo durante el transporte.', 'C');
INSERT INTO `preguntas` VALUES (91, 4, 15, '¿Qué se debe inspeccionar durante el transporte para prevenir movimientos peligrosos de la carga?', 'No es necesario inspeccionar la carga.', 'La documentación únicamente.', 'La carga física para asegurar que esté adecuadamente asegurada.', 'C');
INSERT INTO `preguntas` VALUES (92, 4, 15, '¿Por qué es importante revisar periódicamente la sujeción de la carga durante el viaje?', 'No es importante revisar la sujeción.', 'Para asegurar que la carga permanezca estable y segura.', 'La sujeción de la carga no afecta la seguridad.', 'B');
INSERT INTO `preguntas` VALUES (93, 4, 16, '¿Qué medidas de seguridad pueden implementarse para proteger la carga y el vehículo?', 'No es necesario implementar medidas de seguridad.', 'Utilizar sistemas de seguridad como candados y GPS.', 'Dependiendo solo de la vigilancia pública.', 'B');
INSERT INTO `preguntas` VALUES (94, 4, 16, '¿Cómo se puede reconocer zonas de alto riesgo y qué precauciones deben tomarse?', 'No es necesario reconocer zonas de riesgo.', 'A través del uso de aplicaciones móviles de mapas.', 'A través de la experiencia y la información proporcionada por autoridades locales.', 'C');
INSERT INTO `preguntas` VALUES (95, 4, 16, '¿Por qué es importante colaborar con autoridades locales para reportar incidentes y prevenir la delincuencia?', 'No es necesario colaborar con autoridades.', 'Para evitar multas por no colaborar.', 'Para mejorar la seguridad pública y prevenir futuros incidentes.', 'C');
INSERT INTO `preguntas` VALUES (96, 4, 16, '¿Qué medidas de seguridad adicionales pueden tomarse para proteger la carga y el vehículo en zonas de alto riesgo?', 'No es necesario tomar medidas adicionales.', 'Aumentar la velocidad del vehículo.', 'Reforzar la seguridad con guardias adicionales y sistemas de monitoreo.', 'C');
INSERT INTO `preguntas` VALUES (97, 5, 17, '¿Por qué es importante cumplir con los horarios y normativas de la empresa como conductor?', 'Para evitar sanciones económicas.', 'Para mantener la puntualidad y eficiencia en las operaciones.', 'No es importante cumplir con horarios y normativas.', 'B');
INSERT INTO `preguntas` VALUES (98, 5, 17, '¿Cuál es una forma de mostrar respeto a los derechos de otros usuarios de la vía pública?', 'Ignorando las señales de tránsito.', 'Conduciendo a alta velocidad para evitar obstrucciones.', 'Siguiendo las normas de tráfico y cediendo el paso cuando sea necesario.', 'C');
INSERT INTO `preguntas` VALUES (99, 5, 17, '¿Por qué es fundamental el compromiso con la seguridad y la integridad personal y de terceros?', 'No es necesario comprometerse con la seguridad.', 'Para evitar multas de tránsito.', 'Para proteger la vida y el bienestar de todos en la vía pública.', 'C');
INSERT INTO `preguntas` VALUES (100, 5, 17, '¿Qué acciones pueden considerarse parte de la ética y responsabilidad profesional de un conductor?', 'No respetar las señales de tráfico.', 'Cumplir con las normativas legales y éticas establecidas.', 'Conducir a altas velocidades para cumplir con los horarios de entrega.', 'B');
INSERT INTO `preguntas` VALUES (101, 5, 18, '¿Por qué es importante mejorar continuamente las habilidades de conducción y maniobras?', 'Para evitar la práctica y el entrenamiento.', 'Para mantenerse actualizado y seguro en la carretera.', 'No es necesario mejorar habilidades de conducción.', 'B');
INSERT INTO `preguntas` VALUES (102, 5, 18, '¿Qué beneficios pueden derivarse de participar en programas de formación y capacitación para conductores?', 'Ningún beneficio.', 'Mejora de habilidades y conocimientos.', 'No es necesario participar en programas de formación.', 'B');
INSERT INTO `preguntas` VALUES (103, 5, 18, '¿Qué habilidades son importantes para la resolución de problemas y la toma de decisiones en la conducción?', 'Ignorar los problemas y decisiones.', 'Desarrollar habilidades analíticas y de toma de decisiones efectivas.', 'No es importante desarrollar habilidades de resolución de problemas.', 'B');
INSERT INTO `preguntas` VALUES (104, 5, 18, '¿Por qué es esencial desarrollar habilidades de comunicación efectiva como conductor?', 'No es esencial.', 'Para mejorar la interacción con otros conductores y equipos.', 'Para evitar la comunicación en la carretera.', 'B');
INSERT INTO `preguntas` VALUES (105, 5, 19, '¿Cómo puede fomentarse la colaboración y el apoyo entre conductores en el lugar de trabajo?', 'No es necesario fomentar la colaboración.', 'Reconociendo y promoviendo la importancia del trabajo en equipo.', 'Minimizando la interacción entre conductores.', 'B');
INSERT INTO `preguntas` VALUES (106, 5, 19, '¿Por qué es importante reconocer el liderazgo individual en la seguridad vial?', 'No es importante reconocer el liderazgo.', 'Para motivar y mejorar la seguridad en el entorno laboral.', 'Para aumentar la competitividad entre los conductores.', 'B');
INSERT INTO `preguntas` VALUES (107, 5, 19, '¿Cuál es el impacto de construir un ambiente de trabajo inclusivo y motivador en la seguridad vial?', 'No hay impacto.', 'Mejora el bienestar y la colaboración entre los conductores.', 'No es necesario construir un ambiente inclusivo.', 'B');
INSERT INTO `preguntas` VALUES (108, 5, 19, '¿Qué acciones pueden contribuir a la construcción de un ambiente de trabajo inclusivo y motivador?', 'No tomar acciones.', 'Promover la diversidad y reconocer las contribuciones individuales.', 'Limitar la comunicación entre conductores.', 'B');
INSERT INTO `preguntas` VALUES (109, 5, 20, '¿Por qué es importante identificar conflictos comunes en el lugar de trabajo?', 'No es importante identificar conflictos.', 'Para evitar la resolución de problemas.', 'Para abordar y resolver problemas de manera eficaz.', 'C');
INSERT INTO `preguntas` VALUES (110, 5, 20, '¿Qué técnicas de resolución de conflictos son efectivas para mantener la armonía en el equipo?', 'No utilizar técnicas de resolución.', 'Fomentar la confrontación directa y evitar la comunicación.', 'Utilizar la comunicación abierta y la empatía para encontrar soluciones mutuamente aceptables.', 'C');
INSERT INTO `preguntas` VALUES (111, 5, 20, '¿Por qué es importante la comunicación abierta y la empatía en la resolución de problemas laborales?', 'No es importante.', 'Para mejorar la comunicación entre colegas.', 'Para comprender las perspectivas y llegar a soluciones efectivas.', 'C');
INSERT INTO `preguntas` VALUES (112, 5, 20, '¿Qué beneficios puede traer la gestión efectiva de conflictos en el entorno laboral?', 'Ningún beneficio.', 'Mejora del ambiente laboral y relaciones interpersonales.', 'No es necesario gestionar conflictos.', 'B');
INSERT INTO `preguntas` VALUES (113, 6, 21, '¿Por qué es beneficioso implementar sistemas de navegación GPS y rastreo de vehículos?', 'No es beneficioso.', 'Para mejorar la eficiencia operativa y la navegación precisa.', 'No es necesario utilizar sistemas de navegación.', 'B');
INSERT INTO `preguntas` VALUES (114, 6, 21, '¿Cómo pueden las dashcams y cámaras de retroceso mejorar la seguridad en la conducción?', 'No tienen impacto en la seguridad.', 'Proporcionando una visión clara y mejorando la conciencia situacional.', 'No es necesario utilizar dashcams y cámaras de retroceso.', 'B');
INSERT INTO `preguntas` VALUES (115, 6, 21, '¿Cuál es el objetivo principal de las tecnologías de asistencia al conductor en la prevención de accidentes?', 'No tienen objetivo.', 'Automatizar por completo la conducción.', 'Asistir al conductor en la detección y respuesta a situaciones peligrosas.', 'C');
INSERT INTO `preguntas` VALUES (116, 6, 21, '¿Por qué es esencial actualizar y mantener el software de los sistemas de asistencia al conductor?', 'No es esencial.', 'Para asegurar que las funciones de seguridad estén optimizadas y actualizadas.', 'No es necesario actualizar el software.', 'B');
INSERT INTO `preguntas` VALUES (117, 6, 22, '¿Qué medidas pueden tomarse para proteger los sistemas de gestión de flotas contra ciberataques?', 'No tomar medidas.', 'Implementar protocolos de seguridad robustos y mantener el software actualizado.', 'No es posible proteger contra ciberataques.', 'B');
INSERT INTO `preguntas` VALUES (118, 6, 22, '¿Por qué es importante la seguridad de la información y la protección de datos del conductor en sistemas digitales?', 'No es importante proteger la información.', 'Para evitar la divulgación no autorizada de información personal y de rutas.', 'No es necesario proteger la información del conductor.', 'B');
INSERT INTO `preguntas` VALUES (119, 6, 22, '¿Cuál es la importancia de actualizar regularmente el software en los sistemas digitales de gestión de flotas?', 'No hay importancia.', 'Para garantizar que las vulnerabilidades de seguridad sean mitigadas y la operatividad sea óptima.', 'No es necesario actualizar el software.', 'B');
INSERT INTO `preguntas` VALUES (120, 6, 22, '¿Qué acciones pueden tomarse para mantener la seguridad en carreteras digitales?', 'No tomar acciones.', 'Implementar sistemas de seguridad física únicamente.', 'Actualizar y mantener regularmente el software para prevenir vulnerabilidades.', 'C');
INSERT INTO `preguntas` VALUES (121, 6, 23, '¿Cómo pueden los vehículos autónomos impactar positivamente en la industria del transporte?', 'No pueden impactar positivamente.', 'Mejorando la eficiencia operativa y la seguridad en la carretera.', 'No es posible utilizar vehículos autónomos.', 'B');
INSERT INTO `preguntas` VALUES (122, 6, 23, '¿Qué beneficios pueden proporcionar los sistemas de comunicación vehículo a vehículo (V2V) en términos de seguridad vial?', 'No proporcionan beneficios.', 'Mejora de la capacidad de conducción autónoma.', 'Aumento de la conciencia situacional y prevención de colisiones.', 'C');
INSERT INTO `preguntas` VALUES (123, 6, 23, '¿Por qué es útil el uso de telemática y análisis de datos en la logística de transporte?', 'No es útil.', 'Para optimizar rutas, tiempos de entrega y consumo de combustible.', 'No es posible utilizar telemática en la logística de transporte.', 'B');
INSERT INTO `preguntas` VALUES (124, 6, 23, '¿Cuál es el impacto potencial de las tecnologías emergentes en la seguridad y eficiencia del transporte?', 'No hay impacto potencial.', 'Mejora de la seguridad, eficiencia operativa y reducción de costos.', 'Las tecnologías emergentes no afectan la seguridad del transporte.', 'B');
INSERT INTO `preguntas` VALUES (129, 7, 24, '¿Por qué es importante adoptar prácticas de conducción ecoeficientes para reducir el consumo de combustible?', 'Para aumentar el consumo de combustible.', 'Para mejorar el rendimiento del vehículo.', 'Para reducir el consumo de combustible.', 'C');
INSERT INTO `preguntas` VALUES (130, 7, 24, '¿Qué tecnologías de propulsión alternativa pueden implementarse para reducir las emisiones?', 'Sistemas de combustión interna convencionales.', 'No hay tecnologías alternativas para la propulsión.', 'Vehículos eléctricos o híbridos.', 'C');
INSERT INTO `preguntas` VALUES (131, 7, 24, '¿Qué estrategias de mantenimiento pueden optimizar el rendimiento ambiental de los vehículos?', 'No es necesario mantener los vehículos.', 'Mantener los vehículos en condiciones óptimas de funcionamiento.', 'No hay estrategias de mantenimiento ambiental.', 'B');
INSERT INTO `preguntas` VALUES (132, 7, 24, '¿Qué impacto puede tener la conducción ecoeficiente en la reducción de emisiones contaminantes?', 'Ningún impacto.', 'Reducción significativa de emisiones al minimizar el consumo de combustible.', 'Aumento de las emisiones.', 'B');
INSERT INTO `preguntas` VALUES (133, 7, 25, '¿Cuáles son las buenas prácticas para la disposición adecuada de residuos generados durante el transporte?', 'No hay prácticas adecuadas para la disposición de residuos.', 'Reciclar solo materiales orgánicos.', 'Disponer adecuadamente los residuos y reciclar materiales.', 'C');
INSERT INTO `preguntas` VALUES (134, 7, 25, '¿Qué se puede reciclar en la industria del transporte para reducir el impacto ambiental?', 'Solo plásticos.', 'Materiales de embalaje y productos desechables.', 'No se pueden reciclar materiales en la industria del transporte.', 'B');
INSERT INTO `preguntas` VALUES (135, 7, 25, '¿Qué promueve la economía circular en la cadena de suministro de transporte?', 'El uso exclusivo de recursos nuevos.', 'La reutilización de recursos y la reducción de residuos.', 'La contaminación ambiental.', 'B');
INSERT INTO `preguntas` VALUES (136, 7, 25, '¿Por qué es importante implementar prácticas de reciclaje en la industria del transporte?', 'No es importante el reciclaje en el transporte.', 'Para reducir la huella ambiental y fomentar la sostenibilidad.', 'Para aumentar los costos de operación.', 'B');
INSERT INTO `preguntas` VALUES (137, 7, 26, '¿Por qué es importante participar en iniciativas de responsabilidad social corporativa relacionadas con el transporte?', 'No es importante la responsabilidad social corporativa.', 'Para mejorar la imagen corporativa y contribuir al bienestar social.', 'No hay iniciativas de responsabilidad social corporativa.', 'B');
INSERT INTO `preguntas` VALUES (138, 7, 26, '¿Cómo puede colaborar el transporte con las comunidades locales para minimizar el impacto ambiental y social?', 'No colaborando con las comunidades locales.', 'Aislándose de las comunidades.', 'Colaborando activamente para minimizar impactos negativos y mejorar condiciones locales.', 'C');
INSERT INTO `preguntas` VALUES (139, 7, 26, '¿Por qué es importante sensibilizar sobre la sostenibilidad en la industria del transporte?', 'No es importante sensibilizar sobre sostenibilidad.', 'Para crear conciencia y promover prácticas sostenibles entre los actores del transporte.', 'No es necesario informar sobre sostenibilidad.', 'B');
INSERT INTO `preguntas` VALUES (140, 7, 26, '¿Qué beneficios puede traer el compromiso social y ambiental en la industria del transporte?', 'Ningún beneficio.', 'Mejora de la reputación corporativa y contribución al desarrollo sostenible.', 'Incremento de emisiones contaminantes.', 'B');

-- ----------------------------
-- Table structure for preguntasmental
-- ----------------------------
DROP TABLE IF EXISTS `preguntasmental`;
CREATE TABLE `preguntasmental`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDPrueba` int(11) NOT NULL,
  `Texto` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  INDEX `fk_prueba`(`IDPrueba`) USING BTREE,
  CONSTRAINT `fk_prueba` FOREIGN KEY (`IDPrueba`) REFERENCES `pruebas` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of preguntasmental
-- ----------------------------
INSERT INTO `preguntasmental` VALUES (1, 1, '¿Te sientes tranquilo durante el día de trabajo?');
INSERT INTO `preguntasmental` VALUES (2, 1, '¿Puedes manejar el estrés de manera efectiva?');
INSERT INTO `preguntasmental` VALUES (3, 1, '¿Te sientes abrumado frecuentemente?');
INSERT INTO `preguntasmental` VALUES (4, 2, '¿Te sientes estresado durante el trabajo?');
INSERT INTO `preguntasmental` VALUES (5, 2, '¿Tienes dificultades para dormir debido al estrés?');
INSERT INTO `preguntasmental` VALUES (6, 2, '¿Te resulta difícil concentrarte en el trabajo debido al estrés?');
INSERT INTO `preguntasmental` VALUES (7, 4, '¿Sufres de dolores de espalda?');
INSERT INTO `preguntasmental` VALUES (8, 4, '¿Tomas agua durante tus viajes?');

-- ----------------------------
-- Table structure for pruebas
-- ----------------------------
DROP TABLE IF EXISTS `pruebas`;
CREATE TABLE `pruebas`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Descripcion` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `FechaCreacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pruebas
-- ----------------------------
INSERT INTO `pruebas` VALUES (1, 'Evaluación Emocional', 'Prueba para evaluar el estado emocional del transportista.', '2024-06-26 10:13:10');
INSERT INTO `pruebas` VALUES (2, 'Prueba de Estrés', 'Prueba para medir los niveles de estrés del transportista.', '2024-06-26 10:13:10');
INSERT INTO `pruebas` VALUES (4, 'Salud Fisica', 'Mediciones para evaluar su estado de salud física.', '2024-06-26 23:14:25');

-- ----------------------------
-- Table structure for respuestas
-- ----------------------------
DROP TABLE IF EXISTS `respuestas`;
CREATE TABLE `respuestas`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDPregunta` int(11) NOT NULL,
  `Texto` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Puntaje` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  INDEX `fk_pregunta`(`IDPregunta`) USING BTREE,
  CONSTRAINT `fk_pregunta` FOREIGN KEY (`IDPregunta`) REFERENCES `preguntasmental` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of respuestas
-- ----------------------------
INSERT INTO `respuestas` VALUES (1, 1, 'Sí, siempre', 10);
INSERT INTO `respuestas` VALUES (2, 1, 'A veces', 5);
INSERT INTO `respuestas` VALUES (3, 1, 'No, nunca', 0);
INSERT INTO `respuestas` VALUES (4, 2, 'Sí, siempre', 10);
INSERT INTO `respuestas` VALUES (5, 2, 'A veces', 5);
INSERT INTO `respuestas` VALUES (6, 2, 'No, nunca', 0);
INSERT INTO `respuestas` VALUES (7, 3, 'Sí, siempre', 0);
INSERT INTO `respuestas` VALUES (8, 3, 'A veces', 5);
INSERT INTO `respuestas` VALUES (9, 3, 'No, nunca', 10);
INSERT INTO `respuestas` VALUES (10, 4, 'Sí, siempre', 0);
INSERT INTO `respuestas` VALUES (11, 4, 'A veces', 5);
INSERT INTO `respuestas` VALUES (12, 4, 'No, nunca', 10);
INSERT INTO `respuestas` VALUES (13, 5, 'Sí, siempre', 0);
INSERT INTO `respuestas` VALUES (14, 5, 'A veces', 5);
INSERT INTO `respuestas` VALUES (15, 5, 'No, nunca', 10);
INSERT INTO `respuestas` VALUES (16, 6, 'Sí, siempre', 0);
INSERT INTO `respuestas` VALUES (17, 6, 'A veces', 5);
INSERT INTO `respuestas` VALUES (18, 6, 'No, nunca', 10);
INSERT INTO `respuestas` VALUES (20, 7, 'muchaaa', 5);

-- ----------------------------
-- Table structure for resultados
-- ----------------------------
DROP TABLE IF EXISTS `resultados`;
CREATE TABLE `resultados`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDTransportista` int(11) NOT NULL,
  `IDPrueba` int(11) NOT NULL,
  `FechaRealizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `PuntajeTotal` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`) USING BTREE,
  INDEX `fk_transportista`(`IDTransportista`) USING BTREE,
  INDEX `fk_prueba`(`IDPrueba`) USING BTREE,
  CONSTRAINT `fk_resultados_prueba` FOREIGN KEY (`IDPrueba`) REFERENCES `pruebas` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_resultados_transportista` FOREIGN KEY (`IDTransportista`) REFERENCES `transportistas` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of resultados
-- ----------------------------
INSERT INTO `resultados` VALUES (15, 2, 1, '2024-06-26 21:35:59', 14);
INSERT INTO `resultados` VALUES (16, 2, 2, '2024-06-26 21:36:33', 43);
INSERT INTO `resultados` VALUES (17, 5, 4, '2024-06-27 02:14:38', 20);
INSERT INTO `resultados` VALUES (18, 5, 1, '2024-06-27 02:14:58', 18);
INSERT INTO `resultados` VALUES (19, 5, 2, '2024-06-27 02:15:13', 45);

-- ----------------------------
-- Table structure for resultados_quiz
-- ----------------------------
DROP TABLE IF EXISTS `resultados_quiz`;
CREATE TABLE `resultados_quiz`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDUsuario` int(11) NOT NULL,
  `IDCurso` int(11) NOT NULL,
  `IDSubtema` int(11) NOT NULL,
  `Puntaje` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  INDEX `IDUsuario`(`IDUsuario`) USING BTREE,
  INDEX `IDCurso`(`IDCurso`) USING BTREE,
  INDEX `IDSubtema`(`IDSubtema`) USING BTREE,
  CONSTRAINT `fk_resultados_curso` FOREIGN KEY (`IDCurso`) REFERENCES `cursos` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_resultados_subtema` FOREIGN KEY (`IDSubtema`) REFERENCES `subtemas` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_resultados_usuario` FOREIGN KEY (`IDUsuario`) REFERENCES `transportistas` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 202 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of resultados_quiz
-- ----------------------------
INSERT INTO `resultados_quiz` VALUES (175, 2, 1, 1, 1);
INSERT INTO `resultados_quiz` VALUES (176, 2, 1, 2, 0);
INSERT INTO `resultados_quiz` VALUES (177, 2, 1, 3, 1);
INSERT INTO `resultados_quiz` VALUES (197, 2, 1, 4, 1);
INSERT INTO `resultados_quiz` VALUES (198, 5, 1, 1, 1);
INSERT INTO `resultados_quiz` VALUES (199, 5, 1, 2, 1);
INSERT INTO `resultados_quiz` VALUES (200, 5, 1, 3, 1);
INSERT INTO `resultados_quiz` VALUES (201, 5, 1, 4, 1);

-- ----------------------------
-- Table structure for revisiones
-- ----------------------------
DROP TABLE IF EXISTS `revisiones`;
CREATE TABLE `revisiones`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDCamion` int(11) NULL DEFAULT NULL,
  `IDSeccion` int(11) NULL DEFAULT NULL,
  `Estado` enum('Buen estado','Necesita revisión','Requiere reparación') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `Observaciones` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `IDEstadoCamion` int(11) NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  INDEX `IDCamion`(`IDCamion`) USING BTREE,
  INDEX `IDSeccion`(`IDSeccion`) USING BTREE,
  INDEX `FK_Revisiones_Estados`(`IDEstadoCamion`) USING BTREE,
  CONSTRAINT `FK_Revisiones_Estados` FOREIGN KEY (`IDEstadoCamion`) REFERENCES `estados_camiones` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_revisiones_camiones` FOREIGN KEY (`IDCamion`) REFERENCES `camiones` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_revisiones_secciones` FOREIGN KEY (`IDSeccion`) REFERENCES `secciones` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of revisiones
-- ----------------------------
INSERT INTO `revisiones` VALUES (13, 16, 2, 'Buen estado', 'scsdvvwdw', 2);
INSERT INTO `revisiones` VALUES (14, 17, 1, 'Necesita revisión', 'dvfvre', 1);

-- ----------------------------
-- Table structure for secciones
-- ----------------------------
DROP TABLE IF EXISTS `secciones`;
CREATE TABLE `secciones`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of secciones
-- ----------------------------
INSERT INTO `secciones` VALUES (1, 'Motor');
INSERT INTO `secciones` VALUES (2, 'Neumáticos');
INSERT INTO `secciones` VALUES (3, 'Frenos');
INSERT INTO `secciones` VALUES (4, 'Luces');

-- ----------------------------
-- Table structure for subtemas
-- ----------------------------
DROP TABLE IF EXISTS `subtemas`;
CREATE TABLE `subtemas`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDCurso` int(11) NULL DEFAULT NULL,
  `Nombre` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `Descripcion` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `Orden` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  INDEX `IDCurso`(`IDCurso`) USING BTREE,
  CONSTRAINT `subtemas_ibfk_1` FOREIGN KEY (`IDCurso`) REFERENCES `cursos` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of subtemas
-- ----------------------------
INSERT INTO `subtemas` VALUES (1, 1, 'Manejo de la Fatiga', 'Técnicas para manejar la fatiga al conducir.', 1);
INSERT INTO `subtemas` VALUES (2, 1, 'Prevención de Lesiones', 'Cómo prevenir lesiones durante la conducción.', 2);
INSERT INTO `subtemas` VALUES (3, 1, 'Salud Mental y Bienestar', 'Promoción de la salud mental y bienestar para conductores.', 3);
INSERT INTO `subtemas` VALUES (4, 1, 'Nutrición y Hidratación', 'Importancia de la nutrición y la hidratación en conductores.', 4);
INSERT INTO `subtemas` VALUES (5, 2, 'Inspección Previa al Viaje', 'Procedimientos para una inspección exhaustiva antes de iniciar el viaje.', 1);
INSERT INTO `subtemas` VALUES (6, 2, 'Cuidado de Neumáticos', 'Técnicas para mantener los neumáticos en buen estado.', 2);
INSERT INTO `subtemas` VALUES (7, 2, 'Sistemas de Frenado', 'Mantenimiento y funcionamiento de sistemas de frenado.', 3);
INSERT INTO `subtemas` VALUES (8, 2, 'Mantenimiento del Motor', 'Procedimientos de mantenimiento para el motor del vehículo.', 4);
INSERT INTO `subtemas` VALUES (9, 3, 'Leyes de Tránsito', 'Derechos y responsabilidades de los conductores según la ley mexicana.', 1);
INSERT INTO `subtemas` VALUES (10, 3, 'Señalización Vial', 'Reconocimiento e interpretación de señales de tráfico.', 2);
INSERT INTO `subtemas` VALUES (11, 3, 'Normativas de Carga y Descarga', 'Procedimientos legales y seguridad en la carga y descarga.', 3);
INSERT INTO `subtemas` VALUES (12, 3, 'Regulaciones Ambientales', 'Cumplimiento de normativas ambientales en el transporte.', 4);
INSERT INTO `subtemas` VALUES (13, 4, 'Comunicación Efectiva', 'Importancia de la comunicación clara entre conductores y supervisores.', 1);
INSERT INTO `subtemas` VALUES (14, 4, 'Gestión de Riesgos en Carretera', 'Identificación de peligros y protocolos de respuesta ante incidentes.', 2);
INSERT INTO `subtemas` VALUES (15, 4, 'Seguridad en la Carga', 'Métodos de sujeción de carga y seguridad durante el transporte.', 3);
INSERT INTO `subtemas` VALUES (16, 4, 'Prevención de Robos y Delitos', 'Medidas de seguridad para proteger la carga y el vehículo.', 4);
INSERT INTO `subtemas` VALUES (17, 5, 'Ética y Responsabilidad Profesional', 'Cumplimiento de horarios y normativas de la empresa.', 1);
INSERT INTO `subtemas` VALUES (18, 5, 'Desarrollo de Habilidades', 'Mejora continua de habilidades de conducción y resolución de problemas.', 2);
INSERT INTO `subtemas` VALUES (19, 5, 'Liderazgo y Trabajo en Equipo', 'Fomento de la colaboración entre conductores y liderazgo en seguridad vial.', 3);
INSERT INTO `subtemas` VALUES (20, 5, 'Gestión de Conflictos', 'Técnicas para la resolución de conflictos en el entorno laboral.', 4);
INSERT INTO `subtemas` VALUES (21, 6, 'Uso de Tecnología en la Conducción', 'Implementación de tecnologías para mejorar la seguridad y eficiencia.', 1);
INSERT INTO `subtemas` VALUES (22, 6, 'Seguridad en Carreteras Digitales', 'Protección contra ciberataques y seguridad de la información.', 2);
INSERT INTO `subtemas` VALUES (23, 6, 'Tecnologías Emergentes en Transporte', 'Exploración de vehículos autónomos y telemática en transporte.', 3);
INSERT INTO `subtemas` VALUES (24, 7, 'Eficiencia Energética y Reducción de Emisiones', 'Prácticas ecoeficientes para reducir el impacto ambiental del transporte.', 1);
INSERT INTO `subtemas` VALUES (25, 7, 'Gestión de Residuos y Reciclaje', 'Reciclaje de materiales y gestión adecuada de residuos en transporte.', 2);
INSERT INTO `subtemas` VALUES (26, 7, 'Compromiso Social y Ambiental', 'Participación en iniciativas de responsabilidad social y ambiental.', 3);

-- ----------------------------
-- Table structure for transportistas
-- ----------------------------
DROP TABLE IF EXISTS `transportistas`;
CREATE TABLE `transportistas`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NumeroTrabajador` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `Nombre` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `Apellido` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `Contraseña` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `Imagen` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `CorreoElectronico` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `EstaActivo` tinyint(1) NULL DEFAULT NULL,
  `EsAdmin` tinyint(1) NULL DEFAULT 0,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of transportistas
-- ----------------------------
INSERT INTO `transportistas` VALUES (2, '123', 'Ivan', 'Mora', '$2y$10$Ru2iNzgCjKBtTSa4x0Ca7uxTXffi0l07RVcLOoGTNEllNkKzRf5bu', 'Recursos/imagenes/Mora.png', 'moragarcia012@gmail.com', 1, 0);
INSERT INTO `transportistas` VALUES (5, '889', 'admin', 'ranof', '$2y$10$rVSltSzLMC7vtv1jj7Qp6.8e6QuHS7bSTlBD1KZuZvUl1qUIz78wO', 'Recursos/imagenes/admin.png', 'admin@gmail.com', 1, 1);

-- ----------------------------
-- Table structure for videos
-- ----------------------------
DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDSubtema` int(11) NULL DEFAULT NULL,
  `Titulo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `Descripcion` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `VideoURL` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `Orden` int(11) NULL DEFAULT NULL,
  `MiniaturaVideo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  INDEX `IDSubtema`(`IDSubtema`) USING BTREE,
  CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`IDSubtema`) REFERENCES `subtemas` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of videos
-- ----------------------------
INSERT INTO `videos` VALUES (2, 2, 'Prevención de Lesiones', 'Prevención de Lesiones', 'Cursos/01_SaludYSeguridad/video2.mp4', 2, 'Cursos\\01_SaludYSeguridad\\3.png');
INSERT INTO `videos` VALUES (3, 3, 'Salud Mental y Bienestar', 'Salud Mental y Bienestar', 'Cursos/01_SaludYSeguridad/video3.mp4', 3, 'Cursos\\01_SaludYSeguridad\\4.png');
INSERT INTO `videos` VALUES (4, 4, 'Nutrición y Hidratación', 'Nutrición y Hidratación', 'Cursos/01_SaludYSeguridad/video4.mp4', 4, 'Cursos\\01_SaludYSeguridad\\5.png');
INSERT INTO `videos` VALUES (5, 5, 'Inspección Previa al Viaje', 'Procedimientos para una inspección exhaustiva antes de iniciar el viaje.', 'Cursos\\02_mantenimiento\\video1.mp4', 1, 'Cursos\\02_mantenimiento\\2.png');
INSERT INTO `videos` VALUES (6, 1, 'Manejo del Estres', 'Manejo del estres', 'Cursos\\01_SaludYSeguridad\\video1.mp4', 1, 'Cursos\\01_SaludYSeguridad\\1.png');

-- ----------------------------
-- Table structure for visualizaciones_cursos
-- ----------------------------
DROP TABLE IF EXISTS `visualizaciones_cursos`;
CREATE TABLE `visualizaciones_cursos`  (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDTransportista` int(11) NULL DEFAULT NULL,
  `IDCurso` int(11) NULL DEFAULT NULL,
  `FechaVisualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Visto` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  INDEX `IDTransportista`(`IDTransportista`) USING BTREE,
  INDEX `IDCurso`(`IDCurso`) USING BTREE,
  CONSTRAINT `visualizaciones_cursos_ibfk_1` FOREIGN KEY (`IDTransportista`) REFERENCES `transportistas` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `visualizaciones_cursos_ibfk_2` FOREIGN KEY (`IDCurso`) REFERENCES `cursos` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of visualizaciones_cursos
-- ----------------------------

-- ----------------------------
-- Procedure structure for VerificarCertificado
-- ----------------------------
DROP PROCEDURE IF EXISTS `VerificarCertificado`;
delimiter ;;
CREATE PROCEDURE `VerificarCertificado`(IN curso_id INT, IN usuario_id INT, OUT certificado_disponible BOOLEAN)
BEGIN
    DECLARE total_subtemas INT;
    DECLARE subtemas_con_quiz_resuelto INT;

    -- Contar todos los subtemas del curso específico
    SELECT COUNT(s.ID) INTO total_subtemas
    FROM subtemas s
    WHERE s.IDCurso = curso_id;

    -- Contar los subtemas que tienen quiz resuelto para el curso específico y usuario específico
    SELECT COUNT(DISTINCT r.IDSubtema) INTO subtemas_con_quiz_resuelto
    FROM subtemas s
    LEFT JOIN resultados_quiz r ON s.ID = r.IDSubtema AND r.IDUsuario = usuario_id
    WHERE s.IDCurso = curso_id;

    -- Verificar si todos los subtemas tienen su quiz resuelto
    IF total_subtemas > 0 AND total_subtemas = subtemas_con_quiz_resuelto THEN
        SET certificado_disponible = TRUE;
    ELSE
        SET certificado_disponible = FALSE;
    END IF;
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
