<?php
namespace application\libraries;

class Pagination {
	private $pagePerList;
	private $blockPerPage;
	private $totalCount;
	private $page;
	private $totalPage;
	private $totalBlock;
	private $startPage;
	private $endPage;
	private $prevBlock;
	private $nextBlock;
	private $prevBlockPage;
	private $nextBlockPage;
	private $html;

	function __construct($totalCount, $page = 1) {
		$this->pagePerList = 5;		// 한 페이지당 게시글 수
		$this->blockPerPage = 2;	// 한 블럭당 페이지 수

		$this->totalCount = $totalCount;	// 전체 갯수
		$this->page = $page;				// 현재 페이지

		$this->totalPage = ceil($this->totalCount/$this->pagePerList);		// 전체 페이지 수
		$this->totalBlcok = ceil($this->totalPage/$this->blockPerPage);		// 전체 블럭 수
		$block = ceil($this->page/$this->totalBlcok);						// 현재 블럭
		$this->startPage = ($block - 1) * $this->blockPerPage + 1;			// 블럭당 시작 페이지
		if($this->startPage <= 0) {
			$this->startPage = 1;
		}
		$this->endPage = $block*$this->blockPerPage;		// 블럭당 마지막 페이지
		if($this->endPage > $this->totalPage) {
			$this->endPage = $this->totalPage;
		}

		$this->prevBlock = $block - 1;										// 이전 블럭
		$this->prevBlockPage = $this->prevBlock * $this->blockPerPage;		// 이전 블럭 페이지

		$this->nextBlock = $block + 1;												// 다음 블럭
		$this->nextBlockPage = ($this->nextBlock - 1) * $this->blockPerPage + 1;	// 다음 블럭 페이지 
	}

	public function getPagingHtml($url) {
		if($this->prevBlock > 0) {
			$this->html .= '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . $this->prevBlockPage . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
		}

		for($i = $this->startPage; $i <= $this->endPage; $i++) {
			if($i == $this->page) {
				$this->html .= '<li class="page-item active"><a class="page-link" href="' . $url . '?page=' . $i . '">'. $i . '</a></li>';
			}
			else {
				$this->html .= '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . $i . '">'. $i . '</a></li>';
			}
		}

		if($this->nextBlock <= $this->totalBlcok) {
			$this->html .= '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . $this->nextBlockPage . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
		}

		return $this->html;
	}
}
?>