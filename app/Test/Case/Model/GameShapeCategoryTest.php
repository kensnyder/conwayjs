<?php
App::uses('GameShapeCategory', 'Model');

/**
 * GameShapeCategory Test Case
 *
 */
class GameShapeCategoryTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.game_shape_category',
		'app.game_shape'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->GameShapeCategory = ClassRegistry::init('GameShapeCategory');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->GameShapeCategory);

		parent::tearDown();
	}

}
