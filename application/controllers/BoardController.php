<?php
namespace application\controllers;

use application\libraries\Layout;
use application\models\BoardModel;
use application\dto\BoardDTO;
use application\dto\CommentDTO;

/**
 * 게시글/댓글 관련
 * @author eunalee
 */
class BoardController extends Controller {
	private $model;
	private $layout;

	public function __construct($controller, $method) {
		$this->model = new BoardModel('dbBoard');
		$this->layout = new Layout();

		parent::__construct($controller, $method);
	}

	/**
	 * 게시글 작성폼
	 */
	public function writeListForm() {
		// 로그인 여부 체크하여 로그인 폼 or 글쓰기 폼 이동
		if(!isset($_SESSION['memberSeq'])) {
			$url = '/board_mvc/auth/loginForm?loginUrl=' . urlencode('/board_mvc/board/writeListForm');
			echo '<script>location.href="' . $url . '";</script>';
		}
		else {
			$topData = array();
			$topData['title'] = '게시판 글쓰기';

			$data = array();
			$data['memberSeq'] = $_SESSION['memberSeq'];
			$data['listSeq'] = (isset($_GET['listSeq']) && $_GET['listSeq'] != '') ? $_GET['listSeq'] : 0;
			$data['mode'] = (isset($_GET['mode']) && $_GET['mode'] != '') ? $_GET['mode'] : 'insert';

			if($data['mode'] == 'update') {
				// 게시글 조회
				$boardDto = new BoardDTO();
				$boardDto->setListSeq($data['listSeq']);
				$boardDto->setLimit(1);
				$boardList = $this->model->selectBoardList($boardDto);

				if(sizeof($boardList) > 0) {
					$data['boardList'] = $boardList[0];
				}
			}

			$this->layout->renderWithFrame('board/form', $data, $topData);
		}
	}

	/**
	 * 게시글 등록
	 */
	public function writeList() {
		$data = array();
		$data['title'] = (isset($_POST['title']) && $_POST['title'] != '') ? $_POST['title'] : '';
		$data['content'] = (isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content'] : '';
		$data['memberSeq'] = (isset($_POST['memberSeq']) && $_POST['memberSeq'] != '') ? $_POST['memberSeq'] : 0;

		$boardDto = new BoardDTO();
		$boardDto->setTitle($data['title']);
		$boardDto->setContent($data['content']);
		$boardDto->setMemberSeq($data['memberSeq']);
		$boardDto->setCreateDate(date('Y-m-d H:i:s'));
		$boardDto->setUpdateDate(date('Y-m-d H:i:s'));
		$boardDto->setDisplayYN('Y');
		$boardDto->setHit(0);
		$result = $this->model->insertBoardList($boardDto);

		$message = ($result) ? '게시글 등록 성공' : '게시글 등록 실패';
		echo '<script>alert("' . $message . '"); location.href="/board_mvc";</script>';
	}

	/**
	 * 게시글 상세 
	 */
	public function view() {
		$topData = array();
		$topData['title'] = '게시글 상세';

		$data = array();
		$data['page'] = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
		$data['memberSeq'] = $_SESSION['memberSeq'];
		$data['listSeq'] = (isset($_GET['listSeq']) && $_GET['listSeq'] != '') ? $_GET['listSeq'] : 0;
		$data['mode'] = (isset($_GET['mode']) && $_GET['mode'] != '') ? $_GET['mode'] : 'insert';

		// 게시글 조회수 증가
		$boardDto = new BoardDTO();
		$boardDto->setListSeq($data['listSeq']);
		$result = $this->model->updateHitBoardList($boardDto);
		//$result = 1;

		if($result) {
			// 게시글 조회
			$boardDto->setLimit(1);
			$boardList = $this->model->selectBoardList($boardDto);

			if(sizeof($boardList) > 0) {
				$data['boardList'] = $boardList[0];
			}

			// 댓글 조회
			$commentDto = new CommentDTO();
			$commentDto->setListSeq($data['listSeq']);
			$commentList = $this->model->selectCommentList($commentDto);

			if(sizeof($commentList) > 0) {
				$data['commentList'] = $commentList;
			}
		}

		$this->layout->renderWithFrame('board/view', $data, $topData);
	}

	/**
	 * 게시글 수정
	 */
	public function updateList() {
		$return = array(
				'status' => 0,
				'message' => ''
		);

		$data = array();
		$data['title'] = (isset($_POST['title']) && $_POST['title'] != '') ? $_POST['title'] : '';
		$data['content'] = (isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content'] : '';
		$data['listSeq'] = (isset($_POST['listSeq']) && $_POST['listSeq'] != '') ? $_POST['listSeq'] : 0;

		// 게시글 업데이트
		$boardDto = new BoardDTO();
		$boardDto->setListSeq($data['listSeq']);
		$boardDto->setTitle($data['title']);
		$boardDto->setContent($data['content']);
		$boardDto->setUpdateDate(date('Y-m-d H:i:s'));
		$result = $this->model->updateBoardList($boardDto);

		if($result) {
			$return['status'] = 200;
			$return['message'] = '게시글 수정 성공';
		} else {
			$return['status'] = 201;
			$return['message'] = '게시글 수정 실패';
		}

		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($return);
	}

	/**
	 * 게시글 삭제
	 */
	public function deleteList() {
		$data = array();
		$data['listSeq'] = (isset($_GET['listSeq']) && $_GET['listSeq'] != '') ? $_GET['listSeq'] : 0;

		$boardDto = new BoardDTO();
		$boardDto->setListSeq($data['listSeq']);
		$result = $this->model->deleteBoardList($boardDto);

		$message = ($result) ? '게시글 삭제 성공' : '게시글 삭제 실패';

		echo '<script>alert("' . $message . '"); location.href="/board_mvc";</script>';
	}

	/**
	 * 댓글 등록
	 */
	public function writeComment() {
		$data = array();
		$data['listSeq'] = (isset($_POST['listSeq']) && $_POST['listSeq'] != '') ? $_POST['listSeq'] : 0;
		$data['parentSeq'] = (isset($_POST['parentSeq']) && $_POST['parentSeq'] != '') ? $_POST['parentSeq'] : 0;
		$data['memberSeq'] = (isset($_POST['memberSeq']) && $_POST['memberSeq'] != '') ? $_POST['memberSeq'] : 0;
		$data['content'] = (isset($_POST['comment']) && $_POST['comment'] != '') ? $_POST['comment'] : '';
		$data['depth'] = (isset($_POST['depth']) && $_POST['depth'] != '') ? $_POST['depth'] : 0;
		$data['sort'] = (isset($_POST['sort']) && $_POST['sort'] != '') ? $_POST['sort'] : 0;
		$data['group'] = (isset($_POST['group']) && $_POST['group'] != '') ? $_POST['group'] : 0;
		$data['page'] = (isset($_POST['page']) && $_POST['page'] > 0) ? $_POST['page'] : 1;

		$commentDto = new CommentDTO();
		$commentDto->setListSeq($data['listSeq']);
		$commentDto->setParentSeq($data['parentSeq']);
		$commentDto->setMemberSeq($data['memberSeq']);
		$commentDto->setContent($data['content']);
		$commentDto->setDepth($data['depth']);
		$commentDto->setSort($data['sort']);
		$commentDto->setGroup($data['group']);
		$commentDto->setCreateDate(date('Y-m-d H:i:s'));
		$commentDto->setUpdateDate(date('Y-m-d H:i:s'));
		$commentDto->setDisplayYN('Y');
		$result = $this->model->insertComment($commentDto);

		$message = ($result) ? '댓글 등록 성공' : '댓글 등록 실패';

		echo '<script>alert("' . $message . '"); location.href="/board_mvc/board/view?listSeq='. $data['listSeq'] . '&page=' . $data['page'] . '";</script>';
	}
}
?>