<?php
ini_set('display_errors', 'on');

$task = isset($_REQUEST['task']) ? $_REQUEST['task'] : 'index';

require_once('../site/include/framework.php');

try {
	Fuse_Application::getInstance()
		->setRouter(array('script'=>'register', 'task' => $task))
		->run();
} catch ( Exception $e ) {
	die( $e->getMessage() );
}
?>
