<?php

class IndexController extends Controller{


	function index(){
		$sampleModel = new SampleModel();
		$this->view->name = $sampleModel->getName();
	}
}