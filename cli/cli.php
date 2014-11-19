<?php
require('clibootstrap.php');
$commands = array();
/** Include commands files - must be listed here to be used. **/
$actions = scandir('cliactions');
foreach($actions AS $file){
	if($file <> '.' && $file <> '..'){
		include 'cliactions/'.$file;
	}
}
//Run function or provide help
if(count($argv) > 1){

    $argv[1](@$argv[2]);
} else {
   echo "                                                                                                                                                                                                                      
\033[1;30;32mCLI Command Line Interface for Nectar PHP Framework \033[0m
Author: Brandon Swift 2014\n
To run a command, type 'php cli.php [commandName]' then hit Enter
\n
Available Methods: \n";
   
   foreach($commands AS $command){
       echo "\033[1;30;32m".$command['command'] ."\033[0m - ". $command['description']."\n";
   }
   echo "\n\n";
}
function makeRed($text){
	echo "\033[31m$text\033[0m";
}
function makeGreen($text){
	echo "\033[32m$text\033[0m";
}