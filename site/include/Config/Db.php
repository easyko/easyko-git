<?php
/**
 * @category   Fuse
 * @package    Config
 * @author     jerry (caowlong163@163.com, qq:1226454285)
 */
class Config_Db
{
	public static function toArray($charset = 'UTF8')
	{
		return array (
			'host'     		 => '139.196.104.77',
			'username' 		 => 'easyku_web_user',
			'password' 		 => 'easyku_db_asdWf321',
			'dbname'   		 => 'easyku_web',
			'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . strtoupper($charset) . ';')
		);
	}

}
?>