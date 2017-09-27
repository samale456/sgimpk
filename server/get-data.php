<?php
include_once("constantes.php");
include_once("request.php");
include_once(_PROJECT_PATH . "src/controllers/sesion.ctrl.php");


try{
	$moduledRequest = _UNDEFINED_REQUEST;

	/**
	 * [$moduleText, archivo json con las configuraciones de los modulos del sistema]
	 * @var [string] $moduleText
	 * 
	 * [$modules, objeto json de las configuraciones de los modulos convertidos a un array]
	 * @var [array] $modules
	 */
	$moduleText = file_get_contents("modules.config.json");
	$modules = json_decode($moduleText, true);

	/**
	* [$sesion, ]
	* @var CtrlSesion
	*/
	$sesion = new SesionCtrl();

	/**
	* verificacion de una peticion de modulo al servidor
	*/

	if(empty($_GET['md']))
		throw new Exception('<<< ' . date('Y/m/d - h:i') .
			" -> No se a definido la pecion del modulo >>>");
	else
		$moduledRequest = $_GET['md'];

}catch(Exception $e){
	errorControl($e->getMessage());
}

switch($moduledRequest){
	case _UNDEFINED_REQUEST:

	break;

	case _PAGE:
	try{
		if(empty($_GET['pg']))
			throw new Exception('<<< ' . date('Y/m/d - h:i') .
				" -> No se a definido una peticion a una pagina >>>");

		$pagePath = _PROJECT_PATH . "app/pages/" . $_GET['pg'] . ".page.html";

		if(!$sesion->authenticate())
			$pagePath = _PROJECT_PATH . "app/pages/403.page.html";
		else
			if(!file_exists($pagePath))
				$pagePath = _PROJECT_PATH . "app/pages/404.page.html";

		$pageText = file_get_contents($pagePath);
		echo $pageText;
	}catch(Exception $e){
		Request::errorControl($e->getMessage());
	}
	break;

	case _MENU:
	try{
		$dataUser = [];
		if($sesion->authenticate()){
			$dataUser = $sesion->getDataUser();
			$dataUser['privilegio_perfil'] = json_decode($dataUser['privilegio_perfil']);
		}
		echo json_encode($dataUser);
	}catch(Exception $e){
		errorControl($e->getMessage());
	}
	break;

	default:
	try{
		/**
		* [$module se extrae el modulo correspondiente a la peticion]
		* @var [array] $module
		*/
		$module = $modules[$moduledRequest];

		/**
		* se verifica la definicion del nombre de clase controlador y modelo
		*/
		if(!empty($module['ctrlClassName']) && !empty($module['modClassName']))
			throw new Exception('<<< ' . date('Y/m/d - h:i') .
				" -> la definicion de la configuracion del modulo no esta completa >>>");

		/**
		* [$ctrlString nombre de la clase controlador]
		* @var [string] $ctrlName
		*/
		$ctrlName = $module['ctrlClassName'];

		/**
		 * [$ctrl instancia del controlador segun la configuracion definida en modulos.json]
		 * @var [Ctrl] $ctrl
		 */
		$ctrl = new $ctrlName();

		/**
		 * se establese el modelo, la peticion los datos y se inicia el proceso
		 */
		$ctrl->setModel($module['model']);
		$ctrl->setRequest($_GET['rq']);
		$ctrl->setData($_GET['data']);
		$ctrl->start();

		/**
		 * se retornan (imprimen) los datos en formato json
		 */
		echo json_encode($ctrl->getDataBack());

	}catch(Exception $e){
		errorControl($e->getMessage());
	}
}// fin switch case
?>