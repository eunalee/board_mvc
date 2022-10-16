<?php
namespace application\models;

use \PDO;
use application\dto\BoardDTO;
use application\dto\CommentDTO;

class BoardModel extends Model {
	public function __construct($database) {
		parent::__construct($database);
	}

	/**
	 * 게시글 카운트 조회
	 */
	public function selectBoardListCount() {
		$sql = 'SELECT COUNT(*) AS total FROM tBoardList';

		try {
			$stmt = $this->pdo->prepare($sql);
			if($stmt->execute() == false) {
				$error = $stmt->errorInfo();
				throw new \PDOException('게시글 카운트 조회 에러 [' . $error[0] . ']' . '[' . $error[1] . ']' . $error[2]);
			}
		} catch(\PDOException $e) {
			echo  $e->getMessage();
		}

		$result = $stmt->fetchColumn();

		return $result;
	}

	/**
	 * 게시글 리스트 조회
	 */
	public function selectBoardList(BoardDTO $boardDto) {
		$bindParam = array();

		$sql = "SELECT
						tbl.nListSeq,
						tbl.nMemberSeq,
						tbl.sTitle,
						tbl.sContent,
						tbl.dtCreateDate,
						tbl.emDisplayYN,
						tbl.nHit,
						tm.sId
				FROM 
						tBoardList tbl
							LEFT OUTER JOIN dbMember.tMember tm ON tbl.nMemberSeq = tm.nMemberSeq
				WHERE tbl.emDisplayYN='Y'";

		// 게시글 번호
		if(!empty($boardDto->getListSeq()) && $boardDto->getListSeq() > 0) {
			$sql .= ' AND tbl.nListSeq = :listSeq';
			$bindParam[':listSeq'] = $boardDto->getListSeq();
		}

		// 정렬
		$sql .= ' ORDER BY tbl.nListSeq DESC';

		// 페이징
		if(!empty($boardDto->getLimit()) && $boardDto->getLimit() > 0) {
			if(!empty($boardDto->getOffset()) && $boardDto->getOffset() > 0) {
				$sql .= ' LIMIT ' . $boardDto->getOffset() . ',' . $boardDto->getLimit();
			}
			else {
				$sql .= ' LIMIT ' . $boardDto->getLimit();
			}
		} else {
			throw new \InvalidArgumentException('limit 값은 필수항목입니다.');
		}

		try {
			$stmt = $this->pdo->prepare($sql);
			if($stmt->execute($bindParam) == false) {
				$error = $stmt->errorInfo();
				throw new \PDOException('게시글 리스트 조회 에러 [' . $error[0] . ']' . '[' . $error[1] . ']' . $error[2]);
			}
		} catch(\PDOException $e) {
			echo  $e->getMessage();
		}

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	/**
	 * 게시글 등록
	 */
	public function insertBoardList(BoardDTO $boardDto) {
		if(empty($boardDto->getMemberSeq()) && $boardDto->getMemberSeq() < 1) {
			throw new \InvalidArgumentException('회원 고유번호는 필수항목입니다.');
		}

		if(empty($boardDto->getTitle()) && $boardDto->getTitle() == '') {
			throw new \InvalidArgumentException('제목은 필수항목입니다.');
		}

		if(empty($boardDto->getContent()) && $boardDto->getContent() == '') {
			throw new \InvalidArgumentException('내용은 필수항목입니다.');
		}

		$bindParam = array();
		$sqlList = array();

		// 회원 고유번호
		if(!empty($boardDto->getMemberSeq()) && $boardDto->getMemberSeq() > 0) {
			$sqlList[] = ' nMemberSeq = :memberSeq';
			$bindParam[':memberSeq'] = $boardDto->getMemberSeq();
		}

		// 제목
		if(!empty($boardDto->getTitle()) && $boardDto->getTitle() != '') {
			$sqlList[] = ' sTitle = :title';
			$bindParam[':title'] = $boardDto->getTitle();
		}

		// 내용
		if(!empty($boardDto->getContent()) && $boardDto->getContent() != '') {
			$sqlList[] = ' sContent = :content';
			$bindParam[':content'] = $boardDto->getContent();
		}

		// 작성일자
		if(!empty($boardDto->getCreateDate()) && $boardDto->getCreateDate() != '') {
			$sqlList[] = ' dtCreateDate = :createDate';
			$bindParam[':createDate'] = $boardDto->getCreateDate();
		}

		// 수정일자
		if(!empty($boardDto->getUpdateDate()) && $boardDto->getUpdateDate() != '') {
			$sqlList[] = ' dtUpdateDate = :updateDate';
			$bindParam[':updateDate'] = $boardDto->getUpdateDate();
		}

		// 노출여부
		if(!empty($boardDto->getDisplayYN()) && $boardDto->getDisplayYN() != '') {
			$sqlList[] = ' emDisplayYN = :displayYN';
			$bindParam[':displayYN'] = $boardDto->getDisplayYN();
		}

		// 조회수
		if($boardDto->getHit() >= 0) {
			$sqlList[] = ' nHit = :hit';
			$bindParam[':hit'] = $boardDto->getHit();
		}

		$sql = 'INSERT INTO tBoardList SET ';
		$sql .= implode(',', $sqlList);

		try {
			$stmt = $this->pdo->prepare($sql);
			if($stmt->execute($bindParam) == false) {
				$error = $stmt->errorInfo();
				throw new \PDOException('게시글 등록 에러 [' . $error[0] . ']' . '[' . $error[1] . ']' . $error[2]);
			}
		} catch(\PDOException $e) {
			echo  $e->getMessage();
		}

		return true;
	}

	/**
	 * 게시글 조회 수 증가
	 */
	public function updateHitBoardList(BoardDTO $boardDto) {
		if(empty($boardDto->getListSeq()) && $boardDto->getListSeq() < 1) {
			throw new \InvalidArgumentException('게시글 고유키는 필수항목입니다.');
		}

		$bindParam = array();

		$sql = 'UPDATE tBoardList SET
				nHit = nHit + 1
				WHERE nListSeq = :listSeq';

		$bindParam[':listSeq'] = $boardDto->getListSeq();

		try {
			$stmt = $this->pdo->prepare($sql);
			if($stmt->execute($bindParam) == false) {
				$error = $stmt->errorInfo();
				throw new \PDOException('게시글 조회 수 증가 에러 [' . $error[0] . ']' . '[' . $error[1] . ']' . $error[2]);
			}
		} catch(\PDOException $e) {
			echo  $e->getMessage();
		}

		return true;
	}

	/**
	 * 게시글 수정
	 */
	public function updateBoardList(BoardDTO $boardDto) {
		if(empty($boardDto->getListSeq()) && $boardDto->getListSeq() < 1) {
			throw new \InvalidArgumentException('게시글 고유키는 필수항목입니다.');
		}

		if(empty($boardDto->getTitle()) && $boardDto->getTitle() == '') {
			throw new \InvalidArgumentException('제목은 필수항목입니다.');
		}

		if(empty($boardDto->getContent()) && $boardDto->getContent() == '') {
			throw new \InvalidArgumentException('내용은 필수항목입니다.');
		}

		$bindParam = array();
		$sqlList = array();

		// 제목
		if(!empty($boardDto->getTitle()) && $boardDto->getTitle() != '') {
			$sqlList[] = ' sTitle = :title';
			$bindParam[':title'] = $boardDto->getTitle();
		}

		// 내용
		if(!empty($boardDto->getContent()) && $boardDto->getContent() != '') {
			$sqlList[] = ' sContent = :content';
			$bindParam[':content'] = $boardDto->getContent();
		}

		// 수정일자
		if(!empty($boardDto->getUpdateDate()) && $boardDto->getUpdateDate() != '') {
			$sqlList[] = ' dtUpdateDate = :updateDate';
			$bindParam[':updateDate'] = $boardDto->getUpdateDate();
		}

		$sql = 'UPDATE tBoardList SET ';
		$sql .= implode(',', $sqlList);

		// WHERE 조건
		$sql .= ' WHERE nListSeq = :listSeq';
		$bindParam[':listSeq'] = $boardDto->getListSeq();

		try {
			$stmt = $this->pdo->prepare($sql);
			if($stmt->execute($bindParam) == false) {
				$error = $stmt->errorInfo();
				throw new \PDOException('게시글 수정 에러 [' . $error[0] . ']' . '[' . $error[1] . ']' . $error[2]);
			}
		} catch(\PDOException $e) {
			echo  $e->getMessage();
		}

		return true;
	}

	/**
	 * 게시글 삭제
	 */
	public function deleteBoardList(BoardDTO $boardDto) {
		if(empty($boardDto->getListSeq()) && $boardDto->getListSeq() < 1) {
			throw new \InvalidArgumentException('게시글 고유키는 필수항목입니다.');
		}

		$bindParam = array();

		$sql = 'DELETE FROM tBoardList
				WHERE nListSeq = :listSeq
				LIMIT 1';

		$bindParam[':listSeq'] = $boardDto->getListSeq();

		try {
			$stmt = $this->pdo->prepare($sql);
			if($stmt->execute($bindParam) == false) {
				$error = $stmt->errorInfo();
				throw new \PDOException('게시글 삭제 에러 [' . $error[0] . ']' . '[' . $error[1] . ']' . $error[2]);
			}
		} catch(\PDOException $e) {
			echo  $e->getMessage();
		}

		return true;
	}

	/**
	 * 댓글 등록
	 */
	public function insertComment(CommentDTO $commentDto) {
		if(empty($commentDto->getListSeq()) && $commentDto->getListSeq() < 1) {
			throw new \InvalidArgumentException('게시글 고유키는 필수항목입니다.');
		}

		if(empty($commentDto->getMemberSeq()) && $commentDto->getMemberSeq() < 1) {
			throw new \InvalidArgumentException('회원 고유번호는 필수항목입니다.');
		}

		if(empty($commentDto->getContent()) && $commentDto->getContent() == '') {
			throw new \InvalidArgumentException('내용은 필수항목입니다.');
		}

		$bindParam = array();
		$sqlList = array();

		// 게시글 고유번호
		if(!empty($commentDto->getListSeq()) && $commentDto->getListSeq() > 0) {
			$sqlList[] = ' nListSeq = :listSeq';
			$bindParam[':listSeq'] = $commentDto->getListSeq();
		}

		// 회원 고유번호
		if(!empty($commentDto->getMemberSeq()) && $commentDto->getMemberSeq() > 0) {
			$sqlList[] = ' nMemberSeq = :memberSeq';
			$bindParam[':memberSeq'] = $commentDto->getMemberSeq();
		}

		// 내용
		if(!empty($commentDto->getContent()) && $commentDto->getContent() != '') {
			$sqlList[] = ' sContent = :content';
			$bindParam[':content'] = $commentDto->getContent();
		}

		// 부모 댓글 고유번호
		if($commentDto->getParentSeq() >= 0) {
			$sqlList[] = ' nParentSeq = :parentSeq';
			$bindParam[':parentSeq'] = $commentDto->getParentSeq();
		}

		// depth
		if($commentDto->getDepth() >= 0) {
			$sqlList[] = ' nDepth = :depth';
			$bindParam[':depth'] = $commentDto->getDepth();
		}

		// sort
		if($commentDto->getSort() >= 0) {
			$sqlList[] = ' nSort = :sort';
			$bindParam[':sort'] = $commentDto->getSort();
		}

		// group
		if($commentDto->getGroup() >= 0) {
			$sqlList[] = ' nGroup = :group';
			$bindParam[':group'] = $commentDto->getGroup();
		}

		// 작성일자
		if(!empty($commentDto->getCreateDate()) && $commentDto->getCreateDate() != '') {
			$sqlList[] = ' dtCreateDate = :createDate';
			$bindParam[':createDate'] = $commentDto->getCreateDate();
		}

		// 수정일자
		if(!empty($commentDto->getUpdateDate()) && $commentDto->getUpdateDate() != '') {
			$sqlList[] = ' dtUpdateDate = :updateDate';
			$bindParam[':updateDate'] = $commentDto->getUpdateDate();
		}

		// 노출여부
		if(!empty($commentDto->getDisplayYN()) && $commentDto->getDisplayYN() != '') {
			$sqlList[] = ' emDisplayYN = :displayYN';
			$bindParam[':displayYN'] = $commentDto->getDisplayYN();
		}

		$sql = 'INSERT INTO tBoardComment SET ';
		$sql .= implode(',', $sqlList);

		try {
			$stmt = $this->pdo->prepare($sql);
			if($stmt->execute($bindParam) == false) {
				$error = $stmt->errorInfo();
				throw new \PDOException('댓글 등록 에러 [' . $error[0] . ']' . '[' . $error[1] . ']' . $error[2]);
			}
		} catch(\PDOException $e) {
			echo  $e->getMessage();
		}

		return true;
	}

	/**
	 * 댓글 조회
	 */
	public function selectCommentList(CommentDTO $commentDto) {
		$bindParam = array();

		$sql = "SELECT
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
					FROM
						tBoardComment tbc
							LEFT OUTER JOIN dbMember.tMember tm ON tbc.nMemberSeq = tm.nMemberSeq
					WHERE tbc.emDisplayYN='Y'";

		// 게시글 번호
		if(!empty($commentDto->getListSeq()) && $commentDto->getListSeq() > 0) {
			$sql .= ' AND tbc.nListSeq = :listSeq';
			$bindParam[':listSeq'] = $commentDto->getListSeq();
		}

		// 정렬
		$sql .= '  ORDER BY tbc.nSort ASC';

		try {
			$stmt = $this->pdo->prepare($sql);
			if($stmt->execute($bindParam) == false) {
				$error = $stmt->errorInfo();
				throw new \PDOException('댓글 조회 에러 [' . $error[0] . ']' . '[' . $error[1] . ']' . $error[2]);
			}
		} catch(\PDOException $e) {
			echo  $e->getMessage();
		}

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}
}
?>