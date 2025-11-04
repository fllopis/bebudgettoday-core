<?php
namespace Controllers;
use Funks\Users;

class ApiController
{
	var $page;
	var $app;

	public function execute($page,$app)
	{
		$this->page = $page;
		$this->app = $app;

		$this->app['render']->layout = false;

		if( $this->app['tools']->getValue('token') != _API_TOKEN_ ){
			$this->result(false, 'error', 'Acceso denegado, el token proporcionado no es válido.', 400);
			exit;
		}

		//API:: 
		$this->add('auth/login',function(){
			//Default vars
			$_users 	= new Users($this->app);
			$email 		= $this->app['tools']->getValue('email');
			$password 	= $this->app['tools']->getValue('password');

			$response = $_user->onLogin($email, $password);

			$this->onReturn($response);
		});	

		//API:: Register -- TODO
		$this->add('auth/register',function(){
			// $_casos = new Casos($this->app);
			// $response = $_casos->wsGetModelosByIdCaso();

			// if( is_object($response) || is_array($response) )
			// 	$this->result($response, 'success');
			// else
			// 	$this->result(false, 'error', $response, 400);
		});

		//API:: Login OR Register with Google -- TODO
		$this->add('auth/google',function(){
			// $_casos = new Casos($this->app);
			// $response = $_casos->wsGetModelosByIdCaso();

			// if( is_object($response) || is_array($response) )
			// 	$this->result($response, 'success');
			// else
			// 	$this->result(false, 'error', $response, 400);
		});

	}

	private function onReturn($response){
		if( is_object($response) || is_array($response))
			$this->result($response, 'success');
		else
			$this->result(false, 'error', $response, 400);
	}

	private function result($data = false, $type = 'success', $error = false, $codigoEstado = 200){
        header("Content-Type:application/json");
        header("HTTP/1.1 $codigoEstado $type");

        $response = array( 'type'  => $type );
                
        if( $response['type'] === 'error' )
            $response['error'] = $error;
        
        if( $response['type'] === 'success' )
            $response['data'] = $data;

        echo json_encode($this->app['tools']->arrayUtf8($response));

        return;
    }

	public function add($page,$data)
	{
		if( $page == $this->page )
			return $data($this->app);
	}
}
?>
