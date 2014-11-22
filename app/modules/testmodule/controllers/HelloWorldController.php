<?php

class HelloWorldController extends Controller{
	

	function helloWorld2(){

		$this->view->myVar = "hello";
		

		$myModelModel = new MyModel;
		$users = $myModelModel->testDB();
		$this->view->users = $users;
	}


	function ryan(){

	}
	function helloworld(){

	}
}