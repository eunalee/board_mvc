/**
 * 공통 함수
 */

// 로그인 체크
const loginCheck = () => {
	let result = '';
	const memberSeq = document.getElementById('memberSeq');
	if(Number(memberSeq.value) === 0) {
		result = '/board_mvc/auth/loginForm?loginUrl=' + encodeURIComponent(location.href);
	}
	return result;
}