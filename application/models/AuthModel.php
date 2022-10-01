<?php
namespace application\models;
use \PDO;

class AuthModel extends Model {
	function __construct($database) {
		parent::__construct($database);
	}

	/**
	 * 사용자 정보 조회
	 */
	public function getMemberInfo($id) {
		$sql = 'SELECT nMemberSeq, sName, sId, sPassword FROM tMember ';

		if($id != '') {
			$sql .= "WHERE sId='$id'";	
		}

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	/**
	 * 사용자 정보 입력
	 */
	public function addMemberInfo($params) {
		$sql = "INSERT INTO tMember(sName, sId, sPassword) VALUES (:name, :id, :password)";

		$stmt = $this->pdo->prepare($sql);

		// 쿼리 바인딩
		$stmt->bindParam(':name', $params['name']);
		$stmt->bindParam(':id', $params['id']);
		$stmt->bindParam(':password', $params['password']);

		$stmt->execute();
		$result = $stmt->rowCount();

		return $result;
	}
}
?>
