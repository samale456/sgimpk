<?php
abstract class Model {
	private $server = "localhost";
	private $user = "root";
	private $pass = "";
	private $bd = "SGIM_PK";
	protected $connect;

	public function __construct(){
		try {
			$this->connect = new PDO('mysql:host='.$this->server . ';dbname=' . $this->bd, $this->user, $this->pass);
			$this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			throw new Exception($e->getMessage() . '<<< ' . date('Y/m/d - h:i') .
				' -> Falló la conexión >>>' . chr(10));
		}
	}

	protected function assignId($nameModule){
		$id;
		try {
			$result = $this->connect->prepare(
				'SELECT MAX(id_'.$nameModule.') AS maxID FROM '.$nameModule); 
			$result->execute();
			$id = $result->fetch()[0] + 1;
		}catch (Exception $e) {
			throw new PDOException($e->getMessage() . '<<<' . date('Y/m/d - h:i') .
				' -> Falló la asignacion de id en la clase Model en: model.php ' . chr(10));
		}
		return $id;
	}

}
?>