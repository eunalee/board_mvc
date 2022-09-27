<?php
namespace application\models;
use \PDO;

class Model {
	public $pdo;

	function __construct($database) {
		require_once $_SERVER['DOCUMENT_ROOT']. '/board_mvc/application/libraries/DBConfig.php';
		$dsn = 'mysql:host=' . $DB_CONFIG[$database]['hostname'] . ';dbname=' . $DB_CONFIG[$database]['database'];
		try {
			$this->pdo = new PDO($dsn, $DB_CONFIG[$database]['username'], $DB_CONFIG[$database]['password']);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(\PDOException $e) {
			echo '데이터베이스에 접속할 수 없습니다. ' . $e->getMessage();
		}
	}
}
?>