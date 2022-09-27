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
		$sql = "SELECT nMemberSeq, sName, sId, sPassword FROM tMember ";

		if($id != '') {
			$sql .= "WHERE sId='$id'";	
		}

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}
}
?>
