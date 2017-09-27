<?php 

include_once("constants.conf.php");
include_once(_ROOT_PATH . "server/controllers/session.ctrl.php");
include_once(_ROOT_PATH . "server/controllers/main.ctrl.php");
include_once(_ROOT_PATH . "server/controllers/page.ctrl.php");
include_once(_ROOT_PATH . "server/controllers/file.ctrl.php");
include_once(_ROOT_PATH . "server/controllers/menu.ctrl.php");
include_once(_ROOT_PATH . "server/controllers/captcha.ctrl.php");
include_once(_ROOT_PATH . "server/libs/request.lib.php");

//
Request::route('{
	"name": "session",
	"link":"session/:log",
	"method": "GET",
	"validateSession": false
}', new SessionCtrl(), function($return){ 
	if($return['success'])
		$pageText = file_get_contents("app/index.html");
	else
		$pageText = file_get_contents("app/login.html");
	echo file_get_contents($pageText);
});

Request::route('{
	"name": "page",
	"link":"page/:name",
	"method": "GET",
	"validateSession": true
}', new PageCtrl(), function($return){
	if($return['success'])
		echo $return['page'];
	else
		Request::go('session', '{"log":"in"}');
});

Request::redirect('{"name":"home2", "to":"home", "link":"/"}');

?>