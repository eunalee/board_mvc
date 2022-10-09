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
		try {
			$sql = 'SELECT COUNT(*) AS total FROM tBoardList';
			$stmt = $this->pdo->prepare($sql);

			$stmt->execute();
			$result = $stmt->fetchColumn();

			return $result;
		} catch(\PDOException $e) {
			die($e->getMessage());
		}
	}

	/**
	 * 게시글 리스트 조회
	 */
	public function getBoardList($params) {
		try {
			$sql = 'SELECT tbl.nListSeq, tbl.nMemberSeq, tbl.sTitle, tbl.sContent, tbl.dtCreateDate, tbl.emDisplayYN, tbl.nHit, tm.sId FROM tBoardList tbl';
			$sql .= ' LEFT OUTER JOIN dbMember.tMember tm ON tbl.nMemberSeq=tm.nMemberSeq';
			$sql .= " WHERE tbl.emDisplayYN='Y'";

			if(isset($params['listSeq']) && !empty($params['listSeq'])) {
				$sql .= ' AND tbl.nListSeq=' . $params['listSeq'];
			}

			if(isset($params['offset']) && isset($params['limit'])) {
				$sql .= ' ORDER BY tbl.nListSeq DESC';
				$sql .= ' limit ' . $params['offset'] . ',' . $params['limit'];
			}

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			return $result;
		} catch(\PDOException $e) {
			die($e->getMessage());
		}
	}

	/**
	 * 게시글 등록
	 */
	public function addBoardList($params) {
		try {
			$sql = "INSERT INTO tBoardList (nMemberSeq, sTitle, sContent, dtCreateDate, dtUpdateDate, emDisplayYN, nHit) VALUES (:memberSeq, :title, :content, :createTime, :updateTime, :displayYN, :hit)";

			$stmt = $this->pdo->prepare($sql);

			// 쿼리 바인딩
			$stmt->bindParam(':memberSeq', $params['memberSeq']);
			$stmt->bindParam(':title', $params['title']);
			$stmt->bindParam(':content', $params['content']);
			$stmt->bindParam(':createTime', $params['createTime']);
			$stmt->bindParam(':updateTime', $params['updateTime']);
			$stmt->bindParam(':displayYN', $params['displayYN']);
			$stmt->bindParam(':hit', $params['hit']);

			$stmt->execute();
			$result = $stmt->rowCount();

			return $result;
		} catch(\PDOException $e) {
			die($e->getMessage());
		}
	}

	/**
	 * 게시글 업데이트
	 */
	public function saveBoardList($params) {
		try {
			$sql = 'UPDATE tBoardList';

			if(!empty($params['listSeq']) && !is_null($params['listSeq'])) {
				$sql .= ' SET nHit = nHit + 1';
				$sql .= ' WHERE nListSeq=' . $params['listSeq'];
			}

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute();
			$result = $stmt->rowCount();

			return $result;
		} catch(\PDOException $e) {
			die($e->getMessage());
		}
	}

	/**
	 * 댓글 등록
	 */
	public function addComment($params) {
		try {
			$sql = "INSERT INTO tBoardComment (nListSeq, nParentSeq, nMemberSeq, sContent, nDepth, nSort, nGroup, dtCreateDate, dtUpdateDate, emDisplayYN) VALUES (:listSeq, :parentSeq, :memberSeq, :content, :depth, :sort, :group, :createTime, :updateTime, :displayYN)";

			$stmt = $this->pdo->prepare($sql);

			// 쿼리 바인딩
			$stmt->bindParam(':listSeq', $params['listSeq']);
			$stmt->bindParam(':parentSeq', $params['parentSeq']);
			$stmt->bindParam(':memberSeq', $params['memberSeq']);
			$stmt->bindParam(':content', $params['content']);
			$stmt->bindParam(':depth', $params['depth']);
			$stmt->bindParam(':sort', $params['sort']);
			$stmt->bindParam(':group', $params['group']);
			$stmt->bindParam(':createTime', $params['createTime']);
			$stmt->bindParam(':updateTime', $params['updateTime']);
			$stmt->bindParam(':displayYN', $params['displayYN']);

			$stmt->execute();
			$result = $stmt->rowCount();
			return $result;
		} catch(\PDOException $e) {
			die($e->getMessage());
		}
	}

	/**
	 * 댓글 조회
	 */
	public function getCommentList($params) {
		try {
			$sql = 'SELECT tbc.nCommentSeq, tbc.nListSeq, tbc.nParentSeq, tbc.nMemberSeq, tbc.sContent, tbc.nDepth, tbc.nSort, tbc.nGroup, tbc.dtCreateDate, tbc.emDisplayYN, tm.sId FROM tBoardComment tbc';
			$sql .= ' LEFT OUTER JOIN dbMember.tMember tm ON tbc.nMemberSeq=tm.nMemberSeq';
			$sql .= " WHERE tbc.emDisplayYN='Y'";
			

			if(isset($params['listSeq']) && !empty($params['listSeq'])) {
				$sql .= ' AND tbc.nListSeq=' . $params['listSeq'];
				$sql .= ' ORDER BY tbc.nSort ASC';
			}

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			return $result;
		} catch(\PDOException $e) {
			die($e->getMessage());
		}
	}
}
?>