<?php
namespace application\libraries;

class Layout {
	public function renderTop($topData) {
		$title = isset($topData['title']) && $topData['title'] != '' ? $topData['title'] : '게시판';
		require_once 'application/views/layouts/header.php';
	}

	public function renderBottom() {
		require_once 'application/views/layouts/footer.php';
	}

	public function renderBody($viewName, $bodyData) {
		if(sizeof($bodyData) > 0) {
			$data = $bodyData;
		}
		require_once "application/views/$viewName.php";
	}

	public function renderWithFrame($viewName, $bodyData, $topData) {
		if(sizeof($bodyData) > 0) {
			$data = $bodyData;
		}

		$this->renderTop($topData);
		require_once 'application/views/layouts/navibar.php';
		require_once "application/views/$viewName.php";
		$this->renderBottom();
	}
}
?>