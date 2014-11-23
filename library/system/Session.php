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


}
