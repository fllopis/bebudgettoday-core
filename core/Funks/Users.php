<?php
namespace Funks;

class Users
{
	var $app;

	public function __construct($app)
	{
        $this->app = $app;
  	}

    //Function to do Login
    public function onLogin($email, $password){

        //Parsing password
        $passwordHash = $this->app['tools']->md5($password);

        //Get data user to check if is loged or not.
		$datos = $this->app['bd']->fetchRow("
            SELECT *
            FROM users
            WHERE email = '".$email."'
            AND password = '".$passwordHash."'
        ");

		if($datos){
			return $datos;
		}
        
		return false;
	}
}
