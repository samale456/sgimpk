<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/server/config/constants.conf.php");

include_once(_ROOT_PATH . "server/controllers/session.ctrl.php");
include_once(_ROOT_PATH . "server/libs/errorLog.lib.php");

class Request{
	private static $modules;
	private static $request = array();
	private static $redirect = array();
	private static $session;
	private static $layoutPath;
	private static $indexPath;

	public static function setsession(SessionCtrl $session){
		if(!empty($session)){
			self::$session = $session;
		}else
		throw new Exception("El objeto session esta vacio ó es nulo");
	}

	public static function setModules($arrayModules){
		if(!empty($arrayModules) && is_array($arrayModules))
			self::$modules = $arrayModules;
		else
			throw new Exception("El array de los modulos esta vacio ó no contiene la estructura esperada");
	}

	public static function setLayout($layoutPath){
		if(!empty($layoutPath) && file_exists($layoutPath))
			self::$layoutPath = $layoutPath;
		else
			throw new Exception("La pagina layout no esta establecida ó no existe");
	}

	public static function setPageindex($indexPath){
		if(!empty($indexPath) && file_exists($indexPath))
			self::$indexPath = $indexPath;
		else
			throw new Exception("La pagina index no esta establecida ó no existe");
	}

	public static function route($config, $ctrl, $callBackRq = null){
		$auxConfig = json_decode($config, true);
		self::$request[$auxConfig['name']] = $auxConfig;
		self::$request[$auxConfig['name']]['ctrl'] = $ctrl;

		self::$request[$auxConfig['name']]['callBackRq'] = $callBackRq;
	}

	public static function redirect($config){
		$auxConfig = json_decode($config, true);
		self::$redirect[$auxConfig['name']] = $auxConfig;
	}

	public static function go($eventName){

	}

	public static function start(){
		$method = null;
		$data = null;

		if(empty($_GET))
			$_GET = array(_MODULE_PARAM => 'page', 'name' => 'dashboard');

		$requestModule = self::$request[$_GET[_MODULE_PARAM]];

		if(!empty($requestModule['method']))
			$method = $requestModule['method'];

		if($method == 'GET')
			$data = $_GET;
		else
			$data = $_POST;

		if(_VALIDATE_SESSION && self::$session->authenticate() == false){
			echo file_get_contents("app/login.html");
			echo json_encode(array('status' => 0));
			exit;
		}

		if(_VALIDATE_PROFILE && ($requestModule['validateSession'] && self::$session->authenticateProfile() == false))
			echo ErrorLog::getPageError(403);

		if(!empty($requestModule['ctrl'])){
			$requestModule['ctrl']->setParams($requestModule['link']);
			$requestModule['ctrl']->setDataParams($_GET);
			$requestModule['ctrl']->setData($data);
			$requestModule['ctrl']->start();
			if(!empty($requestModule['callBackRq']))
				$requestModule['callBackRq']($requestModule['ctrl']->getData());
			else
				echo json_encode($requestModule['ctrl']->getMessage());
		}
	}
}

?>