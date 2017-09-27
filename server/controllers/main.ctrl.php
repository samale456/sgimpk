<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/server/config/constants.conf.php");

include_once(_ROOT_PATH . "server/controllers/controller.php");

include_once(_ROOT_PATH . "server/models/bitacora.mod.php");

//////////

class MainCtrl extends Controller {
	private $bitacora;
	private $model;

	public function __construct(){
		parent::__construct();
		$this->bitacora = new BitacoraMod();
	}
	/**
	 * @param [string] modelName
	 * @param [string] modelClassName
	 */
	public function setModel($modelName, $modelClassName){

		try {
			include_once($root . "src/models/" . $modelName . ".php");
			$this->model = new $modelClassName();

		} catch (Exception $e) {
			$this->status = $e->getMessage();
		}
	}

	public function consultRow(){
		try {
			$this->dataO = $this->model->consultRow($this->dataI);
			if($this->dataO == null){
				$this->dataO['success'] = false;
				$this->status = 0;
			}else{
				$this->dataO['success'] = true;
				$this->status = 1;
			}
		}catch(Exception $e){

		}
	}

	public function consultAll(){
		try {
			if($this->dataI == null){
				$this->dataO['datos'] = $this->model->consultAll();
			}
			else{
				$this->dataO['datos'] = $this->model->consultAll($this->dataI);
			}

			if($this->dataO['datos'] == null){
				$this->dataO['success'] = false;
				$this->status = 0;
			}else{
				$this->dataO['success'] = true;
				$this->status = 1;
			}
		}catch(Exception $e){

		}
	}

	public function register(){
		try {
			if(!$this->model->existingData($this->dataI)){
				$result = $this->model->register($this->dataI);
				if($result['success']){
					$this->status = 'Registro exitoso';
					//cargar los datos de la bitacora
					$result['data']['hora'] = date('h: i: s a');  
					$result['data']['fecha'] = date('Y-m-d'); 
					$result['data']['modulo'] = $this->model->getName();
					$result['data']['funcion'] = $this->request;
					$result['data']['id_sesion'] = 1;//de momento mientra se desarrolla el modulo del "login" 
					$this->modBitacora->register($result['data']);
				}else{
					$this->status = 'Registro fallido';
				}
			}else
			$this->status = 'Ya existe ese registro';
		}catch(Exception $e){

		}
	}

	public function modify(){
		try {
			if(!$this->model->existingData($this->dataI)){
				$result = $this->model->modify($this->dataI);
				if($result['success']){
					$this->status = 'Modificación exitosa';
					//cargar los datos de la bitacora
					$result['data']['hora'] = date('h: i: s a');  
					$result['data']['fecha'] = date('Y-m-d'); 
					$result['data']['modulo'] = $this->model->getName();
					$result['data']['funcion'] = $this->request;
					$result['data']['id_sesion'] = 1;//de momento mientra se desarrolla el modulo del "login" 
					$this->modBitacora->register($result['data']);
				}else{
					$this->status = 'Modificación fallida';
				}
			}else
			$this->status = 'Ya existe ese registro';
		}catch(Exception $e){

		}
	}

	public function remove(){
		try {
			$result = $this->model->remove($this->dataI);
			if($result['success']){
				$this->status = 'Inactivación Éxitosa';
				//cargar los datos de la bitacora
				$result['data']['hora'] = date('h: i: s a');  
				$result['data']['fecha'] = date('Y-m-d'); 
				$result['data']['modulo'] = $this->model->getName();
				$result['data']['funcion'] = $this->request;
				$result['data']['id_sesion'] = 1;//de momento mientra se desarrolla el modulo del "login" 
				$this->modBitacora->register($result['data']);
			}else{
				$this->status = 'Inactivación Fallida';
			}
		}catch(Exception $e){

		}
	}

	public function loadFK(){
		try {
			if($this->dataI == null){
				$this->dataO['datos'] = $this->model->consultFk();
			}
			else{
				$this->dataO['datos'] = $this->model->consultFk($this->dataI);
			}

			if($this->dataO['datos'] == null){
				$this->dataO['success'] = false;
				$this->status = 0;
			}else{

				$this->dataO['success'] = true;
				$this->status = 1;
			}
		}catch(Exception $e){

		}
	}

	//sobreescritura del metodo start
	public function start(){
		try {
			switch ($this->request) {
				case _CONSULT:
				$this->consultRow();
				break;

				case _CONSULT_ALL:
				$this->consultAll();
				break;

				case _REGISTER:
				$this->register();
				break;

				case _MODIFY:
				$this->modify();
				break;

				case _REMOVE:
				$this->remove();
				break;

				case _LOAD_FK:
				$this->loadFK();
				break;

				default:

			}
		} catch (Exception $e) {

		}
	}
}
?>
