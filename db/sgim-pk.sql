CREATE DATABASE IF NOT EXISTS `SGIM_PK`;
USE `SGIM_PK`;

CREATE TABLE IF NOT EXISTS `perfil` ( 
	`id_perfil` int(3) NOT NULL,
	`nombre_perfil` varchar(20) NOT NULL,
	`privilegio_perfil` text NOT NULL,
	`status_perfil` char(1) NOT NULL,
	PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `persona` (
	`tipodoc_persona` varchar(1) NOT NULL,
	`documento_persona` varchar(10) NOT NULL,
	`nombre_persona` varchar(40) NOT NULL,
	`apellido_persona` varchar(40) NOT NULL,
	`telefono_persona` varchar(14),
	`correo_persona` varchar(60),
	`tipo_persona` char(1),	
	`id_nivel_educativo` int(2) NOT NULL,
	`status_persona` char(1) NOT NULL,
	PRIMARY KEY (`documento_persona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `usuario` (
	`id_usuario` int(3) NOT NULL,
	`nombre_usuario` varchar(20) NOT NULL,				
	`clave_usuario` varchar(15) NOT NULL,
	`id_perfil` int(3) NOT NULL,
	`documento_persona` varchar(10) NOT NULL,
	`foto_persona` text,
	`status_usuario` char(1) NOT NULL,
 	PRIMARY KEY (`id_usuario`),
 	CONSTRAINT `usuario-perfil` FOREIGN KEY (`id_perfil`) REFERENCES `perfil`(`id_perfil`),
 	CONSTRAINT `usuario-persona` FOREIGN KEY (`documento_persona`) REFERENCES `persona`(`documento_persona`)
) ENGINE = innodb DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `sesion` (
	`id_sesion` int(3) NOT NULL,
	`inicio` datetime NOT NULL,
	`cierre` datetime DEFAULT NULL,
	`ip_usuario` varchar(50) DEFAULT NULL,
	`id_usuario` int(3) NOT NULL,
	`recordar` boolean NOT NULL,
	`token` varchar(32),	
	PRIMARY KEY (`id_sesion`),
 	CONSTRAINT `sesion-usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario`(`id_usuario`)
)ENGINE = innodb DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `bitacora` (
	`id_bitacora` int(3) NOT NULL,
	`hora` varchar(20) NOT NULL,
	`fecha` date NOT NULL,
	`modulo` varchar(30) NULL,
	`funcion` varchar(30) NOT NULL,
	`id_reg` int(3) NULL,
	`id_sesion` int(3) NOT NULL,
	PRIMARY KEY (`id_bitacora`),
 	CONSTRAINT `bitacora-sesion` FOREIGN KEY (`id_sesion`) REFERENCES `sesion`(`id_sesion`)
)ENGINE = innodb DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `estado` (
	`codigo_estado` int(4) NOT NULL,
	`nombre_estado` varchar(40) NOT NULL,
	`codigo_pais` int(3) NOT NULL,
	`status_estado` char(1) NOT NULL,
	PRIMARY KEY (`codigo_estado`)
) ENGINE = innodb DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `municipio` (
	`codigo_municipio` int(4) NOT NULL,
	`nombre_municipio` varchar(40) NOT NULL,
	`codigo_estado` int(4) NOT NULL,
	`status_municipio` char(1) NOT NULL,
	PRIMARY KEY (`codigo_municipio`),
	CONSTRAINT `municipio-estado` FOREIGN KEY (`codigo_estado`) REFERENCES `estado`(`codigo_estado`)
) ENGINE = innodb DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `parroquia` (
	`codigo_parroquia` int(4) NOT NULL,
	`nombre_parroquia` varchar(40) NOT NULL,
	`codigo_municipio` int(4) NOT NULL,
	`status_parroquia` char(1) NOT NULL,
	PRIMARY KEY (`codigo_parroquia`),
	CONSTRAINT `parroquia-municipio` FOREIGN KEY (`codigo_municipio`) REFERENCES `municipio`(`codigo_municipio`)
) ENGINE = innodb DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `direccion` (
	`id_direccion` int(3) NOT NULL,
	`calle_direccion` varchar(30) NOT NULL,
	`avenida_direccion` varchar(30) NOT NULL,
	`referencia_direccion` text NOT NULL,
	`rif_institucion` varchar(15) NOT NULL,
	PRIMARY KEY (`id_direccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;