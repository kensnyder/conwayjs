<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	
	public function getQueryLog() {
		return $this->getModel('User')->getDataSource()->getLog(false, false);
	}
	
	public function setJsonVars($fields) {
		if ($fields == 'all') {
			$fields = array_keys($this->View->viewVars);
		}
		if (is_string($fields)) {
			$fields = explode(',', $fields);
		}
		$this->set('_serialize', $fields);
	}
	
	public function setTitle($title) {
		$this->set('h1', preg_replace('/^(.+) :: .+$/', '$1', $title));
		$this->set('title_for_layout', "$title :: conwayjs.com");
		return $this;
	}
	
	public $helpers = array(
		// Cake helpers
		'Html',
		'Session',
		'Text',
		'Form',
	);
	
	public $components = array(		
		// Cake components
		'Session',
		'RequestHandler',
		'Cookie',
		// Custom components
		'Identity',
	);
	
	public function beforeFilter() {
		$this->set('authUser', $this->Identity->user());
	}
	
	/**
	 * load a model and return it
	 * 
	 * @param string $modelName
	 * @return AppModel
	 */
	public function getModel($modelName) {
		if (!isset($this->$modelName)) {
			$this->loadModel($modelName);
		}
		return $this->$modelName;
	}
	
}
