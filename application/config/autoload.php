<?php
spl_autoload_register(function($path) {
	$path = str_replace('\\', '/', $path);
	$paths = explode('/', $path);

	$className = 'libraries';
	if(preg_match('/models/', strtolower($paths[1]))) {
		$className = 'models';
	} else if(preg_match('/controllers/', strtolower($paths[1]))) {
		$className = 'controllers';
	}

	$loadPath = $paths[0] . '/' . $className . '/' . $paths[2] . '.php';
	if(!file_exists($loadPath)) {
		echo "$loadPath file not found.";
		exit;
	}

	require_once $loadPath;
});
?>