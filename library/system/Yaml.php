<?php


class Yaml{
	
	use Symfony\Component\Yaml\Yaml;


	function parse($path){
		return $this->loadFile($path);
	}

}