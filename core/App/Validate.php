<?php
namespace App;

class Validate
{ 
	var $app;

	//Loading construct
	public function __construct($app){
        $this->app = $app;
    }

	//Validate password
	public function valid_pass($data){
		if(strlen($data) < 6)
	      return false;
		
		if(strlen($data) > 16)
		  return false;
		
		if(!preg_match('`[a-zA-Z]`',$data))
		  return false;
		
		if(!preg_match('`[0-9]`',$data))
		  return false;
			
		return true;
	}

	//Validate password v2
	public function valid_pass2($data){
		if(strlen($data) < 6)
	      return false;

		if(strlen($data) > 16)
		  return false;

		return true;
	}

	public function valid_ip($data){
		if( filter_var($data, FILTER_VALIDATE_IP) )
			return true;
		else
			return false;
	}

	public function is_provider($data){
		return (isset($data['auth_provider']) && $data['auth_provider'] != 'local') ? true : false;
	}

	public function valid_login($email, $password){
		//Default vars
		$passwordHash = $this->app['tools']->md5($password);

		if( !$this->app['tools']->isEmail($email) ){
			return "The email format is not valid. Try again 😃.";
		}
		if($this->app['bd']->countRows("SELECT * FROM users WHERE email = '".$email."'") === 0){
			return "No account found with this email. Please, register first 😥.";
		}
		if($password == ""){
			return "Password can not be empty.";
		}
		if($this->app['bd']->countRows("SELECT * FROM users WHERE email = '".$email."' and password = '".$passwordHash."'") === 0){
			return "The password is incorrect. Please, try again.";
		}

		return true;
	}

	public function valid_providerLogin($auth_provider, $provider_token){
		if($provider_token == ""){
			return "Login failed, please try again.";
		}
		if($this->app['bd']->countRows("SELECT * FROM users WHERE auth_provider = '".$auth_provider."' and provider_token = '".$provider_token."'") === 0){
			return "No account found with ".$auth_provider." provider credentials. Please, register first 😥.";
		}

		return true;
	}

	public function valid_register($data){
		//The validations depend on the data['auth_provider'] to detect if is local or not.
		$auth_provider = isset($data['auth_provider']) ? $data['auth_provider'] : 'local';
		switch ($auth_provider) {
			case 'local':
				//First of all checking if email exists.
				if(!$this->app['tools']->isEmail($data['email'])){
					return "The email format is not valid. Try again 😃.";
				}
		        if($this->app['bd']->countRows("SELECT * FROM users WHERE email = '".$data['email']."'") > 0){
		            return "This email is already used 😥. Try another one.";
		        }
		        if($data['password'] == "" || strlen($data['password']) < 6){
		            return "Password must be at least 6 characters long.";
		        }
		        if($data['name'] == ""){
		            return "The name cannot be empty. You can make it up 😉.";
		        }
				break;
			
			case 'google':
				//First of all checking if email exists.
				if(!isset($data['provider_token']) || $data['provider_token'] == ""){
					return "Wrong token for registration. Please, try again 😃.";
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
}
?>