USE `SGIM_PK`;

INSERT INTO `perfil`(
            `id_perfil`, `nombre_perfil`, `privilegio_perfil`, `status_perfil`)
    VALUES 
(1, 'MASTER', '{
    "configuraciones":{
        "usuario":{"page":"usuario", "label":"Usuario"},
        "perfil":{"page":"perfil", "label":"Perfil"},
        "rol":{"page":"rol", "label":"Rol"}
    }
    }', 'A');

INSERT INTO `persona`(
            `tipodoc_persona`, `documento_persona`, `nombre_persona`, `apellido_persona`, `telefono_persona`, `correo_persona`, 
            `tipo_persona`, `status_persona`)
    VALUES 
('V', '23572113', 'SAMUEL', 'ALVAREZ', '00000000000', 'samale456@gmail.com', 'E', 'A');

INSERT INTO `usuario`(
            `id_usuario`, `nombre_usuario`, `clave_usuario`, `id_perfil`, `documento_persona`, `foto_persona`, `status_usuario`)
    VALUES 
(1, 'master', 'master', 1, '23572113', 'files/img/avatar.jpg', 'A');

INSERT INTO `sesion`(
            `id_sesion`, `inicio`, `cierre`, `ip_usuario`, `id_usuario`, `recordar`, `token`)
    VALUES 
(1, '2017-03-20', '2017-03-20', '192.168.2.234', 1, 0, ' ');