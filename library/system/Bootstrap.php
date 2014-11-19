<?php
/**
* Bootstrap application
*/
class Bootstrap{

	function run(){

		//load router
		$router = new Router();
		$router->route();
	}

}