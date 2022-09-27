<?php
namespace application\controllers;

class Controller {
	function __construct($controller, $method) {
		session_start();

		$this->$method();
	}
}
?>