<?php
$commands[] = array('command'=>'generateModule [name]','description'=>'Generate a skeleton module');

function generateModule($name){
	$moduleName = slugify($name);
	$folder = APPLICATION_PATH."/app/modules/$moduleName";

	$startTime = time();

	echo "\n\nGenerating Module $moduleName... \n\n";
	sleep(1);

	//createDirectory
	echo "Creating Directory... \n";
	createDirectory($folder);

	//create Controller
	echo "Creating Controller... \n";
	generateController($folder."/controllers");

	//create Model
	echo "Creating Model... \n";
	generateModel($folder."/models");

	//create View
	echo "Creating View... \n";
	generateView($folder."/views/index",$moduleName);

	//create Routes
	echo "Creating Routes... \n";
	generateRoutes($folder."/configs",$moduleName);

	//create Sql Folder
	echo "Creating Folder for SQL... \n";
	generateSql($folder."/sql",$moduleName);

	$endTime = time();

	echo $moduleName." created in $folder \n";
	$time = number_format($startTime - $endTime,5);
	echo "Complete.\n\n";

	echo "Navigate to /$moduleName/index to view your new module!\n\n";
}




function createDirectory($folder){
	if(!is_dir($folder)){
		mkdir($folder);
	}
}

function generateController($path){
	$contents="<?php

class IndexController extends Controller{


	function index(){
		\$sampleModel = new SampleModel();
		\$this->view->name = \$sampleModel->getName();
	}
}";
	if(!is_dir($path)){
		mkdir($path);
		file_put_contents($path."/IndexController.php", $contents);
	}
}

function generateModel($path){
	$contents="<?php

class SampleModel extends Model{



	function getName(){
		return 'World';
	}
}";
	if(!is_dir($path)){
		mkdir($path);
		file_put_contents($path."/SampleModel.php", $contents);
	}
}


function generateView($path,$moduleName){
	$contents="Hello, {{name}}!
	<br><br>
	Module $moduleName sample view";
	if(!is_dir($path)){
		mkdir($path,0777,true);
		file_put_contents($path."/index.twig", $contents);
	}
}

function generateSql($path){
	if(!is_dir($path)){
		mkdir($path,0777,true);
//		file_put_contents($path."/index.twig", $contents);
	}
}

function generateRoutes($path,$moduleName){
	$contents="routes.$moduleName.route = /$moduleName/index
routes.$moduleName.module = $moduleName
routes.$moduleName.controller = Index
routes.$moduleName.action = index";
	if(!is_dir($path)){
		mkdir($path);
		file_put_contents($path."/routes.ini", $contents);
	}
}

function slugify($text)
{ 
  // replace non letter or digits by -
  $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

  // trim
  $text = trim($text, '-');

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // lowercase
  $text = strtolower($text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  if (empty($text))
  {
    return 'n-a';
  }

  return $text;
}
