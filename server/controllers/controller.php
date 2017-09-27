<?php
abstract class Controller {
	protected $status;
	protected $message;
	protected $params;
	protected $dataParams;
	protected $dataInput;
	protected $dataOutput;

	public function __construct(){
		try{
			date_default_timezone_set('America/Caracas');
		}catch(Exception $e){
			throw new Exception($e->getMessage() . '<<<'.date('Y/m/d - h:i').
				'Fallo la asignacion de zona horaria en la clase Controller en: controller.php'.chr(10));
		}
	}

	abstract public function start();

	public function setParams($params){
		if(!empty($params)){
			$params = explode('/', $params);
			$this->params = $params;
			//var_dump($this->params);
		}
		else
			throw new Exception('<<<'.date('Y/m/d - h:i').
				'Peticion vacia'.chr(10));
	}

	public function setDataParams($dataParams){
		if(!empty($dataParams))
			$this->dataParams = $dataParams;
		else
			throw new Exception('<<<'.date('Y/m/d - h:i').
				'Peticion vacia'.chr(10));
	}

	public function setData($data){
		if(!empty($data))
			$this->dataInput = $data;
		else
			$this->dataInput = null;
	}

	public function getData(){
		if(!empty($this->status))
			$this->dataOutput['status'] = $this->status;
		else
			$this->dataOutput['status'] = 'falla algo';

		$this->dataOutput['success'] = false;

		return $this->dataOutput;
	}

	public function getMessage(){
		return array(
			"message" => $this->message,
			"status" => $this->status);
	}

	public function getName(){
		return 'Ctrl';
	}
}
?>
