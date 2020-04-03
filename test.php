<?php
error_reporting(E_ALL);
require_once('site/include/Config/Global.php');
//ini_set('display_errors', Config_Global::$displayErrorsFlag );

$script = isset($_GET['script']) ? $_GET['script'] : 'index';
$task = isset($_GET['task']) ? $_GET['task'] : 'index';
$_GET['type'] = 1;

require_once('site/include/framework.php');

// Log
$log = Fuse_Log::getInstance( Config_Global::$config_error_filename );

function error_handler($errno, $errstr, $errfile, $errline) {
    global $log;

    // error suppressed with @
    if (error_reporting() === 0) {
        return false;
    }

    switch ($errno) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $error = 'Notice';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $error = 'Warning';
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $error = 'Fatal Error';
            break;
        default:
            $error = 'Unknown';
            break;
    }

    if ( Config_Global::$displayErrorsFlag == 'on' ) {
        echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
    }
    $log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);

    return true;
}

// Error Handler, E_ERROR级别的错误不能由用户定义的函数处理
set_error_handler('error_handler');

try {
    Fuse_Application::getInstance()
        ->setRouter(array('script' => $script, 'task'=>$task))
        ->run();
} catch ( Exception $e ) {
    die( $e->getMessage() );
}
?>
