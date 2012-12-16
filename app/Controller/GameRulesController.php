<?php
App::uses('AppController', 'Controller');
/**
 * GameRules Controller
 *
 * @property GameRule $GameRule
 */
class GameRulesController extends AppController {

	public function browse() {
		$rules = $this->GameRule->find('all', array(
			'order' => array('sort')
		));
		$this->set(compact('rules'));
		$this->setJsonVars('rules');
	}
	
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->GameRule->recursive = 0;
		$this->set('gameRules', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->GameRule->exists($id)) {
			throw new NotFoundException(__('Invalid game rule'));
		}
		$options = array('conditions' => array('GameRule.' . $this->GameRule->primaryKey => $id));
		$this->set('gameRule', $this->GameRule->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->GameRule->create();
			if ($this->GameRule->save($this->request->data)) {
				$this->Session->setFlash(__('The game rule has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The game rule could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->GameRule->exists($id)) {
			throw new NotFoundException(__('Invalid game rule'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->GameRule->save($this->request->data)) {
				$this->Session->setFlash(__('The game rule has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The game rule could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('GameRule.' . $this->GameRule->primaryKey => $id));
			$this->request->data = $this->GameRule->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->GameRule->id = $id;
		if (!$this->GameRule->exists()) {
			throw new NotFoundException(__('Invalid game rule'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->GameRule->delete()) {
			$this->Session->setFlash(__('Game rule deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Game rule was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
