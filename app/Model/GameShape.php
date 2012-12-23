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
	
	public function findByCategory($catId) {
		$shapes = $this->find('all', array(
			'fields' => array(
				'id','name','desc','comments','link','found_year','found_by','image_path','image_width','image_height','lifespan','created_by'
			),
			'conditions' => array(
				'size_x > 0',
				'game_shape_category_id' => $catId,
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
