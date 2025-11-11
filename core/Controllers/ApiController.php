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
			$this->result(false, 'error', 'Access denied, the token provided is invalid.', 400);
			exit;
		}

		/****************************************
	     *										*
	     *		 AUTHENTICATION ENDPOINTS		*
	     *										*						
	     ****************************************/

		//API:: Login
		$this->add('auth-login',function(){
			//Default vars
			$_users 	= new Users($this->app);

			//Checking if provide from provider or local login.
			if($this->app['validate']->is_provider($_REQUEST)){
				//Provider login
				$auth_provider 		= $this->app['tools']->getValue('auth_provider');
				$provider_token 	= $this->app['tools']->getValue('provider_token');
				$lang 				= $this->app['tools']->getValue('lang');

				$response = $_users->onProviderLogin($auth_provider, $provider_token, $lang);
			} else {
				//Local login
				$email 		= $this->app['tools']->getValue('email');
				$password 	= $this->app['tools']->getValue('password');
				$lang 		= $this->app['tools']->getValue('lang');

				$response = $_users->onLogin($email, $password, $lang);
			}

			$this->onReturn($response);
		});	

		//API:: Register
		$this->add('auth-register',function(){
			//Default vars
			$_users = new Users($this->app);

			if(isset($_REQUEST['data'])){
				$dataReceived = $this->app['tools']->getValue('data');
				$data = json_decode($dataReceived, true);

				//Checking if is a valid json. This validation is only for API requests
				if (json_last_error() !== JSON_ERROR_NONE) {
					$response =  "Data is not a valid JSON.";
				} else {
					$response 	= $_users->onRegister($data);
				}
			} else {
				$response = "No data for register found. Try again.";
			}

			$this->onReturn($response);
		});

		//API:: Login OR Register with Google
		$this->add('auth-google',function(){
			//Default vars
			$_users = new Users($this->app);

			if(isset($_REQUEST['data'])){
				$dataReceived = $this->app['tools']->getValue('data');
				$data = json_decode($dataReceived, true);

				//Checking if is a valid json. This validation is only for API requests
				if (json_last_error() !== JSON_ERROR_NONE) {
					$response =  "Data is not a valid JSON.";
				} else {
					$response 	= $_users->onRegisterProvider($data);
				}
			} else {
				$response = "No data for register found. Try again.";
			}

			$this->onReturn($response);
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

	public function add($page,$data){
		if( $page == $this->page )
			return $data($this->app);
	}
}
?>
