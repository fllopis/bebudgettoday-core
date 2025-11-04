<?php

//Incluimos nucleo
include('core/core.php');

//Cargamos layout
$app['render']->getLayout();

//Mostramos debug
if ( _DEBUG_ && $app['render']->layout )
    $app['debug']->callLog();
