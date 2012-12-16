<?php
/**
 * GameShapeFixture
 *
 */
class GameShapeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'game_shape_category_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 60, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'desc' => array('type' => 'string', 'null' => true, 'length' => 300, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'comments' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 300, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'link' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'found_year' => array('type' => 'integer', 'null' => true, 'default' => null),
		'found_by' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'image_path' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'image_width' => array('type' => 'integer', 'null' => true, 'default' => null),
		'image_height' => array('type' => 'integer', 'null' => true, 'default' => null),
		'size_x' => array('type' => 'integer', 'null' => true, 'default' => null),
		'size_y' => array('type' => 'integer', 'null' => true, 'default' => null),
		'rulestring' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 21, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'game_rule_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'spec' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lifespan' => array('type' => 'integer', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'game_shape_category_id' => array('column' => 'game_shape_category_id', 'unique' => 0),
			'name' => array('column' => 'name', 'unique' => 0),
			'game_rule_id' => array('column' => 'game_rule_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);;

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'game_shape_category_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'desc' => 'Lorem ipsum dolor sit amet',
			'comments' => 'Lorem ipsum dolor sit amet',
			'link' => 'Lorem ipsum dolor sit amet',
			'found_year' => 1,
			'found_by' => 'Lorem ipsum dolor sit amet',
			'image_path' => 'Lorem ipsum dolor sit amet',
			'image_width' => 1,
			'image_height' => 1,
			'size_x' => 1,
			'size_y' => 1,
			'rulestring' => 'Lorem ipsum dolor s',
			'game_rule_id' => 1,
			'spec' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'lifespan' => 1,
			'created' => '2012-12-16 03:59:35'
		),
	);

}
