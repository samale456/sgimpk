<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/server/config/constants.conf.php");

include_once(_ROOT_PATH . "server/models/model.php");

class UserMod extends Model{
	private $datosUsuario = array(
		"id_usuario" => "", 
		"nombre_usuario" => "",
		"clave_usuario" => "",
		"id_perfil" => "",
		"documento_persona" => "",
		"status_usuario" => "");
	
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
			$result = $this->connect->prepare("SELECT usuario.id_usuario, usuario.nombre_usuario, usuario.clave_usuario,usuario.id_perfil, usuario.documento_persona, usuario.status_usuario, persona.nombre_persona, persona.apellido_persona, persona.foto_persona
				FROM usuario 
				JOIN perfil ON perfil.id_perfil =usuario.id_perfil
				JOIN persona ON persona.documento_persona =usuario.documento_persona 
				WHERE usuario.id_usuario = :id_usuario");
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
				$result = $this->connect->prepare("SELECT  usuario.id_usuario,usuario.nombre_usuario, usuario.id_perfil, usuario.documento_persona, usuario.status_usuario, persona.nombre_persona, persona.apellido_persona, perfil.nombre_perfil
					FROM usuario 
					JOIN perfil ON perfil.id_perfil =usuario.id_perfil
					JOIN persona ON persona.documento_persona =usuario.documento_persona ");
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

		public function consultFk($data = null){
			if(isset($data)){
				$this->datosEstado = $data; 
				try {
					$result = $this->connect->prepare("SELECT estado.nombre_estado, estado.codigo_estado 
						FROM estado 
						JOIN pais ON estado.codigo_pais =pais.codigo_pais
						WHERE estado.codigo_pais = :codigo_pais AND estado.status_estado = 'A'");
					$result->execute($data);
					if($result->rowCount() > 0)
						$consult = $result->fetchAll(PDO::FETCH_CLASS);
					else
						$consult = null;
				} catch (PDOException $e) {
					throw new Exception('Error interno revisar el archivo de errores');
				}
			}
			else
				try {
					$result = $this->connect->prepare("SELECT estado.nombre_estado, estado.codigo_estado 
						FROM estado 
						JOIN pais ON estado.codigo_pais =pais.codigo_pais 
						WHERE estado.status_estado = 'A' ");
					$result->execute($data);
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
						$this->datosUsuario = $data; }
						$this->datosUsuario['id_usuario'] = $this->assignId('usuario');
						$result = $this->connect->prepare("INSERT INTO usuario(id_usuario, nombre_usuario, clave_usuario, id_perfil, documento_persona, status_usuario) VALUES (:id_usuario, :nombre_usuario, :clave_usuario, :id_perfil, :documento_persona, 'A')");
						if($result->execute($this->datosUsuario)){
							$success = true;
				//se captura el id recien registrado para retornarlo y usarlo en el regitro de la bitacora
							$resultData['data']['id_reg'] = $this->datosUsuario['id_usuario'];
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
							$this->datosUsuario = $data;	}
							$result = $this->connect->prepare("UPDATE usuario SET nombre_usuario=:nombre_usuario, clave_usuario=:clave_usuario, id_perfil=:id_perfil, documento_persona=:documento_persona, status_usuario=:status_usuario  WHERE id_usuario = :id_usuario");
							if($result->execute($this->datosUsuario)){
								$success = true;
								//se captura el id recien registrado para retornarlo y usarlo en el regitro de la bitacora
								$resultData['data']['id_reg'] = $this->datosUsuario['id_usuario'];
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
								$this->datosEstado = $data;
							}
						//se carga la consulta SQL
							$result = $this->connect->prepare("UPDATE usuario SET status_usuario='I' WHERE id_usuario = :id_usuario");
						//Se ejecuta la consulta
							if($result->execute($this->datosEstado)){
								$success = true;
							//Se cargan los datos de la bitacora
							//se captura el id recien registrado para retornarlo y usarlo en el regitro de la bitacora
								$resultData['data']['id_reg'] = $this->datosEstado['id_usuario'];
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
							if (isset($data['status_usuario'])) {
								$result = $this->connect->prepare('SELECT COUNT(id_usuario) FROM usuario WHERE nombre_usuario = ? AND clave_usuario = ? AND id_perfil = ? AND documento_persona = ?  AND status_usuario = ?');
								$result->execute(array($data['nombre_usuario'], $data['clave_usuario'], $data['id_perfil'], $data['documento_persona'], $data['status_usuario']));
							}
							else{
								$result = $this->connect->prepare('SELECT COUNT(id_usuario) FROM usuario WHERE nombre_usuario = ? AND clave_usuario = ? AND id_perfil = ? AND documento_persona = ?  ');
								$result->execute(array($data['nombre_usuario'], $data['clave_usuario'], $data['id_perfil'], $data['documento_persona'],));
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
							$result = $this->connect->prepare('SELECT COUNT(status_usuario) FROM usuario WHERE id_usuario = ? AND status_usuario = "I"');
							$result->execute(array($data['id_usuario']));
							if($result->fetch()[0] == 0) {
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
						return 'usuario';
					}

	/*function __destruct() {
		parent::__destruct();
		unset($this);
	}*/
}
?>