<?php

class Config{

	public function getConfig(){
		if(file_exists(APPLICATION_PATH."/../application.ini")){
			$application =  parse_ini_file(APPLICATION_PATH."/../application.ini", true, INI_SCANNER_NORMAL );
		} else {
			$application =  parse_ini_file(APPLICATION_PATH."/app/application.ini", true, INI_SCANNER_NORMAL );
		}
		return $application[APPLICATION_ENV];
	}

}