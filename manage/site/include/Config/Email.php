<?php
/**
 * @category   Fuse
 * @package    Config
 * @author     gary wang (msn:wangbaogang123@hotmail.com,qq:465474550)
 */
class Config_Email
{
	public static function toArray()
	{
		return array (
			'host'     => 'smtp.163.com',
			'username' => 'caowlong163@163.com',
			'password' => '',
			'from'     => 'caowlong163@163.com',
			'fromName' => '系统会员注册'
        );
	}

}
?>
