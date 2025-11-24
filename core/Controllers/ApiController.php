<?php
namespace Controllers;
use Funks\Users;
use Funks\Dashboard;
use Funks\Categories;
use Funks\Transactions;

class ApiController
{
	var $page;
	var $app;

	public function execute($page,$app){

		$this->page = $page;
		$this->app = $app;

		$this->app['render']->layout = false;


		/****************************************
	     *										*
	     *		 AUTHENTICATION ENDPOINTS		*
	     *										*						
	     ****************************************/

		//API:: Auth
		$this->add('auth',function(){
			//Default vars
			$authType 	= $this->app['tools']->getValue('id');

			switch($_SERVER['REQUEST_METHOD']) {
				//GET
		        case 'GET':
					$this->result(false, 'error', 'Method not allowed', 405);
					break;
		        case 'POST':
					//Default vars
					$_users 	= new Users($this->app);
					$data 		= $this->getJsonBody();

					//Controling auth type
					switch($authType){
						case 'login':
							//Login
							$this->onReturn($_users->onLogin($data));
							break;
						case 'register':
							//Register
							$this->onReturn($_users->onRegister($data));
							break;
						case 'google':
							//Provider Login
							$this->onReturn($_users->onRegisterProvider($data));
							break;
						default:
							$this->result(false, 'error', 'Auth type not found', 400);
							break;
					}

		            break;
		        case 'PUT':
		        case 'PATCH':
		            $this->result(false, 'error', 'Method not allowed', 405);
					break;
		        case 'DELETE':
					$this->result(false, 'error', 'Method not allowed', 405);
					break;
		        default:
		            $this->result(false, 'error', 'Method not allowed', 405);
		           	break;
		    }
		});	

		/****************************************
	     *										*
	     *		  DASHBOARD ENDPOINTS			*
	     *										*						
	     ****************************************/

		//API:: Dashboard
		$this->add('statistics',function(){
			//Validating JWT Token
			$this->validateJWT();
			
			//Default vars
			$stadiscticType = $this->app['tools']->getValue('id');
			$id_user 		= (isset($_REQUEST['id_user'])) ? $this->app['tools']->getValue('id_user') : null;
			$lang 			= (isset($_REQUEST['lang'])) ? $this->app['tools']->getValue('lang') : _DEFAULT_APP_LANGUAGE_;

			//Load class
			$_dashboard = new Dashboard($this->app);

			switch($_SERVER['REQUEST_METHOD']) {
				//GET
		        case 'GET':
		        	if(!$id_user){
						return $this->onReturn($this->app['lang']->getTranslationStatic("CATEGORY_VALIDATION_USER_NOT_FOUND", $lang));
					}

					//Default params
					$type 		= (isset($_REQUEST['type'])) ? $this->app['tools']->getValue('type') : "expense";
					$start_date = (isset($_REQUEST['start_date'])) ? $this->app['tools']->getValue('start_date') : date('Y-m-01');
					$end_date   = (isset($_REQUEST['end_date'])) ? $this->app['tools']->getValue('end_date') : date('Y-m-t');
					$currency	= (isset($_REQUEST['currency'])) ? $this->app['tools']->getValue('currency') : "";

		        	//Controling stadistic type
					switch($stadiscticType){
						case 'categories-stats':
							return $this->onReturn($_dashboard->getAllCategoriesWithStats($id_user, $type, $start_date, $end_date));
							break;
						case 'monthly-summary':
							//The monthly summary for user
							return $this->onReturn($_dashboard->getMonthSummary($id_user, $start_date, $end_date));
							break;
						case 'saving-motivation':
							//Get the saving motivation to show or not.
							return $this->onReturn($_dashboard->getSavingsMessage($id_user, $start_date, $end_date, $lang, $currency));
							break;
						default:
							$this->result(false, 'error', 'Statidistics type not found', 400);
							break;
					}	            
		            break;
		        default:
		            $this->result(false, 'error', 'Method not allowed', 405);
		           	break;
		    }
		});

		/****************************************
	     *										*
	     *		  CATEGORIES ENDPOINTS			*
	     *										*						
	     ****************************************/

		//API:: Categories
		$this->add('categories',function(){
			//Validating JWT Token
			$this->validateJWT();
			
			//Default vars
			$id_category	= $this->app['tools']->getValue('id');
			$id_user 		= (isset($_REQUEST['id_user'])) ? $this->app['tools']->getValue('id_user') : null;
			$lang 			= (isset($_REQUEST['lang'])) ? $this->app['tools']->getValue('lang') : _DEFAULT_APP_LANGUAGE_;

			//Load class
			$_categories = new Categories($this->app);

			switch($_SERVER['REQUEST_METHOD']) {
				//GET
		        case 'GET':
		        	//Default params
					$type 		= (isset($_REQUEST['type'])) ? $this->app['tools']->getValue('type') : "expense";

		        	if(!$id_user){
						return $this->onReturn($this->app['lang']->getTranslationStatic("CATEGORY_VALIDATION_USER_NOT_FOUND", $lang));
					}

					if ($id_category) {
						return $this->onReturn($_categories->getById($id_user, $id_category, $lang));
					} else {
						return $this->onReturn($_categories->getAll($id_user, $type));
					}

		        //CREATE
		        case 'POST':
		            return $this->handleSave(0, 'categories');

		        //UPDATE
		        case 'PUT':
		        case 'PATCH':
		            if (!$id_category) {
		                return $this->onReturn($this->app['lang']->getTranslationStatic("CATEGORY_VALIDATION_ID_REQUIRED", $lang));
		            }
		            return $this->handleSave($id_category, 'categories');
				//DELETE
		        case 'DELETE':

					if(!$id_user){
						return $this->onReturn($this->app['lang']->getTranslationStatic("CATEGORY_VALIDATION_USER_NOT_FOUND", $lang));
					}

		            if ($id_category) {
		                return $this->onReturn($_categories->delete($id_user, $id_category, $lang));
		            } else {
		                return $this->onReturn($this->app['lang']->getTranslationStatic("CATEGORY_VALIDATION_ID_REQUIRED", $lang));
		            }
		        default:
		            $this->result(false, 'error', 'Method not allowed', 405);
		           	break;
		    }
		});

		/****************************************
	     *										*
	     *		  TRANSACTIONS ENDPOINTS		*
	     *										*						
	     ****************************************/

		//API:: Transactions
		$this->add('transactions',function(){
			//Validating JWT Token
			$this->validateJWT();
			
			//Default vars
			$id_transaction = $this->app['tools']->getValue('id');
			$id_user 		= (isset($_REQUEST['id_user'])) ? $this->app['tools']->getValue('id_user') : null;
			$lang 			= (isset($_REQUEST['lang'])) ? $this->app['tools']->getValue('lang') : _DEFAULT_APP_LANGUAGE_;

			//Load class
			$_transactions = new Transactions($this->app);

			switch($_SERVER['REQUEST_METHOD']) {
				//GET
		        case 'GET':
		        	//Default params
					$start_date = (isset($_REQUEST['start_date'])) ? $this->app['tools']->getValue('start_date') : date('Y-m-01');
					$end_date   = (isset($_REQUEST['end_date'])) ? $this->app['tools']->getValue('end_date') : date('Y-m-t');
					$type 		= (isset($_REQUEST['type'])) ? $this->app['tools']->getValue('type') : "expense";

		        	if(!$id_user){
						return $this->onReturn($this->app['lang']->getTranslationStatic("TRANSACTION_VALIDATION_USER_NOT_FOUND", $lang));
					}

		            if ($id_transaction) {
		                return $this->onReturn($_transactions->getById($id_user, $id_transaction, $lang));
		            } else {
		                return $this->onReturn($_transactions->getByDateRange($id_user, $start_date, $end_date, $type));
		            }
		            break;

		        //CREATE
		        case 'POST':
		            return $this->handleSave(0, 'transactions');

		        //UPDATE
		        case 'PUT':
		        case 'PATCH':
		            if (!$id_transaction) {
		                return $this->onReturn($this->app['lang']->getTranslationStatic("TRANSACTION_VALIDATION_ID_REQUIRED", $lang));
		            }
		            return $this->handleSave($id_transaction, 'transactions');

				//DELETE
		        case 'DELETE':

					if(!$id_user){
						return $this->onReturn($this->app['lang']->getTranslationStatic("TRANSACTION_VALIDATION_USER_NOT_FOUND", $lang));
					}

		            if ($id_transaction) {
		                return $this->onReturn($_transactions->delete($id_user, $id_transaction, $lang));
		            } else {
		                return $this->onReturn($this->app['lang']->getTranslationStatic("TRANSACTION_VALIDATION_ID_REQUIRED", $lang));
		            }
		        default:
		            $this->result(false, 'error', 'Method not allowed', 405);
		           	break;
		    }
		});

	}

	private function handleSave($id, $type){
	    $data = $this->getJsonBody();

	    $id_user = $data['id_user'] ?? null;
	    $lang    = $data['lang']    ?? _DEFAULT_APP_LANGUAGE_;

	    if (!$id_user) {
	        return $this->onReturn(
	            $this->app['lang']->getTranslationStatic("AUTH_VALIDATION_USER_NOT_FOUND", $lang)
	        );
	    }

		switch ($type) {
			case 'categories':
				$_categories = new Categories($this->app);

				return $this->onReturn(
					$_categories->manageCategory($id_user, $id, $data, $lang)
				);
				break;
			case 'transactions':
				$_transactions = new Transactions($this->app);

				return $this->onReturn(
					$_transactions->managTransaction($id_user, $id, $data, $lang)
				);
				break;
		}
	}

	private function getJsonBody(){
	    $raw = file_get_contents('php://input');
	    $json = json_decode($raw, true);
	    return is_array($json) ? $json : [];
	}

	private function validateJWT() {
		$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $this->app['tools']->getValue('token');

		if (!$authHeader) {
			$this->result(false, 'error', 'Authorization header not found', 401);
			exit;
		}

		if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
			$this->result(false, 'error', 'Access denied, the token has invalid format.', 401);
			exit;
		}

		$jwtToken = $matches[1];

		try {
			require_once _PATH_.'core/Helpers/PHPJWT/JWT.php';
        	require_once _PATH_.'core/Helpers/PHPJWT/Key.php';

			\Firebase\JWT\JWT::decode($jwtToken, new \Firebase\JWT\Key(_JWT_SECRET_, 'HS256'));
		} catch (\Exception $e) {
			$this->result(false, 'error', 'Access denied, the token provided is invalid.', 401);
			exit;
		}
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
