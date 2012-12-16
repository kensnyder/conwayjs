<?php
App::uses('GameShape', 'Model');

/**
 * GameShape Test Case
 *
 */
class GameShapeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.game_shape',
		'app.game_shape_category',
		'app.game_rule'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->GameShape = ClassRegistry::init('GameShape');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->GameShape);

		parent::tearDown();
	}

}
