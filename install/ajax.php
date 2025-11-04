<?php
$type = "error";
$error = "";
$success = "";

/**
 * Random bytes generator
 *
 * Thanks to Zend for entropy
 *
 * @param $length Desired length of random bytes
 * @return bool|string Random bytes
 */
function getBytes($length)
{
	$length = (int)$length;

	if ($length <= 0)
		return false;

	if (function_exists('openssl_random_pseudo_bytes'))
	{
		$bytes = openssl_random_pseudo_bytes($length, $crypto_strong);

		if ($crypto_strong === true)
			return $bytes;
	}

	if (function_exists('mcrypt_create_iv'))
	{
		$bytes = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);

		if ($bytes !== false && strlen($bytes) === $length)
			return $bytes;
	}

	// Else try to get $length bytes of entropy.
	// Thanks to Zend

	$result		 = '';
	$entropy		= '';
	$msec_per_round = 400;
	$bits_per_round = 2;
	$total		  = $length;
	$hash_length	= 20;

	while (strlen($result) < $length)
	{
		$bytes  = ($total > $hash_length) ? $hash_length : $total;
		$total -= $bytes;

		for ($i=1; $i < 3; $i++)
		{
			$t1 = microtime(true);
			$seed = mt_rand();

			for ($j=1; $j < 50; $j++)
				$seed = sha1($seed);

			$t2 = microtime(true);
			$entropy .= $t1 . $t2;
		}

		$div = (int) (($t2 - $t1) * 1000000);

		if ($div <= 0)
			$div = 400;

		$rounds = (int) ($msec_per_round * 50 / $div);
		$iter = $bytes * (int) (ceil(8 / $bits_per_round));

		for ($i = 0; $i < $iter; $i ++)
		{
			$t1 = microtime();
			$seed = sha1(mt_rand());

			for ($j = 0; $j < $rounds; $j++)
				$seed = sha1($seed);

			$t2 = microtime();
			$entropy .= $t1 . $t2;
		}

		$result .= sha1($entropy, true);
	}

	return substr($result, 0, $length);
}

/**
* Random password generator
*
* @param int $length Desired length (optional)
* @param string $flag Output type (NUMERIC, ALPHANUMERIC, NO_NUMERIC, RANDOM)
* @return bool|string Password
*/
function passwdGen($length = 8, $flag = 'ALPHANUMERIC')
{
	$length = (int)$length;

	if ($length <= 0)
		return false;

	switch ($flag)
	{
		case 'NUMERIC':
			$str = '0123456789';
			break;
		case 'NO_NUMERIC':
			$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
		case 'RANDOM':
			$num_bytes = ceil($length * 0.75);
			$bytes = getBytes($num_bytes);
			return substr(rtrim(base64_encode($bytes), '='), 0, $length);
		case 'ALPHANUMERIC':
		default:
			$str = 'abcdefghijkmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
	}

	$bytes = getBytes($length);
	$position = 0;
	$result = '';

	for ($i = 0; $i < $length; $i++)
	{
		$position = ($position + ord($bytes[$i])) % strlen($str);
		$result .= $str[$position];
	}

	return $result;
}

if( isset( $_REQUEST['action'] ) )
{
	$action = $_REQUEST['action'];

	switch ($action)
	{
		case 'check_bd':
			$servidor = $_REQUEST['bd_servidor'];
			$nombre = $_REQUEST['bd_nombre'];
			$usuario = $_REQUEST['bd_usuario'];
			$password = $_REQUEST['bd_password'];

			@$conn = new mysqli($servidor, $usuario, $password, $nombre);

			if ($conn->connect_error)
				$error = "Conexión fallida: " . $conn->connect_error;
			else
			{
				$type = 'success';
				$success = "Conexión correcta";
			}
			break;

		case 'install':
			//Generamos tokens
			$security_token = passwdGen(56);
			$cronjob_token = passwdGen(56);

			//Creamos archivo settings.php
			$settingsFile = fopen(dirname(__FILE__).'/../core/settings.php', "w");

			if( $settingsFile === false )
			{
				$error = "Error creando el archivo settings.php";
				break;
			}

			$settingsContent = "<?php
define( '_DEBUG_', ".$_REQUEST['modo_debug']." );

define( '_ROOT_DOMINIO_DEV_', '".$_REQUEST['dominio_dev']."' );
define( '_ROOT_DOMINIO_', '".$_REQUEST['dominio']."' );

define( '_BD_NAME_DEV_', '".$_REQUEST['bd_nombre_dev']."' );
define( '_BD_HOST_DEV_', '".$_REQUEST['bd_servidor_dev']."' );
define( '_BD_USER_DEV_', '".$_REQUEST['bd_usuario_dev']."' );
define( '_BD_PASS_DEV_', '".$_REQUEST['bd_password_dev']."' );

define( '_BD_NAME_', '".$_REQUEST['bd_nombre']."' );
define( '_BD_HOST_', '".$_REQUEST['bd_servidor']."' );
define( '_BD_USER_', '".$_REQUEST['bd_usuario']."' );
define( '_BD_PASS_', '".$_REQUEST['bd_password']."' );

define( '_TITULO_', '".$_REQUEST['nombre_proyecto']."' );

define( '_RECEPTOR_', '".$_REQUEST['admin_email']."' );
define( '_SMTP_SERVER_', '".$_REQUEST['smtp_servidor']."' );
define( '_SMTP_USER_', '".$_REQUEST['smtp_usuario']."' );
define( '_SMTP_PASSWORD_', '".$_REQUEST['smtp_pass']."' );
define( '_SMTP_PORT_', '".$_REQUEST['smtp_puerto']."' );
define( '_SMTP_CHARSET_UTF_', 'utf-8' );
define( '_SMTP_CHARSET_ISO_', 'iso-8859-1' );

define( '_SECURITY_TOKEN_', '".$security_token."' );
define( '_CRONJOB_TOKEN_', '".$cronjob_token."' );
";

			fwrite($settingsFile, $settingsContent);
			fclose($settingsFile);

			//Creamos tablas de la base de datos e insertamos los datos correspondientes cargando el archivo SQL
			if( $_REQUEST['modo_debug'] === true )
			{
				$mysql_host = $_REQUEST['bd_servidor_dev'];
				$mysql_username = $_REQUEST['bd_usuario_dev'];
				$mysql_password = $_REQUEST['bd_password_dev'];
				$mysql_database = $_REQUEST['bd_nombre_dev'];
			}
			else
			{
				$mysql_host = $_REQUEST['bd_servidor'];
				$mysql_username = $_REQUEST['bd_usuario'];
				$mysql_password = $_REQUEST['bd_password'];
				$mysql_database = $_REQUEST['bd_nombre'];
			}
			
			$con = @new mysqli($mysql_host,$mysql_username,$mysql_password,$mysql_database);

			if ($con->connect_errno)
			{
				$error = "Fallo conectando con MySQL: " . $con->connect_errno." - Error: " . $con->connect_error;
				break;
			}

			$templine = '';
			$lines = file('sql/install.sql');

			foreach ($lines as $line)
			{
				// Skip it if it's a comment
				if (substr($line, 0, 2) == '--' || $line == '')
					continue;

				// Add this line to the current segment
				$templine .= $line;
				// If it has a semicolon at the end, it's the end of the query
				if (substr(trim($line), -1, 1) == ';')
				{
					// Perform the query
					$sqlResult = $con->query($templine);

					if( empty($sqlResult) )
					{
						$error = 'Error performing query ' . $templine . ': ' . $con->connect_error;
						break;
					}

					// Reset temp variable to empty
					$templine = '';
				}
			}

			require_once(dirname(__FILE__).'/../core/core.php');

			$sqlInsertAdmin = "INSERT INTO `usuarios_admin` (`id_usuario_admin`, `nombre`, `email`, `password`, `date_created`) VALUES (NULL, '".$_REQUEST['admin_nombre']."', '".$_REQUEST['admin_email']."', '".$app['tools']->md5($_REQUEST['admin_pass'])."', NOW())";

			$sqlResultAdmin = $con->query($sqlInsertAdmin);

			if( empty($sqlResultAdmin) )
			{
				$error = 'Error performing query ' . $sqlInsertAdmin . ': ' . $con->connect_error;
				break;
			}

			$con->close();

			$type = 'success';
			$success = 'Instalación realizada correctamente. Te recomendamos eliminar la carpeta /install/';
			break;

		default:
			$error = 'Action no corresponde con ningun metodo.';
			break;
	}
}
else
	$error = "No existen action";

$response = array('type' => $type);
if( $type == 'success' )
	$response['success'] = $success;
else
	$response['error'] = $error;

die(json_encode($response));