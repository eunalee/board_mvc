<?php
namespace application\controllers;

/**
 * 게시글/댓글 관련
 * @author eunalee
 */
class BoardController extends Controller {
	private $model;
	private $layout;

	function __construct($controller, $method) {
		$this->model = new \application\models\BoardModel('dbBoard');
		$this->layout = new \application\libraries\Layout();

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
				$boardList = $this->model->getBoardList($data);
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
		$params['mode'] = (isset($_POST['mode']) && $_POST['mode'] != '') ? $_POST['mode'] : 'insert';
		$params['title'] = (isset($_POST['title']) && $_POST['title'] != '') ? $_POST['title'] : '';
		$params['content'] = (isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content'] : '';
		$params['memberSeq'] = (isset($_POST['memberSeq']) && $_POST['memberSeq'] != '') ? $_POST['memberSeq'] : 0;
		$params['createTime'] = date('Y-m-d H:i:s');
		$params['updateTime'] = date('Y-m-d H:i:s');
		$params['displayYN'] = 'Y';
		$params['hit'] = 0;

		$result = $this->model->addBoardList($params);
		$message = ($result > 0) ? '게시글 등록 성공' : '게시글 등록 실패';
		echo '<script>alert("' . $message . '"); location.href="/board_mvc";</script>';
	}

	/**
	 * 게시글 상세 
	 */
	public function view() {
		$topData = array();
		$topData['title'] = '게시글 상세';

		$params['listSeq'] = (isset($_GET['listSeq']) && $_GET['listSeq'] > 0) ? $_GET['listSeq'] : 0;
		$params['type'] = 'count';
		$data['page'] = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;

		// 게시글 조회수 증가
		$result = $this->model->saveBoardList($params);
		//$result = 1;

		if($result > 0) {
			// 게시글 조회
			$boardList = $this->model->getBoardList($params);
			if(sizeof($boardList) > 0) {
				$data['boardList'] = $boardList[0];
			}

			// 댓글 조회
			$commentList = $this->model->getCommentList($params);
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
		$returnData = array(
				'status' => 0,
				'message' => ''
		);

		$params['type'] = 'board';
		$params['mode'] = (isset($_POST['mode']) && $_POST['mode'] != '') ? $_POST['mode'] : 'update';
		$params['title'] = (isset($_POST['title']) && $_POST['title'] != '') ? $_POST['title'] : '';
		$params['content'] = (isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content'] : '';
		$params['listSeq'] = (isset($_POST['listSeq']) && $_POST['listSeq'] != '') ? $_POST['listSeq'] : 0;
		$params['updateTime'] = date('Y-m-d H:i:s');

		// 게시글 업데이트
		$result = $this->model->saveBoardList($params);
		if($result > 0) {
			$returnData['status'] = 200;
			$returnData['message'] = '게시글 수정 성공';
		} else {
			$returnData['status'] = 201;
			$returnData['message'] = '게시글 수정 실패';
		}

		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($returnData);
	}

	/**
	 * 게시글 삭제
	 */
	public function deleteList() {
		$params['type'] = 'delete';
		$params['listSeq'] = (isset($_GET['listSeq']) && $_GET['listSeq'] != '') ? $_GET['listSeq'] : 0;
		$params['displayYN'] = 'N';

		// 게시글 업데이트
		$result = $this->model->saveBoardList($params);
		$message = ($result > 0) ? '게시글 삭제 성공' : '게시글 삭제 실패';

		echo '<script>alert("' . $message . '"); location.href="/board_mvc";</script>';
	}

	/**
	 * 댓글 등록
	 */
	public function writeComment() {
		$params['listSeq'] = (isset($_POST['listSeq']) && $_POST['listSeq'] != '') ? $_POST['listSeq'] : 0;
		$params['parentSeq'] = (isset($_POST['parentSeq']) && $_POST['parentSeq'] != '') ? $_POST['parentSeq'] : 0;
		$params['memberSeq'] = (isset($_POST['memberSeq']) && $_POST['memberSeq'] != '') ? $_POST['memberSeq'] : 0;
		$params['content'] = (isset($_POST['comment']) && $_POST['comment'] != '') ? $_POST['comment'] : '';
		$params['depth'] = (isset($_POST['depth']) && $_POST['depth'] != '') ? $_POST['depth'] : 0;
		$params['sort'] = (isset($_POST['sort']) && $_POST['sort'] != '') ? $_POST['sort'] : 0;
		$params['group'] = (isset($_POST['group']) && $_POST['group'] != '') ? $_POST['group'] : 0;
		$params['createTime'] = date('Y-m-d H:i:s');
		$params['updateTime'] = date('Y-m-d H:i:s');
		$params['displayYN'] = 'Y';

		$result = $this->model->addComment($params);

		$message = ($result > 0) ? '댓글 등록 성공' : '댓글 등록 실패';
		$data['page'] = (isset($_POST['page']) && $_POST['page'] > 0) ? $_POST['page'] : 1;

		echo '<script>alert("' . $message . '"); location.href="/board_mvc/board/view?listSeq='. $params['listSeq'] . '&page=' . $data['page'] . '";</script>';
	}
}
?>