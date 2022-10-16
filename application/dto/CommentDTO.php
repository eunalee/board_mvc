<?php
namespace application\dto;

class CommentDTO {
	private $commentSeq;
	private $listSeq;
	private $parentSeq;
	private $memberSeq;
	private $content;
	private $depth;
	private $sort;
	private $group;
	private $createDate;
	private $updateDate;
	private $displayYN;

	public function getCommentSeq() {
		return $this->commentSeq;
	}

	public function setCommentSeq($commentSeq) {
		$this->commentSeq = $commentSeq;
	}

	public function getListSeq() {
		return $this->listSeq;
	}

	public function setListSeq($listSeq) {
		$this->listSeq = $listSeq;
	}

	public function getParentSeq() {
		return $this->parentSeq;
	}

	public function setParentSeq($parentSeq) {
		$this->parentSeq = $parentSeq;
	}

	public function getMemberSeq() {
		return $this->memberSeq;
	}

	public function setMemberSeq($memberSeq) {
		$this->memberSeq = $memberSeq;
	}

	public function getContent() {
		return $this->content;
	}

	public function setContent($content) {
		$this->content = $content;
	}

	public function getDepth() {
		return $this->depth;
	}

	public function setDepth($depth) {
		$this->depth = $depth;
	}

	public function getSort() {
		return $this->sort;
	}

	public function setSort($sort) {
		$this->sort = $sort;
	}

	public function getGroup() {
		return $this->group;
	}

	public function setGroup($group) {
		$this->group = $group;
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
}
?>