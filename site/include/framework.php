<?php
/**
 * Fuse CMS
 * @version		5.0 (2012-12-02)
 * @package		includes
 * @copyright	Copyright (C) 2012 - ? Fuse Network Team. All rights reserved.
 * @author		gary wang (msn:wangbaogang123@hotmail.com,qq:465474550)
 *  
*/

/* time local */
date_default_timezone_set('Asia/Chongqing');

/*
 * system checks
 */
@set_magic_quotes_runtime( 0 );

defined('DS') || define( 'DS', DIRECTORY_SEPARATOR );
defined('VIEW_CLASS') || define( 'VIEW_CLASS', 'Fuse_View_Smarty' );

defined('HOMEDIR') || define('HOMEDIR', realpath(dirname(__FILE__) . '/../../'));
defined('BASEDIR') || define('BASEDIR', realpath(dirname(__FILE__) . '/../'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
	dirname(dirname(__FILE__)).DS.'library',
	dirname(dirname(__FILE__)).DS.'include',
	dirname(dirname(__FILE__)).DS.'class',
    "."//get_include_path()
)));

defined('FUSE_VIEW_PATH') || define('FUSE_VIEW_PATH', realpath(BASEDIR . '/template'));

require_once 'Fuse/Application.php';

//path root / namespace / controller root
Fuse_Application::getInstance()->setDocumentRoot(BASEDIR)->setControllerRoot(BASEDIR.DS.'class')->registerNamespace(array("Config"=>"include","Cache"=>"include"));

// commonApi
require_once 'CommonController.php';

// functions
require_once 'functions.php';

// wxpay
require_once "wxpay/lib/WxPay.Api.php";
require_once "wxpay/WxPay.Config.php";
require_once 'wxpay/log.php';

//view var
defined('HOMEURL') || define('HOMEURL', Config_App::homeurl());

?>