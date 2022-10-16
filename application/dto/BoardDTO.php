<?php
namespace application\dto;

class BoardDTO {
	private $listSeq;
	private $memberSeq;
	private $title;
	private $content;
	private $createDate;
	private $updateDate;
	private $displayYN;
	private $hit;
	private $offset;
	private $limit;

	public function getListSeq() {
		return $this->listSeq;
	}

	public function setListSeq($listSeq) {
		$this->listSeq = $listSeq;
	}

	public function getMemberSeq() {
		return $this->memberSeq;
	}

	public function setMemberSeq($memberSeq) {
		$this->memberSeq = $memberSeq;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getContent() {
		return $this->content;
	}

	public function setContent($content) {
		$this->content = $content;
	}

	public function getCreateDate() {
		return $this->createDate;
	}

	public function setCreateDate($createDate) {
		$this->createDate = $createDate;
	}

	public function getUpdateDate() {
		return $this->updateDate;
	}

	public function setUpdateDate($updateDate) {
		$this->updateDate = $updateDate;
	}

	public function getDisplayYN() {
		return $this->displayYN;
	}

	public function setDisplayYN($displayYN) {
		$this->displayYN = $displayYN;
	}

	public function getHit() {
		return $this->hit;
	}

	public function setHit($hit) {
		$this->hit = $hit;
	}

	public function getOffset() {
		return $this->offset;
	}

	public function setOffset($offset) {
		$this->offset = $offset;
	}

	public function getLimit() {
		return $this->limit;
	}

	public function setLimit($limit) {
		$this->limit = $limit;
	}
}
?>