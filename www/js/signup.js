window.onload = () => {
	// form 요소 이벤트
	const form = document.getElementById('signupForm');
	if(form) {
		form.addEventListener('focusout', (event) => {
			formCheck(event.target);
		});

		form.addEventListener('keyup', (event) => {
			formCheck(event.target);
		});
	}
}

/**
 * 폼 검증
 */
const formCheck = (obj) => {
	// input 클래스 모두 제거
	obj.classList.remove('is-valid');
	obj.classList.remove('is-invalid');
	// 에러 메세지 모두 제거
	obj.nextElementSibling.innerHTML = '';

	// 유효성 검사
	const formId = obj.id;
	const formValue = obj.value;
	const result = formValidate(formId, formValue);
	if(result !== '') {
		// 에러 메세지 노출
		obj.classList.add('is-invalid');
		obj.nextElementSibling.innerHTML = result;
	}
	else {
		if(formId === 'id') {	// 아이디 중복체크
			idCheck(formValue);
		}
		else {
			obj.classList.add('is-valid');
		}
	}

	// 모든 input 에 is-valid 클래스가 있는 경우에만 가입버튼 활성화
	let count = 0;
	let isActive = false;
	const input = document.getElementsByTagName('input');
	for(let i=0; i<input.length; i++) {
		if(input[i].classList.contains('is-valid')) {
			count++;
		}

		isActive = (count === input.length) ? true : false;
	}

	const button = document.getElementById('joinBtn'); 
	button.disabled = isActive ? false : true;
};

/**
 * 입력값 유효성 검사
 */
const formValidate = (id, value) => {
	let message = '';

	switch(id) {
		// 아이디
		case 'id':
			if(value === '') {
				message = '아이디를 입력해 주세요.';
			}
			else if(/\s/.test(value)) {
				message = '공백은 입력할 수 없습니다.';
			}
			else if(/[ㄱ-ㅎ|ㅏ-ㅣ|가-힣]/.test(value)) {
				message = '한글이 포함되어 있습니다.';
			}
			else if(value.length < 4 || value.length > 21) {
				message = '최소 4자 , 최대 20 자 입니다.';
			}
			else if(!/^[a-zA-Z]/.test(value)) {
				message = '첫글자는 영문이어야 합니다.';
			}
			else if(!/^[a-zA-Z0-9~,./\\]{4,20}$/.test(value)) {
				message = '허용되지 않는 문자열이 포함되어 있습니다.';
			}
		break;

		// 비밀번호
		case 'password':
			if(value === '') {
				message = '비밀번호를 입력해 주세요.';
			}
			else if(/\s/.test(value)) {
				message = '공백은 입력할 수 없습니다.';
			}
			else if(value.length < 8) {
				message = '너무 짧습니다. 최소 8자 이상 입력하세요.';
			}
			else if(value.length > 21) {
				message = '너무 깁니다. 최대 20자 이하로 입력하세요.';
			}
			else if(/(?=.*[^a-zA-Z0-9!@#$%^&*]).+/.test(value)) {
				message = '특수문자는 !, @, #, $, %, ^, &, * 만 가능합니다.';
			}
			else if(!/^(?=.*[a-zA-Z])(?=.*[!@#$%^&*])(?=.*[0-9]).{8,}$/.test(value)) {
				message = '영문과 숫자와 특수문자를 조합해서 입력해 주세요.';
			}
		break;

		// 비밀번호 확인
		case 'passwordCheck':
			if(value === '') {
				message = '비밀번호 확인을 입력해 주세요.';
			}
			else if(/\s/.test(value)) {
				message = '공백은 입력할 수 없습니다.';
			}
			else if(document.getElementById('password').value !== value) {
				message = '비밀번호가 일치하지 않습니다.';
			}
		break;

		// 이름
		case 'name':
			if(value === '') {
				message = '이름을 입력해 주세요.';
			}
			else if(/\s/.test(value)) {
				message = '공백은 입력할 수 없습니다.';
			}
			else if(!/^[a-z|A-Z|ㄱ-ㅎ|ㅏ-ㅣ|가-힣]+$/.test(value)) {
				message = '이름은 한글, 또는 영문만 입력할 수 있습니다.';
			}
			else if(/[ㄱ-ㅎ|ㅏ-ㅣ|가-힣]{9,}/.test(value)) {
				message = '한글 8자, 영문 16자까지 가능합니다.';
			}
			else if(/[a-zA-Z]{17,}/.test(value)) {
				message = '한글 8자, 영문 16자까지 가능합니다.';
			}
		break;

		default:
			message = '';
		break;
	}

	return message;
};

/**
 * 아이디 중복 체크
 */
const idCheck = (value) => {
	// XMLHttpRequest 객체 생성
	const xhr = new XMLHttpRequest();

	// HTTP 요청 초기화
	xhr.open('POST', '/board_mvc/auth/idCheck');

	// HTTP 요청 헤더 설정(json)
	xhr.setRequestHeader('Content-type', 'application/json');

	// HTTP 요청 전송
	xhr.send(JSON.stringify({id: value}));	

	// HTTP 요청 성공 시
	xhr.onload = () => {
		if(xhr.status == 200) {
			const response = JSON.parse(xhr.response);
			const element = document.getElementById('id');
			if(response.status === 200 && response.desc !== '') {
				element.classList.add('is-invalid');
				element.nextElementSibling.innerHTML = response.desc;
			}
			else {
				element.classList.add('is-valid');
			}
		}
		else {
			console.log('error', xhr.status, xhr.statusText);
		}
	};
};