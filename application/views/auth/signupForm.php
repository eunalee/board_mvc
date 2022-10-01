<script src="/board_mvc/www/js/signup.js"></script>

<form class="row g-3" method="post" action="/board_mvc/auth/signup" id="signupForm">
	<div class="mb-3">
		<label for="formGroupExampleInput" class="form-label">아이디</label>
		<input type="text" class="form-control" id="id" name="id" placeholder="영문 4자 이상, 최대 20자">
		<div class="invalid-feedback"></div>
	</div>
	<div class="mb-3">
		<label for="formGroupExampleInput" class="form-label">비밀번호</label>
		<input type="password" class="form-control" id="password" name="password" placeholder="숫자, 영문, 특수문자 포함 최소 8자 이상">
		<div class="invalid-feedback"></div>
	</div>
	<div class="mb-3">
		<label for="formGroupExampleInput" class="form-label">비밀번호 확인</label>
		<input type="password" class="form-control" id="passwordCheck" name="passwordCheck" placeholder="숫자, 영문, 특수문자 포함 최소 8자 이상">
		<div class="invalid-feedback"></div>
	</div>
	<div class="mb-3">
		<label for="formGroupExampleInput" class="form-label">이름</label>
		<input type="text" class="form-control" id="name" name="name" placeholder="한글 8자, 영문 16자까지 가능">
		<div class="invalid-feedback"></div>
	</div>
	<div class="mb-3">
		<button type="submit" class="btn btn-primary" id="joinBtn" disabled>회원가입</button>
	</div>
</form>