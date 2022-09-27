<?php
namespace application\controllers;

/**
 * 게시물/댓글 관련
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
	 * 글쓰기 폼
	 */
	public function writeListForm() {
		// 로그인 체크
		if(!isset($_SESSION['memberSeq'])) {
			$url = '/board_mvc/auth/login?url=' . urlencode('board_mvc/board/writeListForm');

			header('Content-type:text-html;charset=utf-8');
			echo '<script>location.href="' . $url . '";</script>';
		}
		else {
			$topData = array();
			$topData['title'] = '게시판 글쓰기';

			$data = array();
			$data['title'] = '게시판 글쓰기';

			$this->layout->renderWithFrame('board/form', $data, $topData);
		}
	}
}
?>