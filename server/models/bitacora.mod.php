<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/server/config/constants.conf.php");

include_once(_ROOT_PATH . "server/models/model.php");

class BitacoraMod extends Model{
	private $datosBitacora;
	
	public function __construct(){
		try {
			parent::__construct();
		} catch (Exception $e) {
			throw new Exception('Error interno revisar el archivo de errores');
		}
	}

	public function setConnection($objConnection){
		$success;
		if(isset($objConnection)){
			$this->connect = $objConnection;
			$success = true;
		}
		else
			$success = false;

		return $success;
	}

	public function consultRow($data){
		//consultar un registro de la bitacora 
	}

	public function consultAll(){
		//consultar el catalogo de bitacora
	}

	public function consultFk($filter){
		//consulta de los combos para los filtros de la bitacora
	}

	public function register($data){
		$success;
		try {
			if(isset($data)){
				$this->datosBitacora = $data;
			}
			$this->datosBitacora['id_bitacora'] = $this->assignId('bitacora');

			$result = $this->connect->prepare("INSERT INTO bitacora(id_bitacora, hora, fecha, modulo, funcion, id_reg, id_sesion) VALUES (:id_bitacora, :hora, :fecha, :modulo, :funcion, :id_reg, :id_sesion)");

			if($result->execute($this->datosBitacora))
				$success = true;
			else
				$success = false;

		} catch (PDOException $e) {
			throw new Exception('Error interno revisar el archivo de errores');
		}
		return $success;
	}

	public function modify($data){
		$success;
		try {
			if(isset($data)){
				$this->datosBitacora = $data;
			}
			$this->datosBitacora['id_bitacora'] = $this->assignId('bitacora');
			$result = $this->connect->prepare("INSERT INTO bitacora(id_bitacora, hora, fecha, modulo, funcion, id_reg, id_sesion) VALUES (:id_bitacora, :hora, :fecha, :modulo, :funcion, :id_reg, :id_sesion)");
			if($result->execute($this->datosBitacora))
				$success = true;
			else
				$success = false;

		} catch (PDOException $e) {
			throw new Exception('Error interno revisar el archivo de errores');
		}
		return $success;
	}

	public function getName(){
		return 'bitacora';
	}

	/*function __destruct() {
		parent::__destruct();
		unset($this);
	}*/
}
?>