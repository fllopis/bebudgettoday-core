<?php
namespace Funks;

class Admin
{
	var $app;

	public function __construct($app)
	{
        $this->app = $app;
  	}

  	//Login::
  	public function doLogin($user, $pass){
		$datos = $this->app['bd']->fetchRow("SELECT * FROM users WHERE user = '".$user."' AND password = '".$pass."'");

		if( $datos )
		{
			$_SESSION['admin'] = $datos;
			return true;
		}
		return false;
	}

	//Logout::
	public function logout(){
		unset($_SESSION['admin']);
	}

	//Function to get user
	public function getById($id){
		$data = $this->app['bd']->fetchObject("SELECT * FROM users WHERe id = '".$id."'");

  		if(count($data) == '1')
  			return $data[0];
  		else
  			return false;
	}
}
