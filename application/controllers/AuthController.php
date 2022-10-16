<?php
namespace application\controllers;

use application\libraries\Layout;
use application\models\AuthModel;
use application\dto\AuthDTO;

/**
 * 인증 관련
 * @author eunalee
 */
class AuthController extends Controller {
	private $model;
	private $layout;

	public function __construct($contoller, $method) {
		$this->model = new AuthModel('dbMember');
		$this->layout = new Layout();

		parent::__construct($contoller, $method);
	}

	/**
	 * 회원가입 폼
	 */
	public function signupForm() {
		$topData = array();
		$topData['title'] = '게시판 회원가입';

		$data = array();

		$this->layout->renderWithFrame('auth/signupForm', $data, $topData);
	}

	/**
	 * 회원가입
	 */
	public function signup() {
		$data = array();
		$data['id'] = (isset($_POST['id']) && $_POST['id'] != '') ? $_POST['id'] : '';
		$data['password'] = (isset($_POST['password']) && $_POST['password'] != '') ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';
		$data['name'] = (isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name'] : '';

		$authDto = new AuthDTO();
		$authDto->setId($data['id']);
		$authDto->setPassword($data['password']);
		$authDto->setName($data['name']);
		$result = $this->model->insertMemberInfo($authDto);

		$message = ($result) ? '회원가입 성공' : '회원가입 실패';
		echo '<script>alert("' . $message . '"); location.href="/board_mvc";</script>';
	}

	/**
	 * 회원가입 아이디 중복체크 - ajax
	 */
	public function idCheck() {
		$result = array(
				'status' => 201,
				'message' => ''
		);

		$rawData = file_get_contents('php://input');	// 파싱 전, POST 데이터
		$data = json_decode($rawData, true);			// JSON 문자열 -> 배열로 변환
		$params['id'] = isset($data['id']) && $data['id'] != '' ? $data['id'] : '';

		$authDto = new AuthDTO();
		$authDto->setId($params['id']);
		$memberInfo = $this->model->selectMemberInfo($authDto);

		if(sizeof($memberInfo) > 0) {
			$result['status'] = 200;
			$result['message'] = '사용중이거나 탈퇴한 아이디입니다.';
		}

		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result);
	}

	/**
	 * 로그인 폼
	 */
	public function loginForm() {
		$topData = array();
		$topData['title'] = '게시판 로그인';

		$data = array();
		$data['loginUrl'] = (isset($_GET['loginUrl']) && $_GET['loginUrl'] != '') ? $_GET['loginUrl'] : '';

		$this->layout->renderWithFrame('auth/loginForm', $data, $topData);
	}

	/**
	 * 로그인
	 */
	public function login() {
		$data = array();
		$data['id'] = (isset($_POST['id']) && $_POST['id'] != '') ? $_POST['id'] : '';
		$data['password'] = (isset($_POST['password']) && $_POST['password'] != '') ? $_POST['password'] : '';
		$url = (isset($_POST['loginUrl']) && $_POST['loginUrl'] != '') ? $_POST['loginUrl'] : '';

		// 사용자 정보 조회
		$isLogin = false;

		$authDto = new AuthDTO();
		$authDto->setId($data['id']);
		$authDto->setPassword($data['password']);
		$memberInfo = $this->model->selectMemberInfo($authDto);

		if(sizeof($memberInfo) > 0) {
			// 비밀번호 검증
			if(password_verify($data['password'], $memberInfo[0]['sPassword'])) {
				$isLogin = true;

				// 세션 생성
				$_SESSION['memberSeq'] = $memberInfo[0]['nMemberSeq'];
				$_SESSION['name'] = $memberInfo[0]['sName'];

				echo '<script>location.href="' . $url . '";</script>';
			}
		}

		if(!$isLogin) {
			echo '<script>alert("아이디와 비밀번호를 확인해주세요."); history.back();</script>';
		}
	}

	/**
	 * 로그아웃
	 */
	public function logout() {
		// 세션 제거
		session_destroy();
		echo '<script>location.href="/board_mvc";</script>';
	}
}
?>