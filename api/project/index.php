<?php
ini_set('display_errors', 'on');

require_once('../site/include/framework.php');
Fuse_Application::getInstance()
	->setRouter(array('script'=>'project', 'task' => 'index'))
	->run();
?>
