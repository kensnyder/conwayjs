<?php

$js .= file_get_contents(__DIR__ . '/js/Game/Game.js');
$js .= file_get_contents(__DIR__ . '/js/Game/GameControls.js');
$js .= file_get_contents(__DIR__ . '/js/Game/GameRenderer.js');
$js .= file_get_contents(__DIR__ . '/js/Game/GameRules.js');
$js .= file_get_contents(__DIR__ . '/js/Game/GameShapes.js');
$js .= file_get_contents(__DIR__ . '/js/Modal.js');
$js .= file_get_contents(__DIR__ . '/js/WindowMessager.js');
$js .= file_get_contents(__DIR__ . '/js/Fullscreen.js');

header('Content-type: text/javascript; charset=utf-8');
echo $js;

