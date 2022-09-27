	<?php if(isset($data['boardList']) && sizeof($data['boardList']) > 0) : ?>
	<ul class="list-group">
		<?php foreach($data['boardList'] as $board) : ?>
		<li class="list-group-item list-group-item-action d-flex justify-content-between align-items-start" onclick="view(<?php echo $board['nListSeq']; ?>)">
			<div class="ms-2 me-auto">
				<div class="fw-bold"><?php echo $board['sTitle']; ?></div>
				<?php echo $board['sId']; ?> | <?php echo date('Y.m.d.', strtotime($board['dtCreateDate'])); ?>
			</div>
			<span class="badge bg-primary rounded-pill"><?php echo $board['nHit']; ?></span>
		</li>
		<?php endforeach;; ?>
	</ul>
	<?php else : ?>
	<ul class="list-group">
		<li class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
			<div class="ms-2 me-auto">
				<div class="fw-bold">등록된 게시글이 없습니다.</div>
			</div>
		</li>
	</ul>
	<?php endif; ?>
	<nav aria-label="Page navigation example">
		<ul class="pagination">
			<?php echo $data['pagination']; ?>
		</ul>
	</nav>
	<a class="btn btn-primary" href="/board_mvc/board/writeListForm" role="button">글쓰기</a>
	<footer></footer>
</body>