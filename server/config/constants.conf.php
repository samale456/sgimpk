<?php
/*A continuacion se definen las constantes que facilitan el cambio de configuracion
del sistema.
*/
const _UNDEFINED_REQUEST = 'undefinedMd';
const _SESION = 'sesion';
const _PAGE = 'page';
const _MENU = 'menu';
const _MODULE = 'module';
const _FILES = 'files';

//eventos de controlador
const _CONSULT = 'consult';
const _CONSULT_ALL = 'consultAll';
const _REGISTER = 'register';
const _MODIFY = 'modify';
const _REMOVE = 'remove';
const _LOAD_FK = 'loadFk';
const _LOG_IN = 'login';
const _LOG_OUT = 'logout';

const _MODULE_PARAM = 'md';
//url
const _URL_HOME = "http://sgimpk.dev/";

//ruta principal del sistema
define("_ROOT_PATH", $_SERVER['DOCUMENT_ROOT'] . "/");

//habilitar captcha
const _VALIDATE_CAPTCHA = false;

//habilitar validación de sesión
const _VALIDATE_SESSION = false;

//habilitar validación del perfil
const _VALIDATE_PROFILE = false;

//segundos, minutos, horas
const _TIME_OUT = (10 * 1) * 6;
?>