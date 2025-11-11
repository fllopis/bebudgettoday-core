<?php
namespace Funks;

class Users
{
	var $app;

	public function __construct($app)
	{
        $this->app = $app;
  	}

    /** 
	 * Function to do login on APP (TODO: Web?)
	 * @param string $email: User email
     * @param string $password: User password
	 * @return object | User data
	 */
    public function onLogin($email, $password, $lang){
        //Login validations
        $validationResponse = $this->app['validate']->valid_login($email, $password, $lang);
        if($validationResponse !== true){
            return $validationResponse;
        }

        //Parsing password
        $passwordHash = $this->app['tools']->md5($password);

        //Get data user to check if is loged or not.
		$datos = $this->app['bd']->fetchRow("
            SELECT *
            FROM users
            WHERE email = '".$email."'
            AND password = '".$passwordHash."'
        ");
        
        return [
            'id'            => $datos->id,
            'account_type'  => $datos->account_type,
            'name'          => $datos->name,
            'email'         => $datos->email,
            'avatar_url'    => $datos->avatar_url,
            'created_at'    => $datos->created_at
        ];
	}

    /** 
	 * Function to do provider login on APP (TODO: Web?)
     * @param string $auth_provider: Auth provider (google, facebook, etc)
	 * @return object | User data
	 */
    public function onProviderLogin($auth_provider, $provider_token, $lang){
        //Login validations
        $validationResponse = $this->app['validate']->valid_providerLogin($auth_provider, $provider_token, $lang);
        if($validationResponse !== true){
            return $validationResponse;
        }

        //Get data user to check if is loged or not.
		$datos = $this->app['bd']->fetchRow("
            SELECT *
            FROM users
            WHERE auth_provider = '".$auth_provider."'
            AND provider_token = '".$provider_token."'
        ");

		return [
            'id'            => $datos->id,
            'account_type'  => $datos->account_type,
            'name'          => $datos->name,
            'email'         => $datos->email,
            'avatar_url'    => $datos->avatar_url,
            'created_at'    => $datos->created_at
        ];
	}

    /** 
	 * Function to do register on APP (TODO: Web?)
	 * @return object | User data
	 */
    public function onRegister($data, $doRegistration = true){
        //Default vars
        $lang = (isset($data['lang'])) ? $data['lang'] : _DEFAULT_APP_LANGUAGE_;

        //Validating data for register
        $validationResponse = $this->app['validate']->valid_register($data, $lang);

        //Checking the validation response for register.
        if($validationResponse !== true){
            return $validationResponse;
        }

        if($doRegistration){
            $register = $this->app['bd']->insert("users", [
                'auth_provider'     => isset($data['auth_provider']) ? $data['auth_provider'] : 'local',
                'provider_token'    => isset($data['provider_token']) ? $data['provider_token'] : '',
                'account_type'      => isset($data['account_type']) ? $data['account_type'] : 'standard',
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => (isset($data['password']) && $data['password'] != "") ? $this->app['tools']->md5($data['password']) : '',
                'avatar_url'        => isset($data['avatar_url']) ? $data['avatar_url'] : '',
                'created_at'        => $this->app['tools']->datetime(),
                'updated_at'        => $this->app['tools']->datetime()
            ]);


            //Creating default Categories
            //-------------------------------------
            $id_user = $this->app['bd']->lastId();
            $_categories = new Categories($this->app);
            $_categories->createDefaultCategories($id_user);
            
        } else {
            $register = true;
        }

        if($register){
            //Returning the user data to auto login after register.
            if(isset($data['auth_provider']) && $data['auth_provider'] != 'local'){
                return $this->onProviderLogin($data['auth_provider'], $data['provider_token'], $lang);
            } else {
                return $this->onLogin($data['email'], $data['password'], $lang);
            }
        } else {
            return $this->app['lang']->getTranslationStatic("AUTH_REGISTRATION_UNKNOW_ERROR", $lang);
        }
	}

    public function onRegisterProvider($data){
        //Default vars
        $lang = (isset($data['lang'])) ? $data['lang'] : _DEFAULT_APP_LANGUAGE_;

        //Checking if provider and auth exists.
        $doRegistration = $this->app['validate']->valid_providerRegistration($data);

        //If provider with token doesn't exist we need to check the email, because if exists in BBDD we need to associate token & email.
        if($doRegistration){
            if($this->app['validate']->valid_providerAssociation($data)){

                //We need to update the token and auth_provider to user.
                $updateData['auth_provider']    = $data['auth_provider'];
                $updateData['provider_token']   = $data['provider_token'];
                $updateData['updated_at']       = $this->app['tools']->datetime();

                $this->app['bd']->update("users", $updateData, " email = '".$data['email']."'");

                //And also do login and return
                return $this->onProviderLogin($data['auth_provider'], $data['provider_token'], $lang);
            }
        } else {
            return $this->onProviderLogin($data['auth_provider'], $data['provider_token'], $lang);
        }

        //Calling on registration user if provider, auth and email don't exist or match
        return $this->onRegister($data, $doRegistration);
    }
}
