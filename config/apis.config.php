<?php

$uri = $_SERVER['REQUEST_URI'];
preg_match('/actions\/(.[^\/]*)\/(.[^\/?]*)/',$uri,$matches);

if ($matches):
	$options = Array(
		'CONSTANT_NAME' => get_field('acf_settings', 'option'),
	);
	
	foreach ($options as $constant => $option):
		$v = $option && !empty($option) ? $option : '';
		define($constant, $option);
	endforeach;
endif;