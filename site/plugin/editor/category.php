<?php
ini_set("display_errors","on");
require_once("include/framework.php");
Fuse_Application::getInstance()
	->setRouter(array("script"=>"category","task"=>"category"))
	->run();
?>