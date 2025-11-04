<?php
namespace Controllers;
use Funks\Admin;
use Funks\About;
use Funks\Projects;
use Funks\Skills;
use Funks\Careers;

class AdminController
{
	var $page;
	var $app;

	//Variables para limitacion de paginador
	var $comienzo = 0;
	var $pagina = 1;
	var $limite = 15;

	public function execute($page,$app){

		$this->page = $page;
		$this->app = $app;

		$this->app['render']->layout = 'back-end';
		$this->app['tools']->registerJavascript(_ASSETS_._ADMIN_.'jquery/jquery.min.js', 'top');
		$this->app['tools']->registerJavascript(_ASSETS_._ADMIN_.'funks-panel.js?c='.time());
		$this->app['tools']->registerJavascript(_ASSETS_._ADMIN_.'croppie.js?c='.time());
		$this->app['tools']->registerJavascript(_ASSETS_._ADMIN_.'croppie-admin.js?c='.time());

		//Dashboard & Login
		$this->add('',function(){

			//Checking if user is logged or not.
			if(!isset($_SESSION['admin'])){

				//Default vars
				$data = array(
				);

				//Rendering login
				$this->app['render']->showAdminPage('login', $data);
			}
			else{

				//Loading clases
				$_skills = new Skills($this->app);
				$_projects = new Projects($this->app);
				$_careers = new Careers($this->app);

				$lastFormations = $_careers->getLastCareers('formation');
				$lastProjects = $_projects->getLastProjects();

				$data = [
					'stats_skills' => $_skills->getSkillsStats(),
					'stats_projects' => $_projects->getProjectsStats(),
					'stats_experiences' => $_careers->getCarrersStats('experience'),
					'stats_formations' => $_careers->getCarrersStats('formation'),
					'lastFormations' => $lastFormations,
					'lastProjects' => $lastProjects,
				];

				$this->app['render']->adminPage('home', $data);
			}
		});

		//Logout of backend
		$this->add('logout',function($app){

			$this->app['render']->layout = false;
			$this->app['admin'] = new Admin($this->app);
			$this->app['admin']->logout();
			header("Location: "._DOMINIO_._ADMIN_);
		});

		//About me page
		$this->add('about-me',function(){

			if(!isset($_SESSION['admin']))
				header("Location: "._DOMINIO_._ADMIN_);
		
			//Data About me
			$_about = new About($this->app);
			$about  = $_about->getAboutBaseById(1);

			$data = [
				'about'	=> $about,
				'comienzo' => $this->comienzo,
				'pagina' => $this->pagina,
				'limite' => $this->limite,
			];

			$this->app['render']->adminPage('about-me', $data);
		});

		//About me page
		$this->add('skills',function(){

			if(!isset($_SESSION['admin']))
				header("Location: "._DOMINIO_._ADMIN_);

			$data = [
				'comienzo' => $this->comienzo,
				'pagina' => $this->pagina,
				'limite' => $this->limite,
			];

			$this->app['render']->adminPage('skills', $data);
		});

		//Projects
		$this->add('projects',function(){

			if(!isset($_SESSION['admin']))
				header("Location: "._DOMINIO_._ADMIN_);

			//Loading class
			$_projects = new Projects($this->app);

			$data = [
				'comienzo' => $this->comienzo,
				'pagina' => $this->pagina,
				'limite' => $this->limite,
				'categories' => $_projects->getCategoriesByLang(2, "", true),
			];

			$this->app['render']->adminPage('projects', $data);
		});

		//Project
		$this->add('project',function(){

			if(!isset($_SESSION['admin']))
				header("Location: "._DOMINIO_._ADMIN_);

			//Loading class
			$_projects = new Projects($this->app);
			$id = $this->app['tools']->getValue('data');

			if($this->app['bd']->countRows("SELECT id FROM projects WHERE id = '".$id."'") == 0)
				header("Location: "._DOMINIO_._ADMIN_.'projects/');

			$data = [
				'id'			=> $id,
				'comienzo' 		=> $this->comienzo,
				'pagina'		=> $this->pagina,
				'limite' 		=> $this->limite,
				'categories' 	=> $_projects->getCategoriesByLang(2, "", true),
			];

			$this->app['render']->adminPage('project', $data);
		});

		//Projects categories
		$this->add('projects-categories',function(){

			if(!isset($_SESSION['admin']))
				header("Location: "._DOMINIO_._ADMIN_);

			$data = [
				'comienzo' => $this->comienzo,
				'pagina' => $this->pagina,
				'limite' => $this->limite,
			];

			$this->app['render']->adminPage('projects-categories', $data);
		});

		//Experience
		$this->add('experience',function(){

			if(!isset($_SESSION['admin']))
				header("Location: "._DOMINIO_._ADMIN_);

			$data = [
				'type'		=> 'experience',
				'icon'		=> 'mdi mdi-briefcase',
				'comienzo' 	=> $this->comienzo,
				'pagina' 	=> $this->pagina,
				'limite' 	=> $this->limite,
			];

			$this->app['render']->adminPage('career', $data);
		});

		//Education
		$this->add('education',function(){

			if(!isset($_SESSION['admin']))
				header("Location: "._DOMINIO_._ADMIN_);

			$data = [
				'type'		=> 'education',
				'icon'		=> 'fa fa-graduation-cap',
				'comienzo' 	=> $this->comienzo,
				'pagina' 	=> $this->pagina,
				'limite' 	=> $this->limite,
			];

			$this->app['render']->adminPage('career', $data);
		});

		//Formation
		$this->add('formation',function(){

			if(!isset($_SESSION['admin']))
				header("Location: "._DOMINIO_._ADMIN_);

			$data = [
				'type'		=> 'formation',
				'icon'		=> 'mdi mdi-book',
				'comienzo' 	=> $this->comienzo,
				'pagina' 	=> $this->pagina,
				'limite' 	=> $this->limite,
			];

			$this->app['render']->adminPage('career', $data);
		});

		//Pages
		$this->add('pages',function(){

			if(!isset($_SESSION['admin']))
				header("Location: "._DOMINIO_._ADMIN_);

			$data = [
				'comienzo' 	=> $this->comienzo,
				'pagina' 	=> $this->pagina,
				'limite' 	=> $this->limite,
			];

			$this->app['render']->adminPage('pages', $data);
		});

		//Documents
		$this->add('documents',function(){

			if(!isset($_SESSION['admin']))
				header("Location: "._DOMINIO_._ADMIN_);

			$data = [
				'comienzo' 	=> $this->comienzo,
				'pagina' 	=> $this->pagina,
				'limite' 	=> $this->limite,
			];

			$this->app['render']->adminPage('documents', $data);
		});

		//Translations page
		$this->add('translations',function(){

			if(!isset($_SESSION['admin']))
				header("Location: "._DOMINIO_._ADMIN_);

			//Getting languages
			$languages = $this->app['lang']->getLanguages();

			//Total translations for each language
			$totalTraductions = 0;
			if( count($languages) > 0 ){
                foreach( $languages as $key => $lang ){
                    $lang->totalTraductions 		= count($this->app['lang']->getAllTraductionsById($lang->id, false, true));
                    $lang->totalTraductionsDone 	= count($this->app['lang']->getAllTraductionsById($lang->id, true));
                }
            }

			//Getting all shortcodes for filtering.
			$traduntionsFor = $this->app['lang']->getTraductionsForGrouped();

			$data = array(
				'default_language' 		=> $this->app['lang']->getDefaultLanguage(),
				'totalTraductions' 		=> $totalTraductions,
				'languages' 			=> $languages,
				'traduntionsFor' 		=> $traduntionsFor,
				'comienzo' 				=> $this->comienzo,
				'pagina' 				=> $this->pagina,
				'limite' 				=> $this->limite,
			);

			$this->app['render']->adminPage('translations', $data);
		});

		//Languages page
		$this->add('languages',function(){

			if(!isset($_SESSION['admin']))
				header("Location: "._DOMINIO_._ADMIN_);

			$data = [
				'comienzo' => $this->comienzo,
				'pagina' => $this->pagina,
				'limite' => $this->limite,
			];

			$this->app['render']->adminPage('languages', $data);
		});

	}

	public function add($page,$data)
	{
		if( $page == $this->page )
			return $data($this->app);
	}
}
?>
