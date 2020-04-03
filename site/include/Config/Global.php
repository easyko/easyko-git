<?php
define('DIR_LOGS', dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR .'library' . DIRECTORY_SEPARATOR . 'logs'. DIRECTORY_SEPARATOR);
/**
 * @category   Fuse
 * @package    Global
 * @author     jerry (caowlong163@163.com, qq:1226454285)
 */
class Config_Global
{
	// 是否开启报错
	public static $displayErrorsFlag = 'off'; // on or off

    // easyku易酷-设置签名名称
    public static $smsSignName = 'easyku易酷';

	// easyku易酷-登录模版CODE
	public static $smsTempCodeByLogin = 'SMS_166377204';
	
	// easyku易酷-注册模版CODE
	public static $smsTempCodeByRegister = 'SMS_166377203';
	
	// easyku易酷-新业务通知模版CODE
	public static $smsTempCodeByBussNotice = 'SMS_166377207';

    // easyku易酷-会员账户生效提醒
    public static $smsTempCodeByAccountEffective = 'SMS_166377207';

    // easyku易酷-会员账户到期提醒
    public static $smsTempCodeByAccountExpiration = 'SMS_166377207';

	// 错误日志文件
    public static $config_error_filename = 'error.log';
}
?>