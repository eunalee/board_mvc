<?php
namespace application\models;

use \PDO;
use application\dto\AuthDTO;

class AuthModel extends Model {
	public function __construct($database) {
		parent::__construct($database);
	}

	/**
	 * 사용자 정보 조회
	 */
	public function selectMemberInfo(AuthDTO $authDto) {
		if(empty($authDto->getId()) && $authDto->getId() == '') {
			throw new \InvalidArgumentException('아이디는 필수항목입니다.');
		}

		$bindParam = array();

		$sql = 'SELECT
					nMemberSeq,
					sName,
					sId,
					sPassword
				FROM
					tMember
				WHERE sId = :id';

		$bindParam[':id'] = $authDto->getId();

		try {
			$stmt = $this->pdo->prepare($sql);
			if($stmt->execute($bindParam) == false) {
				$error = $stmt->errorInfo();
				throw new \PDOException('사용자 정보 조회 에러 [' . $error[0] . ']' . '[' . $error[1] . ']' . $error[2]);
			}
		} catch(\PDOException $e) {
			echo  $e->getMessage();
		}

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	/**
	 * 사용자 정보 입력
	 */
	public function insertMemberInfo(AuthDTO $authDto) {
		if(empty($authDto->getId()) && $authDto->getId() == '') {
			throw new \InvalidArgumentException('아이디는 필수항목입니다.');
		}

		if(empty($authDto->getPassword()) && $authDto->getPassword() == '') {
			throw new \InvalidArgumentException('비밀번호는 필수항목입니다.');
		}

		if(empty($authDto->getName()) && $authDto->getName() == '') {
			throw new \InvalidArgumentException('이름은 필수항목입니다.');
		}

		$bindParam = array();
		$sqlList = array();

		// 아이디
		if(!empty($authDto->getId()) && $authDto->getId() != '') {
			$sqlList[] = ' sId = :id';
			$bindParam[':id'] = $authDto->getId();
		}

		// 비밀번호
		if(!empty($authDto->getPassword()) && $authDto->getPassword() != '') {
			$sqlList[] = ' sPassword = :password';
			$bindParam[':password'] = $authDto->getPassword();
		}

		// 이름
		if(!empty($authDto->getName()) && $authDto->getName() != '') {
			$sqlList[] = ' sName = :name';
			$bindParam[':id'] = $authDto->getName();
		}

		$sql = 'INSERT INTO tMember SET ';
		$sql .= implode(',', $sqlList);
	
		try {
			$stmt = $this->pdo->prepare($sql);
			if($stmt->execute($bindParam) == false) {
				$error = $stmt->errorInfo();
				throw new \PDOException('사용자 정보 입력 에러 [' . $error[0] . ']' . '[' . $error[1] . ']' . $error[2]);
			}
		} catch(\PDOException $e) {
			echo  $e->getMessage();
		}

		return true;
	}
}
?>