<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/server/config/constants.conf.php");

include_once(_ROOT_PATH . "server/models/model.php");

class ProfileMod extends Model{
	private $datosPerfil = array(
		"id_perfil" => "", 
		"nombre_perfil" => "", 
		"privilegio_perfil" => "",
		"status_perfil" => "");
	
	public function __construct(){
		try {
			parent::__construct();
		} catch (Exception $e) {
			throw new Exception('Error interno revisar el archivo de errores');
		}
	}

	public function consultRow($data){
		$consult;
		try {
			$result = $this->connect->prepare("SELECT * FROM perfil WHERE id_perfil = :id_perfil");
			$result->execute($data);
			if($result->rowCount() > 0){ 
				$consult = $result->fetch(PDO::FETCH_BOTH);
			}	
			else {  
			$consult = null; }
				
		} catch (PDOException $e) {
			throw new Exception('Error interno revisar el archivo de errores');
		}
		return $consult;
	}

	public function consultAll(){
		$consult;
		try {
			$result = $this->connect->prepare("SELECT * FROM `perfil`");
			$result->execute();
			if($result->rowCount() > 0)
				$consult = $result->fetchAll(PDO::FETCH_CLASS);
			else
				$consult = null;
		} catch (PDOException $e) {
			throw new Exception('Error interno revisar el archivo de errores');
		}
		return $consult;
	}


	public function consultFk(){
		$consult;
		try {
			$result = $this->connect->prepare("SELECT id_perfil, nombre_perfil FROM `perfil` where status_perfil='A'");
			$result->execute();
			if($result->rowCount() > 0)
				$consult = $result->fetchAll(PDO::FETCH_CLASS);
			else
				$consult = null;
		} catch (PDOException $e) {
			throw new Exception('Error interno revisar el archivo de errores');
		}
		return $consult;
	}

	public function register($data){
		$success;
		$resultData;
		try {
			if(isset($data)){
				$this->datosPerfil = $data;
			}
			$this->datosPerfil['id_perfil'] = $this->assignId('perfil');
			//se carga la consulta SQL
			$result = $this->connect->prepare("INSERT INTO perfil(id_perfil, nombre_perfil, privilegio_perfil, status_perfil) VALUES (:id_perfil, :nombre_perfil, :privilegio_perfil, 'A')");
			//Se ejecuta la consulta
			if($result->execute($this->datosPerfil)){
				$success = true;
				//Se cargan los datos de la bitacora
				//se captura el id recien registrado para retornarlo y usarlo en el regitro de la bitacora
				$resultData['data']['id_reg'] = $this->datosPerfil['id_perfil'];
				//retorno del objeto de conexion para ser usado por el modelo de la bitacora
				$resultData['connect'] = $this->connect;
				//registro exitoso
				$resultData['success'] = $success;
			}
			else
				$success = false;
		} catch (PDOException $e) {
			throw new Exception('Error interno revisar el archivo de errores');
		}catch(Exception $e) {
		}
		return $resultData;
	}

	public function modify($data){
		$success;
		$resultData;
		try {
			if(isset($data)){
				$this->datosPerfil = $data;
			}
			//se carga la consulta SQL
			$result = $this->connect->prepare("UPDATE perfil SET nombre_perfil=:nombre_perfil, privilegio_perfil=:privilegio_perfil, status_perfil=:status_perfil WHERE id_perfil = :id_perfil");
			//Se ejecuta la consulta
			if($result->execute($this->datosPerfil)){
				$success = true;
				//Se cargan los datos de la bitacora
				//se captura el id recien registrado para retornarlo y usarlo en el regitro de la bitacora
				$resultData['data']['id_reg'] = $this->datosPerfil['id_perfil'];
				//retorno del objeto de conexion para ser usado por el modelo de la bitacora
				$resultData['connect'] = $this->connect;
				//registro exitoso
				$resultData['success'] = $success;
			}
			else
				$success = false;
		} catch (PDOException $e) {
			throw new Exception('Error interno revisar el archivo de errores');
		}catch(Exception $e) {
		}
		return $resultData;
	}

	public function remove($data){
			$success;
			$resultData;
			try {
				if(isset($data)){
					$this->datosPerfil = $data;
				}
					//se carga la consulta SQL
				$result = $this->connect->prepare("UPDATE perfil SET status_perfil='I' WHERE id_perfil = :id_perfil");
					//Se ejecuta la consulta
				if($result->execute($this->datosPerfil)){
					$success = true;
						//Se cargan los datos de la bitacora
						//se captura el id recien registrado para retornarlo y usarlo en el regitro de la bitacora
					$resultData['data']['id_reg'] = $this->datosPerfil['id_perfil'];
						//retorno del objeto de conexion para ser usado por el modelo de la bitacora
					$resultData['connect'] = $this->connect;
						//registro exitoso
					$resultData['success'] = $success;
				}
				else
					$success = false;
			} catch (PDOException $e) {
				throw new Exception('Error interno revisar el archivo de errores');
			}catch(Exception $e) {
			}
			return $resultData;
	}

	public function existingData($data){
		$success;
		try {
			if (isset($data['status_perfil'])) {
				$result = $this->connect->prepare('SELECT COUNT(nombre_perfil) FROM perfil WHERE nombre_perfil = ? AND status_perfil = ?');
				$result->execute(array($data['nombre_perfil'], $data['status_perfil']));
			}
			else{
				$result = $this->connect->prepare('SELECT COUNT(nombre_perfil) FROM perfil WHERE nombre_perfil = ?');
				$result->execute(array($data['nombre_perfil']));
			}

			if($result->fetch()[0] == 0)
				$success = false;
			else
				$success = true;
		} catch (PDOException $e) {
			throw new Exception('Error interno revisar el archivo de errores');
		}
		return $success;
	}

	public function eliminated($data){
		$success;
		try {
			$result = $this->connect->prepare('SELECT status_perfil FROM perfil WHERE id_perfil = ?');
			$result->execute(array($data['id_perfil']));
			if($result->fetch()[0] == 'I') {
				$success = false;
			}
			else
				$success = true;
		} catch (PDOException $e) {
			throw new Exception('Error interno revisar el archivo de errores');
		}
		return $success;
	}

	public function getName(){
		return 'perfil';
	}

	/*function __destruct() {
		parent::__destruct();
		unset($this);
	}*/
}
?>