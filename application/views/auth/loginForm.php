<form class="row g-3" method="post" action="/board_mvc/auth/login">
	<div class="mb-3">
		<input type="text" class="form-control" name="id" placeholder="아이디">
	</div>
	<div class="mb-3">
		<input type="password" class="form-control" name="password" placeholder="비밀번호">
	</div>
	<input type="hidden" name="loginUrl" value=<?php echo $data['loginUrl']; ?>>
	<div class="mb-3">
		<button type="submit" class="btn btn-primary" id="loginBtn">로그인</button>
	</div>
</form>