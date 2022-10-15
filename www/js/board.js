window.onload = () => {
	// 게시물 등록 시
	const boardListButton = document.getElementById('boardListWriteBtn');
	if(boardListButton) {
		boardListButton.addEventListener('click', () => {
			boardInsert();
		});
	}

	// 게시물 등록 취소 시
	const boardListCancelButton = document.getElementById('boardListCancelBtn');
	if(boardListCancelButton) {
		boardListCancelButton.addEventListener('click', () => {
			if(confirm('글 등록을 취소하시겠습니까?')) {
				history.back();
			}
		});
	}

	// 게시물 수정 시
	const boardModifyButton = document.getElementById('boardListModifyBtn');
	if(boardModifyButton) {
		boardModifyButton.addEventListener('click', () => {
			boardModify();
		});
	}

	// 게시물 삭제 시
	const boardDeleteButton = document.getElementById('boardListDeleteBtn');
	if(boardDeleteButton) {
		const listSeq = document.getElementById('listSeq').value;
		boardDeleteButton.addEventListener('click', (event) => {
			event.preventDefault();
			boardDelete(listSeq);
		});
	}

	// 댓글 입력 시
	const comment = document.getElementById('comment');
	if(comment) {
		comment.addEventListener('click', () => {
			const url = loginCheck();
			if(url !== '') {
				if(confirm('로그인이 필요한 서비스입니다. 로그인하시겠습니까?')) {
					location.href = url;
				}
				return false;
			}
		});
	}

	// 댓글 등록 시
	const boardCommentButton = document.getElementById('boardCommentWriteBtn');
	if(boardCommentButton) {
		boardCommentButton.addEventListener('click', () => {
			commentFormCheck();
		});
	}

	// 댓글 추가
	const replyForm = document.getElementById('replyFormCreate');
	if(replyForm) {
		replyForm.addEventListener('click', (event) => {
			event.preventDefault();
			replyFormCreate(event.target);
		});
	}
}

// 게시글 입력폼 검증
const boardFormCheck = () => {
	const result = {
		status: true,
		element: {},
		message: '',
	};

	const title = document.getElementById('title');
	if(title.value.trim() === '') {
		result.status = false;
		result.element = title;
		result.message = '제목을 입력해 주세요.';
		return result;
	}

	if(title.value.length >= 100) {
		result.status = false;
		result.element = title;
		result.message = '제목은 최대 100자 까지 작성할 수 있습니다.';
		return result;
	}

	const content = document.getElementById('content');
	content.value = content.value.replace(/(?:\r\n|\r|\n)/g, '<br>')
	if(content.value.trim() === '') {
		result.status = false;
		result.element = content;
		result.message = '내용을 입력해 주세요.';
		return result;
	}
};

// 댓글 입력폼 검증
const commentFormCheck = () => {
	const url = loginCheck();
	if(url !== '') {
		if(confirm('로그인이 필요한 서비스입니다. 로그인하시겠습니까?')) {
			location.href = url;
		}
		return false;
	}

	const content = document.getElementById('comment');
	content.value = content.value.replace(/(?:\r\n|\r|\n)/g, '<br>')
	if(content.value.trim() === '') {
		alert('내용을 입력해 주세요.');
		content.focus();
		return false;
	}

	const form = document.getElementById('commentForm');
	form.submit();
}

// 댓글 입력폼 추가
const replyFormCreate = (value) => {
	const url = loginCheck();
	if(url !== '') {
		if(confirm('로그인이 필요한 서비스입니다. 로그인하시겠습니까?')) {
			location.href = url;
		}
		return false;
	}

	const commentSeq= value.getAttribute('commentseq');

	let strHtml = '';
		strHtml += '<div class="mb-3">';
		strHtml += '	<textarea class="form-control" id="comment" name="comment" placeholder="댓글을 입력해주세요."></textarea>';
		strHtml += '</div>';
		strHtml += '<input type="hidden" id="listSeq" name="listSeq" value="">';
		strHtml += '<input type="hidden" id="parentSeq" name="parentSeq" value="' + commentSeq + '">';
		strHtml += '<input type="hidden" id="memberSeq" name="memberSeq" value="0">';
		strHtml += '<input type="hidden" id="depth" name="depth" value="1">';
		strHtml += '<input type="hidden" id="sort" name="sort" value="1">';
		strHtml += '<input type="hidden" id="group" name="group" value="' + commentSeq + '">';
		strHtml += '<input type="hidden" id="page" name="page" value="">';
		strHtml += '<div class="mb-3">';
		strHtml += '	<button type="reset" class="btn btn-primary">취소</button>';
		strHtml += '	<button type="button" class="btn btn-primary" id="boardCommentReplyWriteBtn">등록</button>';
		strHtml += '</div>';

	const form = document.createElement('form');
	form.setAttribute('class', 'list-group-item list-group-item-action');
	form.setAttribute('method', 'POST');
	form.setAttribute('action', '/board_mvc/board/writeComment');
	form.setAttribute('id', 'commentReplyForm');
	form.innerHTML = strHtml;

	const div = document.getElementById('comment_' + commentSeq);
	div.append(form);
}

// 게시글 등록
const boardInsert = () => {
	// 입력 데이터 검증
	const result = boardFormCheck();
	if(!result.status) {
		alert(result.message);
		result.element.focus();
		return false;
	}

	const form = document.getElementById('writeListForm');
	form.submit();
};

// 게시글 수정
const boardModify = () => {
	// 입력 데이터 검증
	const result = boardFormCheck();
	if(!result.status) {
		alert(result.message);
		result.element.focus();
		return false;
	}

	// form 데이터
	const form = document.getElementById('writeListForm');
	const data = new FormData(form);

	// XMLHttpRequest 객체 생성
	const xhr = new XMLHttpRequest();

	// HTTP 요청 초기화
	xhr.open('POST', '/board_mvc/board/updateList');

	// HTTP 요청 전송
	xhr.send(data);	

	// HTTP 요청 성공 시
	xhr.onload = () => {
		if(xhr.status == 200) {
			const response = JSON.parse(xhr.response);
			alert(response.message);
			location.href = document.referrer;
		}
		else {
			console.log('error', xhr.status, xhr.statusText);
		}
	};
};

// 게시글 삭제
const boardDelete = (listSeq) => {
	if(confirm('삭제하시겠습니까?')) {
		location.href = '/board_mvc/board/deleteList?listSeq=' + listSeq;
	}
}