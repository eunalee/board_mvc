window.onload = () => {
	// 게시물 등록 시
	const boardListButton = document.getElementById('boardListWriteBtn');
	if(boardListButton) {
		boardListButton.addEventListener('click', () => {
			boardFormCheck();
		});
	}

	// 댓글 입력 시
	const comment = document.getElementById('comment');
	if(comment) {
		comment.addEventListener('click', () => {
			const memberSeq = document.getElementById('memberSeq');
			if(Number(memberSeq.value) === 0) {
				if(confirm('로그인이 필요한 서비스입니다. 로그인하시겠습니까?')) {
					location.href = '/board_mvc/auth/loginForm?loginUrl=' + encodeURIComponent(location.href);
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
}

/**
 * 게시글 입력폼 검증
 */
const boardFormCheck = () => {
	const title = document.getElementById('title');
	if(title.value.trim() === '') {
		alert('제목을 입력해 주세요.');
		title.focus();
		return false;
	}

	if(title.value.length >= 100) {
		alert('제목은 최대 100자 까지 작성할 수 있습니다.');
		title.focus();
		return false;
	}

	const content = document.getElementById('content');
	content.value = content.value.replace(/(?:\r\n|\r|\n)/g, '<br>')
	if(content.value.trim() === '') {
		alert('내용을 입력해 주세요.');
		content.focus();
		return false;
	}

	const form = document.getElementById('writeListForm');
	form.submit();
};

/**
 * 댓글 입력폼 검증
 */
const commentFormCheck = () => {
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