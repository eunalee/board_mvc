<?php
namespace application\controllers;

/**
 * 인증 관련
 * @author eunalee
 */
class AuthController extends Controller {
	private $model;
	private $layout;

	function __construct($contoller, $method) {
		$this->model = new \application\models\AuthModel('dbMember');
		$this->layout = new \application\libraries\Layout();

		parent::__construct($contoller, $method);
	}

	/**
	 * 로그인 폼
	 */
	public function loginForm() {
		$topData = array();
		$topData['title'] = '게시판 로그인';

		$data = array();
		$data['loginUrl'] = isset($_GET['url']) && $_GET['url'] != '' ? $_GET['url'] : '';

		$this->layout->renderWithFrame('auth/loginForm', $data, $topData);
	}

	/**
	 * 로그인
	 */
	public function login() {
		$url = isset($_GET['url']) && $_GET['url'] != '' ? $_GET['url'] : '';

		// 로그인 성공 시, 이동
		header('Content-type:text-html;charset=utf-8');
		echo '<script>location.href="' . $url . '";</script>';
	}
}
?>
