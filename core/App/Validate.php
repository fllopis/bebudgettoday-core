<?php
namespace App;

/* 
	Validate | Validación de formulario en rapidos pasos
*/

/*
$data_ejemplo = array(
	'class' => 'Posicionamiento',
	'function_ok' => 'guarda_datos',
	'error' => 'Ha ocurrido un error interno. Intente nuevamente',
	'method' => 'post',
	'data' => array(
		'nombre' => array( 
			'validacion' => 'no_vacio', 
			'mensaje_error' => 'El nombre no debe estar vacío.' 
		),
		'email' => array( 
			'validacion' => 'email', 
			'mensaje_error' => 'El email debe tener un formato correcto.' 
		),
		'numero' => array( 
			'validacion' => 'int', 
			'mensaje_error' => 'El nombre no debe estar vacío.' 
		),
		'telefono' => array( 
			'validacion' => false, 
			'mensaje_error' => '' 
		),
	)
);
*/

class Validate
{ 
	var $app;
	var $error='';
	var $success='';

	//Cargamos funcion
	public function __construct($app)
	{
        $this->app = $app;
    }

	//Function que valida el form
	public function form($data)
	{
		$method 		= $data['method'];
		$error_return 	= $data['error'];
		$data 			= $data['data'];
		$new_data 		= array();
		
		foreach( $data as $k => $v )
		{
			if( ( $method == 'post' && isset($_POST[$k]) ) || ( $method == 'get' && isset($_GET[$k]) ) )
			{
				//Validamos archivos
				if( $v['validacion'] == 'archivo' )
				{
					$name = $_FILES[$k];
					if( $name['name'] != '' )
					{
						if( is_uploaded_file($name['tmp_name']) )
						{
							$soportados = explode(',',$v['soportados']);
							$extension = $this->app['tools']->extension_($name['name']);
							if( in_array($extension,$soportados) )
							{
								$maximo = $v['tamano_maximo'];
								$tamano = $name['size']*1024;
								if( $tamano > $maximo )
								{
									$this->error = 'El tamaño del archivo ['.$k.'] supera al permitido. Máximo '.$maximo.' KBs';
									return false;
								}
							}
							else
							{
								$this->error = 'Extension incorrecta en archivo ['.$k.']. Soportadas: '.$v['soportados'].'.';
								return false;
							}
						}
						else
						{
							$this->error = 'El archivo ['.$k.'] no se ha subido correctamente.';
							return false;
						}
					}
				}
				else
				{
					//Valodamos variables normales
					if( $method == 'post' )
						$valor = is_array($_POST[$k]) || ( isset($v['html']) && $v['html'] ) ? $_POST[$k] : $this->app['tools']->getValue($k);
					else if( $method == 'get' )
						$valor = is_array($_GET[$k]) || ( isset($v['html']) && $v['html'] ) ? $_GET[$k] : $this->app['tools']->getValue($k);		

					$validacion = $v['validacion'];

					if( $validacion )
					{
						$res = $this->{'valid_'.$v['validacion']}($valor);
						if( $res )
							$new_data[$k] = $valor;	
						else
						{
							$this->error = $v['mensaje_error'];
							return false;
						}
					}
					else
						$new_data[$k] = $valor;
				}
			}
		}
		
		return $new_data;
	}

	/*
		Functiones que conforman los tipos de validación
	*/

	//Comprueba si la cadena esta vacía o no
	public function valid_no_vacio($data)
	{
		return $data == '' ? false : true;		
	}

	//Valida el formato de un email
	public function valid_email($data)
	{
		return preg_match('/^[A-Za-z0-9-_.+%]+@[A-Za-z0-9-.]+\.[A-Za-z]{2,4}$/', $data) ? true : false;
	}


	//Valida si una variable es numerica o no
	public function valid_numero($data)
	{
		return is_numeric($data) == 1 ? true : false;			
	}

	//Valida un porcentaje
	public function valid_porcentaje($data)
	{
		return is_numeric($data) == 1 && $data >= 0 && $data <= 100 ? true : false;			
	}

	//Valida que no sea cero
	public function valid_no_cero($data)
	{
		return $data == 0 ? false : true;			
	}

	//Valida si una variable es int o no pero si es 0 tambien cuenta como invalido
	public function valid_int($data)
	{
		return (is_numeric($data) == 1 && $data != '0') ? true : false;			
	}

	//Valida si una cadena es amigable o no
	public function valid_amigable($data)
	{
		return $data == '' ? false : true;			
	}

	//Validamos contrasena
	public function valid_pass($data)
	{
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

	//Validamos contrasena
	public function valid_pass2($data)
	{
		if(strlen($data) < 6)
	      return false;

		if(strlen($data) > 16)
		  return false;

		return true;
	}

	public function valid_check($data)
	{
		if($data)
			return true;
		else
			return false;	
	}

	public function valid_ip($data)
	{
		if( filter_var($data, FILTER_VALIDATE_IP) )
			return true;
		else
			return false;
	}

	public function is_provider()
	{
		return (isset($_REQUEST['auth_provider']) && $_REQUEST['auth_provider'] != 'local') ? true : false;
	}

	public function valid_login($email, $password)
	{
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

	public function valid_providerLogin($auth_provider, $provider_token)
	{
		if($provider_token == ""){
			return "Login failed, please try again.";
		}
		if($this->app['bd']->countRows("SELECT * FROM users WHERE auth_provider = '".$auth_provider."' and provider_token = '".$provider_token."'") === 0){
			return "No account found with ".$auth_provider." provider credentials. Please, register first 😥.";
		}

		return true;
	}

	public function valid_register($data)
	{
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

		return true;
	}

	/*
		Demas funciones de formulario
	*/

	public function get_value($edit,$name,$data='')
	{
		if( isset($_POST[$name]) )
			return $this->app['tools']->getValue($name);	
		else
		{
			if( $edit )
				return $data->$name;	
			else
				return '';
		}	
	}

	public function get_value_html($edit,$name,$data='')
	{
		if( isset($_POST[$name]) )
			return trim( $_POST[$name]);	
		else
		{
			if( $edit )
				return $data->$name;	
			else
				return '';
		}	
	}

	public function get_selected($edit,$name,$data,$value)
	{
		if( isset($_POST[$name]) )
			return $this->app['tools']->getValue($name) == $value ? 'selected="selected"' : '';	
		else
		{
			if( $edit )
				return $data->$name == $value ? 'selected="selected"' : '';	
			else
				return '';
		}	
	}

	public function get_checked($edit,$name,$data,$value)
	{
		if( isset($_POST[$name]) )
			return $this->app['tools']->getValue($name) == $value ? 'checked="checked"' : '';	
		else
		{
			if( $edit )
				return $data->$name == $value ? 'checked="checked"' : '';	
			else
				return '';
		}	
	}
	
	public function catch_error()
	{
		return $this->error;	
	}
	
	public function catch_success()
	{
		return $this->success;	
	}
}
?>