<?php
namespace Controllers;
use Funks\Users;
use Funks\Categories;

class ApiController
{
	var $page;
	var $app;

	public function execute($page,$app)
	{
		$this->page = $page;
		$this->app = $app;

		$this->app['render']->layout = false;

		$apiTokenReceived = $_SERVER['HTTP_AUTHORIZATION'] ?? $this->app['tools']->getValue('token');

		//Extracting Token if is received via Bearer
		if ($apiTokenReceived && preg_match('/Bearer\s(\S+)/', $apiTokenReceived, $matches)) {
		    $apiTokenReceived = $matches[1];
		}

		if( $apiTokenReceived != _API_TOKEN_ ){
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

		/****************************************
	     *										*
	     *		  CATEGORIES ENDPOINTS			*
	     *										*						
	     ****************************************/

		//API:: Categories
		$this->add('categories',function(){
			//Default vars
			$id_category 	= $this->app['tools']->getValue('id');

			//Load class
			$_categories = new Categories($this->app);

			switch($_SERVER['REQUEST_METHOD']) {
				//GET
		        case 'GET':
		        	//Default params
		        	$id_user 		= (isset($_REQUEST['id_user'])) ? $this->app['tools']->getValue('id_user') : null;
					$lang 			= (isset($_REQUEST['lang'])) ? $this->app['tools']->getValue('lang') : _DEFAULT_APP_LANGUAGE_;
					$type = (isset($_REQUEST['type'])) ? $this->app['tools']->getValue('type') : "expense";

		        	if(!$id_user){
						return $this->onReturn($this->app['lang']->getTranslationStatic("CATEGORY_VALIDATION_USER_NOT_FOUND", $lang));
					}

		            if ($id_category) {
		                return $this->onReturn($_categories->getById($id_user, $id_category));
		            } else {
		                return $this->onReturn($_categories->getAll($id_user, $type));
		            }
		            break;

		        // CREATE
		        case 'POST':
		            return $this->handleSaveCategory(0);

		        // UPDATE
		        case 'PUT':
		        case 'PATCH':
		            if (!$id_category) {
		                return $this->onReturn("ID required for update");
		            }
		            return $this->handleSaveCategory($id_category);

		        case 'DELETE':
		            if ($id_category) {
		                // Eliminar categoría
		                return $this->onReturn("delete category");
		            } else {
		                return $this->onReturn("ID required for delete");
		            }
		        default:
		            $this->result(false, 'error', 'Method not allowed', 405);
		           	break;
		    }
		});

	}

	private function handleSaveCategory($id_category){
	    $data = $this->getJsonBody();

	    $id_user = $data['id_user'] ?? null;
	    $lang    = $data['lang']    ?? _DEFAULT_APP_LANGUAGE_;

	    if (!$id_user) {
	        return $this->onReturn(
	            $this->app['lang']->getTranslationStatic("CATEGORY_VALIDATION_USER_NOT_FOUND", $lang)
	        );
	    }

	    $_categories = new Categories($this->app);

	    return $this->onReturn(
	        $_categories->manageCategory($id_user, $id_category, $data, $lang)
	    );
	}

	private function getJsonBody(){
	    $raw = file_get_contents('php://input');
	    $json = json_decode($raw, true);
	    return is_array($json) ? $json : [];
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
