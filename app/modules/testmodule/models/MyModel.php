<?php
namespace testmodule\models;
use system\Model as NectarModel;

class MyModel extends NectarModel{

	protected $_name = "users";

	function testDb(){
		$select = $this->_db->select('*')
           ->from($this->_name)
           ->execute();
           return $select->fetchAll();
	}



}