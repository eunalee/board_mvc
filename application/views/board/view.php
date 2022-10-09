<script src="/board_mvc/www/js/board.js"></script>

<div class="card">
	<div class="card-header">
		<h5><?php echo $data['boardList']['sTitle']; ?></h5>
		<p class="card-text"><?php echo $data['boardList']['sId']; ?> | <?php echo date('Y.m.d. H:i:s', strtotime($data['boardList']['dtCreateDate'])); ?> | <?php echo $data['boardList']['nHit']; ?></p>
	</div>
	<div class="card-body">
		<div class="card-title">
		<?php echo str_replace('&lt;br&gt;', '<br>', $data['boardList']['sContent']); ?>
		</div>
	</div>
</div>
<p class="lead"></p>
<a href="/board_mvc?page=<?php echo $data['page']; ?>" class="btn btn-primary">목록</a>
<?php if(isset($_SESSION['memberSeq']) && in_array($_SESSION['memberSeq'], array(1, $data['nMemberSeq']))) : ?>
<a href="#" class="btn btn-primary">수정</a>
<a href="#" class="btn btn-primary">삭제</a>
<?php endif; ?>

<p class="lead"></p>
<div class="list-group">
	<?php if(isset($data['commentList']) && sizeof($data['commentList']) > 0) : ?>
	<p class="col-sm-3"><strong>댓글(<?php echo sizeof($data['commentList']); ?>)</strong></p>
	<?php foreach($data['commentList'] as $comment) : ?>
	<div class="list-group-item list-group-item-action">
		<div class="d-flex w-100 justify-content-between">
			<span><?php echo $comment['sId']; ?> | <?php echo date('Y.m.d. H:i:s', strtotime($comment['dtCreateDate'])); ?></span>
			<small><a href="#" class="text-reset text-decoration-none" commentseq="<?php echo $comment['nCommentSeq']; ?>">댓글</a></small>
		</div>
		<p class="mb-1"><?php echo str_replace('&lt;br&gt;', '<br>', $comment['sContent']); ?></p>
	</div>
	<?php
			endforeach; 
		endif;
	?>
	<form class="list-group-item list-group-item-action" method="post" action="/board_mvc/board/writeComment" id="commentForm">
		<div class="mb-3">
			<textarea class="form-control" id="comment" name="comment" placeholder="댓글을 입력해주세요."></textarea>
		</div>
		<input type="hidden" id="listSeq" name="listSeq" value="<?php echo $data['boardList']['nListSeq']; ?>">
		<input type="hidden" id="parentSeq" name="parentSeq" value="0">
		<input type="hidden" id="memberSeq" name="memberSeq" value="<?php echo isset($_SESSION['memberSeq']) ? $_SESSION['memberSeq'] : 0; ?>">
		<input type="hidden" id="depth" name="depth" value="1">
		<input type="hidden" id="sort" name="sort" value="1">
		<input type="hidden" id="group" name="group" value="0">
		<input type="hidden" id="page" name="page" value="<?php echo $data['page']; ?>">
		<div class="mb-3">
			<button type="reset" class="btn btn-primary">취소</button>
			<button type="button" class="btn btn-primary" id="boardCommentWriteBtn">등록</button>
		</div>
	</form>
</div>