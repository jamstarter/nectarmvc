<?php
namespace system;

/**
* Main controller all other controllers extend
*/
class Controller{

	public $_route;
	public $_params;
	public $_viewPath;
	public $_layout;
	public $view;
	public $_viewFile;
	public $_application;
	public $_config;

	function __construct($route,$params){
		$this->_route = (object) $route;

		if(isset($_POST)){
			$params = array_merge($params,$_POST);
			$this->_post = $_POST;
		}
		if(isset($_GET)){
			$params = array_merge($params,$_GET);
			$this->_get = $_POST;
		}
		$this->_params = (object) $params;

		//set default layout
		$this->_layout = "layout";

		$this->view = (object) $this->_params;

		//get config
		$config = new Config;
		$application = $config->getConfig();
		$this->_config = $application;

		
		$this->_application = $application;

		$this->_isPost = !empty($_POST);

	}

	function setViewPath($path){
		$this->_viewPath = $path;
	}

	function setView($view){
		$this->_viewFile = $view;
	}

	function initView(){
		if($this->_layout <> 'none'){
			$loader = new \Twig_Loader_Filesystem(APPLICATION_PATH.'/app/layouts');
			$twig = new \Twig_Environment($loader);

			$loaderView = new \Twig_Loader_Filesystem(APPLICATION_PATH.'/app/modules/'.strtolower($this->_route->module).'/views/'.strtolower($this->_route->controller));
			$twigView = new \Twig_Environment($loaderView);

			$content = $twigView->render(strtolower($this->_route->action).'.twig', (Array)$this->view);

			echo $twig->render($this->_layout.'.twig', array_merge(array('content'=>$content,'server'=>$_SERVER,'session'=>$_SESSION),(array)$this->view));
		} else {
			$loaderView = new \Twig_Loader_Filesystem(APPLICATION_PATH.'/app/modules/'.strtolower($this->_route->module).'/views/'.strtolower($this->_route->controller));
			$twigView = new \Twig_Environment($loaderView);

			$content = $twigView->render(strtolower($this->_route->action).'.twig', (Array)$this->view);
			echo $content;
		}

	}

	function redirect($to){
		header('Location: '.$to);
	}

}