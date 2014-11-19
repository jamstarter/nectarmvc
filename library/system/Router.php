<?php

class Router{

	/**
	* Intercept and process the current route
	*/
	public function route(){
		//if not cli, then render view
		if(php_sapi_name() <> 'cli'){
			$route = $this->getCurrentRoute();

			$this->dispatch($route);
		}


	}

	public function dispatch($route){

		$moduleName = strtolower($route['module']);
		$controllerName = $route['controller']."Controller";
		$actionName = $route['action'];

		$params = array();
		foreach($route as $key => $value){
			$ignore = array('route','module','controller','action');
			if(!in_array($key,$ignore)){
				$params[$key] = $value;
			}
		}
		
			//Initialize Controller
			$controller = new $controllerName($route,$params);
			$controller->$actionName();

			//Set View script
			$controller->setViewPath(APPLICATION_PATH."/app/modules/".$moduleName."/".strtolower($controllerName));
			$controller->setView(strtolower($actionName).".twig");

			$controller->initView();
		

	}



	public function getCurrentRoute(){
		if(@$_SERVER['REQUEST_URI'] == ""){
			$url = "/";
		} else {
			$url = $_SERVER['REQUEST_URI'];
		}
		$urlParts = explode("?",$url);
		$requestURI = array_filter(explode('/', $urlParts[0]));
		
		//Parse routes config file
		$rawRoutes = parse_ini_file(APPLICATION_PATH."/app/routes.ini", true, INI_SCANNER_NORMAL );
	
		$module_files = scandir(APPLICATION_PATH.'/app/modules/');
		$moduleNames = array();
		foreach($module_files AS $moduleName){
			if($moduleName <> '..' && $moduleName <> '.'){
				$moduleNames[] =$moduleName;
			}
		}
		foreach($moduleNames AS $module){
			$routesFile = APPLICATION_PATH."/app/modules/".$module."/configs/routes.ini";
			if(file_exists($routesFile)){
				$raw2 = parse_ini_file($routesFile, true, INI_SCANNER_NORMAL );
				$rawRoutes = array_merge($rawRoutes, $raw2);
			}
		}



		



		$routes = array();
		foreach($rawRoutes AS $key => $route){
			$keyParts = explode(".",$key);
			$routes[$keyParts[0]][$keyParts[1]][$keyParts[2]] = $route;
		}


		$chosenRoute = array();
		$scoreRoutes = array();
		foreach($routes['routes'] AS $name => $routeData){
			if($_SERVER['REQUEST_URI'] == $routeData['route']){
				$chosenRoute = $routeData;
			} else {

				$thisRouteSplit = array_filter(explode("/",$routeData['route']));
				$i = 0;
				$max = count($thisRouteSplit) + 1;

				while($i <= $max){

					if((@$requestURI[$i] == @$thisRouteSplit[$i]) || strstr(@$thisRouteSplit[$i],':') ) {

						$scoreRoutes[$name] = @$scoreRoutes[$name] + 1;

						if(strstr(@$thisRouteSplit[$i],':')){
						
							$routes['routes'][$name][str_replace(":","",@$thisRouteSplit[$i])] = @$requestURI[$i];
						}

					} else if((!isset($requestURI[$i]) && !strstr($thisRouteSplit[$i],':') ) ){

						$scoreRoutes[$name] = @$scoreRoutes[$name] - 1;
					}



					$i++;
				}

				
			}

		}

		asort($scoreRoutes);
		if(empty($chosenRoute)){
			foreach($scoreRoutes AS $name => $value){
				$chosenRoute = $routes['routes'][$name];
			}
		}

		return $chosenRoute;

	}


}