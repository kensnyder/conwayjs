<?php
App::uses('AppModel', 'Model');
/**
 * GameShapeCategory Model
 *
 * @property GameShape $GameShape
 */
class GameShapeCategory extends AppModel {

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
		'description' => array(
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
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'GameShape' => array(
			'className' => 'GameShape',
			'foreignKey' => 'game_shape_category_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	public function getCategories() {
		$categories = array_merge(
			array($this->getUserSubmitted()),
			array($this->getRandomlyFilled()),
			$this->find('all')
		);
		return $categories;	
	}
	
	public function getUserSubmitted() {
		return array(
			'GameShapeCategory' => array(
				'id' => 'user',
				'name' => 'User Submissions',
				'description' => 'Submissions from users of this site that have not yet been approved',
				'link' => false,
			)
		);
	}
	
	public function getRandomlyFilled() {
		return array(
			'GameShapeCategory' => array(
				'id' => 'random',
				'name' => 'Randomly Filled Board',
				'description' => 'Randomly fill the board with points',
				'link' => false,
			)
		);
	}

}
