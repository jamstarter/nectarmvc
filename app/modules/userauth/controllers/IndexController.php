<?php
namespace userauth\controllers;
use system\Controller as NectarController;

class IndexController extends NectarController{

	//register a user
	function register(){
		$session = new \system\Session;

		if($this->_isPost){

			//get model for user table
			$userAuthModel = new \userauth\models\UserauthUsers;
			

			//validate fields
			$errors = $userAuthModel->validateFields($this->_params);

			//if no errors, proceed
			if(empty($errors)){
				//create user
				$user = $userAuthModel->createUser($this->_params);
				//create session for user
				$session->login($user);

			} else {  
				//send errors to the view
				$this->view->errors = $errors;
			}

		}
		//if user is logged in, redirect them
		if($session->hasSession()){
			$this->redirect("/");
		}
	}

	//user login
	function login(){
		$session = new \system\Session;

		//get model for user table
		$userAuthModel = new \userauth\models\UserauthUsers;

		if($this->_isPost){
			
			//validate fields
			$errors = $userAuthModel->validateFields($this->_params,false);

			if(!$validUser = $userAuthModel->isValid(trim($this->_params->email), sha1(trim($this->_params->password))) ){
				$errors[] = "Email or password is incorrect.";
			}

			if(empty($errors)){
				//login user
				$session->login($validUser);
				$this->redirect("/");

			} else {
				//send errors to the view
				$this->view->errors = $errors;
			}
		}
		//if user is logged in, redirect them
		if($session->hasSession()){
			$this->redirect("/");
		}
	}

	//log a user out
	function logout(){
		$session = new \system\Session;
		$session->logout();

		$this->redirect("/login");
	}
}