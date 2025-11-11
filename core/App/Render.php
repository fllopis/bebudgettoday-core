<?php
namespace App;
use App\Bd;
use App\Configuracion;
use App\Debug;
use App\Sendmail;
use App\Tools;
use App\Metas;
use App\Validate;
use App\Languages;
use Controllers\Controllers;

/* Funciones de renderizado */
class Render
{
	var $app;
	var $page;
	var $data;
	var $layout_data;
	var $layout = 'front-end';

	//Funcion que devuelve el layaout.
	function getLayout()
	{
		if( $this->layout_data )
		{
			foreach($this->layout_data as $key => $value)
				${$key} = $value;
		}

		if( $this->data )
		{
			foreach($this->data as $key => $value)
				${$key} = $value;
		}

		if($this->layout)
			include(_PATH_.'layout/'.$this->layout.'.php');
	}

	//Creamos pagina
	public function getPage()
	{
		if( $this->layout_data )
		{
			foreach($this->layout_data as $key => $value)
				${$key} = $value;
		}

		if( $this->data )
		{
			foreach($this->data as $key => $value)
				${$key} = $value;
		}

		$file = _PATH_.'pages'.DIRECTORY_SEPARATOR.$this->page.'.php';

		if( !file_exists($file) )
			$file = _PATH_.'pages'.DIRECTORY_SEPARATOR.'404.php';
	
		include($file);
	}

	//Configuramos página para mostrarla con layout
	public function page($name,$data=array())
	{
		$this->page = $name;
		$this->data = $data;
	}

	//Configuramos y mostramos pagina sin layout
	public function showPage($name,$data=array())
	{
		$this->page = $name;
		$this->data = $data;
		$this->layout = false;
		$this->getPage();
	}

	//Creamos pagina
	public function getAdminPage()
	{
		if( $this->layout_data )
		{
			foreach($this->layout_data as $key => $value)
				${$key} = $value;
		}

		if ( $this->data )
		{
			foreach($this->data as $key => $value)
				${$key} = $value;
		}

		$file = _PATH_.'pages/'._ADMIN_.DIRECTORY_SEPARATOR.$this->page.'.php';

		if ( !file_exists($file) )
			$file = _PATH_.'pages/'._ADMIN_.DIRECTORY_SEPARATOR.'404.php';

		include($file);
	}

	//Configuramos página admin para mostrarla con layout
	public function adminPage($name,$data=array())
	{
		$this->page = $name;
		$this->data = $data;
	}

	//Configuramos y mostramos pagina
	public function showAdminPage($name,$data=array())
	{
		$this->page = $name;
		$this->data = $data;
		$this->layout = false;
		$this->getAdminPage();
	}

	//Mostramos bloque
	public function bloq($page,$data=array())
	{
		if ( $data )
		{
			foreach($data as $key => $value)
				${$key} = $value;
		}

		$file = _PATH_.'pages'.DIRECTORY_SEPARATOR.$page.'.php';

		if ( !file_exists($file) )
			$file = _PATH_.'pages'.DIRECTORY_SEPARATOR.'404.php';

		include($file);
	}

	public function getAjaxPage($name,$data=array())
	{	

		if( $this->layout_data )
		{
			foreach($this->layout_data as $key => $value)
				${$key} = $value;
		}

		if ( $data )
		{
			foreach($data as $key => $value)
				${$key} = $value;
		}

		$file = _PATH_.'pages/ajax'.DIRECTORY_SEPARATOR.$name.'.php';

		if ( !file_exists($file) )
			$file = _PATH_.'pages/ajax'.DIRECTORY_SEPARATOR.'404.php';

		ob_start();
		include($file);
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	//Configuramos aplicacion
	public function APP()
	{
		$app = array();

		$_SESSION['routing'] = array();

		//Añadimos debug
		$app['debug'] = new Debug();

		//Creamos acceso a la base de datos
		$bd = new Bd();
		$bd->bd_name = bd_name;
		$bd->bd_host = bd_host;
		$bd->bd_user = bd_user;
		$bd->bd_pass = bd_pass;
		$app['bd'] = $bd;

		//Añadimos el propio render
		$app['render'] = $this;

		//Añadimos Envío de emails
		$app['mail'] = new Sendmail($app);

		//Añadimos Tools ( Herramientas varias )
		$app['tools'] = new Tools($app);

		//Añadimos Configuracion
		$app['config'] = new Configuracion($app);

		//Añadimos Languages
		$app['lang'] = new Languages($app);

		//Añadimos Validate
		$app['validate'] = new Validate($app);

		//Añadimos controlador
		$app['controller'] = new Controllers($app);

		//Añadimos Metas
		$app['metas'] = new Metas($app);

		$this->app = $app;

		return $app;
	}
}
