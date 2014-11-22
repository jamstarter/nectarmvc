<?php
namespace testmodule\controllers;
use system\Controller as NectarController;

class HelloWorldController extends NectarController{
	


	function helloWorld2(){

		$this->view->myVar = "hello";
		

		$myModelModel = new \testmodule\models\MyModel;
		$users = $myModelModel->testDB();
		$this->view->users = $users;
	}


	function ryan(){

	}
	function helloworld(){

	}
}