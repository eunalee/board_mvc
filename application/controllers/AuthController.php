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
		$params['id'] = isset($_POST['id']) && $_POST['id'] != '' ? $_POST['id'] : '';
		$params['password'] = isset($_POST['password']) && $_POST['password'] != '' ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';
		$params['name'] = isset($_POST['name']) && $_POST['name'] != '' ? $_POST['name'] : '';

		$result = $this->model->addMemberInfo($params);
		$message = ($result > 0) ? '회원가입 성공' : '회원가입 실패';
		echo '<script>alert("' . $message . '"); location.href="/board_mvc";</script>';
	}

	/**
	 * 회원가입 아이디 중복체크 - ajax
	 */
	public function idCheck() {
		$result = array(
				'status' => 201,
				'desc' => ''
		);

		$rawData = file_get_contents('php://input');	// 파싱 전, POST 데이터
		$data = json_decode($rawData, true);			// JSON 문자열 -> 배열로 변환
		$params['id'] = isset($data['id']) && $data['id'] != '' ? $data['id'] : '';

		$memberInfo = $this->model->getMemberInfo($params['id']);
		if(sizeof($memberInfo) > 0) {
			$result['status'] = 200;
			$result['desc'] = '사용중이거나 탈퇴한 아이디입니다.';
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
		$data['loginUrl'] = isset($_GET['loginUrl']) && $_GET['loginUrl'] != '' ? $_GET['loginUrl'] : '';

		$this->layout->renderWithFrame('auth/loginForm', $data, $topData);
	}

	/**
	 * 로그인
	 */
	public function login() {
		// params
		$params['id'] = isset($_POST['id']) && $_POST['id'] != '' ? $_POST['id'] : '';
		$params['password'] = isset($_POST['password']) && $_POST['password'] != '' ? $_POST['password'] : '';
		$params['url'] = isset($_POST['loginUrl']) && $_POST['loginUrl'] != '' ? $_POST['loginUrl'] : '';

		// 사용자 정보 조회
		$isLogin = false;
		$memberInfo = $this->model->getMemberInfo($params['id']);
		if(sizeof($memberInfo) > 0) {
			$isLogin = true;

			// 비밀번호 검증
			if(password_verify($params['password'], $memberInfo[0]['sPassword'])) {
				// 세션 생성
				$_SESSION['memberSeq'] = $memberInfo[0]['nMemberSeq'];
				$_SESSION['name'] = $memberInfo[0]['sName'];

				echo '<script>location.href="' . $params['url'] . '";</script>';
			}
		}

		if(!$isLogin) {
			echo '<script>alert("아이디와 비밀번호를 확인해주세요.");</script>';
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
