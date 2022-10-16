<?php
namespace application\dto;

class AuthDTO {
	private $memberSeq;
	private $name;
	private $id;
	private $password;

	public function getMemberSeq() {
		return $this->memberSeq;
	}

	public function setMemberSeq($memberSeq) {
		$this->memberSeq = $memberSeq;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getPassword() {
		return $this->password;
	}

	public function setPassword($password) {
		$this->password = $password;
	}
}
?>