<script src="/board_mvc/www/js/board.js"></script>

<form class="row g-3" method="post" action="/board_mvc/board/writeList" id="writeListForm">
	<div class="mb-3">
		<label for="formGroupExampleInput" class="form-label">제목</label>
		<input type="text" class="form-control" id="title" name="title" placeholder="제목을 입력해주세요.">
	</div>
	<div class="mb-3">
		<label for="formGroupExampleInput" class="form-label">내용</label>
		<textarea class="form-control" id="content" name="content" placeholder="내용을 입력해주세요."></textarea>
	</div>
	<input type="hidden" id="memberSeq" name="memberSeq" value="<?php echo isset($data['memberSeq']) ? $data['memberSeq'] : 0; ?>">
	<div class="mb-3">
		<button type="reset" class="btn btn-primary">취소</button>
		<button type="button" class="btn btn-primary" id="boardListWriteBtn">등록</button>
	</div>
</form>