<?php
namespace Controllers;

class CronsController
{
	var $page;
	var $app;

	public function execute($page,$app)
	{
		$this->page = $page;
		$this->app = $app;

		if( $this->app['tools']->getValue('token') != _CRONJOB_TOKEN_ )
		{
			echo "Go away!";
			exit;
		}

		$this->app['render']->layout = false;

		$this->add('enviar-emails',function()
		{
		});
	}

	public function add($page,$data)
	{
		if ( $page == $this->page )
			return $data($this->app);
	}
}
?>
