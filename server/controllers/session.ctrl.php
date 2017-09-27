<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/server/config/constants.conf.php");

include_once(_ROOT_PATH . "server/controllers/controller.php");

include_once(_ROOT_PATH . "server/models/session.mod.php");

class SessionCtrl extends Controller{
	private $session;

	public function __construct(){
		parent::__construct();
		$this->session = new SessionMod();
	}

	public function start(){
		
	}

	public function onLog($event, $data = null){
		$success = false;
		if($event == 'in'){
			$this->logIn($data['userName'], $data['password'], $data['recordar']);
			$success = true;
		}

		if($event == 'out'){
			$this->logOut();
			$success = true;
		}
		return $success;
	}

	private function logIn($userName, $password, $record){
		$success;
		$aux;
		session_start();
		$aux = $this->sesion->in($userName, $password, $record);

		if($aux['success']){
			$_SESSION['user'] = $aux;
			session_commit();
			$success = true;
		}else{
			session_destroy();
			$success = false;
		}
		return $success;
	}

	private function logOut(){
		$success;

		session_start();
		if(empty($_SESSION['user'])){
			session_commit();
			return;
		}
		
		if($this->sesion->out($_SESSION['user']['dataUser']['id_sesion'],
			$_SESSION['user']['token'])){
			unset($_SESSION['user']);
			session_destroy();
			$success = true;
		}else{
			session_commit();
			$success = false;
		}
		return $success;
	}

	public function authenticate(){
		$authenticate = false;
		session_start();
		
		if(isset($_SESSION['user']))
			$authenticate = true;

		session_commit();
		return $authenticate;
	}

	public function authenticateProfile(){
		$authenticate = false;

		return $authenticate;
	}

	public function getDataUser(){
		$privileges = '{}';
		session_start();
		if(isset($_SESSION['user']['dataUser']))
			$privileges = $_SESSION['user']['dataUser'];
		session_commit();
		return $privileges;
	}
}
?>