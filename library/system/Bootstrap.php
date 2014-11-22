<?php
/**
* Bootstrap application
*/
class Bootstrap{

	function run(){

		//load router
		$router = new system\Router();
		$router->route();
	}

}