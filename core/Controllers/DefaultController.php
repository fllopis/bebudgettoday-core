<?php
namespace Controllers;
use Funks\Skills;
use Funks\About;
use Funks\Careers;
use Funks\Projects;
use Funks\Pages;
use Funks\Documents;

class DefaultController
{
	var $page;
	var $app;

	public function execute($page,$app)
	{
		$this->page = $page;
		$this->app = $app;

		//Layout por defecto
		$this->app['render']->layout = 'front-end';

		$_pages = new Pages($this->app);

		//Credenciales de acceso a zonas/secciones
		$this->app['render']->layout_data = array(
			'tools' => $this->app['tools'],

			//Footer pages
			'copyLinks' 		=> $_pages->getPagesForZoneAndLang('policy', $_SESSION['lang']),
			
			//Language & translations
			'lang' 				=> $this->app['lang'],
			'translation' 		=> $this->app['lang']->getTranslationFor("home", $_SESSION['lang']),
			'languages' 		=> $this->app['lang']->getLanguagesTranslatedVisible(),
		);

		//PAGE:: Inicio/Home
		$this->add('',function(){

            //Loading class
            $_skills = new Skills($this->app);
			$_about = new About($this->app);
			$_careers = new Careers($this->app);
			$_projects = new Projects($this->app);
			$_documents = new Documents($this->app);

			//Getting METAS
			$metaTitle = $this->app['lang']->getTranslation('meta-title', 'home');
			$metaDescription = $this->app['lang']->getTranslation('meta-description', 'home');

			//Setting METAS for HOME
			$this->app['tools']->metaTitle = (isset($metaTitle)) ? $metaTitle : _TITLE_;
			$this->app['tools']->metaDescription = (isset($metaDescription)) ? $metaDescription : _DESCRIPTION_;

			//Getting data
			$skillData 			= $_skills->getSkillInformationByLang($_SESSION['lang']);
			$about 				= $_about->getAboutMeByLang($_SESSION['lang']);
			$experiences 		= $_careers->getCareersByLang("experience", $_SESSION['lang']);
			$educations 		= $_careers->getCareersByLang("education", $_SESSION['lang']);
			$formations 		= $_careers->getCareersByLang("formation", $_SESSION['lang']);
			$projects 			= $_projects->getProjectsByLang($_SESSION['lang']);
			$project_categories = $_projects->getCategoriesByLang(0, $_SESSION['lang']);
			$document_cv 		= $_documents->getDocumentByShortcodeAndLang('curriculum-vitae', $_SESSION['lang']);

			//Array de datos a enviar a la página
			$data = array(
				'about' => $about,
				'skillData' => $skillData,
				'experiences' => $experiences,
				'educations' => $educations,
				'formations' => $formations,
				'projects' => $projects,
				'project_categories' => $project_categories,
				'document_cv' => $document_cv,
				'_careers' 	=> $_careers,
			);

			$this->app['render']->page('home',$data);
		});

		//PAGE:: for PAGES
		$this->add('pages',function(){

			//Getting page data
			$_pages = new Pages($this->app);
			$pageData = $_pages->getPageBySlugAndByLang($_REQUEST['mod'], $_SESSION['lang']);

			if(!$pageData)
				header('Location:'._DOMINIO_.$_SESSION['lang']."/");

			//Metas
			$this->app['tools']->metaTitle = (isset($pageData->seo_title)) ? $pageData->seo_title : _TITLE_;
			$this->app['tools']->metaDescription = (isset($pageData->seo_description)) ? $pageData->seo_description : _DESCRIPTION_;

			//Array de datos a enviar a la página
			$data = array(
				'pageData' => $pageData,
			);

			$this->app['render']->page("pages", $data);
		});
	}

	public function add($page,$data)
	{
		if ( $page == $this->page )
			return $data($this->app);
	}
}
