<?php
namespace system;

class Config{

	public function getConfig(){
		if(file_exists(APPLICATION_PATH."/../application.ini")){
			$application =  parse_ini_file(APPLICATION_PATH."/../application.ini", true, INI_SCANNER_NORMAL );
		} else {
			$application =  parse_ini_file(APPLICATION_PATH."/app/application.ini", true, INI_SCANNER_NORMAL );
		}


		$settings = (object) array();
		foreach($application[APPLICATION_ENV] AS $key => $value){
			$keyParts = explode(".",$key);
			@$settings->{$keyParts[0]}->{$keyParts[1]}->{$keyParts[2]} = $value;
		}
		$application = (object) $settings;
		return $application;
	}

}