<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<div class="container-fluid">
		<a class="navbar-brand" href="/board">BOARD</a>
		<?php
		if(!isset($_SESSION['memberSeq'])) :
			$url = '/board_mvc/auth/loginForm?url=' . urlencode($_SERVER['REQUEST_URI']);
		?>
		<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
			<div class="navbar-nav">
				<a class="nav-link" href="<?php echo $url; ?>">로그인</a>
				<a class="nav-link" href="">회원가입</a>
			</div>
		</div>
		<?php
		else :
			$id = $_SESSION['id'];
		?>
		<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
			<div class="navbar-nav">
				<span class="nav-link"><?php echo $id; ?>님</span>
				<a class="nav-link" href="">로그아웃</a>
			</div>
		</div>
		<?php endif; ?>
	</div>
</nav>