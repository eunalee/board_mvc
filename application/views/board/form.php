<script src="/board_mvc/www/js/board.js"></script>

<div class="container">
	<form class="row g-3" method="post" action="/board_mvc/board/writeList" id="writeListForm">
		<div class="mb-3">
			<label for="formGroupExampleInput" class="form-label">제목</label>
			<input type="text" class="form-control" id="title" name="title" placeholder="제목을 입력해주세요." value="<?php echo isset($data['boardList']['sTitle']) ? $data['boardList']['sTitle'] : ''; ?>">
		</div>
		<div class="mb-3">
			<label for="formGroupExampleInput" class="form-label">내용</label>
			<textarea class="form-control" id="content" name="content" placeholder="내용을 입력해주세요."><?php if(isset($data['boardList']['sContent'])) : echo str_replace('&lt;br&gt;', '<br>', $data['boardList']['sContent']); endif; ?></textarea>
		</div>
		<input type="hidden" id="memberSeq" name="memberSeq" value="<?php echo isset($data['memberSeq']) ? $data['memberSeq'] : 0; ?>">
		<input type="hidden" id="listSeq" name="listSeq" value="<?php echo $data['listSeq']; ?>">
		<input type="hidden" id="mode" name="mode" value="<?php echo $data['mode']; ?>">
		<div class="mb-3">
			<button type="button" class="btn btn-primary" id="boardListCancelBtn">취소</button>
			<?php if($data['mode'] == 'update') : ?>
			<button type="button" class="btn btn-primary" id="boardListModifyBtn">수정</button>
			<?php else : ?>
			<button type="button" class="btn btn-primary" id="boardListWriteBtn">등록</button>
			<?php endif; ?>
		</div>
	</form>
</div>