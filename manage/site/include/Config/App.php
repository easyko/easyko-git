<?php
define("CACHE_LEFTTIME",Config_App::$cache_lefttime);
class Config_App
{
	public static $cache_lefttime = 3600;

	/**
	 * Current script scope directory root
	 */
	public static function basedir(){
		if(defined('BASEDIR')){
			return BASEDIR;
		}
		return dirname(dirname(__FILE__));

	}

	/**
	 * Root directory of this site
	 */
	public static function homedir(){
		if(defined('HOMEDIR')){
			return HOMEDIR;
		}
		return dirname(dirname(dirname(dirname(__FILE__))));
	}

	/**
	 * Root directory of this site
	 */
	public static function rootdir(){
		return dirname(dirname(dirname(dirname(__FILE__))));
	}

	/**
	 * Root web root of this site
	 */
	public static function homeurl(){
		return "http://".self::domain();
	}

	/**
	 * current domain
	 */
	public static function domain(){
		return $_SERVER['HTTP_HOST'];
	}

	public static function rootDomain(){
		return $_SERVER['HTTP_HOST'];
	}

/**---------------------Cookie config----------------------------*/
	/**
	 * @return cookie domain
	 */
	public static function getCookieDomain()
	{
		return $_SERVER['HTTP_HOST'];
	}

	/**
	 * @return cookie expires
	 */
	public static function getCookieExpires()
	{
		return 0;
	}

	/**
	 * @return cookie path
	 */
	public static function getCookiePath()
	{
		return '/';
	}

	/**
	 * @return cookie secure
	 */
	public static function getCookieSecure()
	{
		return false;
	}

	/**
	 * @return cookie key
	 */
	public static function getCookieKey()
	{
		return "apllpsdsmmwqewqewqwennhyr!!@@#";
	}

	public static function formhash($specialadd='',$timestamp=''){
		$user_id  = Fuse_Cookie::getInstance()->user_id;
		$nickname = Fuse_Cookie::getInstance()->nickname;
		$hashadd  = 'qn2';
		$sitekey  = self::getCookieKey();
		if(empty($timestamp)){
			$timestamp = time();
		}
		return substr(md5(substr($timestamp, 0, -7).$nickname.$user_id.$sitekey.$hashadd.$specialadd), 8, 10);
	}

	/**
	* @return real ip
	*/
	public static function getIP()
	{
		global $ip;
		if (getenv("HTTP_CLIENT_IP"))
		$ip = getenv("HTTP_CLIENT_IP");
		else if(getenv("HTTP_X_FORWARDED_FOR"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if(getenv("REMOTE_ADDR"))
		$ip = getenv("REMOTE_ADDR");
		else $ip = "Unknow";
		return $ip;
	}

   /**
	* @return time
	*/
	public static function getTime()
	{
		return date("Y-m-d H:i:s");
	}

   /**
	* @return time
	*/
	public static function getDate()
	{
		return date("Y-m-d");
	}

    /**
     * 截取指定长度的字符
     *
     */
	public static function getStr($str, $length, $end = '...')
	{
		include_once(Config_App::homedir() . "/site/library/Fuse/Tool.php");
		$oldlen = strlen($str);
		$str = Fuse_Tool::getstr(strip_tags($str), $length);
		if ($oldlen > strlen($str)) {
			$str .= $end;
		}
		return $str;
	}

	/**
	 * 获取文件扩展名
	 *
	 */
	public static function getExt($name)
	{
		return pathinfo($name, PATHINFO_EXTENSION);
	}
}
?>
