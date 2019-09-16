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
			'host'     		 => '',
			'username' 		 => '',
			'password' 		 => '',
			'dbname'   		 => '',
			'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . strtoupper($charset) . ';')
        );
	}

}
?>
