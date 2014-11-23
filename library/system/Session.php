<?php
namespace system;

class Session{

	function login($user){
		$_SESSION['user'] = $user;
	}

	function logout(){
		unset($_SESSION['user']);
	}

	function getSession(){
		return $_SESSION['user'];
	}

	function hasSession(){
		if(!empty($_SESSION['user'])){
			return true;
		} else {
			return false;
		}
	}

}
