<?php
namespace application\models;
use \PDO;

class BoardModel extends Model {
	function __construct($database) {
		parent::__construct($database);
	}

	/**
	 * 게시글 카운트 조회
	 */
	public function getBoardListCount() {
		$sql = 'SELECT COUNT(*) AS total FROM tBoardList';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchColumn();

		return $result;
	}

	/**
	 * 게시글 리스트 조회
	 */
	public function getBoardList($offset, $limit) {
		$sql = 'SELECT tb.nListSeq, tb.sTitle, tb.sContent, tb.dtCreateDate, tb.emDisplayYN, tb.nHit, tm.sId FROM tBoardList tb ';

		if(isset($offset) && isset($limit)) {
			$sql .= 'LEFT OUTER JOIN dbMember.tMember tm ON tb.nMemberSeq=tm.nMemberSeq';
			$sql .= " WHERE tb.emDisplayYN='Y'";
			$sql .= ' ORDER BY nListSeq DESC';
			$sql .= " limit $offset, $limit";
		}

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}
}
?>