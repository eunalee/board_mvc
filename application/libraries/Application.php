<?php
namespace application\libraries;

class Application {
	private $controller;
	private $action;

	function __construct() {
		$url = '';
		if(isset($_GET['url'])) {
			$url = rtrim($_GET['url'], '/');
			$url = filter_var($url, FILTER_SANITIZE_URL);
		}

		$params = explode('/', $url);
		$params['controller'] = isset($params[0]) && $params[0] != '' ? $params[0] : 'Home';
		$params['method'] = isset($params[1]) && $params[1] != '' ? $params[1] : 'index';

		if(!file_exists('application/controllers/' . $params['controller'] . 'Controller.php')) {
			echo $params['controller'] . 'Controller 없음';
			exit;
		}

		$controller = '\application\controllers\\' . $params['controller'] . 'Controller';
		new $controller($params['controller'], $params['method']);
	}
}
?>