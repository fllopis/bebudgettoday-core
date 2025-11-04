<?php

/*
     ----   ----    ---    ----
    |      |    |  | _ |  |_
    |      |    |  |  \   |
     ----   ----   -   -   ----   3.0
*/

/**
 * @author Anelis Network
 */

@session_start();

// Definimos constantes
define( 'DS', DIRECTORY_SEPARATOR );
define( '_PATH_', str_replace(DS.'core',DS,dirname(__FILE__)) );

// Incluimos configuracion
require dirname(__FILE__).'/config.php';

define( 'log_folder', _PATH_.'log/' );
define( 'log_max_kb', 2048 );

// Autoload de todas las clases
spl_autoload_register(function($class)
{
    if ( strpos($class, 'Funks') !== false || strpos($class, 'App') !== false || strpos($class, 'Controller') !== false )
    {
        $class = str_replace("\\","/", $class);
        include _PATH_.'core'.DS.$class.'.php';
    }
});

// Función genérica var_dump
function vd($var1, $var2='')
{
    if( empty($var2) )
    {
        echo '<pre>';
        var_dump($var1);
        echo '</pre>';
        die;
    }
    else
    {
        echo '<div style="width:50%; float:left;"><pre>';
        var_dump($var1);
        echo '</pre></div>';
        echo '<div style="width:50%; float:left;"><pre>';
        var_dump($var2);
        echo '</pre></div><div style="clear:both;"></div>';
        die;
    }
}

function el($var, $base64_encode=true, $json_encode=true)
{
    if( $base64_encode && $json_encode )
        $var_to_dump = base64_encode(json_encode($var));
    elseif( $base64_encode && !$json_encode )
        $var_to_dump = base64_encode($var);
    elseif( !$base64_encode && $json_encode )
        $var_to_dump = json_encode($var);
    else
        $var_to_dump = $var;

    error_log($var_to_dump);
}

// Render
$render = new App\Render();

// Creamos App
$app = $render->APP();

// Abrimos base de datos
$app['bd']->openBd();

//Establecemos idioma por defecto
$app['lang']->setLanguage();

// Cargamos controllador
$app['controller']->load();

// Cerramos base de datos
$app['bd']->closeBd();

// Mostramos errores si corresponde
if ( _DEBUG_ && $app['render']->layout )
    ini_set("display_errors", 1);
else
    ini_set("display_errors", 0);
