<?php

class MyModel extends Model{

	protected $_name = "users";

	function testDb(){
		$select = $this->_db->select()
                        ->from($this->_name);
        $result = $this->_db->fetchAll($select);
        return $result;
		
	}

	public function getById($id){
        $select = $this->_db->select()
                        ->from($this->_name)
                        ->where('id=?',$id);
        $result = $this->_db->fetchRow($select);
        return $result;
    }

}