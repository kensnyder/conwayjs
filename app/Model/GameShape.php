<?php
App::uses('AppModel', 'Model');
/**
 * GameShape Model
 *
 * @property GameShapeCategory $GameShapeCategory
 * @property GameRule $GameRule
 * @property User $User
 */
class GameShape extends AppModel {
	
	public function beforeSave($options = array()) {
		if (isset($this->data['GameShape']['rulestring']) && !isset($this->data['GameShape']['game_rule_id'])) {
			$rule = $this->getModel('GameRule')->findByRulestring($this->data['GameShape']['rulestring']);
			if ($rule) {
				$ruleId = $rule['GameRule']['id'];
			}
			else {
				$this->GameRule->create();
				$this->GameRule->save(array(
					'GameRule' => array(
						'name' => $this->data['GameShape']['rulestring'],
						'description' => '',
						'rulestring' => $this->data['GameShape']['rulestring'],
						'type' => null,
						'link' => '',
						'sort' => '1000',
						'is_custom' => '1',
					)
				));
				$ruleId = $this->GameRule->id;
			}
			$this->data['GameShape']['game_rule_id'] = $ruleId;
			unset($this->data['GameShape']['rulestring']);
		}
		return true;
	}
	
	public function findByCategory($catId) {
		$shapes = $this->find('all', array(
			'fields' => array(
				'id','name','desc','comments','link','found_year','found_by','image_path','image_width','image_height','period','created_by'
			),
			'conditions' => array(
				'spec !=' => '',
				'game_shape_category_id' => $catId,
				'is_approved' => true,
			),
			'contain' => array(
				'GameRule' => array('id','name','rulestring','link'),
				'User' => array('id','name','is_active')
			),
			'order' => array('GameShape.name')
		));
		return $shapes;
	}
	
	public function addHit($shapeId) {
		$this->updateAll(
			array('GameShape.hits' => 'GameShape.hits + 1'),
			array('GameShape.id' => $shapeId)
		);		
	}
	
	public function getStartPositions() {
		return array(
			'top left',
			'top center',
			'top right',
			'middle left',
			'middle center',
			'middle right',
			'bottom left',
			'bottom center',
			'bottom right',
		);
	}
	
	public function getRandomlyFilled() {
		$shapes = array();
		for ($i = 10; $i <= 90; $i += 10) {
			$shapes[] = array('GameShape' => array(
				'id' => 'random' . $i,
				'name' => "Randomly filled: $i%",
				'desc' => '',
				'comments' => '',
				'link' => '',
				'found_year' => '',
				'found_by' => '',
				'image_path' => '',
				'image_width' => '',
				'image_height' => '',
				'period' => '',
				'created_by' => '',				
			));
		}
		return $shapes;
	}
	
	public function getUnapproved() {
		$shapes = $this->find('all', array(
			'conditions' => array(
				'is_approved' => false,
			)
		));
		return $shapes;		
	}
	
	public function search($term) {
		$shapes = $this->find('all', array(
			'conditions' => array(
				'OR' => array(
					'name LIKE' => "%$term%", 
					'desc LIKE' => "%$term%", 
					'comments LIKE' => "%$term%", 
					'link LIKE' => "%$term%", 
					'found_by LIKE' => "%$term%", 
				)
			)
		));
		return $shapes;
	}

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'spec' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'GameShapeCategory' => array(
			'className' => 'GameShapeCategory',
			'foreignKey' => 'game_shape_category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'GameRule' => array(
			'className' => 'GameRule',
			'foreignKey' => 'game_rule_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
