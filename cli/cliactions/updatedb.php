<?php
$commands[] = array('command'=>'updatedb [module/all]','description'=>'Update the database from module YAML files');

use Symfony\Component\Yaml\Yaml;

function updatedb($module){
	if(trim($module) == ''){
		die("Please specify a module name or 'all'! \n");
	}
	$modules = array();
	if(trim($module) <> 'all'){	
		$modules[] = $module;
	} else {
		$folders = scandir(APPLICATION_PATH.'/app/modules');
		foreach($folders AS $folder){
			if($folder <> '.' && $folder <> '..'){
				$modules[] = $folder;
			}
		}
	}
	foreach($modules AS $module){
		$modulePath = APPLICATION_PATH.'/app/modules/'.$module;
		$dbMapPath = trim($modulePath."/db/db.yaml");

		try{
		$dbMap = Yaml::parse(file_get_contents($dbMapPath));
		print_r($dbMap);

		} catch (Exception $e){
			echo $e->getMessage()."\n";
		}
	}
}