		<form class="row g-3" method="post" action="/board/writeList.php" id="writeListForm">
			<div class="mb-3">
				<label for="formGroupExampleInput" class="form-label">제목</label>
				<input type="text" class="form-control" id="title" name="title" placeholder="제목을 입력해주세요.">
			</div>
			<div class="mb-3">
				<label for="formGroupExampleInput" class="form-label">내용</label>
				<textarea class="form-control" id="content" name="content" placeholder="내용을 입력해주세요."></textarea>
			</div>
			<input type="hidden" id="memberSeq" name="memberSeq" value="<?php echo isset($memberSeq) ? $memberSeq : 0; ?>">
			<div class="mb-3">
				<button type="reset" class="btn btn-primary">취소</button>
				<button type="button" class="btn btn-primary" id="insertBtn">등록</button>
			</div>
		</form>
	</body>
	<footer></footer>
</body>