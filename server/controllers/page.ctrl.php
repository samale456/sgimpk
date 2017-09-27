<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/server/config/constants.conf.php");

include_once(_ROOT_PATH . "server/controllers/controller.php");


class PageCtrl extends Controller{
	public function start(){
		echo file_get_contents("app/layout.html");
	}

	public function getData(){
		if(!empty($this->status))
			$this->dataOutput['status'] = $this->status;
		else
			$this->dataOutput['status'] = 'falla algo';

		$this->dataOutput['success'] = false;

		return $this->dataOutput;
	}
}
?>