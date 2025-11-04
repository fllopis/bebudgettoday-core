-- TABLA USUARIOS_ADMIN --
CREATE TABLE usuarios_admin (
id_usuario_admin int(10) NOT NULL,
nombre varchar(100) NOT NULL,
email varchar(100) NOT NULL,
password varchar(250) NOT NULL,
date_created datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE usuarios_admin ADD PRIMARY KEY (id_usuario_admin);
ALTER TABLE usuarios_admin MODIFY id_usuario_admin int(10) NOT NULL AUTO_INCREMENT;

-- TABLA EMAILS_CACHE --
CREATE TABLE emails_cache (
id_email int(255) NOT NULL,
asunto varchar(250) NOT NULL,
mensaje text NOT NULL,
destinatario varchar(100) NOT NULL,
enviado int(1) NOT NULL DEFAULT 0,
error int(1) NOT NULL DEFAULT 0,
date_sent datetime NOT NULL,
date_created datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE emails_cache ADD PRIMARY KEY (id_email);
ALTER TABLE emails_cache MODIFY id_email int(255) NOT NULL AUTO_INCREMENT;

-- TABLA CONFIGURACION --
CREATE TABLE configuracion (
id_configuracion int(10) UNSIGNED NOT NULL,
nombre varchar(254) NOT NULL,
valor text,
date_modified datetime NOT NULL,
date_created datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE configuracion ADD PRIMARY KEY (id_configuracion), ADD KEY nombre (nombre);
ALTER TABLE configuracion MODIFY id_configuracion int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

INSERT INTO `configuracion` (`id_configuracion`, `nombre`, `valor`, `date_modified`, `date_created`) VALUES (NULL, 'cronjob_email_cantidad', '10', NOW(), NOW());
