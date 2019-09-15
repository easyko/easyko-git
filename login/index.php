<?php
require_once('../site/include/Config/Global.php');
ini_set('display_errors', Config_Global::$displayErrorsFlag);

require_once('../site/include/framework.php');
$task = isset($_REQUEST['task']) ? $_REQUEST['task'] : 'index';

try {
	Fuse_Application::getInstance()
		->setRouter(array('script'=>'login', 'task' => $task))
		->run();
} catch ( Exception $e ) {
	die( $e->getMessage() );
}
?>
