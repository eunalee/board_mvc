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
			$sql = 'SELECT COUNT(*) AS total 
					FROM tBoardList';

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
			$sql = 'SELECT 
						tbl.nListSeq, 
						tbl.nMemberSeq, 
						tbl.sTitle, 
						tbl.sContent, 
						tbl.dtCreateDate, 
						tbl.emDisplayYN, 
						tbl.nHit, 
						tm.sId 
					FROM tBoardList tbl';
			$sql .= ' LEFT OUTER JOIN dbMember.tMember tm';
			$sql .= ' ON tbl.nMemberSeq=tm.nMemberSeq';
			$sql .= " WHERE tbl.emDisplayYN='Y'";

			if(isset($params['listSeq']) && !empty($params['listSeq'])) {
				$sql .= ' AND tbl.nListSeq=:listSeq';
			}

			if(isset($params['offset']) && isset($params['limit'])) {
				$sql .= ' ORDER BY tbl.nListSeq DESC';
				$sql .= ' LIMIT :offset, :limit';
			}

			$stmt = $this->pdo->prepare($sql);

			// 쿼리 바인딩
			if(isset($params['listSeq']) && !empty($params['listSeq'])) {
				$stmt->bindParam(':listSeq', $params['listSeq'], PDO::PARAM_INT);
			}

			if(isset($params['offset']) && isset($params['limit'])) {
				$stmt->bindParam(':offset', $params['offset'], PDO::PARAM_INT);
				$stmt->bindParam(':limit', $params['limit'], PDO::PARAM_INT);
			}

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
			$sql = "INSERT INTO tBoardList (
						nMemberSeq, 
						sTitle, 
						sContent, 
						dtCreateDate, 
						dtUpdateDate, 
						emDisplayYN, 
						nHit
					) 
					VALUES (
						:memberSeq, 
						:title, 
						:content, 
						:createTime, 
						:updateTime, 
						:displayYN, 
						:hit
					)";

			$stmt = $this->pdo->prepare($sql);

			// 쿼리 바인딩
			$stmt->bindParam(':memberSeq', $params['memberSeq'], PDO::PARAM_INT);
			$stmt->bindParam(':title', $params['title']);
			$stmt->bindParam(':content', $params['content']);
			$stmt->bindParam(':createTime', $params['createTime']);
			$stmt->bindParam(':updateTime', $params['updateTime']);
			$stmt->bindParam(':displayYN', $params['displayYN']);
			$stmt->bindParam(':hit', $params['hit'], PDO::PARAM_INT);

			$stmt->execute();
			$result = $stmt->rowCount();

			return $result;
		} catch(\PDOException $e) {
			die($e->getMessage());
		}
	}

	/**
	 * 게시글 수정
	 */
	public function saveBoardList($params) {
		try {
			$sql = 'UPDATE tBoardList';

			if($params['type'] == 'count') {
				$sql .= ' SET nHit = nHit + 1';
			}
			else if($params['type'] == 'board') {
				if(!empty($params['title']) && $params['title'] != '') {
					$sql .= ' SET sTitle=:title';
				}

				if(!empty($params['content']) && $params['content'] != '') {
					$sql .= ', sContent=:content';
				}

				if(!empty($params['updateTime']) && $params['updateTime'] != '') {
					$sql .= ', dtUpdateDate=:updateTime';
				}
			}
			else if($params['type'] == 'delete') {
				if(!empty($params['displayYN']) && $params['displayYN'] != '') {
					$sql .= ' SET emDisplayYN=:displayYN';
				}
			}

			if(!empty($params['listSeq']) && !is_null($params['listSeq'])) {
				$sql .= ' WHERE nListSeq=:listSeq';
			}

			$stmt = $this->pdo->prepare($sql);

			// 쿼리 바인딩
			if($params['type'] == 'board') {
				$stmt->bindParam(':title', $params['title']);
				$stmt->bindParam(':content', $params['content']);
				$stmt->bindParam(':updateTime', $params['updateTime']);
			}
			else if($params['type'] == 'delete') {
				$stmt->bindParam(':displayYN', $params['displayYN']);
			}

			if(!empty($params['listSeq']) && !is_null($params['listSeq'])) {
				$stmt->bindParam(':listSeq', $params['listSeq'], PDO::PARAM_INT);
			}

			$stmt->execute();
			$result = $stmt->rowCount();

			return $result;
		} catch(\PDOException $e) {
			die($e->getMessage());
		}
	}

	/**
	 * 게시글 삭제
	 */
	public function removeBoardList($params) {
		try {
			$sql = 'DELETE FROM tBoardList';

			if(!empty($params['listSeq']) && !is_null($params['listSeq'])) {
				$sql .= ' WHERE nListSeq=:listSeq';
			}

			$stmt = $this->pdo->prepare($sql);

			// 쿼리 바인딩
			$stmt->bindParam(':listSeq', $params['listSeq']);

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
			$sql = "INSERT INTO tBoardComment (
						nListSeq, 
						nParentSeq, 
						nMemberSeq, 
						sContent, 
						nDepth, 
						nSort, 
						nGroup, 
						dtCreateDate, 
						dtUpdateDate, 
						emDisplayYN
					) 
					VALUES (
						:listSeq, 
						:parentSeq, 
						:memberSeq, 
						:content, 
						:depth, 
						:sort, 
						:group, 
						:createTime, 
						:updateTime, 
						:displayYN
					)";

			$stmt = $this->pdo->prepare($sql);

			// 쿼리 바인딩
			$stmt->bindParam(':listSeq', $params['listSeq'], PDO::PARAM_INT);
			$stmt->bindParam(':parentSeq', $params['parentSeq'], PDO::PARAM_INT);
			$stmt->bindParam(':memberSeq', $params['memberSeq'], PDO::PARAM_INT);
			$stmt->bindParam(':content', $params['content']);
			$stmt->bindParam(':depth', $params['depth'], PDO::PARAM_INT);
			$stmt->bindParam(':sort', $params['sort'], PDO::PARAM_INT);
			$stmt->bindParam(':group', $params['group'], PDO::PARAM_INT);
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
			$sql = 'SELECT 
						tbc.nCommentSeq, 
						tbc.nListSeq, 
						tbc.nParentSeq, 
						tbc.nMemberSeq, 
						tbc.sContent, 
						tbc.nDepth, 
						tbc.nSort, 
						tbc.nGroup, 
						tbc.dtCreateDate, 
						tbc.emDisplayYN, 
						tm.sId 
					FROM tBoardComment tbc';
			$sql .= ' LEFT OUTER JOIN dbMember.tMember tm';
			$sql .= ' ON tbc.nMemberSeq=tm.nMemberSeq';
			$sql .= " WHERE tbc.emDisplayYN='Y'";

			if(isset($params['listSeq']) && !empty($params['listSeq'])) {
				$sql .= ' AND tbc.nListSeq=:listSeq';
				$sql .= ' ORDER BY tbc.nSort ASC';
			}

			$stmt = $this->pdo->prepare($sql);

			// 쿼리 바인딩
			if(isset($params['listSeq']) && !empty($params['listSeq'])) {
				$stmt->bindParam(':listSeq', $params['listSeq'], PDO::PARAM_INT);
			}

			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			return $result;
		} catch(\PDOException $e) {
			die($e->getMessage());
		}
	}
}
?>