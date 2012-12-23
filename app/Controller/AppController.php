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
		'Auth',
		'RequestHandler',
		'Cookie',
	);	
	
	public function beforeFilter() {
		if (isset($this->request->params['admin'])) {
			// the user has accessed an admin function, so handle it accordingly.
			$this->layout = 'admin';
			$this->Auth->loginRedirect = array('controller'=>'users','action'=>'index');
			$this->Auth->allow('login');
		} else {
			// the user has accessed a NON-admin function, so handle it accordingly.
			$this->Auth->allow();
		}
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