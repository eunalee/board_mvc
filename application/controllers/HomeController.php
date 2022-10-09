<?php
namespace application\controllers;

/**
 * 메인
 * @author eunalee
 */
class HomeController extends Controller {
	private $model;
	private $layout;
	private $pagination;

	function __construct($controller, $method) {
		$this->model = new \application\models\BoardModel('dbBoard');
		$this->layout = new \application\libraries\Layout();

		parent::__construct($controller, $method);
	}

	/**
	 * 메인 페이지
	 */
	public function index() {
		$topData = array();
		$topData['title'] = '게시판 메인';

		// params
		$data = array();
		$data['page'] = isset($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1;

		// 게시글 전체 카운트
		$totalCount = $this->model->getBoardListCount();
		$this->pagination = new \application\libraries\Pagination($totalCount, $data['page']);
		$offset = $this->pagination->getOffset();

		// 페이징
		$pagination = $this->pagination->getPagingHtml('/board_mvc/home/index');
		if(isset($pagination) && !empty($pagination)) {
			$data['pagination'] = $pagination;
		}

		// 게시글 조회
		$params['offset'] = $offset;
		$params['limit'] = 5;
		$boardList = $this->model->getBoardList($params);
		if(sizeof($boardList) > 0) {
			$data['boardList'] = $boardList; 
		}

		$this->layout->renderWithFrame('main', $data, $topData);
	}
}
?>