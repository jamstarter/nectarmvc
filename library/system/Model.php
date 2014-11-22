<?php
namespace system;




class Model{

	public $_db;

	function __construct(){
		//get config
		$config = new \system\Config;
		$application = $config->getConfig();

		$settings = (object) array();
		foreach($application AS $key => $value){
			$keyParts = explode(".",$key);
			@$settings->{$keyParts[0]}->{$keyParts[1]}->{$keyParts[2]} = $value;
		}
		$this->_application = (object) $settings;

		$config = new \Doctrine\DBAL\Configuration();
		$connectionParams = array(
		    'dbname' => $this->_application->resources->db->database,
		    'user' => $this->_application->resources->db->username,
		    'password' => $this->_application->resources->db->password,
		    'host' => $this->_application->resources->db->host,
		    'driver' => strtolower($this->_application->resources->db->adapter),
		);
		$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);


		$this->_db = $conn->createQueryBuilder();
	}

}