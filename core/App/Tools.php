<?php
namespace App;

class Tools
{
	private $app;
	
	public function __construct($app)
	{
		$this->app = $app;
	}

	var $metaTitle = _TITLE_;
	var $metaDescription = _DESCRIPTION_;

	public function getMetas()
	{
		?><title><?=$this->metaTitle?></title>
		<meta name="description" content="<?=$this->metaDescription?>" />
		<?php
	}

	/*
	|--------------------------------------------------------------------------
	| Funciones para fechas
	|--------------------------------------------------------------------------
	*/

	/** 
	 * Fecha en formato ingles para almacenar en base de datos
	 * 
	 * @return string YYYY-MM-DD HH-MM-SS 
	 */
	public function datetime()
	{
		$fecha = date("Y") ."-". date("m") ."-". date("d");
		$fecha .= " ";
		$fecha .= date("H") .":". date("i") .":". date("s");
		return($fecha);
	}

	/**
	 * Formatea una fecha de YYYY-MM-DD a DD-MM-YYYY
	 *
	 * @param string $input YYYY-MM-DD
	 * @param string $separator / || -
	 * @return string DD-MM-YYYY
	 */
	public function fecha($input, $separator='/')
	{
		if( stripos($input, ':') )
		{
			$input = explode(' ',$input);
			$input = explode('-',$input[0]);
		}
		else
			$input = explode('-',$input);

		$input = $input[2].$separator.$input[1].$separator.$input[0];
		return $input;
	}

	/**
	 * Formatea datetime a fecha y hora española
	 *
	 * @param string $input YYYY-MM-DD H:i:s
	 * @param string $separator / || -
	 * @return string $input 
	 */
	public function formatDatetime($input, $date_separator='/')
	{
		$input = explode(' ',$input);
		$tiempo= $input[1];
		$tiempo= explode(':',$tiempo);
		$fecha = $input[0];
		$fecha = explode('-',$fecha);
		$input = $fecha[2].$date_separator.$fecha[1].$date_separator.$fecha[0].' '.$tiempo[0].':'.$tiempo[1];
		return $input;
	}


	/*
	|--------------------------------------------------------------------------
	| Funciones para strings
	|--------------------------------------------------------------------------
	*/

	/**
	 * Devuelve si un imei es valido.
	 *
	 * @param string $var
	 * @return string
	 */
	public function arrayUtf8($var,$deep=TRUE){
	    if(is_array($var))
	    {
	        foreach($var as $key => $value)
	        {
	            if($deep)
	                $var[$key] = $this->arrayUtf8($value,$deep);
	            elseif(!is_array($value) && !is_object($value) && !mb_detect_encoding($value,'utf-8',true))
	                 $var[$key] = utf8_encode($var);
	        }
	        return $var;
	    }
	    elseif(is_object($var))
	    {
	        foreach($var as $key => $value)
	        {
	            if($deep)
	                $var->$key = $this->arrayUtf8($value,$deep);
	            elseif(!is_array($value) && !is_object($value) && !mb_detect_encoding($value,'utf-8',true))
	                 $var->$key = utf8_encode($var);
	        }
	        return $var;
	    }
	    else
	        return (!mb_detect_encoding($var,'utf-8',true))?utf8_encode($var):$var;
	}

	/**
	 * Devuelve la extension de un archivo en minúsculas
	 *
	 * @param string $file nombre del archivo
	 * @return string .extension (ej.: .jpg)
	 */
	public function getExtension($file)
	{
		$ext = explode('.',$file);
		$ext = $ext[count($ext)-1];
		return strtolower($ext);	
	}

	/**
	 * Convierte un string a url amigable
	 *
	 * @param string $var
	 * @param bool $allow_dot
	 * @return string $var
	 */
	public function urlAmigable($var, $allow_dot=true)
	{  	  
		if (class_exists('Transliterator')) {
        	$var = transliterator_transliterate('Any-Latin; Latin-ASCII; [\u0100-\u7fff] remove', $var);
		} else {
			// Fallback manual
			$wrong  = array('á', 'é', 'í', 'ó', 'ú', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'ç', 'ü');
			$right  = array('a', 'e' ,'i', 'o', 'u', 'n', 'A', 'E', 'I', 'O', 'U', 'N', 'c', 'u');
			$var = str_replace($wrong, $right, $var);
		}

		$var = strtolower($var);

		$pattern = $allow_dot ? "/[^a-z0-9.]+/" : "/[^a-z0-9]+/";
		$var = preg_replace($pattern, "-", $var);
		$var = trim($var, '-');
		
		return $var;  
	}

	/**
	 * Limita caracteres y añade puntos suspensivos. 
	 *
	 * @param int $caracteres Cantidad de caracteres
	 * @param string $string cadena de caracteres la cual quieres limitar
	 * @param bool $dots
	 * @return string
	 */
	public function cortarString($caracteres, $string, $dots=true)
	{
		return strlen($string) > $caracteres ? substr($string,0,$caracteres).(($dots)? '...': '') : $string;
	}

	/**
	 * Detecta si el string es UTF-8
	 *
	 * @param string $string
	 * @return bool 
	 */
	public function isUtf8($string)
	{
		return preg_match('%(?:
		[\xC2-\xDF][\x80-\xBF]		# non-overlong 2-byte
		|\xE0[\xA0-\xBF][\x80-\xBF]			   # excluding overlongs
		|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}	  # straight 3-byte
		|\xED[\x80-\x9F][\x80-\xBF]			   # excluding surrogates
		|\xF0[\x90-\xBF][\x80-\xBF]{2}	# planes 1-3
		|[\xF1-\xF3][\x80-\xBF]{3}				  # planes 4-15
		|\xF4[\x80-\x8F][\x80-\xBF]{2}	# plane 16
		)+%xs', $string);
	}

	/**
	 * Comprueba si el string tiene formato valido email
	 *
	 * @param string $mail 
	 * @return bool
	 */
	public function isEmail($mail)
	{
		return (filter_var($mail, FILTER_VALIDATE_EMAIL) ? true : false);
	}

	/**
	 * Encripta o desencripta un string en base64.
	 *
	 * @param string $var Cadena de texto a encriptar o desencriptar
	 * @param string $tipo encrypt || decrypt
	 * @param int $tot Total de veces a encriptar o desencriptar
	 * @return string encriptado
	 */
	public function b64($var, $tipo='encrypt', $count=10)
	{
		$var_result = $var;

		for($i=0; $i<$count; $i++)
		{
			//Comprobamos si encriptamos o desencriptamos.
			if($tipo == 'encrypt')
				$var_result = base64_encode($var_result);
			elseif($tipo == 'decrypt')
				$var_result = base64_decode($var_result);
		}

		return $var_result;
	}

	/**
	 * Descodifica un texto
	 *
	 * @deprecated
	 * @param string $txt cadena de caracteres que queremos descodificar
	 * @return string 
	 */
	public function decodeTxt($txt)
	{
		$wrong = array('Ã¡','Ã©','Ã­','Ã³','Ãº','Ã±');
		$right = array('á','é','í','ó','ú','ñ');
		return str_replace($wrong,$right,$txt);
	}

	/**
	 * Busca las palabras de un string en un array. Si hay una coincidencia se para la funcion y devuelve true, en caso contrario false
	 *
	 * @param array $array El array que contiene los strings de los cuales se va a hacer la búsqueda
	 * @param string $str Cadena de texto con la cual quieres hacer la búsqueda en el array
	 * @return boolean
	 */
	public function searchStrInArray($array, $str)
	{
		foreach ( $array as $ar)
		{
			if( stripos( $str, $ar ) !== false )
				return true;
		}
		return false;
	}


	/*
	|--------------------------------------------------------------------------
	| Funciones para arrays
	|--------------------------------------------------------------------------
	*/

	/**
	 * Se le envia un array y crea un array asociativo numérico en caso de que no lo sea
	 *
	 * @param array $array
	 * @return array 
	 */
	public function arrayPassing($array)
	{
		if ( !isset($array[0]) || (count($array) == '1' && !empty($array[0])) )
		{
			//Solo tiene un elemento, asi que lo transformamos en array
			$passing = $array;
			unset($array);
			$array = array(
				0 => $passing
			);
			unset($passing);
		}
		return $array;
	}

	/**
	 * Genera breadcrumbs en HTML a partir de un array
	 * 
	 * @param array $array 
	 * @return html
	 */ 
	public function breadcrumbs($array, $home_default = true)
	{
		$result = '<ul class="breadcrumb">';
		if( $homeDefault === true )
			$result .= '<li><a href="'._DOMINIO_.'">HOME</a></li>';

		foreach ( $array as $key => $value )
		{
			if( $value != '' )
				$result .= '<li><a href="'.$value.'">'.$key.'</a></li>';
			else
				$result .= '<li>'.$key.'</li>';
		}
		$result .= '</ul>';

		return $result;
	}


	/*
	|--------------------------------------------------------------------------
	| Funciones de seguridad
	|--------------------------------------------------------------------------
	*/

	/**
	 * Encripta un string con MD5 mezclandolo con el token de seguridad
	 *
	 * @param string $val Texto a encriptar
	 * @param string $val String encriptado
	 */
	public function md5($val)
	{
		$val = md5($val);
		$val = $val._SECURITY_TOKEN_.$val._SECURITY_TOKEN_.$val;
		$val = md5($val);
		$val = md5($val);
		return $val;
	}

	/**
	 * Comprueba si una variable existe como POST o GET
	 *
	 * @param string $key Nombre de la variable
	 * @param bool
	 */
	public function getIsset($key)
	{
		if (!isset($key) || empty($key) || !is_string($key))
			return false;

		return isset($_POST[$key]) ? true : (isset($_GET[$key]) ? true : false);
	}

	/**
	* Obtiene un valor de POST o GET. Si no está disponible devuelve $default_value
	*
	* @param string $key Nombre de la variable
	* @param mixed $default_value (opcional)
	* @return mixed Valor
	*/
	public function getValue($key, $default_value = false)
	{
		if (!isset($key) || empty($key) || !is_string($key)) {
			return false;
		}

		$ret = (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $default_value));

		if (is_string($ret)) {
			return stripslashes(urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret))));
		}

		return $ret;
	}

	/*
	|--------------------------------------------------------------------------
	| Funciones de utilidad
	|--------------------------------------------------------------------------
	*/

	/**
	 * Genera mensaje de alerta en HTML con el texto enviado
	 *
	 * @param string $texto Texto
	 * @return html
	 */
	public function warning($texto)
	{
		return '<div class="warning"><img src="'._DOMINIO_.'img/warning.png" align="absmiddle"> '.$texto.'</div>';	
	}

	/**
	 * Genera mensaje de confirmacion en HTML con el texto enviado
	 *
	 * @param string $texto Texto
	 * @return html
	 */
	public function confirm($texto)
	{
		return '<div class="confirm"><img src="'._DOMINIO_.'img/ok.png" align="absmiddle"> '.$texto.'</div>';		
	}

	/**
	 * Redirección mediante JavaScript
	 *
	 * @param string $url redirecciona a la url indicada mediante el string
	 */
	public function location($url,$time=0)
	{
		if( $time != 0 )
		{
			?>
			<script language="javascript">
				setTimeout("document.location='<?=$url?>'",<?=$time?>);
			</script>
			<?php		
		}
		else
		{
			?>
			<script language="javascript">
				document.location="<?=$url?>";
			</script>
			<?php
		}
	}

	/**
	 * Mensaje que aparecerá al redireccionar. Muestra un logo y un gif que se encuentran en la carpeta img
	 *
	 * @param string $message Cadena de caracteres que quieres mostrar.
	 */
	public function redirMessage($message)
	{
		?>
		<div style="position:absolute; width:100%; height:100%; background-color:#000; top:0; left:0; z-index:2000; opacity: 0.7; filter: alpha(opacity=75);"></div>
		<div style="position:fixed; top:50%; left:50%; background-color:#fff; width:500px; height:300px; margin-left:-250px; border-radius:5px; margin-top:-150px; border:1px solid #ccc; z-index:3000; text-align:center">
			<br /><br /><br />
			<img src="<?=_DOMINIO_?>img/logo.png" class="flat" />
			<br /><br />
			<span style="font-family:Bree Serif; font-size:20px; color:#666;"><?=$message?></span>
			<br /><br /><br />
			<img src="<?=_DOMINIO_?>img/loading.gif" class="flat" />
		</div>
		<?php		
	}

	/**
	 * Sube imagen (si la hay) a la ruta mandada 
	 *
	 * @param string $ruta ruta a la que queremos mandar la imagen
	 * @param string $nombre
	 * @param string $imageName
	 */
	public function uploadDocument($route, $inputName, $imageName){
		if($_FILES[$inputName]["name"] != ''){
			$fullRoute = _PATH_ . $route;
		
			//We check the directory because if we do not create the directory
			if(!file_exists($fullRoute))
				mkdir ($fullRoute, 0777, true);
				
			$completeName = $_FILES[$inputName]["name"];
			$extension = $this->getExtension($completeName);
			$extensionsAllowed = ["pdf", "PDF", "docx", "DOCX", "xlsx", "XLSX", "csv", "CSV"];
			
			if(in_array($extension, $extensionsAllowed)){
				$ico = "$imageName.$extension";
				$temp = $_FILES[$inputName]['tmp_name'];

				//BBDD Route that has been stored
				$bdRoute = $route . $ico;

				//Absolute route where document will be uploaded
				$route = _PATH_ . $route;
				move_uploaded_file($temp, $route . $ico);
				return array(
					'type' => 'success',
					'data' => $bdRoute
				);
			}
			else
			{
				return array(
					'type' => 'error',
					'error' => 'The file does not have a valid extension.'
				);
			}
		}
		else
		{
			return array(
				'type' => 'error',
				'error' => 'There was a problem uploading the file. Please try again and/or try another document.'
			);
		}
	}

	/**
	 * Sube imagen (si la hay) a la ruta mandada 
	 *
	 * @param string $ruta ruta a la que queremos mandar la imagen
	 * @param string $nombre
	 * @param string $nombre_imagen
	 */
	public function uploadImage($ruta, $nombre, $nombre_imagen)
	{	
		if($_FILES[$nombre]["name"] != '')
		{
			$ruta_completa = _PATH_ . $ruta;
		
			//Comprobamos el directorio ya que si no lo creamos
			if(!file_exists($ruta_completa))
				mkdir ($ruta_completa, 0777, true);
				
			$nombrecompleto = $_FILES[$nombre]["name"];
			$extension = strtolower($this->getExtension($nombrecompleto));
			$extensionsAllowed = ["jpg", "jpeg", "png", "svg"];
			
			if(in_array($extension, $extensionsAllowed)){
				$temp = $_FILES[$nombre]['tmp_name'];
				$ico_original = "$nombre_imagen.$extension";
				$destino_original = $ruta_completa . $ico_original;

				move_uploaded_file($temp, $destino_original);

				// Ruta WebP
				$webp_filename = "$nombre_imagen.webp";
				$webp_path = $ruta_completa . $webp_filename;

				// Convertir a WebP
				if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
					switch ($extension) {
						case 'jpg':
						case 'jpeg':
							$image = imagecreatefromjpeg($destino_original);
							break;
						case 'png':
							$image = imagecreatefrompng($destino_original);
							imagepalettetotruecolor($image); // Better conversion
							imagealphablending($image, true);
							imagesavealpha($image, true);
							break;
					}

					if ($image) {
						imagewebp($image, $webp_path, 100); // Quality
						imagedestroy($image);
						
						//Delete the last original saved (jpg, jpeg or png) because only want webp
						unlink($destino_original);
					}
				}

				$ruta_bd_webp = $ruta . $webp_filename;
				
				return array(
					'type' => 'success',
					'data' => $ruta_bd_webp
				);
			}
			else
			{
				return array(
					'type' => 'error',
					'error' => 'El archivo no tiene una extensión válida.'
				);
			}
		}
		else
		{
			return array(
				'type' => 'error',
				'error' => 'Ha habido un problema subiendo el archivo. Inténtalo de nuevo y/o prueba con otra imagen.'
			);
		}
	}

	/**
	 * Crear Thumbnail
	 */
	public function thumb($file,$upload,$width,$height,$type='fit')
	{
		$nombre_img = explode('/',$file);
		$nombre_img = $nombre_img[count($nombre_img)-1];

		$extension = $this->getExtension($file);

		if( $extension == "jpeg" || $extension == "jpg" )
			$img_original = imagecreatefromjpeg($file);
		elseif( $extension == "png" )
			$img_original = imagecreatefrompng($file);

		list($ancho, $alto) = getimagesize($file);

		$x1 = 0;
		$y1 = 0;
		$x2 = 0;
		$y2 = 0;

		//Redimensionamos deformando imagen
		if( $type == 'resize' )
		{
			$ancho_final = $width;
			$alto_final = $height;	
			$ancho_imagen = $ancho_final;
			$alto_imagen = $alto_final;
		}

		//Redimensionamos sin deformar imagen. En fit: Cambiamos un valor dependiendo de cual es mas grande.  
		if( $type == 'fit' || $type == 'crop' )
		{
			$max_ancho = $width;
			$max_alto = $height;
			$ancho_ratio = $max_ancho / $ancho;
			$alto_ratio = $max_alto / $alto;
			if( $ancho >= $alto )
			{
				$ancho_final = $width;
				$alto_final = ceil($ancho_ratio * $alto);
			}
			else
			{
				$alto_final = $height;
				$ancho_final = ceil($alto_ratio * $ancho);
			}
			$ancho_imagen = $ancho_final;
			$alto_imagen = $alto_final;
		}

		//En crop: mantenemos tamaño añadiendo blanco
		if( $type == 'crop' )
		{
			if( $alto_final < $height )
			{
				$margen = ($height-$alto_final)/2;
				$y1 = $margen;
			}
			if( $ancho_final < $width )
			{
				$margen = ($width-$ancho_final)/2;
				$x1 = $margen;
			}
			
			$ancho_imagen = $width;
			$alto_imagen = $height;						
		}

		$thumbnail_tmp = imagecreatetruecolor($ancho_imagen,$alto_imagen);
		$trans_colour = imagecolorallocatealpha($thumbnail_tmp, 255, 255, 255, 127);
		imagefill($thumbnail_tmp, 0, 0, $trans_colour);

		imagecopyresampled($thumbnail_tmp, $img_original, $x1, $y1, $x2, $y2, $ancho_final, $alto_final, $ancho, $alto);
		imagedestroy($img_original);

		$calidad = 100;
		$ruta_carpeta_thumbnail = _PATH_ . $upload . $nombre_img;
		
		if( !file_exists(_PATH_.$upload) )
			mkdir(_PATH_.$upload, 0777, true);

		if($extension == "jpeg" || $extension == "jpg")
			imagejpeg($thumbnail_tmp, $ruta_carpeta_thumbnail,$calidad);
		elseif($extension == "png")
			imagepng($thumbnail_tmp,$ruta_carpeta_thumbnail);
	}

	/**
	 * Crea paginador
	 *
	 * @param int $page Pagina
	 * @param int $cantidad_por_pagina
	 * @param string $clase Clase de Funks
	 * @param string $nombre_funcion_clase Nombre de la funcion de la clase $clase que se llamará
	 * @param string $nombre_funcion_js Nobre de la funcion javascript que ejecuta el ajax
	 * @param string $extra_data Datos extra del paginador
	 * @param string $size Tamaño del paginador (''|'sm'|'lg') 
	 * @param string $alineacion Alineación del paginador (''|'center'|'end') 
	 */
	public function getPaginador($page, $cantidad_por_pagina, $clase, $nombre_funcion_clase, $nombre_funcion_js, $extra_data='', $size = '', $alineacion = '')
	{
		//Cargamos clase
		if( $clase == 'lang' )
			$class = $this->app[$clase];
		else
		{
			$class_name = 'Funks\\'.$clase;
			$class = new $class_name($this->app);
		}

		//Total de pujas realizadas para calcular si hay mas pujas que paginas
		if($extra_data == ''){
			$cantidad_total = count($class->$nombre_funcion_clase(0, $cantidad_por_pagina, false));
		}
		else{
			$cantidad_total = count($class->$nombre_funcion_clase(0, $cantidad_por_pagina, false, $extra_data));
		}

		if ( $cantidad_total > $cantidad_por_pagina ) {

			$paginas = ceil($cantidad_total/$cantidad_por_pagina);

			  ?>
			  <nav>
		  	<ul class="pagination <?=(!empty($size) ? 'pagination-'.$size : '')?> <?=(!empty($alineacion) ? 'justify-content-'.$alineacion : '')?>">
				<?php
					if($page != 1){
						$pagina_anterior = $page-1;
						$comienzo = ($pagina_anterior-1) * $cantidad_por_pagina;
					?>
						<li class="prev page-item">
							<a class="page-link" href="javascript:void(0)" onclick="<?=$nombre_funcion_js?>(<?=$comienzo?>, <?=$cantidad_por_pagina?>, <?=$pagina_anterior?><?=(!empty($extra_data)) ? ', \''.$extra_data .'\'': '';?>);">
								<i class="fa fa-angle-double-left"></i>
							</a>
						</li>
						<?php
					}
				?>
				<?php
					$show_pages = 10;
					$start = $page > ($show_pages/2) ? $page-($show_pages/2) : 1;
					$finish = $start+$show_pages > $paginas ? $paginas : $start+$show_pages;
					$start = $finish-$show_pages < 1 ? 1 : $finish-$show_pages;
					for ( $i=$start; $i<=$finish; $i++ ) {
						$comienzo = ($i-1) * $cantidad_por_pagina;
						?>
						<li class="page-item <?=$page == $i ? 'active' : '' ?>">
							<a class="page-link" href="javascript:void(0)" onclick="<?=$nombre_funcion_js?>(<?=$comienzo?>, <?=$cantidad_por_pagina?>, <?=$i?><?=(!empty($extra_data)) ? ', \''.$extra_data .'\'' : '';?>);">
								<?=$i?>
							</a>
						</li>
						<?php
					}

					if($page != $finish){
						$pagina_siguiente= $page+1;
						$comienzo = ($pagina_siguiente-1) * $cantidad_por_pagina;
						?>
						<li class="next page-item">
							<a class="page-link" href="javascript:void(0)" onclick="<?=$nombre_funcion_js?>(<?=$comienzo?>, <?=$cantidad_por_pagina?>, <?=$pagina_siguiente?><?=(!empty($extra_data)) ? ', \''.$extra_data .'\'' : '';?>);">
								<i class="fa fa-angle-double-right"></i>
							</a>
						</li>
						<?php
					}

				?>
			   </ul>
			   </nav>
		  	<?php
		 }
	}


	/*
	|--------------------------------------------------------------------------
	| Funciones para cargar librerias
	|--------------------------------------------------------------------------
	*/

	/**
	 * Cargamos FontAwesome
	 */
	public function loadFontawesome()
	{
		?>
		<link rel="stylesheet" href="<?=_DOMINIO_?>assets/fontawesome/font-awesome.min.css">
		<?php
	}

	/**
	 * Cargamos Bootstrap
	 *
	 * @param string $type both|js|css
	 */
	public function loadBootstrap($type="both")
	{
		if( $type == 'both' || $type == 'css' )
		{
			?>
			<link rel="stylesheet" href="<?=_DOMINIO_?>assets/admin/bootstrap/bootstrap.min.css">
			<?php
		}

		if( $type == 'both' || $type == 'js' )
		{
			?>
			<script src="<?=_DOMINIO_?>assets/admin/bootstrap/bootstrap.min.js"></script>
			<?php
		}
	}

	/**
	 * Devuelve un string añadiendo ceros a la izquierda, hasta 4.
	 * @param string $string
	 */
	public function addCeroNumberOnLeft($string, $quantity=4){

		$string 		= strval($string);
		$totalString 	= strlen($string);

		$newString = '';
		for($i=$totalString; $i<$quantity; $i++){
			$newString .= '0';
		}
		$newString .= $string;

		return $newString;
	}

	/**
	 * Cargamos Sweetalert
	 */
	public function loadSweetalert()
	{
		?>
		<script src="<?=_DOMINIO_?>assets/sweetalert/sweet-alert.min.js"></script>
		<link rel="stylesheet" href="<?=_DOMINIO_?>assets/sweetalert/sweet-alert.css">
		<?php
	}

	/**
	 * Cargamos Tinymce
	 */ 
	public function loadTinymce()
	{
		/* Poner aqui los id de los textarea */
		?>
		<script src='<?=_DOMINIO_;?>assets/tinymce/tinymce.min.js'></script>
		<script>
		tinymce.init({
			selector: '#descripcion_larga',
			plugins: "advlist"
		});
		</script>
		<script src='<?=_DOMINIO_;?>assets/tinymce/langs/es.js'></script>
		<?php
	}

	/**
	 * Cargamos libreria de la carpeta Helpers
	 * @param string $relative_file_path
	 */
	public function loadHelper($relative_file_path)
	{
		require_once _PATH_.'core/Helpers/'.$relative_file_path;
	}

	/**
	 * Guarda un array de urls para cargarlos posteriormente en la vista
	 * @param string $path
	 * @param string $position top|bottom
	 */
	public function registerJavascript($path, $position='bottom')
	{
		if( empty($_SESSION['js_paths']) )
		{
			$_SESSION['js_paths'] = array(
				'top' => array(),
				'bottom' => array()
			);
		}

		if( !empty($_SESSION['js_paths'][$position]) )
		{
			$alreadyExists = false;
			foreach( $_SESSION['js_paths'][$position] as $js )
			{
				if( $path == $js )
					$alreadyExists = true;
			}

			if( !$alreadyExists )
				$_SESSION['js_paths'][$position][] = $path;
		}
		else
			$_SESSION['js_paths'][$position][] = $path;
	}

	/**
	 * Guarda un array de urls para cargarlos posteriormente en la vista
	 * @param string $path
	 */
	public function registerStylesheet($path)
	{
		if( !empty($_SESSION['css_paths']) )
		{
			$alreadyExists = false;
			foreach( $_SESSION['css_paths'] as $css )
			{
				if( $path == $css )
					$alreadyExists = true;
			}

			if( !$alreadyExists )
				$_SESSION['css_paths'][] = $path;
		}
		else
			$_SESSION['css_paths'][] = $path;
	}

	public function getCurrencies(){
		$filePath = _PATH_ . '/core/Helpers/Currencies/currencies.php';

	    if (!file_exists($filePath)) {
	        return [];
	    }
	    
	    $currencies = require $filePath;
	    return is_array($currencies) ? $currencies : [];
	}
}
?>
