<?php
App::uses('GameRule', 'Model');

/**
 * GameRule Test Case
 *
 */
class GameRuleTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.game_rule',
		'app.game_shape'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->GameRule = ClassRegistry::init('GameRule');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->GameRule);

		parent::tearDown();
	}

}
