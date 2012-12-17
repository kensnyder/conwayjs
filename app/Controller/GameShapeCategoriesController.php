<?php
App::uses('AppController', 'Controller');
/**
 * GameShapeCategories Controller
 *
 * @property GameShapeCategory $GameShapeCategory
 */
class GameShapeCategoriesController extends AppController {

	public function browse() {
		$categories = $this->GameShapeCategory->find('all');
		$this->set(compact('categories'));
		$this->setJsonVars('categories');
	}
	
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->GameShapeCategory->recursive = 0;
		$this->set('gameShapeCategories', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->GameShapeCategory->exists($id)) {
			throw new NotFoundException(__('Invalid game shape category'));
		}
		$options = array('conditions' => array('GameShapeCategory.' . $this->GameShapeCategory->primaryKey => $id));
		$this->set('gameShapeCategory', $this->GameShapeCategory->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->GameShapeCategory->create();
			if ($this->GameShapeCategory->save($this->request->data)) {
				$this->Session->setFlash(__('The game shape category has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The game shape category could not be saved. Please, try again.'));
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
		if (!$this->GameShapeCategory->exists($id)) {
			throw new NotFoundException(__('Invalid game shape category'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->GameShapeCategory->save($this->request->data)) {
				$this->Session->setFlash(__('The game shape category has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The game shape category could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('GameShapeCategory.' . $this->GameShapeCategory->primaryKey => $id));
			$this->request->data = $this->GameShapeCategory->find('first', $options);
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
		$this->GameShapeCategory->id = $id;
		if (!$this->GameShapeCategory->exists()) {
			throw new NotFoundException(__('Invalid game shape category'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->GameShapeCategory->delete()) {
			$this->Session->setFlash(__('Game shape category deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Game shape category was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
