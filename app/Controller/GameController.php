<?php

App::uses('AppController', 'Controller');
/**
 * Game Controller
 */
class GameController extends AppController {

	public $uses = array();
	
	public function start() {
		$this->redirect('/run.html');
		
		
		$this->layout = 'game';
	}
	
	public function about() {
		$this->layout = 'modal';
		// HTML page
	}
	
}