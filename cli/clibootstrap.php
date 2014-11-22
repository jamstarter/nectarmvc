<?php
session_start();
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);
ini_set('error_log','script_errors.log');
ini_set('log_errors','On');
define("APPLICATION_PATH",dirname(dirname(__FILE__)));
if(file_exists(APPLICATION_PATH.'/../environment.conf')){
	define("APPLICATION_ENV",trim(file_get_contents(APPLICATION_PATH.'/../environment.conf')));
} else {
	define("APPLICATION_ENV",'production');	
}
//require autoloader
require_once(dirname(__FILE__).'/../library/system/AutoLoader.php');
require_once(APPLICATION_PATH.'/library/vendors/Twig/Autoloader.php');
Twig_Autoloader::register();

// set config settings 
autoloader(array(array( 
      'basepath' => dirname(__FILE__).'/../', // basepath is used to define where your project is located 
      'extensions' => array('.php'), // allowed class file extensions 
))); 

// now we can set class autoload paths 
autoloader(array( 
      'library/system', 
)); 



$bootstrap = new Bootstrap();
$bootstrap->run();
