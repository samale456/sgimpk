<?php
include_once("server/config/constants.conf.php");
include_once("server/libs/request.lib.php");
include_once("server/libs/errorLog.lib.php");
include_once("server/controllers/session.ctrl.php");

include_once("server/config/router.conf.php");

try{
	$moduleText = file_get_contents("server/config/modules.config.json");
	$modules = json_decode($moduleText, true);

	Request::setSession(new SessionCtrl());
	Request::setModules($modules);
	Request::setLayout("app/layout.html");
	Request::setPageindex("app/pages/dashboard.page.html");

	Request::start();
}catch(Exception $e){
	ErrorLog::record('error: ' . $e->getMessage());
	echo ErrorLog::getPageError(500, true);
}

?>

