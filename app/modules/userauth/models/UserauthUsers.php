<?php
namespace userauth\models;
use system\Model as NectarModel;

class UserauthUsers extends NectarModel{

	public $_name = 'userauth_users';

	function getById($id){
		$select = $this->_db->select('*')
			->from($this->_name)
			->where("id=$id")
			->execute();
		return $select->fetch();
	}

	function exists($email){
		$select = $this->_db->select('*')
			->from($this->_name)
			->where("email='$email'")
			->execute();
		return $select->fetch();
	}
	
	function isValid($email,$password){
		$select = $this->_db->select('*')
			->from($this->_name)
			->where("email='$email' AND password='$password'")
			->execute();
		return $select->fetch();
	}

	function createUser($data){
		$newData = array();
		$newData['password'] = sha1($data->password);
		$newData['first_name'] = $data->first_name;
		$newData['last_name'] = $data->last_name;
		$newData['email'] = $data->email;
		$this->_conn->insert($this->_name,$newData);
		//get user id
		$id = $this->_conn->lastInsertId();
		//get user record from database
		$user = $this->getById($id);
		return $user;
	}

	function validateFields($data,$checkExisting=true){

		$errors = array();

		if(isset($data->first_name) && trim($data->first_name) == ''){
			$errors[] = "Please provide first name";
		}
		if(isset($data->last_name) && trim($data->last_name) == ''){
			$errors[] = "Please provide last name";
		}
		if(trim($data->email) == ''){
			$errors[] = "Please provide email";
		}
		if (!filter_var(trim($data->email), FILTER_VALIDATE_EMAIL)) {
			$errors[] = "Email not valid.";
		}
		if(trim($data->password) == ''){
			$errors[] = "Please provide a password";
		}
		if($checkExisting){
			if($exists = $this->exists(trim($data->email))){
				$errors[] = "Email already in use.";
			}
		}

		
		return $errors;
		
	}
}