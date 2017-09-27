<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/server/config/constants.conf.php");

include_once(_ROOT_PATH . "server/models/model.php");

class SessionMod extends Model{

	public function __construct(){
		try {
			parent::__construct();
		} catch (Exception $e) {
			throw new Exception($e->getMessage().
				'<<< Error en el contructor del modelo sesion >>>'.chr(10));
		}
	}

	public function in($userName, $password, $record){
		$dataLog['userName'] = $userName;
		$dataLog['password'] = $password;

		try{
			$result = $this->connect->prepare("SELECT usuario.id_usuario, usuario.nombre_usuario, usuario.clave_usuario, usuario.status_usuario, usuario.foto_persona, persona.nombre_persona, persona.apellido_persona, perfil.nombre_perfil, perfil.privilegio_perfil,
				perfil.status_perfil
				FROM usuario 
				JOIN perfil ON perfil.id_perfil = usuario.id_perfil
				JOIN persona ON persona.documento_persona = usuario.documento_persona
				WHERE usuario.nombre_usuario = :userName AND usuario.clave_usuario = :password AND usuario.status_usuario = 'A'");
			$result->execute($dataLog);
			if($result->rowCount() > 0){ 
				$consult = $result->fetch(PDO::FETCH_BOTH);
				$success = true;
			}	
			else {  
				$consult = null; 
				$success = false;
			}

			if($success){
				$dataSesion['id_sesion'] = $this->assignId('sesion');
				$dataSesion['inicio'] = date('Y-m-d H:i:s');
				$dataSesion['cierre'] = null;
				$dataSesion['ip_usuario'] = $this->getRealIP();
				$dataSesion['id_usuario'] = $consult['id_usuario'];
				$dataSesion['recordar'] = $record;
				$dataSesion['token'] = $this->generateToken($consult['id_usuario']);

				$result = $this->connect->prepare("INSERT INTO sesion(id_sesion, inicio, cierre, ip_usuario, id_usuario, recordar, token) VALUES (:id_sesion, :inicio, :cierre, :ip_usuario, :id_usuario, :recordar, :token)");

				if($result->execute($dataSesion)){
					$resultData['token'] = $dataSesion['token'];
					$resultData['dataUser']['id_usuario'] = $consult['id_usuario'];
					$resultData['dataUser']['privilegio_perfil'] = $consult['privilegio_perfil'];
					$resultData['dataUser']['nombre_persona'] = $consult['nombre_persona'];
					$resultData['dataUser']['apellido_persona'] = $consult['apellido_persona'];
					$resultData['dataUser']['foto_persona'] = $consult['foto_persona'];
					$resultData['dataUser']['id_sesion'] = $dataSesion['id_sesion'];
				}else
				$success = false;
			}

			$resultData['success'] = $success;
		} catch (PDOException $e) {
			throw new Exception('Error interno revisar el archivo de errores');
		}

		return $resultData;
	}

	public function out($idSesion, $token){
		$dataLog['idSesion'] = $idSesion;
		$dataLog['token'] = $token;

		try{
			$result = $this->connect->prepare("SELECT id_sesion, cierre
				FROM sesion WHERE id_sesion = :idSesion AND token = :token");
			$result->execute($dataLog);
			if($result->rowCount() > 0){
				$consult = $result->fetch(PDO::FETCH_BOTH);
				$success = true;
			}
			else{
				$consult = null;
				$success = false;
			}

			if($success && $consult['cierre'] == null){
				$dataOut = $dataLog;
				$dataOut['cierre'] = date('Y-m-d H:i:s');
				$result = $this->connect->prepare("UPDATE sesion SET cierre = :cierre WHERE id_sesion = :idSesion AND token = :token");

				if(!$result->execute($dataOut))
					$success = false;

			}else
			$success = false;

			return $success;
		} catch (PDOException $e) {
			throw new Exception('Error interno revisar el archivo de errores');
		}
	}

	private function generateToken($idUser){
		return md5($idUser .'-'. date('Y-m-d H:i:s'));
	}

	private function getRealIP() {
		$ip = null;
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
			$ip = $_SERVER['HTTP_CLIENT_IP'];

		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else
			$ip = $_SERVER['REMOTE_ADDR'];

		return $ip;
	}
}

?>