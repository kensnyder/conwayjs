<?php

$files = array('Game','GameControls','GameRenderer','GameRules','GameShapes');

$js = '';
foreach ($files as $file) {
	$js .= file_get_contents("/Users/ksnyder/Sites/retreat/assets/js/$file.js") . "\n\n";
}
$js .= file_get_contents(__DIR__ . '/js/GameControls.js');
$js .= file_get_contents(__DIR__ . '/js/Modal.js');
$js .= file_get_contents(__DIR__ . '/js/WindowMessager.js');

header('Content-type: text/javascript; charset=utf-8');
echo $js;

