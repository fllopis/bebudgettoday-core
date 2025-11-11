<?php
namespace App;
use App\Slugs;

class Languages
{
	private $app;

	public function __construct($app)
	{
	  	$this->app = $app;
	}

	public function getCurrentLanguageId($slug){
		if($slug != ''){
            $lang = $this->getLangBySlug($slug);
            return $lang->id;
        }
        elseif(isset($_SESSION['lang'])){
            $lang = $this->getLangBySlug($_SESSION['lang']);
            return $lang->id;
        }
        else{
            $lang = $this->getDefaultLanguage();
            return $lang->id;
        }
	}

	public function setLanguage(){

		if( !isset($_SESSION['lang']) || empty($_SESSION['lang']) ){

			$language_default = $this->getDefaultLanguage();

			if( _MULTI_LANGUAGE_ ){
			    $langNavegador = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);				
			    $languagesAvailables = $this->getLanguagesVisiblesArray();
			    $_SESSION['lang'] = in_array($langNavegador, $languagesAvailables) ? $langNavegador : $language_default->slug;
			}
			else
				$_SESSION['lang'] = $language_default->slug;
		}
	}

	//Funcion que devuelve el ID_LANGUAGE
	public function getIDLangCorrectly($slug_language=""){

		if($slug_language != ''){
            $lang = $this->getLangBySlug($slug_language);
            $id_lang = $lang->id;
        }
        elseif(isset($_SESSION['lang'])){
            $lang = $this->getLangBySlug($_SESSION['lang']);
            $id_lang = $lang->id;
        }
        else{
            $lang = $this->getDefaultLanguage();
            $id_lang = $lang->id;
        }

        return $id_lang;
	}

	//Funcion que devuelve el idioma por defecto
	public function getDefaultLanguage(){

		$datos = $this->app['bd']->fetchRow('SELECT * FROM languages WHERE is_default = "1"');

		if(!empty($datos))
			return $datos;
		else
			return false;
	}

	//Funcion que devuelve el total de idiomas
	public function getLanguages($id=""){

		//Obtenemos el idioma default.
		$lang = $this->getDefaultLanguage();
        $id_lang = $lang->id;

		if( $id == "" )
			return $this->app['bd']->fetchObject('
				SELECT l.*, ll.name as name
				FROM languages l, languages_lang ll
				WHERE l.id = ll.id_language
				GROUP BY l.id
				ORDER BY l.is_default DESC, ll.name ASC
			');
		else{
			$datos = $this->app['bd']->fetchObject('
				SELECT l.*, ll.name as name
				FROM languages l, languages_lang ll 
				WHERE l.id = ll.id_language
				AND l.id = "'.$id.'"
				GROUP BY l.id
			');

			if( count($datos) == 1 )
				return $datos[0];
			else
				return false;
		}
	}

	//Funcion que devuelve el total de idiomas
	public function getLanguagesVisibles(){
		return $this->app['bd']->fetchObject('SELECT * FROM languages WHERE status = "active" ORDER BY id ASC');
	}

	//Funcion que devuelve el total de idiomas
	public function getLanguagesVisiblesArray(){

		$languages = $this->getLanguagesVisibles();
		$result = array();
		foreach( $languages as $lang )
			$result[] = $lang->slug;
		return $result;
	}

	//Funcion que devuelve los idiomas con sus correspondientes traducciones.
	public function getLanguagesTranslatedVisible(){

		$language = $this->getLangBySlug($_SESSION['lang']);

		return $this->app['bd']->fetchObject('
			SELECT l.*, ll.name as name
			FROM languages l, languages_lang ll
			WHERE l.id = ll.id_language
			AND l.status = "active"
			AND ll.id_lang = "'.$language->id.'"
			ORDER BY l.id ASC
		');
	}

	//Funcion que devuelve idioma
	public function getLangBySlug($slug, $withName=false){

		if(!$withName){
			$datos = $this->app['bd']->fetchObject('SELECT * FROM languages WHERE slug = "'.$slug.'"');
		}
		else{
			$datos = $this->app['bd']->fetchObject('
				SELECT l.*, ll.name as name
				FROM languages l, languages_lang ll 
				WHERE l.id = ll.id_language
				AND l.slug = "'.$slug.'"
				GROUP BY l.id
			');
		}

		if( count($datos) == 1 )
			return $datos[0];
		else
			return false;
	}

	//Funcion que devuelve todas las traducciones de cada idioma
	public function getAllTraductionsById($id_idioma, $withContenido=false, $defaultLang=false){

		if( $withContenido )
			return $this->app['bd']->fetchObject('SELECT * FROM languages_translations WHERE id_language = "'.$id_idioma.'" AND content != ""');
		else{
			if($defaultLang){

				$datos_lang = $this->getDefaultLanguage();

				return $this->app['bd']->fetchObject('SELECT * FROM languages_translations WHERE id_language = "'.$datos_lang->id.'"');
			}
			else
				return $this->app['bd']->fetchObject('SELECT * FROM languages_translations WHERE id_language = "'.$id_idioma.'"');
		}
	}

	//Funcion que en base a idioma y shortcode devuelve su traduccion
	public function getTraductionByLangShort($id_idioma, $shortcode){

		$datos = $this->app['bd']->fetchObject('SELECT * FROM languages_translations WHERE id_language = "'.$id_idioma.'" AND shortcode = "'.$shortcode.'"');

		if( count($datos) == 1 )
			return $datos[0];
		else
			return false;
	}

	public function getIdiomasWithFiltros($comienzo, $limite, $applyLimit=true){

		$search = "";
		$limit = "";

		if($applyLimit)
			$limit = "LIMIT $comienzo, $limite";

		return $this->app['bd']->fetchObject("SELECT * FROM languages WHERE 1=1 ORDER BY id ASC $limit");
	}

	//Funcion que va a devolver todas las traducciones agrupadas por shortcode
	public function getAllTraductionsGroupedFiltered($comienzo, $limite, $applyLimit=true){

		//Recogemos variables
		$busqueda 			= $this->app['tools']->getValue('busqueda');
		$translation_status	= $this->app['tools']->getValue('translation_status');
		$slug_idioma 		= $this->app['tools']->getValue('slug_idioma');
		$traduction_for 	= $this->app['tools']->getValue('traduction_for');

		if( $traduction_for != "all" )
			$whereTraductionFor = "AND traduction_for = '".$traduction_for."'";
		else
			$whereTraductionFor = "";

		if( $applyLimit ){
			$multiplicador = !empty($slug_idioma) ? '1' : count($this->getLanguages());
			$limit = "LIMIT ".$comienzo*$multiplicador.", ".$limite*$multiplicador;
		}
		else
			$limit = "";

		if( !empty($slug_idioma) ){

			$language_default = $this->getDefaultLanguage();

			//Obtenemos el idioma seleccionado
			$datosIdiomaDefault = $this->app['bd']->fetchObject('SELECT id FROM languages WHERE slug = "'.(!empty($slug_idioma) ? $slug_idioma : $language_default->slug).'"');
			$whereIdioma = " AND id_language = ".(int)$datosIdiomaDefault[0]->id;
		}
		else
			$whereIdioma = "";

		if( empty($busqueda) && $translation_status == '1' )
		{
			$busqueda = " AND content LIKE '%".$busqueda."%'";
		}
		elseif( !empty($busqueda) )
		{
			$busqueda = " AND content LIKE '%".$busqueda."%'";
		}


		//Obtenemos todas las traducciones por shortcode del idioma default
		$datosShortcodes = $this->app['bd']->fetchObject("
			SELECT *
			FROM languages_translations
			WHERE 1
			$busqueda
			$whereTraductionFor
			$whereIdioma
			ORDER BY shortcode ASC
			$limit
		");

		//Diferentes idiomas
		$datosIdiomas = $this->getLanguages();

		//Array principal que contendra cada shortcode y sus traducciones
		$arrayTraductions = array();

		//Comprobamos y recorremos todas las traducciones
		if( count($datosShortcodes) > 0 )
		{
			foreach( $datosShortcodes as $key => $mainTraduction )
			{
				if( !array_key_exists($mainTraduction->shortcode, $arrayTraductions) )
					$arrayTraductions[$mainTraduction->shortcode] = array();

				//Obtenemos datos del idioma de la traduccion
				$idioma = $this->getLanguages($mainTraduction->id_language);

				if( ($translation_status == '1' && empty($mainTraduction->content)) || empty($translation_status) )
					$arrayTraductions[$mainTraduction->shortcode][$idioma->slug] = $mainTraduction;

				if( count($arrayTraductions[$mainTraduction->shortcode]) == '0' )
					unset($arrayTraductions[$mainTraduction->shortcode]);
			}
		}

		return $arrayTraductions;
	}

	//Funcion que va a devolver todas las traducciones agrupadas por shortcode
	public function getAllTraductionsGroupedFilteredV2($comienzo, $limite, $applyLimit = true){
		//Getting searchs params
		$filter_busqueda      = $this->app['tools']->getValue('search');
		$filter_slug_idioma   = $this->app['tools']->getValue('slug_language');
		$traduction_for       = $this->app['tools']->getValue('traduction_for');

		//Where sqls additional
		$whereBusqueda = '';
		if ($filter_busqueda !== '') {
			$searchEscaped = addslashes($filter_busqueda);
			$whereBusqueda = "AND (content LIKE '%$searchEscaped%' OR shortcode LIKE '%$searchEscaped%')";
		}

		$whereTraductionFor = '';
		if ($traduction_for !== 'all') {
			$traductionForEscaped = addslashes($traduction_for);
			$whereTraductionFor = "AND traduction_for = '$traductionForEscaped'";
		}

		//Getting the language
		if ($filter_slug_idioma !== '') {
			$language = $this->getLangBySlug($filter_slug_idioma);
		} else {
			$language = $this->getDefaultLanguage();
		}
		$id_lang = $language->id;

		//Apply limits
		$limit = $applyLimit ? "LIMIT $comienzo, $limite" : '';

		//Language translations with pagination
		$datos = $this->app['bd']->fetchObject("
			SELECT *
			FROM languages_translations
			WHERE id_language = '$id_lang'
			$whereBusqueda
			$whereTraductionFor
			ORDER BY shortcode ASC
			$limit
		");

		if (count($datos) === 0) return [];

		//Getting different languages
		$languages = $this->getLanguagesVisibles();

		//Getting shortcodes and zones to masive fetch
		$shortcodes = array_column($datos, 'shortcode');
		$zones = array_column($datos, 'traduction_for');

		$shortcodeList = "'" . implode("','", array_map('addslashes', $shortcodes)) . "'";
		$zoneList = "'" . implode("','", array_map('addslashes', array_unique($zones))) . "'";

		//Masive translations
		$allLangsTranslations = $this->app['bd']->fetchObject("
			SELECT *
			FROM languages_translations
			WHERE shortcode IN ($shortcodeList)
			AND traduction_for IN ($zoneList)
		");

		//Reorganization of content
		$translationsIndex = [];
		foreach ($allLangsTranslations as $row) {
			$key = $row->traduction_for . '||' . $row->shortcode;
			$translationsIndex[$key][$row->id_language] = $row;
		}

		foreach ($datos as $translation) {
			$key = $translation->traduction_for . '||' . $translation->shortcode;
			$translation->langs = [];

			foreach ($languages as $lang) {
				if (isset($translationsIndex[$key][$lang->id])) {
					$langData = $translationsIndex[$key][$lang->id];
					$translation->langs[$lang->slug] = true;

					//Getting the main content of default lang
					if (
						($filter_slug_idioma && $filter_slug_idioma == $lang->slug) ||
						(!$filter_slug_idioma && $lang->is_default == '1')
					) {
						$translation->content = $langData->content;
					}

					if ($lang->slug == $language->slug) {
						$translation->id = $langData->id;
					}
				} else {
					$translation->langs[$lang->slug] = false;
				}
			}
		}

		return $datos;
	}

	//Funcion que devuelve las traducciones agrupadas para un shortcode
	public function getTraductionGrouped($id){

		//Obtenemos todas las traducciones por shortcode
		$datosShortcodes = $this->app['bd']->fetchObject('SELECT * FROM languages_translations WHERE id = "'.$id.'"');

		//Diferentes idiomas
		$datosIdiomas = $this->getLanguages();

		//Array principal que contendra cada shortcode y sus traducciones
		$arrayTraductions = [];

		//Comprobamos y recorremos todas las traducciones
		if( count($datosShortcodes) > 0 )
		{
			foreach($datosShortcodes as $key => $mainTraduction)
			{
				//Recorremos los diferentes idiomas para buscar sus textos por shortcode
				foreach($datosIdiomas as $keyI => $idioma)
				{
					//Buscamos su traduccion por shortcode
					$datosTraduction = $this->getTraductionByLangShort($idioma->id, $mainTraduction->shortcode);

					//Añadimos la traduccion de ese shortcode en ese array
					$arrayTraductions[$idioma->slug] = $datosTraduction;
				}
			}
		}

		return $arrayTraductions;
	}

	//Funcion que actualiza datos del usuario
	public function insertTraduction($datos){

		//Añadimos idioma por defecto
		$this->app['bd']->insert('languages_translations', $datos);

		$idiomas = $this->getLanguages();
		$defaultLanguage = $datos['id_language'];

		foreach( $idiomas as $i )
		{
			if( $i->id != $defaultLanguage )
			{
				$datos['id_language'] = $i->id;
				$datos['content'] = '';
				$this->app['bd']->insert('languages_translations', $datos);
			}
		}
	}

	//Funcion que actualiza datos del usuario
	public function updateTraduction($id, $datos){
		$this->app['bd']->update('languages_translations', $datos, 'id = "'.$id.'"');
	}

	//Funcion que crea/actualiza un idioma
	public function administrarIdioma(){

		//ID producto
		$id = $this->app['tools']->getValue('id');
		$msg = "ok";

		//Obtenemos los datos del idioma (si es update)
		if( $id != '0' )
			$datos = $this->getLanguages($id);

		$upd['name'] 			= $this->app['tools']->getValue('name');
		$upd['slug'] 			= $this->app['tools']->getValue('slug');
		$upd['colour'] 			= $this->app['tools']->getValue('colour');
		$upd['status'] 			= (isset($_REQUEST['status'])) ? 'active' : 'inactive';

		if( $upd['name'] != "" )
		{
			if( $upd['slug'] != "" )
			{
				//Vamos a buscar otros idiomas con mismo slug
				$datosLang = $this->app['bd']->fetchObject('SELECT * FROM languages WHERE slug = "'.$upd['slug'].'"');

				if( count($datosLang) == '0' )
				{
					//DO NOTHING
				}
				else
				{
					if( $id == '0' )
						$msg = "La abreviatura que indicas ya está siendo usada en el idioma <strong>" . $datosLang[0]->name . "</strong>. <a href='"._ADMIN_."administrar-idioma/".$datosLang[0]->id."/' class='text-white'><u>Editar idioma</u></a>";
				}

				//Comprobamos color si no hay errores.
				if( $msg == 'ok' )
				{
					if( $upd['colour'] != "" )
					{
						//DO NOTHING
					}
					else
						$msg = "Especifica el color, coge por defecto <strong>primary</strong> si no sabes cual poner.";
				}
			}
			else
				$msg = "Debes indicar la abreviatura como mínimo para que el idioma sea funcional.";
		}
		else
			$msg = "Debes indicar el nombre del idioma";

		//Comprobamos si no tiene imagen destacada ya que sera obligatoria que suba
		if( $msg == 'ok' )
		{
			if( isset($_FILES['icon']) && $_FILES['icon']['size'] > '0' )
			{
				$imagenes 	= $_FILES['icon'];
				$ruta_img = $this->app['tools']->uploadImage('img/flags/', 'icon', $upd['slug'].'-flag-'.time());

				//Guardamos las imagenes en la BD.
				if( $ruta_img['type'] == 'success' )
				{
					$upd['icon'] 	= $ruta_img['data'];

					//Comrpobamos si tenia imagen destacada para eliminarla
					if( isset($datos->icon) && $datos->icon != "" && file_exists(_PATH_.$datos->icon) )
						unlink(_PATH_.$datos->icon);

					$msg = "ok";
				}
				elseif( $ruta_img['type'] == 'error' )
					$msg = $ruta_img['error'];
			}
			else
			{
				if( $id == '0' )
					$msg = "No se ha seleccionado ninguna imagen de bandera y es obligatoria.";
			}
		}

		//Solo si es OK actualizamos
		if( $msg == "ok" )
		{
			if( $id == '0' )
			{
				if( $this->app['bd']->insert('languages', $upd) )
				{

					//Idioma default.
					$language_default = $this->getDefaultLanguage();

					//Generamos todas las traducciones en blanco para el nuevo idioma
					$createdLanguage = $this->app['bd']->lastId();
					$defaultLang = $this->getLangBySlug($language_default->slug);
					$traducciones = $this->getAllTraductionsByIdGroupedShortcode($defaultLang->id);

					foreach( $traducciones as $trad )
					{
						$dummyTrad = array(
							'id_language' => $createdLanguage,
							'traduction_for' => $trad->traduction_for,
							'shortcode' => $trad->shortcode,
							'content' => '',
							'creation_date' => $this->app['tools']->datetime(),
							'update_date' => $this->app['tools']->datetime()
						);
						$this->app['bd']->insert('languages_translations', $dummyTrad);
						unset($dummyTrad);
					}
					return "ok";
				}
				else
					return "Ha ocurrido un error interno al intentar crear el idioma. Inténtalo de nuevo y si el problema persiste comunícalo.";
			}
			else
			{
				//Si es update, y ha subido imagen, debemos actualizarla.
				if( isset($upd['icon']) && $upd['icon'] != "" )
				{

					//Eliminamos la imagen que ya tenia
					if( isset($datos->icon) && $datos->icon != "" && file_exists(_PATH_.$datos->icon) )
						unlink(_PATH_.$datos->icon);
				}

				if( $this->app['bd']->update('languages', $upd, "id = '".$id."'") )
					return "ok";
				else
					return "Ha ocurrido un error interno al intentar guardar el idioma. Inténtalo de nuevo y si el problema persiste comunícalo.";
			}
		}
		else
			return $msg;
	}

	//Funcion que devuelve las traducciones en funcion de la url y language session
	public function getTranslationFor($slug, $slug_language=''){

		if( empty($slug_language) )
			$slug_language = $_SESSION['lang'];

		//En base al slug obtenemos el id_idioma
		$datos_idioma = $this->getLangBySlug($slug_language);

		$datosTraduction = $this->app['bd']->fetchObject('SELECT * FROM languages_translations where traduction_for = "'.$slug.'" AND id_language = "'.$datos_idioma->id.'"');

		$arrayOfTraduction = [];

		//Recorremos y devolvemos el mismo array pero con shortcode como key
		foreach( $datosTraduction as $key => $traduction )
			$arrayOfTraduction[$traduction->shortcode] = $traduction;

		return $arrayOfTraduction;
	}

	//Funcion que devuelve solo los shortcodes agrupados 
	public function getTraductionsForGrouped(){

		//Obtenemos el idioma default.
		$lang = $this->getDefaultLanguage();
        $id_lang = $lang->id;

		return $this->app['bd']->fetchObject("
			SELECT traduction_for 
			FROM languages_translations 
			WHERE id_language = '".$id_lang."' 
			GROUP BY traduction_for
		");
	}

	//Devuelve una cadena segun el shortcode, la zone de traduccion y el idioma
	public function getTranslation($shortcode, $traduction_for){
		$currentLang = $this->getLangBySlug($this->app['tools']->getValue('lang', $_SESSION['lang']));
		$traduccion = $this->app['bd']->fetchRow("SELECT content FROM languages_translations WHERE traduction_for =  '".$traduction_for."' AND shortcode = '".$shortcode."' AND id_language = ".(int)$currentLang->id." LIMIT 1");
		return (!empty($traduccion) && !empty($traduccion->content) ? $traduccion->content : "untranslated");
	}

	//Devuelve una cadena segun el shortcode en un array de traducciones
	public function getTranslationByShortcode($shortcode, $translations){
		if(isset($translations[$shortcode]) && isset($translations[$shortcode]->content) && !empty($translations[$shortcode]->content))
			return $translations[$shortcode]->content;
		else
			return "untranslated";
	}

	//Funcion que reemplaza un argumento en el contenido
	public function replaceArg($string, $arg, $newValue){
		return str_replace("%".$arg."%", $newValue, $string);
	}

	public function loadPageTranslations($page, $prefix='', $lang=''){
		if( $page == '' )
			$page = 'home';

		if( $prefix != '' )
			$page = $prefix.'_'.$page;

		$_SESSION['traducciones'] = $this->getTranslationFor($page, $lang);
	}

	public function getAllTraductionsByIdGroupedShortcode($id_idioma){
		$traducciones = $this->getAllTraductionsById($id_idioma);
		$result = array();
		if( !empty($traducciones) )
			foreach( $traducciones as $t )
				$result[$t->shortcode] = $t;
		return $result;
	}

	//Funcion que devuelve una traduccion
    public function getTranslationById($id){

        $datos = $this->app['bd']->fetchObject("
            SELECT *
            FROM languages_translations
            WHERE id  = '".$id."'
        ");

        if(count($datos) == '1')
            return $datos[0];
        else
            return false;
    }

    //Funcion que devuelve una traduccion en un idioma.
    public function getTranslationByTraductionForAndShortcode($traduction_for, $shortcode, $slug_language=""){

        //Si no proporciona idioma, obtenemos el default.
        if($slug_language == ''){
            $lang = $this->getDefaultLanguage();
            $id_lang = $lang->id;
        }
        else{
            $lang = $this->getLangBySlug($slug_language);
            $id_lang = $lang->id;
        }

        $datos = $this->app['bd']->fetchObject("
            SELECT *
            FROM languages_translations
            WHERE id_language = '".$id_lang."'
            AND traduction_for  = '".$traduction_for."'
            AND shortcode  = '".$shortcode."'
        ");

        if(count($datos) == '1')
            return $datos[0];
        else
            return false;
    }

	//Funcion que devuelve los datos de la traduccion en diferentes idiomas.
    public function getTranslationByIdWithTranslations($id){

    	//Variables default
    	$datos = new \stdClass;

        //Obtenemos los diferentes idiomas visibles.
        $languages = $this->getLanguagesVisibles();

        //Obtenemos los datos del key segun idioma.
        $datos_default = $this->getTranslationById($id);

        if($datos_default){
        	foreach($languages as $key => $lang){

        		//Obtenemos los datos del key segun idioma.
            	$datos_trans = $this->getTranslationByTraductionForAndShortcode($datos_default->traduction_for, $datos_default->shortcode, $lang->slug);

	            //Preparamos un array objecto por cada idioma para guardar su traduccion si es que tiene.
	            $datos->{'lang_'.$lang->slug} = [];

	            if($datos_trans)
	                $datos->{'lang_'.$lang->slug} = $datos_trans;
	        }
        }

        return $datos;
    }

    //Funcion que elimina una traduccion
    public function deleteTranslation($id){

    	//Obtenemos los datos base segun el ID para obtener traduction_for y shortcode
    	$datos_base = $this->getTranslationById($id);

        //Eliminamos
        if($datos_base && isset($datos_base->traduction_for) && $datos_base->shortcode)
        	$this->app['bd']->query("DELETE FROM languages_translations WHERE traduction_for = '".$datos_base->traduction_for."' AND shortcode = '".$datos_base->shortcode."'");
    }

    //Funcion que genera los hreflangs
    public function generateHreflangs(){

    	//Variables default
    	$mod_id = '';
    	$arrayHreflangs = [];

    	//Obtenemos los diferentes idiomas activos.
    	$languages = $this->getLanguagesVisibles();

    	//Si es una pagina diferente a la home.
		if(isset($_REQUEST['mod'])){

			//En base al idioma y al mod, obtenemos la info para saber que MOD_ID es.
			$_pageData = $this->app['bd']->fetchRow('
				SELECT *
				FROM slugs
				WHERE status = "active"
				AND slug = "'.$_REQUEST['mod'].'"
				AND id_language = (
					SELECT id
					FROM languages
					WHERE slug = "'.$_SESSION['lang'].'"
					AND status = "active"
				)
			');

			//Si existe la pagina, continuamos.
			if(isset($_pageData->mod_id) && $_pageData->mod_id != '')
				$mod_id = $_pageData->mod_id;
		}

    	if(count($languages) > 0){
    		foreach($languages as $key => $lang){

    			/*
    				Pasos a seguir:
    				=====================
    					1 - Obtener el slug del mod en el idioma (si es distinto de vacio.)
    					2 - Si tiene slug del idioma, entonces buscamos los hrflang
    			*/

    			//Vamos a obtener los hreflangs del idioma y entonces generar los diferentes hreflangs
				$hreflangs = $this->app['bd']->fetchObject('
					SELECT *
					FROM languages_hreflangs
					WHERE id_lang = "'.$lang->id.'"
				');

    			if($mod_id != ''){
	    			$slugPage = $this->app['bd']->fetchRow('
						SELECT slug
						FROM slugs
						WHERE status = "active"
						AND mod_id = "'.$mod_id.'"
						AND id_language = (
							SELECT id
							FROM languages
							WHERE slug = "'.$lang->slug.'"
							AND status = "active"
						)
					');

	    			//Si tiene slug, entonces
					if(isset($slugPage->slug) && $slugPage->slug != ''){

						//Recorremos los HREFLANGS
						if(count($hreflangs) > 0){
							foreach($hreflangs as $keyH => $hreflang){
								$arrayHreflangs[] = '<link rel="alternate" hreflang="'.$hreflang->hreflang .'" href="'._DOMINIO_.$lang->slug.'/'.$slugPage->slug.'/" />';
							}
						}
					}
	    		}
	    		else{
	    			$slugPage = '';

	    			//Recorremos los HREFLANGS
					if(count($hreflangs) > 0){
						foreach($hreflangs as $keyH => $hreflang){
							$arrayHreflangs[] = '<link rel="alternate" hreflang="'.$hreflang->hreflang .'" href="'._DOMINIO_.$lang->slug.'/" />';
						}
					}
	    		}
    		}
    	}

    	return $arrayHreflangs;
    }

    //Funcion que genera los canonicals
    public function generateCanonical(){

    	//Variables default
    	$mod_id 			= '';
    	$arrayCanonicals 	= [];
    	$data 				= (isset($_REQUEST['data'])) ? $this->app['tools']->getValue('data') : '';
    	$data2 				= (isset($_REQUEST['data2'])) ? $this->app['tools']->getValue('data2') : '';
    	$data3 				= (isset($_REQUEST['data3'])) ? $this->app['tools']->getValue('data3') : '';
    	$slugData 			= '';

    	//Slug with DATA
    	if($data != ''){

    		$slugData .= $data . '/';

    		if($data2 != '')
    			$slugData .= $data2 . '/';

    		if($data3 != '')
    			$slugData .= $data3 . '/';
    	}

    	//Obtenemos los diferentes idiomas activos.
    	$languages = $this->getLanguagesVisibles();

    	//Si es una pagina diferente a la home.
		if(isset($_REQUEST['mod'])){

			//En base al idioma y al mod, obtenemos la info para saber que MOD_ID es.
			$_pageData = $this->app['bd']->fetchRow('
				SELECT *
				FROM slugs
				WHERE status = "active"
				AND slug = "'.$_REQUEST['mod'].'"
				AND id_language = (
					SELECT id
					FROM languages
					WHERE slug = "'.$_SESSION['lang'].'"
					AND status = "active"
				)
			');

			//Si existe la pagina, continuamos.
			if(isset($_pageData->mod_id) && $_pageData->mod_id != '')
				$mod_id = $_pageData->mod_id;
		}

    	if(count($languages) > 0){
    		foreach($languages as $key => $lang){

    			/*
    				Pasos a seguir:
    				=====================
    					1 - Obtener el slug del mod en el idioma (si es distinto de vacio.)
    			*/

    			if($lang->slug == $_SESSION['lang']){
	    			if($mod_id != ''){
		    			$slugPage = $this->app['bd']->fetchRow('
							SELECT slug
							FROM slugs
							WHERE status = "active"
							AND mod_id = "'.$mod_id.'"
							AND id_language = (
								SELECT id
								FROM languages
								WHERE slug = "'.$lang->slug.'"
								AND status = "active"
							)
						');

		    			//Si tiene slug, entonces
						if(isset($slugPage->slug) && $slugPage->slug != ''){

							$arrayCanonicals[] = '<link rel="canonical" href="'._DOMINIO_.$lang->slug.'/'.$slugPage->slug.'/'.$slugData.'" />';
						}
		    		}
		    		else{
		    			$slugPage = '';

		    			$arrayCanonicals[] = '<link rel="canonical" href="'._DOMINIO_.$lang->slug.'/'.$slugData.'" />';
		    		}
		    	}
    		}
    	}

    	return $arrayCanonicals;
    }

    /********************************************
	 *											*
	 * 			FUNCIONES SOBRE LANGUAGES 		*
	 *											*
     ********************************************/

    //Function que devuelve los datos base de un idioma
    public function getLanguageBaseById($id){

        $datos = $this->app['bd']->fetchObject("
            SELECT *
            FROM languages
            WHERE id = '".$id."'
        ");

        if(count($datos) == '1')
            return $datos[0];
        else
            return false;
    }

    //Funcion que devuelve un language en un idioma.
    public function getLanguageById($id_language, $slug_language=""){

        //Si no proporciona idioma, obtenemos el default.
        if($slug_language == ''){
            $lang = $this->getDefaultLanguage();
            $id_lang = $lang->id;
        }
        else{
            $lang = $this->getLangBySlug($slug_language);
            $id_lang = $lang->id;
        }

        $datos = $this->app['bd']->fetchObject("
            SELECT *
            FROM languages_lang
            WHERE id_lang = '".$id_lang."'
            AND id_language  = '".$id_language."'
        ");

        if(count($datos) == '1')
            return $datos[0];
        else
            return false;
    }

    //Funcion que devuelve idiomas filtrados
    public function getLanguagesFiltered($comienzo, $limite, $applyLimit=true){

        //Obtenemos idioma default.
        $lang = $this->getDefaultLanguage();

        if($applyLimit)
            $limit = "LIMIT $comienzo, $limite";
        else
            $limit = "";

        $datos = $this->app['bd']->fetchObject("
            SELECT l.*
            FROM languages l, languages_lang ll
            WHERE l.id = ll.id_language 
            AND ll.id_lang = '".$lang->id."'
            ORDER BY ll.name ASC
            $limit
        ");

        //Recorremos los planes obtenidos para revisar si tiene traduccion de los diversos idiomas.
        if(count($datos) > 0){

            //Obtenemos todos los idiomas y simplemente vamos a comprobar si tiene traduccion para cada idioma.
            $languages = $this->getLanguagesVisibles();

            foreach($datos as $key => $language){

                $language->name 	= '';
                $language->langs 	= [];

                //Para cada destino, comprobamos si tiene traduccion de un idioma especifico.
                foreach($languages as $key => $lang){

                    $datos_lang = $this->app['bd']->fetchObject("SELECT * FROM languages_lang WHERE id_language  = '".$language->id."' AND id_lang = '".$lang->id."'");

                    if(count($datos_lang) == '1'){

                        //Si existe marcamos como "OK".
                        $datos_lang = $datos_lang[0];
                        $language->langs[$lang->slug] = true;

                        //Cogemos determinados datos del destino default.
                        if($lang->is_default == '1'){
                            if(isset($datos_lang->name))
                                $language->name = $datos_lang->name;
                        }
                    }
                    else
                        $language->langs[$lang->slug] = false;
                }
            }
        }

        return $datos;
    }

    //Funcion que devuelve los datos de un idioma en diferentes idiomas.
    public function getLanguageByIdWithTranslations($id){

        //Obtenemos los diferentes idiomas visibles.
        $languages = $this->getLanguagesVisibles();

        $datos = $this->getLanguageBaseById($id);

        foreach($languages as $key => $lang){

            //Obtenemos los datos del idioma segun slug.
            $datos_lang = $this->getLanguageById($id, $lang->slug);

            //Preparamos un array objecto por cada idioma para guardar su traduccion si es que tiene.
            $datos->{'lang_'.$lang->slug} = [];

            if($datos_lang)
                $datos->{'lang_'.$lang->slug} = $datos_lang;
        }

        return $datos;
    }

    //Funcion que comprueba slug y devuelve si se puede usar o no.
    public function checkIfSlugIsAvailable($slug, $id_language=""){

        $whereLanguage = '';
        if($id_language != '')
            $whereLanguage = " AND id != '".$id_language."'";

        $sql = "
            SELECT id
            FROM languages
            WHERE slug = '".$slug."'
            ".$whereLanguage."
        ";

        if($this->app['bd']->countRows($sql) == '1')
            return false;
        else
            return true;
    }

    /********************************************
	 *											*
	 * 			FUNCIONES SOBRE COOKIES 		*
	 *											*
     ********************************************/

    public function getCookiesTranslations($slug){

    	//Variables default
    	$_slugs = new Slugs($this->app);
    	$slug_politica = $_slugs->getSlugByModId('textos-legales');
    	$slug_contacto = $_slugs->getSlugByModId('contacto');

    	//Traducciones de cookies
    	$translations = $this->getTranslationFor('cookies', $slug);

    	if(empty($translations))
    		return [];

    	$cookiesArray = [];

    	//MAIN INFORMATION BLOCK
    	$cookiesArray['slug_lang'] 					= $slug;
    	$cookiesArray['main']['title'] 				= $this->getTranslationByShortcode('main-title', $translations);
    	$cookiesArray['main']['description'] 		= $this->getTranslationByShortcode('main-description', $translations);
    	$cookiesArray['main']['description'] 		= $this->replaceArg($cookiesArray['main']['description'], 'start_link', '<a href="'._DOMINIO_.$_SESSION['lang'].'/'.$slug_politica.'/cookies/">');
    	$cookiesArray['main']['description'] 		= $this->replaceArg($cookiesArray['main']['description'], 'end_link', '</a>');

    	$cookiesArray['main']['description_choose'] = $this->getTranslationByShortcode('main-description-choose', $translations);
    	$cookiesArray['main']['accept_all'] 		= $this->getTranslationByShortcode('main-btn-accept-all', $translations);
    	$cookiesArray['main']['reject_all'] 		= $this->getTranslationByShortcode('main-btn-reject-all', $translations);

    	//SETTINGS DEFAULT
    	$cookiesArray['settings']['title'] 			= $this->getTranslationByShortcode('settings-title', $translations);
    	$cookiesArray['settings']['save_settings'] 	= $this->getTranslationByShortcode('settings-btn-save', $translations);
    	$cookiesArray['settings']['accept_all'] 	= $this->getTranslationByShortcode('settings-btn-accept-all', $translations);
    	$cookiesArray['settings']['reject_all'] 	= $this->getTranslationByShortcode('settings-btn-reject-all', $translations);
    	$cookiesArray['settings']['close'] 			= $this->getTranslationByShortcode('settings-btn-close', $translations);
    	$cookiesArray['settings']['col1'] 			= $this->getTranslationByShortcode('settings-col1', $translations);
    	$cookiesArray['settings']['col2'] 			= $this->getTranslationByShortcode('settings-col2', $translations);
    	$cookiesArray['settings']['col3'] 			= $this->getTranslationByShortcode('settings-col3', $translations);
    	$cookiesArray['settings']['col4'] 			= $this->getTranslationByShortcode('settings-col4', $translations);

    	//BLOQUES

    		//BLOCK1
    		$cookiesArray['settings']['block1']['title'] 			= $this->getTranslationByShortcode('settings-block1-title', $translations);
    		$cookiesArray['settings']['block1']['description'] 		= $this->getTranslationByShortcode('settings-block1-description', $translations);
    		$cookiesArray['settings']['block1']['description'] 		= $this->replaceArg($cookiesArray['settings']['block1']['description'], 'start_link', '<a href="'._DOMINIO_.$_SESSION['lang'].'/'.$slug_politica.'/cookies/">');
    		$cookiesArray['settings']['block1']['description'] 		= $this->replaceArg($cookiesArray['settings']['block1']['description'], 'end_link', '</a>');

    		//BLOCK2
    		$cookiesArray['settings']['block2']['title'] 			= $this->getTranslationByShortcode('settings-block2-title', $translations);
    		$cookiesArray['settings']['block2']['description'] 		= $this->getTranslationByShortcode('settings-block2-description', $translations);

    		//BLOCK3
    		$cookiesArray['settings']['block3']['title'] 			= $this->getTranslationByShortcode('settings-block3-title', $translations);
    		$cookiesArray['settings']['block3']['description'] 		= $this->getTranslationByShortcode('settings-block3-description', $translations);

    		//BLOCK4
    		$cookiesArray['settings']['block4']['title'] 			= $this->getTranslationByShortcode('settings-block4-title', $translations);
    		$cookiesArray['settings']['block4']['description'] 		= $this->getTranslationByShortcode('settings-block4-description', $translations);

    		//BLOCK5
    		$cookiesArray['settings']['block5']['title'] 			= $this->getTranslationByShortcode('settings-block5-title', $translations);
    		$cookiesArray['settings']['block5']['description'] 		= $this->getTranslationByShortcode('settings-block5-description', $translations);
    		$cookiesArray['settings']['block5']['description'] 		= $this->replaceArg($cookiesArray['settings']['block5']['description'], 'start_link', '<a href="'._DOMINIO_.$_SESSION['lang'].'/'.$slug_contacto.'/">');
    		$cookiesArray['settings']['block5']['description'] 		= $this->replaceArg($cookiesArray['settings']['block5']['description'], 'end_link', '</a>');


    	return json_encode($cookiesArray);
    }

    /********************************************
	 *											*
	 * 			STATIC TRANSLATIONS 			*
	 *											*
     ********************************************/

    public function getTranslationStatic($shortcode, $lang = _DEFAULT_APP_LANGUAGE_, $vars = []) {
    	//Default vars
	    static $cache = [];

	    if (!isset($cache[$lang])) {
	        $file = _PATH_ . "/lang/$lang.php";
	        $cache[$lang] = file_exists($file)
	            ? include $file
	            : include _PATH_ . "/lang/en.php";
	    }
	    
	    $text = $cache[$lang][$shortcode] ?? $shortcode;

	    // Replace placeholders
	    foreach ($vars as $key => $value) {
	        $text = str_replace("%$key%", $value, $text);
	    }

	    return $text;
	}
}
