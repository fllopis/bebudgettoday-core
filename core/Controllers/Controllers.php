<?php
namespace Controllers;
use Controllers\DefaultController;
use Controllers\AjaxController;
use Controllers\AdminController;
use Controllers\DebugController;
use Controllers\CronsController;
use Controllers\ApiController;

/* Funciones de renderizado */
class Controllers
{
	//Controlador por defecto
	var $defaultController = 'default';
	var $app;
	var $cntrl = array();

	public function __construct($app)
	{
		$this->app = $app;

		//Cargamos controladores
		$this->cntrl['default'] = new DefaultController;
		$this->cntrl['ajax']	= new AjaxController;
		$this->cntrl['admin']   = new AdminController;
		$this->cntrl['debug']   = new DebugController;
		$this->cntrl['crons']   = new CronsController;
		$this->cntrl['api']   	= new ApiController;
	}

	//Leemos controlador
	public function load()
	{
		//Language
		/*if(isset($_GET['lang'])){

			//Obtenemos el parametro idioma de la URL.
			$lang = $this->app['tools']->getValue('lang');

			//Comprobamos si el "LANG" que seria como un MOD especial es BLOG.
			if($lang != 'blog'){

				//Comprobamos que exista el idioma, si no redirigimos al idioma defecto o al de la sesion.
				$language = $this->app['lang']->getLangBySlug($lang);

				//Si el idioma existe, actualizamos la sesion, pero si no existe comprobamos si existe la sesion para redirigir a la home o redirigir a la home con el idioma default.
				if($language)
					$_SESSION['lang'] = $lang;
				else{

					//Si el idioma indicado en la URL es erróneo, pasamos a comprobar si existe la sesion de idioma para redirigir a la home.
					if(isset($_SESSION['lang'])){
						header('Location: ' . _DOMINIO_.$_SESSION['lang'].'/');
					}
					else{
						$defaultLang 		= $this->app['lang']->getDefaultLanguage();
						$_SESSION['lang'] 	= $defaultLang->slug;
						header('Location: ' . _DOMINIO_.$defaultLang->slug.'/');
					}
				}
			}
		}
		elseif(isset($_GET['controller']) && $_GET['controller'] == 'default'){

			//Prevent to redirect if is in language es
			if (preg_match('#^/es/?$#', $_SERVER['REQUEST_URI'])) {
				return;
			}
			
			//Si el idioma indicado en la URL es erróneo, pasamos a comprobar si existe la sesion de idioma para redirigir a la home.
			if(isset($_SESSION['lang'])){
				header('Location: ' . _DOMINIO_.$_SESSION['lang'].'/');
				exit;
			}
			else{
				$defaultLang 		= $this->app['lang']->getDefaultLanguage();
				$_SESSION['lang'] 	= $defaultLang->slug;
				header('Location: ' . _DOMINIO_.$defaultLang->slug.'/');
				exit;
			}
		}*/

		//Controlador
		$controller = $this->app['tools']->getValue('controller', $this->defaultController);

		//Pagina
		if( isset($_GET['mod']) )
			$page = $this->app['tools']->getValue('mod');
		else
			$page = '';

		//La pagina será el controlador si existe un controlador llamado así
		if( array_key_exists($page,$this->cntrl) )
		{
			$controller = $page;
			$page = '';
		}

		//Si no existe controlador
		if( !array_key_exists($controller,$this->cntrl) )
		{
			$controller = 'default';
			$page = '404';
		}

		//Ejecutamos controlador
		$this->cntrl[$controller]->execute($page, $this->app);
	}
}
?>
