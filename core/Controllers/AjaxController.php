<?php
namespace Controllers;
use Funks\Admin;
use Funks\About;
use Funks\Projects;
use Funks\Careers;
use Funks\Skills;
use Funks\Pages;
use Funks\Documents;

class AjaxController
{
	var $page;
	var $app;

	public function execute($page,$app)
	{
		$this->page = $page;
		$this->app = $app;

		$this->app['render']->layout = false;

		/****************************************************
		 *													*
		 *		   		MANAGE ABOUT ME VIA AJAX 			*
		 *													*
		 ****************************************************/

		 //Funtion to get modal to manage about
		$this->add('ajax-get-modal-manage-about',function(){

			//Default variables
			$about	= new \stdClass();
			$_about = new About($this->app);
			$action = 'create';

			//Getting data
			$id 		= $this->app['tools']->getValue('id');

			if($id != '0'){
				$action = 'update';
				$about 		= $_about->getAboutByIdWithTranslations($id);
			}

			$data = [
				'id' 		=> $id,
				'action' 	=> $action,
				'about' 	=> $about,
				'languages' => $this->app['lang']->getLanguagesTranslatedVisible(),
			];

			$html = $this->app['render']->getAjaxPage('about/ajax-get-modal-manage-about',$data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'error' => 'Hubo algun problema cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Function to manage the translation of each about me
		$this->add('ajax-manage-about',function(){

			//Default variables
			$msg_error = "";
			$data = [];
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			//Getting variables
			$id					= $this->app['tools']->getValue('id');
			$action 			= $this->app['tools']->getValue('action');
			$id_lang 			= $this->app['tools']->getValue('id_lang');
			$title 				= $this->app['tools']->getValue('title');
			$description		= (isset($_REQUEST['description'])) ? addslashes($_REQUEST['description']) : '';

			//Checking data
			if($title == "")
				$response['error'] = "You must indicate the name of the language.";
			if($description == "")
				$response['error'] = "You must indicate the description of the language.";

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){
				switch ($action) {
					case 'create':

						$data['id_about'] 		= $id;
						$data['id_lang'] 		= $id_lang;
						$data['title'] 			= $title;
						$data['description'] 	= $description;

						//Creating translation for about me
						$this->app['bd']->insert('about_lang', $data);
						
						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
					case 'update':
						
						$data['title'] 			= $title;
						$data['description'] 	= $description;

						//Updating translation for about block
						$this->app['bd']->update('about_lang', $data, "id_about  = '".$id."' AND id_lang = '".$id_lang."'");

						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
				}
			}

			die(json_encode($response));
		});

		//Function to manage about base information
		$this->add('ajax-manage-about-base',function(){

			//Default variables
			$msg_error = "";
			$data = [];
			$base_route 		= "images/about-me/";
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			//Getting variables
			$id 				= $this->app['tools']->getValue('id');
			$action 			= $this->app['tools']->getValue('action');

			//Checking if image/icon is mandatory
			$isMandatoryImage = ($action == "create") ? true : false;
			if($isMandatoryImage)
				if(isset($_FILES['image']["name"]) && $_FILES['image']["name"] == "")
					$response['error'] = "You must select an image of you.";

			//Checking data
			if(isset($_FILES['image']["name"]) && $_FILES['image']["name"] != ""){

				//Uploading image
				$image_route = $this->app['tools']->uploadImage($base_route, 'image', 'fernando-llopis-'.time());

				//Checking if the image was uploaded successfully
				if(isset($image_route["type"]) && $image_route["type"] == "success"){
					$data['image'] = $image_route["data"];
				}
				else
					$msg_error = "There was an error uploading the image. Please try again and/or try another image.";
			}

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){
				//Creating or updating
				switch ($action) {
					case 'create':
						
						$data['creation_date'] 		= $this->app['tools']->datetime();
						$data['updated_date'] 		= $this->app['tools']->datetime();

						//Creating the about
						$this->app['bd']->insert('about', $data);

						$id = $this->app['bd']->lastId();
						
						$response['type'] 	= 'success';
						$response['id'] 	= $id;
						$response['image'] 	= _DOMINIO_ . $data['image'];

						break;
					case 'update':

						//If user selects a new image, we need to delete the old one
						if(isset($data['image'])){

							//Actual data to delete the icon
							$_about = new About($this->app);
							$oldImageData = $_about->getAboutBaseById($id);

							if(isset($oldImageData->image) && $oldImageData->image != ""){
								if(file_exists(_PATH_.$oldImageData->image))
									unlink(_PATH_.$oldImageData->image);
							}
						}
						
						$data['updated_date'] 		= $this->app['tools']->datetime();

						//Updating the language
						$this->app['bd']->update('about', $data, "id  = '".$id."'");

						$response['type'] 		= 'success';
						$response['id'] 		= $id;
						$response['image'] 		= _DOMINIO_ . $data['image'];

						break;
				}
			}

			die(json_encode($response));
		});

		/****************************************************
		 *													*
		 *		   	MANAGE ABOUT BLOCKS VIA AJAX 			*
		 *													*
		 ****************************************************/

		//Function to get all about blocks
		$this->add('ajax-get-blocks-filtered', function(){

			//Default variables
			$_about 			= new About($this->app);
			$languages 			= $this->app['lang']->getLanguages();

			//Recogemos variables
			$slug_idioma 		= $this->app['tools']->getValue('slug_idioma');
			$translation_status = $this->app['tools']->getValue('translation_status');
			$comienzo 			= $this->app['tools']->getValue('comienzo');
			$limite 			= $this->app['tools']->getValue('limite');
			$pagina 			= $this->app['tools']->getValue('pagina');

			//Getting about blocks filtered
			$aboutBlocks = $_about->getAboutBlocksFiltered($comienzo, $limite);

			$data = array(
				'aboutBlocks' 	=> $aboutBlocks,
				'languages' 	=> $languages,
				'comienzo'  	=> $comienzo,
				'limite' 		=> $limite,
				'pagina' 		=> $pagina,
				'tools' 		=> $this->app['tools'],
			);

			$html = $this->app['render']->getAjaxPage('about/ajax-get-about-blocks-filtered', $data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'html' => 'Hubo un error cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Funtion to get modal to manage about block
		$this->add('ajax-get-modal-manage-about-block',function(){

			//Default variables
			$about	= new \stdClass();
			$_about = new About($this->app);
			$action = 'create';

			//Getting data
			$id 		= $this->app['tools']->getValue('id');
			$comienzo 	= $this->app['tools']->getValue('comienzo');
			$limite 	= $this->app['tools']->getValue('limite');
			$pagina 	= $this->app['tools']->getValue('pagina');

			if($id != '0'){
				$action = 'update';
				$about 		= $_about->getAboutBlockByIdWithTranslations($id);
			}

			$data = [
				'id' 		=> $id,
				'action' 	=> $action,
				'about' 	=> $about,
				'comienzo' 	=> $comienzo,
				'limite' 	=> $limite,
				'pagina' 	=> $pagina,
				'languages' => $this->app['lang']->getLanguagesTranslatedVisible(),
			];

			$html = $this->app['render']->getAjaxPage('about/ajax-get-modal-manage-about-block',$data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'error' => 'Hubo algun problema cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Function to manage the translation of each block
		$this->add('ajax-manage-about-block',function(){

			//Default variables
			$msg_error = "";
			$data = [];
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			//Getting variables
			$id					= $this->app['tools']->getValue('id');
			$action 			= $this->app['tools']->getValue('action');
			$id_lang 			= $this->app['tools']->getValue('id_lang');
			$title 				= $this->app['tools']->getValue('title');
			$content			= $this->app['tools']->getValue('content');

			//Checking data
			if($title == "")
				$response['error'] = "You must indicate the name of the language.";
			if($content == "")
				$response['error'] = "You must indicate the content of the language.";

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){
				switch ($action) {
					case 'create':

						$data['id_about_block'] = $id;
						$data['id_lang'] 		= $id_lang;
						$data['title'] 			= $title;
						$data['content'] 		= $content;

						//Creating translation for about block
						$this->app['bd']->insert('about_blocks_lang', $data);
						
						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
					case 'update':
						
						$data['title'] 		= $title;
						$data['content'] 	= $content;

						//Updating translation for about block
						$this->app['bd']->update('about_blocks_lang', $data, "id_about_block  = '".$id."' AND id_lang = '".$id_lang."'");

						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
				}
			}

			die(json_encode($response));
		});

		//Function to manage about block base
		$this->add('ajax-manage-about-block-base',function(){

			//Default variables
			$msg_error = "";
			$data = [];
			$response = [];

			$data['creation_date'] 		= $this->app['tools']->datetime();
			$data['updated_date'] 		= $this->app['tools']->datetime();

			//Creating the about block
			$this->app['bd']->insert('about_blocks', $data);

			$id = $this->app['bd']->lastId();

			//Creating the about block translation for default language because is necessary
			$lang = $this->app['lang']->getDefaultLanguage();

			$addTrans = [];
			$addTrans['id_about_block'] 	= $id;
			$addTrans['id_lang'] 			= $lang->id;
			$addTrans['title'] 				= "";
			$addTrans['content'] 			= "";

			//Creating translation for default language
			$this->app['bd']->insert('about_blocks_lang', $addTrans);
			
			$response['type'] 	= 'success';
			$response['id'] 	= $id;

			die(json_encode($response));
		});

		//Function to delete a about block
		$this->add('ajax-delete-about-block',function(){

			//Default variables
			$id = $this->app['tools']->getValue('id');

			//Deleting the translation
			$_about = new About($this->app);
			$_about->deleteAboutBlock($id);

			echo "ok";
		});

		/********************************************************
		 *														*
		 *		   	MANAGE ABOUT SOCIAL LINKS VIA AJAX 			*
		 *														*
		 ********************************************************/

		//Function to get all social links
		$this->add('ajax-get-about-social-links-filtered', function(){

			//Default variables
			$_about 			= new About($this->app);
			$languages 			= $this->app['lang']->getLanguages();

			//Recogemos variables
			$comienzo 			= $this->app['tools']->getValue('comienzo');
			$limite 			= $this->app['tools']->getValue('limite');
			$pagina 			= $this->app['tools']->getValue('pagina');

			//Getting about social filtered
			$aboutSocials = $_about->getAboutSocialLinksFiltered($comienzo, $limite);

			$data = array(
				'aboutSocials' 	=> $aboutSocials,
				'languages' 	=> $languages,
				'comienzo'  	=> $comienzo,
				'limite' 		=> $limite,
				'pagina' 		=> $pagina,
				'tools' 		=> $this->app['tools'],
			);

			$html = $this->app['render']->getAjaxPage('about/ajax-get-about-social-links-filtered', $data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'html' => 'Hubo un error cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Funtion to get modal to manage about social
		$this->add('ajax-get-modal-manage-about-social',function(){

			//Default variables
			$social	= new \stdClass();
			$_about = new About($this->app);
			$action = 'create';
			$nextPosition = 0;

			//Getting data
			$id 		= $this->app['tools']->getValue('id');
			$comienzo 	= $this->app['tools']->getValue('comienzo');
			$limite 	= $this->app['tools']->getValue('limite');
			$pagina 	= $this->app['tools']->getValue('pagina');

			if($id != '0'){
				$action = 'update';
				$social 		= $_about->getAboutSocialLinkById($id);
			} else {
				$nextPosition = $_about->app['bd']->fetchObject("SELECT MAX(position) as position FROM about_social_links");
				$nextPosition = $nextPosition[0]->position + 1;
			}

			$data = [
				'id' 			=> $id,
				'action' 		=> $action,
				'social' 		=> $social,
				'nextPosition' 	=> $nextPosition,
				'comienzo' 		=> $comienzo,
				'limite' 		=> $limite,
				'pagina' 		=> $pagina,
			];

			$html = $this->app['render']->getAjaxPage('about/ajax-get-modal-manage-about-social',$data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'error' => 'Hubo algun problema cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Function to manage the about social link
		$this->add('ajax-manage-about-social-link',function(){

			//Default variables
			$msg_error = "";
			$data = [];
			$response = [
				'type' => 'error',
				'error' => '',
				'message' => '',
			];

			//Getting variables
			$id 				= $this->app['tools']->getValue('id');
			$action 			= $this->app['tools']->getValue('action');
			$id_lang 			= $this->app['tools']->getValue('id_lang');
			$name 				= $this->app['tools']->getValue('name');
			$icon 				= addslashes($_REQUEST['icon']);
			// $icon 				= $this->app['tools']->getValue('icon');
			$position 			= $this->app['tools']->getValue('position');
			$url 				= $this->app['tools']->getValue('url');
			$visible 			= (isset($_POST['visible']) && $_POST['visible']) ? "1" : "0";
			$external 			= (isset($_POST['external']) && $_POST['external']) ? "1" : "0";

			//Checking data
			if($name == ""){
				$response['error'] = 'You must indicate the name of the social network';
			} elseif($icon == ""){
				$response['error'] = 'You must indicate the icon of the social network';
			} elseif($url == ""){
				$response['error'] = 'You must indicate the url of the social network';
			}

			//CREATION OR UPDATE
			if($response['error'] == ""){
				switch ($action) {
					case 'create':

						$data['name'] 			= $name;
						$data['url'] 			= $url;
						$data['icon'] 			= $icon;
						$data['visible'] 		= $visible;
						$data['external'] 		= $external;
						$data['position'] 		= $position;
						$data['creation_date'] 	= $this->app['tools']->datetime();
						$data['updated_date'] 	= $this->app['tools']->datetime();

						//Creating the social
						$this->app['bd']->insert('about_social_links', $data);

						$response['type'] 	= 'success';

						break;
					case 'update':
						
						$data['name'] 			= $name;
						$data['url'] 			= $url;
						$data['icon'] 			= $icon;
						$data['visible'] 		= $visible;
						$data['external'] 		= $external;
						$data['position'] 		= $position;
						$data['updated_date'] 	= $this->app['tools']->datetime();

						//Updating the translation
						$this->app['bd']->update('about_social_links', $data, "id  = '".$id."'");

						$response['type'] 	= 'success';

						break;
				}
			}

			die(json_encode($response));
		});

		//Function to delete about social link
		$this->add('ajax-delete-about-social-link',function(){

			//Default variables
			$id = $this->app['tools']->getValue('id');

			//Deleting the social link
			$_about = new About($this->app);
			$_about->deleteAboutSocialLink($id);

			echo "ok";
		});

		/****************************************************
		 *													*
		 *		   		MANAGE SKILLS VIA AJAX 				*
		 *													*
		 ****************************************************/

		 //Function to get all skills
		$this->add('ajax-get-skills', function(){

			//Default variables
			$_skills 			= new Skills($this->app);

			//Getting about categories filtered
			$skills = $_skills->getSkills();

			$data = array(
				'skills' 		=> $skills,
				'tools' 		=> $this->app['tools'],
			);

			$html = $this->app['render']->getAjaxPage('skills/ajax-get-skills', $data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'html' => 'Hubo un error cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Funtion to get modal to manage skill information
		$this->add('ajax-get-modal-manage-skill-information',function(){

			//Default variables
			$skills		= new \stdClass();
			$_skills 	= new Skills($this->app);
			$action 	= 'create';

			$skill	= $_skills->getSkillInformationWithTranslations();

			$data = [
				'action' 	=> $action,
				'skill'	=> $skill,
				'languages' => $this->app['lang']->getLanguagesTranslatedVisible(),
			];

			$html = $this->app['render']->getAjaxPage('skills/ajax-get-modal-manage-skill-information',$data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'error' => 'Hubo algun problema cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Function to manage the translation of skill information
		$this->add('ajax-manage-skill-information-translations',function(){

			//Default variables
			$msg_error = "";
			$data = [];
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			//Getting variables
			$action 			= $this->app['tools']->getValue('action');
			$id_lang 			= $this->app['tools']->getValue('id_lang');
			$title 				= $this->app['tools']->getValue('title');
			$description 		= addslashes($_REQUEST['description']);

			//Checking data
			if($title == "")
				$response['error'] = "You must indicate the title for skill information.";
			elseif($description == "")
				$response['error'] = "You must indicate the description for skill information.";
			

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){
				switch ($action) {
					case 'create':

						$data['id_lang'] 			= $id_lang;
						$data['title'] 				= $title;
						$data['description'] 		= $description;
						$data['creation_date']		= $this->app['tools']->datetime();
						$data['updated_date']		= $this->app['tools']->datetime();

						//Creating the translation
						$this->app['bd']->insert('skills_langs', $data);
						
						$response['type'] 			= 'success';

						break;
					case 'update':
						
						$data['title'] 				= $title;
						$data['description'] 		= $description;
						$data['updated_date']		= $this->app['tools']->datetime();

						//Updating the translation
						$this->app['bd']->update('skills_langs', $data, " id_lang = '".$id_lang."'");

						$response['type'] 			= 'success';

						break;
				}
			}

			die(json_encode($response));
		});

		//Funtion to get modal to manage a skill
		$this->add('ajax-get-modal-manage-skill',function(){

			//Default variables
			$skill	= new \stdClass();
			$action 		= 'create';

			//Getting data
			$id 		= $this->app['tools']->getValue('id');

			if($id != '0'){
				$action 	= 'update';
				$_skills 	= new Skills($this->app);
				$skill 		= $_skills->getSkillById($id);
			}

			$data = [
				'id' 		=> $id,
				'action' 	=> $action,
				'skill' 	=> $skill,
			];

			$html = $this->app['render']->getAjaxPage('skills/ajax-get-modal-manage-skill',$data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'error' => 'Hubo algun problema cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Function to manage the skill
		$this->add('ajax-manage-skill',function(){

			//Default variables
			$msg_error = "";
			$data = [];
			$base_route = "images/skills/";
			$_skills = new Skills($this->app);
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			//Getting variables
			$id 				= $this->app['tools']->getValue('id');
			$action 			= $this->app['tools']->getValue('action');
			$name 				= $this->app['tools']->getValue('name');
			$percentage			= $this->app['tools']->getValue('percentage');
			$position 			= $this->app['tools']->getValue('position');

			$isMandatoryImage = ($action == "create") ? true : false;
			if($isMandatoryImage)
				if(isset($_FILES['image']["name"]) && $_FILES['image']["name"] == "")
					$response['error'] = "You must select an icon for skill.";

			//Checking data
			if($name == "")
				$response['error'] = "You must indicate the name of the skill.";
			elseif($percentage == "")
				$response['error'] = "You must indicate the percentage of the skill and must be more than 0.";
			elseif($percentage <= 0 || $percentage > 100)
				$response['error'] = "The percentage should be more than 0 and max 100. <br />Currently: " . intval($percentage);
			elseif($position == "")
				$response['error'] = "You must indicate the position of the skill.";
			else{
				//Checking image
				if(isset($_FILES['image']["name"]) && $_FILES['image']["name"] != ""){

					//Uploading image
					$nameAmigable = $this->app['tools']->urlAmigable($name);
					$image_route = $this->app['tools']->uploadImage($base_route, 'image', 'skill-'.$nameAmigable."-".time());

					//Checking if the image was uploaded successfully
					if(isset($image_route["type"]) && $image_route["type"] == "success"){
						$data['icon'] = $image_route["data"];
					}
					else
						$msg_error = "There was an error uploading the image. Please try again and/or try another image.";
				}
			}

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){
				switch ($action) {
					case 'create':

						$data['name'] 			= $name;
						$data['percentage'] 	= $percentage;
						$data['position'] 		= $position;
						$data['creation_date'] 	= $this->app['tools']->datetime();
						$data['updated_date'] 	= $this->app['tools']->datetime();

						//Creating the translation
						$this->app['bd']->insert('skills', $data);

						$newIdCreated = $this->app['bd']->lastId();

						$_skills->reorderSkillsPosition($newIdCreated, $position);
						
						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
					case 'update':

						//If user selects a new image, we need to delete the old one
						if(isset($data['icon'])){

							//Actual data to delete the icon
							$oldIconData = $_skills->getSkillById($id);

							if(isset($oldIconData->icon) && $oldIconData->icon != ""){
								if(file_exists(_PATH_.$oldIconData->icon))
									unlink(_PATH_.$oldIconData->icon);
							}
						}

						//Reordering positions
						$_skills->reorderSkillsPosition($id, $position);
						
						$data['name'] 			= $name;
						$data['percentage'] 	= $percentage;
						$data['position'] 		= $position;
						$data['updated_date'] 	= $this->app['tools']->datetime();

						//Updating the translation
						$this->app['bd']->update('skills', $data, "id  = '".$id."'");

						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
				}
			}

			die(json_encode($response));
		});

		//Function to delete skill by id
		$this->add('ajax-delete-skill-by-id',function(){

			//Default variables
			$id = $this->app['tools']->getValue('id');

			//Deleting the translation
			$_skills = new Skills($this->app);
			$_skills->deleteSkillById($id);

			//Also reorder skills without the deleted
			$_skills->reorderSkillsPosition($id, 0);

			echo "ok";
		});

		/****************************************************
		 *													*
		 *		   	   MANAGE PROJECTS VIA AJAX 			*
		 *													*
		 ****************************************************/

		//Function to get all projects filtered
		$this->add('ajax-get-projects-filtered', function(){

			//Default variables
			$_projects 			= new Projects($this->app);
			$languages 			= $this->app['lang']->getLanguages();

			//Recogemos variables
			$comienzo 			= $this->app['tools']->getValue('comienzo');
			$limite 			= $this->app['tools']->getValue('limite');
			$pagina 			= $this->app['tools']->getValue('pagina');

			//Getting projects filtered
			$projects = $_projects->getProjectsFiltered($comienzo, $limite);

			$data = array(
				'projects' 		=> $projects,
				'languages'		=> $languages,
				'comienzo'  	=> $comienzo,
				'limite' 		=> $limite,
				'pagina' 		=> $pagina,
				'tools' 		=> $this->app['tools'],
			);

			$html = $this->app['render']->getAjaxPage('projects/ajax-get-projects-filtered', $data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'html' => 'Hubo un error cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Function to get project base
		$this->add('ajax-get-project-base', function(){

			//Default variables
			$_projects 			= new Projects($this->app);

			//Getting data
			$id 			= $this->app['tools']->getValue('id');
			$project		= $_projects->getProjectBaseById($id);

			$data = array(
				'id'			=> $id,
				'project' 		=> $project,
				'categories' 	=> $_projects->getCategoriesByLang(2, "", true),
				'tools' 		=> $this->app['tools'],
			);

			$html = $this->app['render']->getAjaxPage('projects/ajax-get-project-base', $data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html,
					'title' => $project->title 
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'html' => 'Hubo un error cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Function to manage project base information
		$this->add('ajax-manage-project-base',function(){

			//Default variables
			$_projects = new Projects($this->app);
			$msg_error = "";
			$data = [];
			$base_route 		= "images/portfolio/";
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			//Getting variables
			$id 				= $this->app['tools']->getValue('id');
			$action 			= $this->app['tools']->getValue('action');

			$title 				= $this->app['tools']->getValue('title');
			$developed_in 		= $this->app['tools']->getValue('developed_in');
			$id_category 		= $this->app['tools']->getValue('id_category');
			$url 				= $this->app['tools']->getValue('url');
			$position			= $this->app['tools']->getValue('position');
			$technologies		= $this->app['tools']->getValue('technologies');
			$visible 			= (isset($_REQUEST['visible'])) ? '1' : '0';

			//Checking data
			if($title == "")
				$response["error"] = "You must indicate the title of project";
			elseif($developed_in == "")
				$response["error"] = "You must indicate the developed_in of project";
			elseif($id_category == "0")
				$response["error"] = "You must select the category of project";
			elseif($position == "")
				$response["error"] = "You must indicate the position of project";
			elseif($technologies == "")
				$response["error"] = "You must indicate the technologies of project";
			else{
				//Checking if image is mandatory
				$isMandatoryImage = ($action == "create") ? true : false;
				if($isMandatoryImage){
					if(isset($_FILES['image_thumb']["name"]) && $_FILES['image_thumb']["name"] == "")
						$response['error'] = "You must select an image thumb for project.";
					if(isset($_FILES['image_main']["name"]) && $_FILES['image_main']["name"] == "")
						$response['error'] = "You must select an image main for project.";
				}
			}


			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){
				//Creating or updating
				switch ($action) {
					case 'create':
						
						$data['title '] 		= $title;
						$data['developed_in'] 	= $developed_in;
						$data['id_category'] 	= $id_category;
						$data['url'] 			= $url;
						$data['position'] 		= $position;
						$data['technologies'] 	= $technologies;
						$data['visible']		= $visible;
						$data['creation_date'] 	= $this->app['tools']->datetime();
						$data['updated_date'] 	= $this->app['tools']->datetime();

						//Creating the language
						$this->app['bd']->insert('projects', $data);

						$id = $this->app['bd']->lastId();

						//Uploading images and saving it.
						$dataImage = [];
						$titleAmigable = $this->app['tools']->urlAmigable($title);
						if(isset($_FILES['image_thumb']["name"]) && $_FILES['image_thumb']["name"] != ""){

							//Uploading image
							$image_route = $this->app['tools']->uploadImage($base_route.$id."/", 'image_thumb', $titleAmigable.'-thumb-'.time());

							//Checking if the image was uploaded successfully
							if(isset($image_route["type"]) && $image_route["type"] == "success"){
								$dataImage['image_thumb'] = $image_route["data"];
							}
						}
						if(isset($_FILES['image_main']["name"]) && $_FILES['image_main']["name"] != ""){

							//Uploading image
							$image_route = $this->app['tools']->uploadImage($base_route.$id."/", 'image_main', $titleAmigable.'-main-'.time());

							//Checking if the image was uploaded successfully
							if(isset($image_route["type"]) && $image_route["type"] == "success"){
								$dataImage['image_main'] = $image_route["data"];
							}
						}

						//Updating images
						$this->app['bd']->update('projects', $dataImage, " id = '".$id."'");

						//Creating the project translation for default language because is necessary
						$lang = $this->app['lang']->getDefaultLanguage();

						$addTrans = [];
						$addTrans['id_project'] 	= $id;
						$addTrans['id_lang'] 		= $lang->id;
						$addTrans['title'] 			= "";

						//Creating translation for default project language default
						$this->app['bd']->insert('project_lang', $addTrans);
						
						$response['type'] 	= 'success';
						$response['action'] = $action;
						$response['id'] 	= $id;

						break;
					case 'update':

						//If user selects a new image, we need to delete the old one
						$titleAmigable = $this->app['tools']->urlAmigable($title);

						if(isset($_FILES['image_thumb']["name"]) && $_FILES['image_thumb']["name"] != ""){

							//Uploading image
							$image_route = $this->app['tools']->uploadImage($base_route.$id."/", 'image_thumb', $titleAmigable.'-thumb-'.time());

							//Checking if the image was uploaded successfully
							if(isset($image_route["type"]) && $image_route["type"] == "success"){
								$data['image_thumb'] = $image_route["data"];
							}
						}
						if(isset($_FILES['image_main']["name"]) && $_FILES['image_main']["name"] != ""){

							//Uploading image
							$image_route = $this->app['tools']->uploadImage($base_route.$id."/", 'image_main', $titleAmigable.'-main-'.time());

							//Checking if the image was uploaded successfully
							if(isset($image_route["type"]) && $image_route["type"] == "success"){
								$data['image_main'] = $image_route["data"];
							}
						}

						//If upload the image_thumb then delete the actual
						$oldProjectData = $_projects->getProjectBaseById($id);
						if(isset($data['image_thumb'])){
							if(isset($oldProjectData->image_thumb) && $oldProjectData->image_thumb != ""){
								if(file_exists(_PATH_.$oldProjectData->image_thumb))
									unlink(_PATH_.$oldProjectData->image_thumb);
							}
						}
						if(isset($data['image_main'])){

							if(isset($oldProjectData->image_main) && $oldProjectData->image_main != ""){
								if(file_exists(_PATH_.$oldProjectData->image_main))
									unlink(_PATH_.$oldProjectData->image_main);
							}
						}
						
						$data['title '] 		= $title;
						$data['developed_in'] 	= $developed_in;
						$data['id_category'] 	= $id_category;
						$data['url'] 			= $url;
						$data['position'] 		= $position;
						$data['technologies'] 	= $technologies;
						$data['visible']		= $visible;
						$data['updated_date'] 	= $this->app['tools']->datetime();

						//Updating the project base
						$this->app['bd']->update('projects', $data, "id  = '".$id."'");

						$response['type'] 	= 'success';
						$response['action'] = $action;
						$response['id'] 	= $id;

						break;
				}
			}

			die(json_encode($response));
		});

		//Function to get project gallery
		$this->add('ajax-get-project-gallery', function(){

			//Default variables
			$_projects 			= new Projects($this->app);
			$id 			= $this->app['tools']->getValue('id');

			$data = array(
				'gallery' 		=> $_projects->getProjectGalleryById($id),
				'tools' 		=> $this->app['tools'],
			);

			$html = $this->app['render']->getAjaxPage('projects/ajax-get-project-gallery', $data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'html' => 'Hubo un error cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Function to manage new project gallery
		$this->add('ajax-manage-new-project-gallery',function(){

			//Default variables
			$msg_error = "";
			$data = [];
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			//Getting variables
			$id 				= $this->app['tools']->getValue('id');
			$base_route 		= "images/portfolio/".$id."/gallery/";

			//Checking if image is mandatory
			if(isset($_FILES['image_gallery']["name"]) && $_FILES['image_gallery']["name"] == "")
					$response['error'] = "You must select an image.";

			//Checking data
			if($response['error'] == ''){
				
				//Checking image
				if(isset($_FILES['image_gallery']["name"]) && $_FILES['image_gallery']["name"] != ""){

					//Uploading image
					$image_route = $this->app['tools']->uploadImage($base_route, 'image_gallery', 'gallery-'.time());

					//Checking if the image was uploaded successfully
					if(isset($image_route["type"]) && $image_route["type"] == "success"){
						$data['image'] = $image_route["data"];
					}
					else
						$msg_error = "There was an error uploading the image. Please try again and/or try another image.";
				}
			}

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){
				
				$data['id_project']			= $id;
				$data['creation_date'] 		= $this->app['tools']->datetime();
				$data['updated_date'] 		= $this->app['tools']->datetime();

				//Creating the image gallery
				$this->app['bd']->insert('project_gallery', $data);
				
				$response['type'] 	= 'success';
				$response['id'] 	= $id;
			}

			die(json_encode($response));
		});

		//Function to delete a category
		$this->add('ajax-delete-project-gallery-image',function(){

			//Default variables
			$id 		= $this->app['tools']->getValue('id');
			$id_project = $this->app['tools']->getValue('id_project');

			//Deleting the translation
			$_projects = new Projects($this->app);
			$_projects->deleteGalleryImageById($id, $id_project);

			echo "ok";
		});

		//Funtion to get modal to manage project translations
		$this->add('ajax-get-project-translations',function(){

			//Default variables
			$_projects = new Projects($this->app);
			$action = 'create';

			//Getting data
			$id_project 		= $this->app['tools']->getValue('id_project');
			$project 	= $_projects->getProjectWithTranslations($id_project);

			$data = [
				'id_project'=> $id_project,
				'action' 	=> $action,
				'project' 	=> $project,
				'languages' => $this->app['lang']->getLanguagesTranslatedVisible(),
			];

			$html = $this->app['render']->getAjaxPage('projects/ajax-get-project-translations',$data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'error' => 'Hubo algun problema cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Function to manage the translation of each project
		$this->add('ajax-manage-project-translations',function(){

			//Default variables
			$msg_error = "";
			$data = [];
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			//Getting variables
			$id_project			= $this->app['tools']->getValue('id_project');
			$action 			= $this->app['tools']->getValue('action');
			$id_lang 			= $this->app['tools']->getValue('id_lang');
			$title 				= $this->app['tools']->getValue('title');
			$subtitle 			= $this->app['tools']->getValue('subtitle');
			$description_short 	= $this->app['tools']->getValue('description_short');
			$title_long 		= $this->app['tools']->getValue('title_long');
			$description_long 	= addslashes($_REQUEST['description_long']);

			//Checking data
			if($title == "")
				$response['error'] = "You must indicate the title of the project.";
			elseif($subtitle == "")
				$response['error'] = "You must indicate the subtitle of the project.";
			elseif($description_short == "")
				$response['error'] = "You must indicate the description short of the project.";
			

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){
				switch ($action) {
					case 'create':

						$data['id_project'] 		= $id_project;
						$data['id_lang'] 			= $id_lang;
						$data['title'] 				= $title;
						$data['subtitle'] 			= $subtitle;
						$data['description_short'] 	= $description_short;
						$data['title_long'] 		= $title_long;
						$data['description_long'] 	= $description_long;
						$data['creation_date']		= $this->app['tools']->datetime();
						$data['updated_date']		= $this->app['tools']->datetime();

						//Creating the translation
						$this->app['bd']->insert('project_lang', $data);
						
						$response['type'] 			= 'success';
						$response['id_project'] 	= $id_project;

						break;
					case 'update':
						
						$data['title'] 				= $title;
						$data['subtitle'] 			= $subtitle;
						$data['description_short'] 	= $description_short;
						$data['title_long'] 		= $title_long;
						$data['description_long'] 	= $description_long;
						$data['updated_date']		= $this->app['tools']->datetime();

						//Updating the translation
						$this->app['bd']->update('project_lang', $data, "id_project  = '".$id_project."' AND id_lang = '".$id_lang."'");

						$response['type'] 			= 'success';
						$response['id_project'] 	= $id_project;

						break;
				}
			}

			die(json_encode($response));
		});

		//Function to delete project by id
		$this->add('ajax-delete-project-by-id',function(){

			//Default variables
			$id_project = $this->app['tools']->getValue('id_project');

			//Deleting the translation
			$_projects = new Projects($this->app);
			$_projects->deleteProjectById($id_project);

			echo "ok";
		});

		/****************************************************
		 *													*
		 *		   MANAGE PROJECTS CATEGORIES VIA AJAX 		*
		 *													*
		 ****************************************************/

		//Function to get all projects categories
		$this->add('ajax-get-projects-categories-filtered', function(){

			//Default variables
			$_projects 			= new Projects($this->app);
			$languages 			= $this->app['lang']->getLanguages();

			//Recogemos variables
			$comienzo 			= $this->app['tools']->getValue('comienzo');
			$limite 			= $this->app['tools']->getValue('limite');
			$pagina 			= $this->app['tools']->getValue('pagina');

			//Getting about categories filtered
			$categories = $_projects->getCategoriesFiltered($comienzo, $limite);

			$data = array(
				'categories' 	=> $categories,
				'languages'		=> $languages,
				'comienzo'  	=> $comienzo,
				'limite' 		=> $limite,
				'pagina' 		=> $pagina,
				'tools' 		=> $this->app['tools'],
			);

			$html = $this->app['render']->getAjaxPage('projects/ajax-get-projects-categories-filtered', $data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'html' => 'Hubo un error cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Funtion to get modal to manage projects category
		$this->add('ajax-get-modal-manage-projects-category',function(){

			//Default variables
			$category	= new \stdClass();
			$_projects 	= new Projects($this->app);
			$action 	= 'create';

			//Getting data
			$id 		= $this->app['tools']->getValue('id');
			$comienzo 	= $this->app['tools']->getValue('comienzo');
			$limite 	= $this->app['tools']->getValue('limite');
			$pagina 	= $this->app['tools']->getValue('pagina');

			if($id != '0'){
				$action = 'update';
				$category 		= $_projects->getCategoryByIdWithTranslations($id);
			}

			$data = [
				'id' 		=> $id,
				'action' 	=> $action,
				'category' 	=> $category,
				'comienzo' 	=> $comienzo,
				'limite' 	=> $limite,
				'pagina' 	=> $pagina,
				'languages' => $this->app['lang']->getLanguagesTranslatedVisible(),
			];

			$html = $this->app['render']->getAjaxPage('projects/ajax-get-modal-manage-projects-category',$data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'error' => 'Hubo algun problema cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Function to manage projects category base
		$this->add('ajax-manage-projects-category-base',function(){

			//Default variables
			$msg_error = "";
			$data = [];
			$response = [];

			//Getting data
			$id 		= $this->app['tools']->getValue('id');
			$action 	= $this->app['tools']->getValue('action');
			$visible 	= (isset($_REQUEST['visible'])) ? '1' : '0';

			switch ($action) {
				case 'create':
					
					$data['visible']			= '0';
					$data['creation_date'] 		= $this->app['tools']->datetime();
					$data['updated_date'] 		= $this->app['tools']->datetime();

					//Creating the category base
					$this->app['bd']->insert('project_categories', $data);

					$id = $this->app['bd']->lastId();

					//Creating the about block translation for default language because is necessary
					$lang = $this->app['lang']->getDefaultLanguage();

					$addTrans = [];
					$addTrans['id_category']	= $id;
					$addTrans['id_lang'] 		= $lang->id;
					$addTrans['name'] 			= "";
					$addTrans['slug'] 		= "";

					//Creating translation for default language
					$this->app['bd']->insert('project_categories_lang', $addTrans);
					
					$response['type'] 	= 'success';
					$response['id'] 	= $id;

					break;
				
				case 'update':

					$data['visible']			= $visible;
					$data['updated_date'] 		= $this->app['tools']->datetime();

					//Updating the category base
					$this->app['bd']->update('project_categories', $data, ' id= "'.$id.'"');

					$response['type'] 	= 'success';
					$response['id'] 	= $id;
					break;
			}

			die(json_encode($response));
		});

		//Function to manage the translation of each category
		$this->add('ajax-manage-projects-category',function(){

			//Default variables
			$msg_error = "";
			$data = [];
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			//Getting variables
			$id			= $this->app['tools']->getValue('id');
			$action 	= $this->app['tools']->getValue('action');
			$id_lang 	= $this->app['tools']->getValue('id_lang');
			$name 		= $this->app['tools']->getValue('name');
			$slug		= $this->app['tools']->getValue('slug');

			//Checking data
			if($name == "")
				$response['error'] = "You must indicate the name of the language.";
			if($slug == "")
				$response['error'] = "You must indicate the slug of the language.";

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){
				switch ($action) {
					case 'create':

						$data['id_category'] 	= $id;
						$data['id_lang'] 		= $id_lang;
						$data['name'] 			= $name;
						$data['slug'] 			= $slug;

						//Creating translation for category
						$this->app['bd']->insert('project_categories_lang', $data);
						
						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
					case 'update':
						
						$data['name'] 		= $name;
						$data['slug'] 		= $slug;

						//Updating translation for category
						$this->app['bd']->update('project_categories_lang', $data, "id_Category  = '".$id."' AND id_lang = '".$id_lang."'");

						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
				}
			}

			die(json_encode($response));
		});

		//Function to delete a category
		$this->add('ajax-delete-projects-category',function(){

			//Default variables
			$id = $this->app['tools']->getValue('id');

			//Deleting the translation
			$_projects = new Projects($this->app);
			$_projects->deleteCategory($id);

			echo "ok";
		});

		/****************************************************
		 *													*
		 *		   		MANAGE CAREER VIA AJAX 				*
		 *													*
		 ****************************************************/

		//Function to get all career type filtered
		$this->add('ajax-get-career-filtered', function(){

			//Default variables
			$_careers 			= new Careers($this->app);
			$languages 			= $this->app['lang']->getLanguages();

			//Recogemos variables
			$type 		= $this->app['tools']->getValue('type');
			$comienzo 	= $this->app['tools']->getValue('comienzo');
			$limite 	= $this->app['tools']->getValue('limite');
			$pagina 	= $this->app['tools']->getValue('pagina');

			//Getting about careers filtered
			$careers = $_careers->getCareersFiltered($comienzo, $limite, true, $type);

			$data = array(
				'type'			=> $type,
				'careers' 		=> $careers,
				'languages'		=> $languages,
				'comienzo'  	=> $comienzo,
				'limite' 		=> $limite,
				'pagina' 		=> $pagina,
				'tools' 		=> $this->app['tools'],
			);

			$html = $this->app['render']->getAjaxPage('careers/ajax-get-careers-filtered', $data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'html' => 'Hubo un error cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Funtion to get modal to manage career
		$this->add('ajax-get-modal-manage-career',function(){

			//Default variables
			$career		= new \stdClass();
			$_careers 	= new Careers($this->app);
			$action 	= 'create';

			//Getting data
			$id 		= $this->app['tools']->getValue('id');
			$type 		= $this->app['tools']->getValue('type');
			$comienzo 	= $this->app['tools']->getValue('comienzo');
			$limite 	= $this->app['tools']->getValue('limite');
			$pagina 	= $this->app['tools']->getValue('pagina');

			if($id != '0'){
				$action = 'update';
				$career 		= $_careers->getCarrerByIdWithTranslations($id);
			}

			$data = [
				'id' 		=> $id,
				'action' 	=> $action,
				'career' 	=> $career,
				'type'		=> $type,
				'comienzo' 	=> $comienzo,
				'limite' 	=> $limite,
				'pagina' 	=> $pagina,
				'languages' => $this->app['lang']->getLanguagesTranslatedVisible(),
			];

			$html = $this->app['render']->getAjaxPage('careers/ajax-get-modal-manage-career',$data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'error' => 'Hubo algun problema cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Function to manage career base information
		$this->add('ajax-manage-career-base',function(){

			//Getting variables
			$id 				= $this->app['tools']->getValue('id');
			$type 				= $this->app['tools']->getValue('type');
			$action 			= $this->app['tools']->getValue('action');
			$enterprise 		= $this->app['tools']->getValue('enterprise');
			$date_from_month 	= $this->app['tools']->getValue('date_from_month');
			$date_from_year 	= $this->app['tools']->getValue('date_from_year');
			$date_to_month 		= $this->app['tools']->getValue('date_to_month');
			$date_to_year 		= $this->app['tools']->getValue('date_to_year');
			$currently 			= (isset($_REQUEST['currently'])) ? '1' : '0';

			//Default variables
			$msg_error = "";
			$data = [];
			$base_route 		= "images/career/".$type."/";
			$_careers = new Careers($this->app);
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			//Checking if logo is mandatory
			$isMandatoryImage = ($action == "create") ? true : false;
			if($isMandatoryImage)
				if(isset($_FILES['image']["name"]) && $_FILES['image']["name"] == "")
					$response['error'] = "You must select a logo for the ".$type.".";

			//Checking data
			if($response['error'] == ''){
				if($enterprise == ""){
					$response['error'] = "You must write the enterprise for the ".$type.".";
				} elseif(isset($_FILES['image']["name"]) && $_FILES['image']["name"] != ""){
					
					//Enterprise name in slug
					$enterpriseName = $this->app['tools']->urlAmigable($enterprise);

					//Uploading image
					$image_route = $this->app['tools']->uploadImage($base_route, 'image', $enterpriseName.'-'.time());

					//Checking if the image was uploaded successfully
					if(isset($image_route["type"]) && $image_route["type"] == "success"){
						$data['logo'] = $image_route["data"];
					}
					else
						$msg_error = "There was an error uploading the logo. Please try again and/or try another image.";
				}
			}

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){
				//Creating or updating
				switch ($action) {
					case 'create':
						
						$data['type'] 				= $type;
						$data['enterprise'] 		= $enterprise;
						$data['date_from_month'] 	= $date_from_month;
						$data['date_from_year'] 	= $date_from_year;
						$data['date_to_month'] 		= $date_to_month;
						$data['date_to_year'] 		= $date_to_year;
						$data['currently'] 			= $currently;
						$data['creation_date'] 		= $this->app['tools']->datetime();
						$data['updated_date'] 		= $this->app['tools']->datetime();

						//Creating the career
						$this->app['bd']->insert('career', $data);

						$id = $this->app['bd']->lastId();

						//Creating the career translation for default language because is necessary
						$lang = $this->app['lang']->getDefaultLanguage();

						$addTrans = [];
						$addTrans['id_career'] 		= $id;
						$addTrans['id_lang'] 		= $lang->id;
						$addTrans['title'] 			= "";
						$addTrans['description']	= "";
						$addTrans['creation_date'] 	= $this->app['tools']->datetime();
						$addTrans['updated_date'] 	= $this->app['tools']->datetime();

						//Creating translation for default language
						$this->app['bd']->insert('career_lang', $addTrans);
						
						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
					case 'update':

						//If user selects a new logo, we need to delete the old one
						if(isset($data['logo'])){

							//Actual data to delete the logo
							$oldLogoData = $_careers->getCareerBaseById($id);

							if(isset($oldLogoData->logo) && $oldLogoData->logo != ""){
								if(file_exists(_PATH_.$oldLogoData->logo))
									unlink(_PATH_.$oldLogoData->logo);
							}
						}
						
						$data['enterprise'] 		= $enterprise;
						$data['date_from_month'] 	= $date_from_month;
						$data['date_from_year'] 	= $date_from_year;
						$data['date_to_month'] 		= $date_to_month;
						$data['date_to_year'] 		= $date_to_year;
						$data['currently'] 			= $currently;
						$data['updated_date'] 		= $this->app['tools']->datetime();

						//Updating the language
						$this->app['bd']->update('career', $data, "id  = '".$id."'");

						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
				}
			}

			die(json_encode($response));
		});

		//Function to manage the translation of each career
		$this->add('ajax-manage-career',function(){

			//Default variables
			$msg_error = "";
			$data = [];
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			//Getting variables
			$id				= $this->app['tools']->getValue('id');
			$type			= $this->app['tools']->getValue('type');
			$action 		= $this->app['tools']->getValue('action');
			$id_lang 		= $this->app['tools']->getValue('id_lang');
			$title 			= $this->app['tools']->getValue('title');
			$description	= $this->app['tools']->getValue('description');

			//Checking data
			if($title == "")
				$response['error'] = "You must indicate the title of the ".$type.".";

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){
				switch ($action) {
					case 'create':

						$data['id_career'] 			= $id;
						$data['id_lang'] 			= $id_lang;
						$data['title'] 				= $title;
						$data['description'] 		= $description;
						$data['creation_date'] 		= $this->app['tools']->datetime();
						$data['updated_date'] 		= $this->app['tools']->datetime();

						//Creating translation for category
						$this->app['bd']->insert('career_lang', $data);
						
						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
					case 'update':
						
						$data['title'] 				= $title;
						$data['description'] 		= $description;
						$data['updated_date'] 		= $this->app['tools']->datetime();

						//Updating translation for category
						$this->app['bd']->update('career_lang', $data, "id_career  = '".$id."' AND id_lang = '".$id_lang."'");

						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
				}
			}

			die(json_encode($response));
		});

		//Function to delete a career
		$this->add('ajax-delete-career',function(){

			//Default variables
			$id = $this->app['tools']->getValue('id');

			//Deleting the career
			$_careers = new Careers($this->app);
			$_careers->deleteCareerById($id);

			echo "ok";
		});

		/****************************************************
		 *													*
		 *		   		MANAGE PAGES VIA AJAX 				*
		 *													*
		 ****************************************************/

		//Function to get all pages filtered
		$this->add('ajax-get-pages-filtered', function(){

			//Default variables
			$_pages 			= new Pages($this->app);
			$languages 			= $this->app['lang']->getLanguages();

			//Recogemos variables
			$comienzo 	= $this->app['tools']->getValue('comienzo');
			$limite 	= $this->app['tools']->getValue('limite');
			$pagina 	= $this->app['tools']->getValue('pagina');

			//Getting about pages filtered
			$pages = $_pages->getPagesFiltered($comienzo, $limite, true);

			$data = array(
				'pages' 		=> $pages,
				'languages'		=> $languages,
				'comienzo'  	=> $comienzo,
				'limite' 		=> $limite,
				'pagina' 		=> $pagina,
				'tools' 		=> $this->app['tools'],
			);

			$html = $this->app['render']->getAjaxPage('pages/ajax-get-pages-filtered', $data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'html' => 'Hubo un error cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Funtion to get modal to manage page
		$this->add('ajax-get-modal-manage-page',function(){

			//Default variables
			$page		= new \stdClass();
			$_pages 	= new Pages($this->app);
			$action 	= 'create';

			//Getting data
			$id 		= $this->app['tools']->getValue('id');
			$comienzo 	= $this->app['tools']->getValue('comienzo');
			$limite 	= $this->app['tools']->getValue('limite');
			$pagina 	= $this->app['tools']->getValue('pagina');

			if($id != '0'){
				$action = 'update';
				$page 		= $_pages->getPageByIdWithTranslations($id);
			}

			$data = [
				'id' 		=> $id,
				'action' 	=> $action,
				'page' 		=> $page,
				'comienzo' 	=> $comienzo,
				'limite' 	=> $limite,
				'pagina' 	=> $pagina,
				'languages' => $this->app['lang']->getLanguagesTranslatedVisible(),
			];

			$html = $this->app['render']->getAjaxPage('pages/ajax-get-modal-manage-page',$data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'error' => 'Hubo algun problema cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Function to manage page base information
		$this->add('ajax-manage-page-base',function(){

			//Getting variables
			$id 				= $this->app['tools']->getValue('id');
			$action 			= $this->app['tools']->getValue('action');
			$title 				= $this->app['tools']->getValue('title');
			$mod_id 			= $this->app['tools']->getValue('mod_id');
			$zone 				= $this->app['tools']->getValue('zone');
			$visible 			= (isset($_REQUEST['visible'])) ? '1' : '0';

			//Default variables
			$msg_error = "";
			$data = [];
			$_pages = new Pages($this->app);
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			if($title == "")
				$response['error'] = "You must write the title for the page.";
			elseif($mod_id == "")
				$response['error'] = "You must indicate the mod_id if not by default indicate 'pages'.";

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){
				//Creating or updating
				switch ($action) {
					case 'create':
						
						$data['title'] 				= $title;
						$data['mod_id'] 			= $mod_id;
						$data['zone'] 				= $zone;
						$data['visible'] 			= $visible;
						$data['creation_date'] 		= $this->app['tools']->datetime();
						$data['updated_date'] 		= $this->app['tools']->datetime();

						//Creating the pages
						$this->app['bd']->insert('pages', $data);

						$id = $this->app['bd']->lastId();

						//Creating the page translation for default language because is necessary
						$lang = $this->app['lang']->getDefaultLanguage();

						$addTrans = [];
						$addTrans['id_page'] 			= $id;
						$addTrans['id_lang'] 			= $lang->id;
						$addTrans['h1'] 				= "";
						$addTrans['slug']			= "";
						$addTrans['content']			= "";
						$addTrans['seo_title']			= "";
						$addTrans['seo_description']	= "";
						$addTrans['creation_date'] 		= $this->app['tools']->datetime();
						$addTrans['updated_date'] 		= $this->app['tools']->datetime();

						//Creating translation for default language
						$this->app['bd']->insert('pages_lang', $addTrans);
						
						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
					case 'update':
						
						$data['title'] 				= $title;
						$data['mod_id'] 			= $mod_id;
						$data['zone'] 				= $zone;
						$data['visible'] 			= $visible;
						$data['updated_date'] 		= $this->app['tools']->datetime();

						//Updating the page
						$this->app['bd']->update('pages', $data, "id  = '".$id."'");

						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
				}
			}

			die(json_encode($response));
		});

		//Function to manage the translation of each page
		$this->add('ajax-manage-page',function(){

			//Default variables
			$msg_error = "";
			$data = [];
			$_pages = new Pages($this->app);
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			//Getting variables
			$id					= $this->app['tools']->getValue('id');
			$action 			= $this->app['tools']->getValue('action');
			$id_lang 			= $this->app['tools']->getValue('id_lang');
			$h1 				= $this->app['tools']->getValue('h1');
			$content			= addslashes($_REQUEST['content']);
			$slug				= $this->app['tools']->getValue('slug');
			$seo_title			= $this->app['tools']->getValue('seo_title');
			$seo_description	= $this->app['tools']->getValue('seo_description');

			//Checking data
			if($slug == "")
				$response['error'] = "You must indicate the slug of the page.";
			elseif($_pages->isAvailableSlug($id, $slug, $id_lang) > 0)
				$response['error'] = "The slug '<strong>".$slug."</strong>' is not available.";

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){
				switch ($action) {
					case 'create':

						$data['id_page'] 			= $id;
						$data['id_lang'] 			= $id_lang;
						$data['h1'] 				= $h1;
						$data['content'] 			= $content;
						$data['slug'] 				= $slug;
						$data['seo_title'] 			= $seo_title;
						$data['seo_description'] 	= $seo_description;
						$data['creation_date'] 		= $this->app['tools']->datetime();
						$data['updated_date'] 		= $this->app['tools']->datetime();

						//Creating translation for page
						$this->app['bd']->insert('pages_lang', $data);
						
						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
					case 'update':
						
						$data['h1'] 				= $h1;
						$data['content'] 			= $content;
						$data['slug'] 				= $slug;
						$data['seo_title'] 			= $seo_title;
						$data['seo_description'] 	= $seo_description;
						$data['updated_date'] 		= $this->app['tools']->datetime();

						//Updating translation for page
						$this->app['bd']->update('pages_lang', $data, "id_page  = '".$id."' AND id_lang = '".$id_lang."'");

						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
				}
			}

			die(json_encode($response));
		});

		//Function to delete a page
		$this->add('ajax-delete-page',function(){

			//Default variables
			$id = $this->app['tools']->getValue('id');

			//Deleting the page
			$_pages = new Pages($this->app);
			$_pages->deletePageById($id);

			echo "ok";
		});

		/****************************************************
		 *													*
		 *		   	MANAGE DOCUMENTS VIA AJAX 				*
		 *													*
		 ****************************************************/

		//Function to get all documents filtered
		$this->add('ajax-get-documents-filtered', function(){

			//Default variables
			$_documents 		= new Documents($this->app);
			$languages 			= $this->app['lang']->getLanguages();

			//Recogemos variables
			$comienzo 	= $this->app['tools']->getValue('comienzo');
			$limite 	= $this->app['tools']->getValue('limite');
			$pagina 	= $this->app['tools']->getValue('pagina');

			//Getting documents filtered
			$documents = $_documents->getDocumentsFiltered($comienzo, $limite, true);

			$data = array(
				'documents' 	=> $documents,
				'languages'		=> $languages,
				'comienzo'  	=> $comienzo,
				'limite' 		=> $limite,
				'pagina' 		=> $pagina,
				'tools' 		=> $this->app['tools'],
			);

			$html = $this->app['render']->getAjaxPage('documents/ajax-get-documents-filtered', $data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'html' => 'Hubo un error cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Funtion to get modal to manage document
		$this->add('ajax-get-modal-manage-document',function(){

			//Default variables
			$document		= new \stdClass();
			$_documents 	= new Documents($this->app);
			$action 		= 'create';

			//Getting data
			$id 		= $this->app['tools']->getValue('id');
			$comienzo 	= $this->app['tools']->getValue('comienzo');
			$limite 	= $this->app['tools']->getValue('limite');
			$pagina 	= $this->app['tools']->getValue('pagina');

			if($id != '0'){
				$action = 'update';
				$document 		= $_documents->getDocumentByIdWithTranslations($id);
			}

			$data = [
				'id' 		=> $id,
				'action' 	=> $action,
				'document' 	=> $document,
				'comienzo' 	=> $comienzo,
				'limite' 	=> $limite,
				'pagina' 	=> $pagina,
				'languages' => $this->app['lang']->getLanguagesTranslatedVisible(),
			];

			$html = $this->app['render']->getAjaxPage('documents/ajax-get-modal-manage-document',$data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'error' => 'Hubo algun problema cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Function to manage document base information
		$this->add('ajax-manage-document-base',function(){

			//Getting variables
			$id 				= $this->app['tools']->getValue('id');
			$action 			= $this->app['tools']->getValue('action');
			$title 				= $this->app['tools']->getValue('title');
			$shortcode 			= $this->app['tools']->getValue('shortcode');
			$visible 			= (isset($_REQUEST['visible'])) ? '1' : '0';

			//Default variables
			$msg_error = "";
			$data = [];
			$_documents = new Documents($this->app);
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			if($title == "")
				$response['error'] = "You must write the title for the document.";
			elseif($shortcode == "" && $_documents->isUniqueShortcode($id, $shortcode))
				$response['error'] = "You must indicate the shortcode and must be unique.";

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){
				//Creating or updating
				switch ($action) {
					case 'create':
						
						$data['title'] 				= $title;
						$data['shortcode'] 			= $shortcode;
						$data['visible'] 			= $visible;
						$data['creation_date'] 		= $this->app['tools']->datetime();
						$data['updated_date'] 		= $this->app['tools']->datetime();

						//Creating the pages
						$this->app['bd']->insert('documents', $data);

						$id = $this->app['bd']->lastId();

						//Creating the page translation for default language because is necessary
						$lang = $this->app['lang']->getDefaultLanguage();

						$addTrans = [];
						$addTrans['id_document'] 		= $id;
						$addTrans['id_lang'] 			= $lang->id;
						$addTrans['name'] 				= "";
						$addTrans['url']				= "";
						$addTrans['creation_date'] 		= $this->app['tools']->datetime();
						$addTrans['updated_date'] 		= $this->app['tools']->datetime();

						//Creating translation for default language
						$this->app['bd']->insert('documents_lang', $addTrans);
						
						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
					case 'update':
						
						$data['title'] 				= $title;
						$data['shortcode'] 			= $shortcode;
						$data['visible'] 			= $visible;
						$data['updated_date'] 		= $this->app['tools']->datetime();

						//Updating the page
						$this->app['bd']->update('documents', $data, "id  = '".$id."'");

						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
				}
			}

			die(json_encode($response));
		});

		//Function to manage the document
		$this->add('ajax-manage-document',function(){

			//Getting variables
			$id					= $this->app['tools']->getValue('id');
			$action 			= $this->app['tools']->getValue('action');
			$id_lang 			= $this->app['tools']->getValue('id_lang');
			$name 				= $this->app['tools']->getValue('name');

			//Default variables
			$msg_error = "";
			$data = [];
			$base_route = "documents/".$id."/";
			$_documents = new Documents($this->app);
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			$isMandatoryImage = ($action == "create") ? true : false;
			if($isMandatoryImage)
				if(isset($_FILES['document']["name"]) && $_FILES['document']["name"] == "")
					$response['error'] = "You must upload a document.";

			//Checking data
			if($name == "")
				$response['error'] = "You must indicate the name of the document.";
			else{
				//Checking image
				if(isset($_FILES['document']["name"]) && $_FILES['document']["name"] != ""){

					//Uploading image
					$nameAmigable = $this->app['tools']->urlAmigable($name);
					$document_route = $this->app['tools']->uploadDocument($base_route, 'document', $nameAmigable . "-" . time());

					//Checking if the image was uploaded successfully
					if(isset($document_route["type"]) && $document_route["type"] == "success"){
						$data['url'] = $document_route["data"];
					}
					else
						$msg_error = "There was an error uploading the document. Please try again or try another document.";
				}
			}

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){
				switch ($action) {
					case 'create':

						$data['id_document']	= $id;
						$data['id_lang']		= $id_lang;
						$data['name'] 			= $name;
						$data['creation_date'] 	= $this->app['tools']->datetime();
						$data['updated_date'] 	= $this->app['tools']->datetime();

						//Creating the translation
						$this->app['bd']->insert('documents_lang', $data);
						
						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
					case 'update':

						//If user selects a new image, we need to delete the old one
						if(isset($data['url'])){

							//Actual data to delete the last document saved
							$oldDocumentData = $_documents->getDocumentById($id, $id_lang);

							if(isset($oldDocumentData->url) && $oldDocumentData->url != ""){
								if(file_exists(_PATH_.$oldDocumentData->url))
									unlink(_PATH_.$oldDocumentData->url);
							}
						}

						$data['name'] 			= $name;
						$data['updated_date'] 	= $this->app['tools']->datetime();

						//Updating the translation
						$this->app['bd']->update('documents_lang', $data, "id_document  = '".$id."' AND id_lang = '".$id_lang."'");

						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
				}
			}

			die(json_encode($response));
		});

		//Function to delete a document
		$this->add('ajax-delete-document',function(){

			//Default variables
			$id = $this->app['tools']->getValue('id');

			//Deleting the page
			$_documents = new Documents($this->app);
			$_documents->deleteDocumentById($id);

			echo "ok";
		});
		
		/****************************************************
		 *													*
		 *		   MANAGE TRANSLATIONS VIA AJAX 			*
		 *													*
		 ****************************************************/

		//Function to get all translations
		$this->add('ajax-get-traductions-filtered', function(){

			//Obtenemos los diferentes idiomas
			$languages 			= $this->app['lang']->getLanguages();

			//Recogemos variables
			$slug_idioma 		= $this->app['tools']->getValue('slug_idioma');
			$translation_status = $this->app['tools']->getValue('translation_status');
			$comienzo 			= $this->app['tools']->getValue('comienzo');
			$limite 			= $this->app['tools']->getValue('limite');
			$pagina 			= $this->app['tools']->getValue('pagina');

			//Obtenemos todas las traducciones segmentadas por shortcode y predominando el español
			$traductions = $this->app['lang']->getAllTraductionsGroupedFilteredV2($comienzo, $limite);

			$data = array(
				'traductions' 	=> $traductions,
				'languages' 	=> $languages,
				'comienzo'  	=> $comienzo,
				'limite' 		=> $limite,
				'pagina' 		=> $pagina,
			);

			$html = $this->app['render']->getAjaxPage('translations/ajax-get-translations-filtered',$data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'html' => 'Hubo un error cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Function to create a new translation
		$this->add('ajax-manage-new-translation',function(){

			//Default variables
			$msg_error = "";
			$data = [];
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
			);

			//Get variables
			$id 				= $this->app['tools']->getValue('id');
			$id_language 		= $this->app['tools']->getValue('id_language');
			$traduction_for 	= $this->app['tools']->getValue('traduction_for');
			$shortcode 			= $this->app['tools']->getValue('shortcode');
			$content 			= $this->app['tools']->getValue('content');
			$action 			= 'create';

			//Checking data
			if($shortcode != ''){
				if($content != ''){

					//Checking if the shortcode already exists
					if($this->app['bd']->countRows("SELECT id FROM languages_translations WHERE id_language = '".$id_language."' AND traduction_for = '".$traduction_for."' AND shortcode = '".$shortcode."'") != '0')
						$response['error'] = "The shortcode <strong>".$shortcode."</strong> already exists for the selected language and translation zone <strong>".$traduction_for."</strong>.";
				}
				else
					$response['error'] = "You must indicate the translation";
			}
			else
				$response['error'] = "You must indicate the shortcode of the translation.";

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){

				//Creating
				switch ($action) {
					case 'create':
						$data['id_language'] 		= $id_language;
						$data['traduction_for'] 	= $traduction_for;
						$data['shortcode'] 			= $shortcode;
						$data['content'] 			= $content;
						$data['creation_date'] 		= $this->app['tools']->datetime();
						$data['updated_date'] 		= $this->app['tools']->datetime();

						//Insert the new translation
						$this->app['bd']->insert('languages_translations', $data);
						
						$response['type'] 	= 'success';

						break;
					case 'update':
						break;
				}
			}

			die(json_encode($response));
		});

		//Function to get the modal to manage a translation
		$this->add('ajax-get-modal-manage-translation',function(){

			//Default variables
			$datos 	= new \stdClass();

			//Getting data
			$id 		= $this->app['tools']->getValue('id');
			$comienzo 	= $this->app['tools']->getValue('comienzo');
			$limite 	= $this->app['tools']->getValue('limite');
			$pagina 	= $this->app['tools']->getValue('pagina');

			//Checking if the id is not empty
			if($id != '0'){

				//Variables default
				$action = 'update';

				//Get translation by id
				$translation = $this->app['lang']->getTranslationById($id);

				//Getting the translations
				$translations = $this->app['lang']->getTranslationByIdWithTranslations($id);
			}
			else
				$action = 'create';

			$data = [
				'id' 			=> $id,
				'action' 		=> $action,
				'translation' 	=> $translation,
				'translations' 	=> $translations,
				'comienzo' 		=> $comienzo,
				'limite' 		=> $limite,
				'pagina' 		=> $pagina,
				'languages' 	=> $this->app['lang']->getLanguagesTranslatedVisible(),
			];

			$html = $this->app['render']->getAjaxPage('translations/ajax-get-modal-manage-translation',$data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'error' => 'Hubo algun problema cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Function to manage the translation
		$this->add('ajax-manage-translation',function(){

			//Default variables
			$msg_error = "";
			$data = [];
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			//Getting variables
			$id 				= $this->app['tools']->getValue('id');
			$action 			= $this->app['tools']->getValue('action');
			$id_lang 			= $this->app['tools']->getValue('id_lang');
			$traduction_for 	= $this->app['tools']->getValue('traduction_for');
			$shortcode 			= $this->app['tools']->getValue('shortcode');
			$content 			= $this->app['tools']->getValue('content');

			//Checking data
			if($content != ""){}
			else
				$response['error'] = "You must indicate the text of the translation.";

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){

				//Creating or updating
				switch ($action) {
					case 'create':
						$data['id_language'] 		= $id_lang;
						$data['traduction_for'] 	= $traduction_for;
						$data['shortcode'] 			= $shortcode;
						$data['content'] 			= $content;
						$data['creation_date']		= $this->app['tools']->datetime();
						$data['updated_date']		= $this->app['tools']->datetime();

						//If the shortcode does not exist, we insert it
						if($this->app['bd']->countRows("SELECT id FROM languages_translations WHERE id_language = '".$id_lang."' AND traduction_for = '".$traduction_for."' AND shortcode = '".$shortcode."' ") == '0'){
							$this->app['bd']->insert('languages_translations', $data);

							$response['type'] 	= 'success';
							$response['id'] 	= $id;
						} else {
							$response['error'] 	= 'The shortcode <strong>'.$shortcode.'</strong> already exists for the selected language and translation zone <strong>'.$traduction_for.'</strong>.';
							$response['id'] 	= $id;
						}
					
						break;
					case 'update':
						$data['content'] 		= $content;
						$data['updated_date']	= $this->app['tools']->datetime();

						//Updating the translation
						$this->app['bd']->update('languages_translations', $data, "traduction_for  = '".$traduction_for."' AND shortcode = '".$shortcode."' AND id_language = '".$id_lang."'");

						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
				}
			}

			die(json_encode($response));
		});

		//Function to delete a translation
		$this->add('ajax-delete-translation',function(){

			//Default variables
			$id = $this->app['tools']->getValue('id');

			//Deleting the translation
			$this->app['lang']->deleteTranslation($id);

			echo "ok";
		});

		/****************************************************
		 *													*
		 *		   	MANAGE LANGUAGES VIA AJAX 				*
		 *													*
		 ****************************************************/

		//Function to get all languages filtered
		$this->add('ajax-get-languages-filtered',function(){

			//Variables default
			$comienzo 		= $this->app['tools']->getValue('comienzo');
			$limite 		= $this->app['tools']->getValue('limite');
			$pagina 		= $this->app['tools']->getValue('pagina');

			//Obtenemos datos filtrodos
			$languagesFiltered = $this->app['lang']->getLanguagesFiltered($comienzo, $limite);

			$data = [
				'languagesFiltered' => $languagesFiltered,
				'comienzo' => $this->app['tools']->getValue('comienzo'),
				'limite' => $this->app['tools']->getValue('limite'),
				'pagina' => $this->app['tools']->getValue('pagina'),
				'tools' => $this->app['tools'],
				'languages' => $this->app['lang']->getLanguagesVisibles(),
			];

			$html = $this->app['render']->getAjaxPage('translations/ajax-get-languages-filtered',$data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'error' => 'Hubo algun problema cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Funtion to get modal to manage a language
		$this->add('ajax-get-modal-manage-language',function(){

			//Default variables
			$language	= new \stdClass();
			$action 		= 'create';

			//Getting data
			$id 		= $this->app['tools']->getValue('id');
			$comienzo 	= $this->app['tools']->getValue('comienzo');
			$limite 	= $this->app['tools']->getValue('limite');
			$pagina 	= $this->app['tools']->getValue('pagina');

			if($id != '0'){
				$action = 'update';
				$language 		= $this->app['lang']->getLanguageByIdWithTranslations($id);
			}

			$data = [
				'id' 		=> $id,
				'action' 	=> $action,
				'language' 	=> $language,
				'comienzo' 	=> $comienzo,
				'limite' 	=> $limite,
				'pagina' 	=> $pagina,
				'languages' => $this->app['lang']->getLanguagesTranslatedVisible(),
			];

			$html = $this->app['render']->getAjaxPage('translations/ajax-get-modal-manage-language',$data);

			if( !empty($html) )
			{
				$response = array(
					'type' => 'success',
					'html' => $html
				);
			}
			else
			{
				$response = array(
					'type' => 'error',
					'error' => 'Hubo algun problema cargando el html'
				);
			}

			die(json_encode($response));
		});

		//Function to manage the translation of each language
		$this->add('ajax-manage-language',function(){

			//Default variables
			$msg_error = "";
			$data = [];
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			//Getting variables
			$id 				= $this->app['tools']->getValue('id');
			$action 			= $this->app['tools']->getValue('action');
			$id_lang 			= $this->app['tools']->getValue('id_lang');
			$name 				= $this->app['tools']->getValue('name');

			//Checking data
			if($name == "")
				$response['error'] = "Debes indicar el nombre del idioma.";

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){
				switch ($action) {
					case 'create':

						$data['id_language'] 	= $id;
						$data['id_lang'] 		= $id_lang;
						$data['name'] 			= $name;

						//Creating the translation
						$this->app['bd']->insert('languages_lang', $data);
						
						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
					case 'update':
						
						$data['name'] = $name;

						//Updating the translation
						$this->app['bd']->update('languages_lang', $data, "id_language  = '".$id."' AND id_lang = '".$id_lang."'");

						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
				}
			}

			die(json_encode($response));
		});

		//Function to manage language base information
		$this->add('ajax-manage-language-base',function(){

			//Default variables
			$msg_error = "";
			$data = [];
			$base_route 		= "images/flags/";
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			//Getting variables
			$id 				= $this->app['tools']->getValue('id');
			$action 			= $this->app['tools']->getValue('action');
			$slug 				= $this->app['tools']->getValue('slug');
			$status 			= (isset($_REQUEST['status'])) ? 'active' : 'inactive';
			$is_default 		= (isset($_REQUEST['is_default'])) ? '1' : '0';

			//Checking if image/icon is mandatory
			$isMandatoryImage = ($action == "create") ? true : false;
			if($isMandatoryImage)
				if(isset($_FILES['image']["name"]) && $_FILES['image']["name"] == "")
					$response['error'] = "You must select an icon for the language.";

			//Checking data
			if($response['error'] == ''){
				if($slug != ''){

					//Checking if the slug is available
					$prev_slug 		= $this->app['tools']->urlAmigable($slug);
					$slugAvailable 	= $this->app['lang']->checkIfSlugIsAvailable($prev_slug, $id);

					if($slugAvailable){

						//Checking image
						if(isset($_FILES['image']["name"]) && $_FILES['image']["name"] != ""){

							//Uploading image
							$image_route = $this->app['tools']->uploadImage($base_route, 'image', 'flag-'.time());

							//Checking if the image was uploaded successfully
							if(isset($image_route["type"]) && $image_route["type"] == "success"){
								$data['icon'] = $image_route["data"];
							}
							else
								$msg_error = "There was an error uploading the image. Please try again and/or try another image.";
						}
					}
					else
						$response['error'] = "The indicated slug is not available, it is already in use.";
				}
				else
					$response['error'] = "You must enter the language slug.";
			}

			//If there is no error message, we continue with the update or creation
			if($response['error'] == ""){
				//Creating or updating
				switch ($action) {
					case 'create':
						
						$data['slug '] 				= $slug;
						$data['status'] 			= $status;
						$data['creation_date'] 		= $this->app['tools']->datetime();
						$data['updated_date'] 		= $this->app['tools']->datetime();

						//Creating the language
						$this->app['bd']->insert('languages', $data);

						$id = $this->app['bd']->lastId();

						//Creating the language translation for default language because is necessary
						$lang = $this->app['lang']->getDefaultLanguage();

						$addTrans = [];
						$addTrans['id_language'] 	= $id;
						$addTrans['id_lang'] 		= $lang->id;
						$addTrans['name'] 			= "";

						//Creating translation for default language
						$this->app['bd']->insert('languages_lang', $addTrans);
						
						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
					case 'update':

						//If user selects a new image, we need to delete the old one
						if(isset($data['icon'])){

							//Actual data to delete the icon
							$oldIconData = $this->app['lang']->getLanguageBaseById($id);

							if(isset($oldIconData->icon) && $oldIconData->icon != ""){
								if(file_exists(_PATH_.$oldIconData->icon))
									unlink(_PATH_.$oldIconData->icon);
							}
						}
						
						$data['slug'] 				= $slug;
						$data['status'] 			= $status;
						$data['is_default'] 		= $is_default;
						$data['updated_date'] 		= $this->app['tools']->datetime();

						//Updating the language
						$this->app['bd']->update('languages', $data, "id  = '".$id."'");

						$response['type'] 	= 'success';
						$response['id'] 	= $id;

						break;
				}

				//If this language si default then we unselect all the others languages
				if($is_default == '1'){
					$this->app['bd']->query("UPDATE languages SET is_default = '0' WHERE id != '".$id."'");
				}
			}

			die(json_encode($response));
		});

		/****************************************************
		 *													*
		 *		   	MANAGE LOGIN VIA AJAX 					*
		 *													*
		 ****************************************************/

		//Function to manage the login
		$this->add('ajax-manage-admin-login',function(){

			//Default variables
			$_admin = new Admin($this->app);
			$response = array(
				'type' => 'error',
				'error' => '',
			);

			//Getting variables
			$user 			= $this->app['tools']->getValue('user');
			$pass			= $this->app['tools']->md5($this->app['tools']->getValue('password'));
			$recaptchaToken = $_REQUEST['g-recaptcha-response'];

			//Checking the reCAPTCHA Token
			$gResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret="._GOOGLE_RECAPTCHA_SECRET_KEY_."&response=".$recaptchaToken);
			$gResult = json_decode($gResponse);

			//Checking reCAPTCHA before do login
			if ($gResult->success && $gResult->score >= 0.5) {
				
				//Checking the login
				if($_admin->doLogin($user, $pass)){
					$response['type'] 	= 'success';
				}
				else{
					$response['error'] 	= "Incorrect username and/or password.";
				}
			}
			else {
				$response['error'] = $this->app['lang']->getTranslationByShortcode('contact-input-recaptcha-error', $translations);
			}

			die(json_encode($response));
		});

		/****************************************************
		 *													*
		 *		   			FRONT ACTIONS 					*
		 *													*
		 ****************************************************/

		//Send a new contact form
        $this->add('ajax-manage-contact-form',function($app) {
			
			//Default vars
			$response = array(
				'type' => 'error',
				'error' => '',
				'message' => '',
				'id' => '',
			);

			//Getting translations for home
			$translations = $this->app['lang']->getTranslationFor("home", $_SESSION['lang']);
			
			//Getting vars
            $name     		= $this->app['tools']->getValue('name');
            $lastname      	= $this->app['tools']->getValue('lastname');
            $email   		= $this->app['tools']->getValue('email');
            $phone    		= $this->app['tools']->getValue('phone');
			$message    	= $this->app['tools']->getValue('message');
			$recaptchaToken = $_REQUEST['g-recaptcha-response'];
			$policy			= isset($_REQUEST['policy']) ? true : false;

			if($name == "")
				$response['error'] = $this->app['lang']->getTranslationByShortcode('contact-input-name-error', $translations);
			elseif($email == "" || !$this->app['tools']->isEmail($email))
				$response['error'] = $this->app['lang']->getTranslationByShortcode('contact-input-email-error', $translations);
			elseif($message == "")
				$response['error'] = $this->app['lang']->getTranslationByShortcode('contact-input-message-error', $translations);
			elseif(!$policy)
				$response['error'] = $this->app['lang']->getTranslationByShortcode('contact-input-policy-error', $translations);
			else{
				//Checking the reCAPTCHA Token
				$gResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret="._GOOGLE_RECAPTCHA_SECRET_KEY_."&response=".$recaptchaToken);
				$gResult = json_decode($gResponse);

				if ($gResult->success && $gResult->score >= 0.5) {}
				else {
					$response['error'] = $this->app['lang']->getTranslationByShortcode('contact-input-recaptcha-error', $translations);
				}
			}

			if($response['error'] == ""){

				//Sending email
				$newMessage = "New contact form - Fllopis.com<br><br>";
				$newMessage .= "<strong>Language: </strong> " . $_SESSION['lang'] . "<br>";
				$newMessage .= "<strong>Name: </strong> " . $name . "<br>";
				$newMessage .= "<strong>Lastname: </strong> " . $lastname . "<br>";
				$newMessage .= "<strong>Email: </strong> " . $email . "<br>";
				$newMessage .= "<strong>Phone: </strong> " . $phone . "<br>";
				$newMessage .= "<strong>Message: </strong> " . $message . "<br>";
				
				$this->app['mail']->send(_RECEPTOR_,'Contact form from - Fllopis.com', $newMessage);

				$response['type'] 		= 'success';
				$response['message']	= $this->app['lang']->getTranslationByShortcode('contact-input-success', $translations);
			}

			die(json_encode($response));
            
        });
	}

	public function add($page,$data)
	{
		if ( $page == $this->page )
			return $data($this->app);
	}
}
?>
