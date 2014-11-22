<?php
namespace system;




class Model{

	public $_db;
	public $_rawDb;

	function __construct(){
		//get config
		$config = new \system\Config;
		$application = $config->getConfig();

		
		$this->_application =$application;

		$config = new \Doctrine\DBAL\Configuration();
		$connectionParams = array(
		    'dbname' => $this->_application->resources->db->database,
		    'user' => $this->_application->resources->db->username,
		    'password' => $this->_application->resources->db->password,
		    'host' => $this->_application->resources->db->host,
		    'driver' => strtolower($this->_application->resources->db->adapter),
		);
		$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

		$this->_rawDb = $conn;
		$this->_db = $conn->createQueryBuilder();
	}

}