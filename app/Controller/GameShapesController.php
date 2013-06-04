<?php
App::uses('AppController', 'Controller');
/**
 * GameShapes Controller
 *
 * @property GameShape $GameShape
 */
class GameShapesController extends AppController {
	
	public function browse($catId) {
		if ($catId == 'user') {
			$category = 'User Submissions';
			$shapes = $this->GameShape->getUnapproved();
		}
		elseif ($catId == 'random') {
			$category = $this->getModel('GameShapeCategory')->getRandomlyFilled();
			$shapes = $this->GameShape->getRandomlyFilled();
		}
		else {
			$category = $this->getModel('GameShapeCategory')->findById($catId);
			$shapes = $this->GameShape->findByCategory($catId);
		}
		$this->set(compact('shapes','category'));
		$this->layout = 'panel';
	}
	
	public function search() {
		$category = 'Search Results';
		$shapes = $this->GameShape->search($_REQUEST['term']);
		$this->set(compact('shapes','category'));
		$this->layout = 'panel';
		$this->view = 'browse';
	}
	
	public function spec($shapeId) {
		if (preg_match('/^random(\d+)/', $shapeId, $match)) {
			$percent = $match[1];
			$spec = array(
				'random' => true,
				'ratio' => "0.$percent",
			);
			$name = "Randomly filled: $percent%";
		}			
		else {
			$shapeId = Cipher::usePreset('Id')->unobfuscate($shapeId);
			$this->GameShape->contain('GameRule');
			$shape = $this->GameShape->findById($shapeId);
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
			$name = $shape['GameShape']['name'];
			$this->GameShape->addHit($shapeId);
		}
		$this->set(compact('name','spec'));
		$this->setJsonVars(array('name','spec'));
	}
	
	public function submit() {
		$this->setTitle('Add New Shape');
		if (!empty($this->request->data)) {
			$this->request->data['GameShape']['is_user_submitted'] = '1';
			$this->GameShape->save($this->request->data);
		}
	}
	
	public function save() {
		$this->setTitle('Add New Shape');
		if (!empty($this->request->data)) {
			$this->_saveImage();
			$this->request->data['GameShape']['is_user_submitted'] = '1';
			$this->request->data['GameShape']['is_approved'] = '0';
			$this->GameShape->save($this->request->data);
			$this->redirect('saved_ok');
		}
		$gameShapeCategories = $this->getModel('GameShapeCategory')->find('list');
		$gameRules = $this->getModel('GameRule')->find('list', array(
			'order' => 'sort'
		));
		$startPositions = $this->GameShape->getStartPositions();
		$this->set(compact('gameShapeCategories','gameRules','startPositions'));
		$this->layout = 'modal';
	}
	
	public function saved_ok() {
		die('Saved ok.');
	}
	
	protected function _saveImage() {
		$dataURI = $this->request->data['GameShape']['image'];
		$base64 = preg_replace('/data:.+,/', '', $dataURI);
		$filename = md5(microtime(true) . uniqid()) . '.png';
		$path = APP . "/Writeable/shapes/$filename";
		$bytes = file_put_contents($path, base64_decode($base64));
		if ($bytes > 0) {
			$size = getimagesize($path);
			$this->request->data['GameShape']['image_width'] = $size[0];
			$this->request->data['GameShape']['image_height'] = $size[1];
			$this->request->data['GameShape']['image_path'] = "/shapes/$filename";
		}
		unset($this->request->data['GameShape']['image']);		
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
