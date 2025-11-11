<?php
namespace App;

class Validate
{ 
	var $app;

	//Loading construct
	public function __construct($app){
        $this->app = $app;
    }

    /********************************
     *								*
     *		 AUTH VALIDATIONS		*
     *								*						
     ********************************/

	public function is_provider($data){
		return (isset($data['auth_provider']) && $data['auth_provider'] != 'local') ? true : false;
	}

	public function valid_login($email, $password, $lang = _DEFAULT_APP_LANGUAGE_){
		//Default vars
		$passwordHash = $this->app['tools']->md5($password);

		if( !$this->app['tools']->isEmail($email) ){
			return $this->app['lang']->getTranslationStatic("AUTH_LOGIN_EMAIL_WRONG_FORMAT", $lang);
		}
		if($this->app['bd']->countRows("SELECT * FROM users WHERE email = '".$email."'") === 0){
			return $this->app['lang']->getTranslationStatic("AUTH_LOGIN_EMAIL_NOT_FOUND", $lang);
		}
		if($password == ""){
			return $this->app['lang']->getTranslationStatic("AUTH_LOGIN_PASSWORD_EMPTY", $lang);
		}
		if($this->app['bd']->countRows("SELECT * FROM users WHERE email = '".$email."' and password = '".$passwordHash."'") === 0){
			return $this->app['lang']->getTranslationStatic("AUTH_LOGIN_PASSWORD_WRONG", $lang);
		}

		return true;
	}

	public function valid_providerLogin($auth_provider, $provider_token, $lang = _DEFAULT_APP_LANGUAGE_){
		if($provider_token == ""){
			return $this->app['lang']->getTranslationStatic("AUTH_PROVIDER_LOGIN_FAILED", $lang);
		}
		if($this->app['bd']->countRows("SELECT * FROM users WHERE auth_provider = '".$auth_provider."' and provider_token = '".$provider_token."'") === 0){
			return $this->app['lang']->getTranslationStatic("AUTH_PROVIDER_LOGIN_NOT_FOUND", $lang, ['provider' => $auth_provider]);
		}

		return true;
	}

	public function valid_register($data, $lang = _DEFAULT_APP_LANGUAGE_){
		//The validations depend on the data['auth_provider'] to detect if is local or not.
		$auth_provider = isset($data['auth_provider']) ? $data['auth_provider'] : 'local';
		switch ($auth_provider) {
			case 'local':
				//First of all checking if email exists.
				if(!$this->app['tools']->isEmail($data['email'])){
					return $this->app['lang']->getTranslationStatic("AUTH_REGISTRATION_EMAIL_WRONG_FORMAT", $lang);
				}
		        if($this->app['bd']->countRows("SELECT * FROM users WHERE email = '".$data['email']."'") > 0){
		        	return $this->app['lang']->getTranslationStatic("AUTH_REGISTRATION_EMAIL_USED", $lang);
		        }
		        if($data['password'] == "" || strlen($data['password']) < 6){
		        	return $this->app['lang']->getTranslationStatic("AUTH_REGISTRATION_PASSWORD_SHORT", $lang);
		        }
		        if($data['name'] == ""){
		        	return $this->app['lang']->getTranslationStatic("AUTH_REGISTRATION_NAME_EMPTY", $lang);
		        }
				break;
			
			case 'google':
				//First of all checking if email exists.
				if(!isset($data['provider_token']) || $data['provider_token'] == ""){
					return $this->app['lang']->getTranslationStatic("AUTH_REGISTRATION_PROVIDER_TOKEN_WRONG", $lang);
				}
				break;
		}

		return true;
	}

	public function valid_providerRegistration($data){
		$doRegistration = true;

        //If user try to register with provider, we check if is created don't do the registration
        if( isset($data['auth_provider']) && $data['auth_provider'] != 'local' ){
            if($this->app['bd']->countRows("SELECT * FROM users WHERE auth_provider = '".$data['auth_provider']."' AND provider_token = '".$data['provider_token']."'") == 1){
                $doRegistration = false;
            }
        }

        return $doRegistration;
	}

	public function valid_providerAssociation($data){
		//Checking if email exists to associate new token to this email
        if( isset($data['email']) ){
            if($this->app['bd']->countRows("SELECT * FROM users WHERE email = '".$data['email']."'") == 1){
                return true;
            }
        }

        return false;
	}
}
?>