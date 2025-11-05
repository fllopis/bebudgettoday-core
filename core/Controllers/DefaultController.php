<?php
namespace Controllers;

class DefaultController
{
	var $page;
	var $app;

	public function execute($page,$app)
	{
		$this->page = $page;
		$this->app = $app;

		//Layout por defecto
		$this->app['render']->layout = 'front-end';

		//PAGE:: Inicio/Home
		$this->add('',function(){

			//Array de datos a enviar a la página
			$data = array(
			);

			$this->app['render']->page('home',$data);
		});
	}

	public function add($page,$data)
	{
		if ( $page == $this->page )
			return $data($this->app);
	}
}
