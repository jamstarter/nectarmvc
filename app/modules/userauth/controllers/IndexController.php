<?php
namespace userauth\controllers;
use system\Controller as NectarController;

class IndexController extends NectarController{

	//register a user
	function register(){

		if($this->_isPost){
			$error = false;

			//get model for user table
			$userAuthModel = new \userauth\models\UserauthUsers;

			//validate fields
			if(trim($this->_params->first_name) == ''){
				$error = "Please provide first name";
			}
			if(trim($this->_params->last_name) == ''){
				$error = "Please provide last name";
			}
			if(trim($this->_params->email) == ''){
				$error = "Please provide email";
			}
			if (!filter_var(trim($this->_params->email), FILTER_VALIDATE_EMAIL)) {
				$error = "Email not valid.";
			}
			if(trim($this->_params->password) == ''){
				$error = "Please provide a password";
			}
			if($exists = $userAuthModel->exists(trim($this->_params->email))){
				$error = "Email already in use.";
			}

			//if no errors, proceed
			if(!$error){
				//create user
				$userAuthModel->createUser($this->_params);
				//get user id
				$id = $userAuthModel->_conn->lastInsertId();
				//get user record from database
				$user = $userAuthModel->getById($id);
				//create session for user
				$_SESSION['user'] = $exists;

			} else {  
				//send errors to the view
				$this->view->error = $error;
			}

		}
		//if user is logeed in, redirect them
		if(isset($_SESSION['user'])){
			$this->redirect("/");
		}
	}

	//user login
	function login(){

		//get model for user table
		$userAuthModel = new \userauth\models\UserauthUsers;

		if($this->_isPost){
			$error = 0;
			if(trim($this->_params->email) == ''){
				$error = "Please provide email";
			}
			if (!filter_var(trim($this->_params->email), FILTER_VALIDATE_EMAIL)) {
				$error = "Email not valid.";
			}
			if(trim($this->_params->password) == ''){
				$error = "Please provide a password";
			}
			if(!$validUser = $userAuthModel->isValid(trim($this->_params->email), sha1(trim($this->_params->password))) ){
				$error = "Email or password is incorrect.";
			}

			if(!$error){
				$_SESSION['user'] = $validUser;
				$this->redirect("/");

			} else {
				//send errors to the view
				$this->view->error = $error;
			}
		}
		//if user is logeed in, redirect them
		if(isset($_SESSION['user'])){
			$this->redirect("/");
		}
	}

	//log a user out
	function logout(){
		unset($_SESSION['user']);
		$this->redirect("/login");
	}
}