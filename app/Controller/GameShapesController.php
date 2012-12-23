<?php
App::uses('AppController', 'Controller');
/**
 * GameShapes Controller
 *
 * @property GameShape $GameShape
 */
class GameShapesController extends AppController {
	
	public function browse($catId) {
		$category = $this->getModel('GameShapeCategory')->findById($catId);
		$shapes = $this->GameShape->findByCategory($catId);
		$this->set(compact('shapes','category'));
		$this->setJsonVars('shapes,category');
		$this->layout = 'panel';
	}
	
	public function spec($shapeId) {
		$this->GameShape->contain('GameRule');
		$shape = $this->GameShape->findById($shapeId);
		if ($shape) {
			$s = (object) $shape['GameShape'];
			$spec = array(
				'size' => array((int) $s->size_x, (int) $s->size_y),
				'pos' => $s->start_position,
				'rule' => $shape['GameRule']['rulestring'],
			);
			if ($s->start_block_size) {
				if ($s->start_block_size < 0) {
					$spec['zoom'] = -1 / $s->start_block_size;
				}
				else {
					$spec['zoom'] = $s->start_block_size;
				}
			}
			if ($s->start_speed !== null) {
				$spec['speed'] = (int) $s->start_speed;
			}
			$spec[$s->format] = $s->spec;
			$this->GameShape->addHit($shapeId);
		}
		else {
			$spec = array();
		}
		$this->set(compact('spec'));
		$this->setJsonVars('spec');
	}
	
	public function submit() {
		if (!empty($this->request->datay)) {
			$this->request->data['GameShape']['is_user_submitted'] = '1';
			$this->GameShape->save($this->request->data);
		}
	}
	
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->GameShape->recursive = 0;
		$this->set('gameShapes', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->GameShape->exists($id)) {
			throw new NotFoundException(__('Invalid game shape'));
		}
		$options = array('conditions' => array('GameShape.' . $this->GameShape->primaryKey => $id));
		$this->set('gameShape', $this->GameShape->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->GameShape->create();
			if ($this->GameShape->save($this->request->data)) {
				$this->Session->setFlash(__('The game shape has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The game shape could not be saved. Please, try again.'));
			}
		}
		$gameShapeCategories = $this->GameShape->GameShapeCategory->find('list');
		$gameRules = $this->GameShape->GameRule->find('list');
		$this->set(compact('gameShapeCategories', 'gameRules'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->GameShape->exists($id)) {
			throw new NotFoundException(__('Invalid game shape'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->GameShape->save($this->request->data)) {
				$this->Session->setFlash(__('The game shape has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The game shape could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('GameShape.' . $this->GameShape->primaryKey => $id));
			$this->request->data = $this->GameShape->find('first', $options);
		}
		$gameShapeCategories = $this->GameShape->GameShapeCategory->find('list');
		$gameRules = $this->GameShape->GameRule->find('list');
		$this->set(compact('gameShapeCategories', 'gameRules'));
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
		$this->GameShape->id = $id;
		if (!$this->GameShape->exists()) {
			throw new NotFoundException(__('Invalid game shape'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->GameShape->delete()) {
			$this->Session->setFlash(__('Game shape deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Game shape was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
