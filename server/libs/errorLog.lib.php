<?php  
include_once($_SERVER['DOCUMENT_ROOT'] . "/server/config/constants.conf.php");

class ErrorLog{
	public static function record($msg){
		$msg .= (chr(10) . chr(10));
		$lastErrorFile = fopen(_ROOT_PATH . 'server/lastError.log', 'w');
		$listError = fopen(_ROOT_PATH . 'server/listError.log', 'a');

		error_log($msg, 3, _ROOT_PATH . 'server/lastError.log');
		error_log($msg, 3, _ROOT_PATH . 'server/listError.log');

		fclose($lastErrorFile);
		fclose($listError);
	}

	public static function getPageError($num, $isLayout = false){
		if($isLayout){
			$pagePath = _ROOT_PATH . "/app/" . $num . ".html";
			$pageError = file_get_contents($pagePath);
		}else{
			$pagePath = _ROOT_PATH . "/app/pages/" . $num . ".page.html";
			$pageError = file_get_contents($pagePath);

			$pageError = json_encode(array('status' => 0, 'page' => $pageError));
			$pageError = str_replace('\n', '', $pageError);
			$pageError = str_replace('\/', '/', $pageError);
			$pageError = str_replace('\"', '"', $pageError);
		}
		
		return $pageError;
	}

	private function setStatusCode($code){

	}
}

?>