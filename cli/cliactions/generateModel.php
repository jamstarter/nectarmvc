<?php
$commands[] = array('command'=>'generateModel [module] [dbtable]','description'=>'Generate a model for a module');

function generateModel($module,$dbtable){
	if(trim($module) == '' || trim($dbtable) == ''){
		die("Provide a module and a database table name, please.\n");
	}
	$className = str_replace(" ","",ucwords(str_replace("_"," ",$dbtable)));
	echo "Generating $className...\n";
		$contents="<?php
namespace $module\models;
use system\Model as NectarModel;

class $className extends NectarModel{

	public \$_name = '$dbtable';

	function getById(\$id){
		\$select = \$this->_db->select('*')
			->from(\$this->_name)
			->where(\"id=$id\")
			->execute();
		return \$select->fetch();
	}
}";
	$path = APPLICATION_PATH."/app/modules/$module/models";
	if(!file_exists($path."/$className.php")){
		file_put_contents($path."/$className.php", $contents);
		echo "Complete.\n";
	} else {
		echo "File already $className.php exists!\n";
	}
}