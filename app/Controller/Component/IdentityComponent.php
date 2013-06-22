<?php

App::uses('Component', 'Controller');

class IdentityComponent {
	
	public $fields = array('username'=>'email', 'password'=>'password');
	
	public $rememberMeDuration = '30 days';
	
	public $error;
	
	public $components = array('Session', 'Cookie');
	
	protected $_user = null;
	
	const USER_NOT_FOUND = 1;
	
	const WRONG_PASSWORD = 2;
	
	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}	
	
	public function login($username, $password, $rememberMe) {
		$this->controller->loadModel('User');
		$user = $this->controller->User->find('first', array(
			'conditions' => array(
				$this->fields['username'] => $username
			)
		));
		if (!$user) {
			$this->error = self::USER_NOT_FOUND;
			return false;
		}
		$expectedHash = $this->hashPassword($user['User'][$this->fields['username']], $password);
		if ($expectedHash != $user['User'][$this->fields['password']]) {
			$this->error = self::WRONG_PASSWORD;
		}
		$this->Session->write('Identity', $user);
		if ($rememberMe) {
			$token = sha1(uniqid() . microtime());
			$this->Cookie->write('Identity.token', $token, $this->rememberMeDuration, $encrypt=true);
			$this->controller->loadModel('RememberMe');
			$this->controller->RememberMe->create();
			$this->controller->RememberMe->save(array(
				'RememberMe' => array(
					'token' => $token,
					'user_id' => $user['User']['id']
				)
			));
		}
	}
	
	public function logout() {
		$this->Cookie->delete('Identity.token');
		$this->Session->delete('Identity');
		return $this;
	}
	
	public function user($field = null) {
		if ($this->_user === null) {
			// attempt to load user identity
			$this->_loadUser();
		}
		if ($this->_user === false) {
			// user identity could not be loaded
			return false;
		}
		// we have the identity; return the field if requested or the entire record if field is null
		return $field ? @$this->_user['User'][$field] : $this->_user;
	}
	
	protected function _loadUser() {
		// 1. Read user identity from session
		$this->_user = $this->Session->read('Identity');
		if ($this->_user) {
			// we've got it!
			return;
		}
		// 2. Read remember me token
		$token = $this->Cookie->read('Identity.token');
		if (!$token) {
			// no token
			return;
		}
		// 3. Verify remember me token
		$this->controller->loadModel('RememberMe');
		$rm = $this->controlller->RememberMe->findByToken($token);
		if (!$rm) {
			// token not valid
			$this->Cookie->delete('Identity.token');
			return;
		}
		// 4. Attempt to load user from the token's corresponding user id
		$this->controller->loadModel('User');
		$this->_user = $this->controller->User->read($rm['RememberMe']['user_id']);
		if ($this->_user) {
			// Renew the remember me token
			$this->Cookie->write('Identity.token', $token, $this->rememberMeDuration, $encrypt=true);
		}
	}
	
	public function hashPassword($username, $password) {
		return sha1(Configure::read('Security.salt') . $username . $password);
	}
	
	public function mandate($redirectTo = '/user/login', $message = 'Please log in to continue.') {
		if ($this->user() === false) {
			// TODO: pass $message to login page
			$this->controller->redirect($redirectTo);
		}
	}
	
}
