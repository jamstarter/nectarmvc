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
		return $this->_conn->insert($this->_name,$newData);

	}
}