<?php

/** check if the environment if development and display errors **/

function setReporting() {
    error_reporting(E_ALL);
    if (DEVELOPMENT_ENVIRONMENT == true) {
        ini_set('display_errors', 'On');
    } else {
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
    }
}

/** Main call function **/
function callHook() {
	global $url;
	
	$urlArray = explode("/",$url);
	$controller = $urlArray[0];
	array_shift($urlArray);
	$action = $urlArray[0];
	array_shift($urlArray);
	$queryString = $urlArray;
	
	$controllerName = $controller;
	$controller = ucwords($controller);
	$model = $controller;
	$controller .= 'Controller';
	
	$dispatch = new $controller($model,$controllerName,$action);
	
	if ((int)method_exists($controller, $action)) {
		call_user_func_array([$dispatch,$action],$queryString);
	} else {
        /* Error Generation Code Here */
    }
}

/** Autoload any classes that are required **/
 
function __autoload($className) {
    if (file_exists(ROOT . DS . 'protected' . DS . strtolower($className) . '.class.php')) {
        require_once(ROOT . DS . 'protected' . DS . strtolower($className) . '.class.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php');
    } else {
        /* Error Generation Code Here */
    }
}

setReporting();
callHook();
