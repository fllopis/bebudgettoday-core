<?php

if( !file_exists(_PATH_.'core/settings.php') )
{
	if( file_exists(_PATH_.'install') )
		header('Location: install/');
	else
		die('Error: El directorio de instalación no existe.');
}

require_once('settings.php');

/* Configuración */
date_default_timezone_set('Europe/Madrid');

/* Configuramos dominio */
if( _DEBUG_ )
	define( '_DOMINIO_', _ROOT_DOMINIO_DEV_ );
else
	define( '_DOMINIO_', _ROOT_DOMINIO_ );

define( '_ASSETS_',_DOMINIO_.'assets/' );
define( '_CSS_',_DOMINIO_.'css/' );
define( '_JS_',_DOMINIO_.'js/' );
define( '_IMG_',_DOMINIO_.'img/' );
define( '_INCLUDES_',_PATH_.'includes/' );
define( '_ADMIN_', 'admin/' );

/* Configuracion de Base de datos */
if( _DEBUG_ )
{
	define( 'bd_name', _BD_NAME_DEV_ );
	define( 'bd_host', _BD_HOST_DEV_ );
	define( 'bd_user', _BD_USER_DEV_ );
	define( 'bd_pass', _BD_PASS_DEV_ );
}
else
{
	define( 'bd_name', _BD_NAME_ );
	define( 'bd_host', _BD_HOST_ );
	define( 'bd_user', _BD_USER_ );
	define( 'bd_pass', _BD_PASS_ );
}
