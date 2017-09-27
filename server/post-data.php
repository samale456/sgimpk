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
	$sesion = new CtrlSesion();

	/**
	* verificacion de una peticion de modulo al servidor
	*/

	if(empty($_GET['md']))
		throw new Exception('<<< ' . date('Y/m/d - h:i') .
			" -> No se a definido la peticion del modulo >>>");
	else
		$moduledRequest = $_GET['md'];

}catch(Exception $e){
	Request::errorControl($e->getMessage());
}


switch($moduledRequest){
	case _UNDEFINED_REQUEST:

	break;

	case _SESION:
	try{
		$status = false;

		if($sesion->onLog($_GET['rq'], $_POST['datos']))
			$status = true;

		echo json_encode(array("status" => $status));
	}catch(Exception $e){
		Request::errorControl($e->getMessage(), false);
	}
	break;

	case _FILES:
	try{
		if(!empty($_FILES)){
			//datos de la imagen
			$imagen = $_FILES["imagen"];
			//carpeta destino
			$carpeta = "../files/img/";
			//se toma el tipo de archivo
			$imageType = '.' . explode('/', $imagen["type"])[1];
			//se establese el nuevo nombre unico
			$nuevoNombre = md5($imagen["name"] . date('h: i: s a')).$imageType;

			$ruta = "../" . $carpeta . $nuevoNombre;
			move_uploaded_file($imagen["tmp_name"], $ruta);

			//AQUI YA TIENES LA RUTA COMPLETA CON EL DOMINIO LISTA PARA GUARDAR EN LA BD
			echo json_encode(array("rutaImagen" => $carpeta . $nuevoNombre));
		}
	}catch(Exception $e){
		Request::errorControl($e->getMessage());
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
		$ctrl->setData($_POST['data']);
		$ctrl->start();

		/**
		 * se retornan (imprimen) los datos en formato json
		 */
		echo json_encode($ctrl->getDataBack());
	}catch(Exception $e){
		Request::errorControl($e->getMessage());
	}
}// fin switch case

?>