<?php
namespace application\controllers;

use application\libraries\Layout;
use application\libraries\Pagination;
use application\models\BoardModel;
use application\dto\BoardDTO;

/**
 * 메인
 * @author eunalee
 */
class HomeController extends Controller {
	private $model;
	private $layout;
	private $pagination;

	public function __construct($controller, $method) {
		$this->model = new BoardModel('dbBoard');
		$this->layout = new Layout();

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
		$data['page'] = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;

		// 게시글 전체 카운트
		$totalCount = $this->model->selectBoardListCount();
		$this->pagination = new Pagination($totalCount, $data['page']);

		// 페이징
		$pagination = $this->pagination->getPagingHtml('/board_mvc/home/index');
		if(isset($pagination) && !empty($pagination)) {
			$data['pagination'] = $pagination;
		}

		// 게시글 조회
		$boardDTO = new BoardDTO();
		$boardDTO->setLimit(5);
		$boardDTO->setOffset(($data['page'] - 1) * 5);
		$boardList = $this->model->selectBoardList($boardDTO);
		if(sizeof($boardList) > 0) {
			$data['boardList'] = $boardList; 
		}

		$this->layout->renderWithFrame('main', $data, $topData);
	}
}
?>