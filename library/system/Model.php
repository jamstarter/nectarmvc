<?php

include_once(APPLICATION_PATH.'/library/vendors/Zend/Db.php');
class Model{

	public $_db;

	function __construct(){
		//get config
		$config = new Config;
		$application = $config->getConfig();

		$settings = (object) array();
		foreach($application AS $key => $value){
			$keyParts = explode(".",$key);
			@$settings->{$keyParts[0]}->{$keyParts[1]}->{$keyParts[2]} = $value;
		}
		$this->_application = (object) $settings;

		$db = Zend_Db::factory($this->_application->resources->db->adapter, array(
			'host'             => $this->_application->resources->db->host,
			'username'         => $this->_application->resources->db->username,
			'password'         => $this->_application->resources->db->password,
			'dbname'           => $this->_application->resources->db->database
		));

		$this->_db = $db;
	}

}