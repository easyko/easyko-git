<?php
require_once('../../site/include/Config/Global.php');
ini_set('display_errors', Config_Global::$displayErrorsFlag);

require_once('../../site/include/framework.php');
Fuse_Application::getInstance()
	->setRouter(array('script'=>'job', 'task' => 'index'))
	->run();
?>
