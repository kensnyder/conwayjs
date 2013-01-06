<?php

$files = array(
	__DIR__ . '/css/fonts.css',
	'/Users/ksnyder/Sites/retreat/assets/css/main.css', 
	__DIR__ . '/css/main.css',
	__DIR__ . '/css/game.css',
	__DIR__ . '/css/Modal.css',
);

$css = '';
foreach ($files as $file) {
	$css .= file_get_contents($file) . "\n\n";
}

header('Content-type: text/css; charset=utf-8');
echo $css;

