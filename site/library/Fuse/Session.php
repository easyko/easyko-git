<?php
/**
 * Fuse Cookie
 * @category   Fuse
 * @package    Fuse_Cookie
 * @copyright
 * @author
 *
 */
class Fuse_Session
{
	protected static $_instance = null;
	public $data = array();

	public function __construct() {
		if (!session_id()) {
			ini_set('session.use_only_cookies', 'On');
			ini_set('session.use_trans_sid', 'Off');
			ini_set('session.cookie_httponly', 'On');
			session_set_cookie_params(0, '/');
			session_start();
		}
		$this->data =& $_SESSION;
	}

    /**
     * Get instance
     *
     * @return Fuse_Session  	$this
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Set cookie value
     *
     * @param  string           $name
     * @param  string           $value
     * @param  int              $expires
     * @param  string           $domain
     * @param  string           $path
     * @param  boolean          $secure
     * @return Fuse_Cookie  	$this
     *
     */
    public function set($name, $value, $expires = 0, $domain=null, $path = null, $secure = false)
    {
    	if($domain==null){
    		$domain = Config_App::getCookieDomain();
    	}
    	if($path==null){
    		$path = Config_App::getCookiePath();
    	}
    	if($secure==false){
    		$secure = Config_App::getCookieSecure();
    	}
    	$value = self::authcode($value,'ENCODE','',0);

    	setcookie($name, $value, $expires, $path, $domain, $secure);
    }

    public function clear($name,$domain=null, $path = null, $secure = false)
    {
    	if($domain==null){
    		$domain = Config_App::getCookieDomain();
    	}
    	if($path==null){
    		$path = Config_App::getCookiePath();
    	}
    	if($secure==false){
    		$secure = Config_App::getCookieSecure();
    	}
    	setcookie($name, '', -3600, $path, $domain, $secure);
    }

    /**
     * Getter
     *
     * @param  string           $property
     * @param  mixed            $default
     * @return mixed   			$default
     *
     */
    public function __get($property)
	{
		$value = "";
		if(isset($_SESSION[$property]))
		{
			$value = self::authcode($_COOKIE[$property]);
		}
		return $value;
	}

	/**
     * Setter
     * @param  string           $property
     * @param  mixed            $value
     * @return boolean    $previous
     *
     */
	public function __set( $property, $value = null )
	{
		if(!isset($_SESSION[$property]))
		{
			$_SESSION[$property] = self::authcode($value);
		}
		return true;
	}

	/**
	 * Crypt value use discuz method
	 *
	 * @param  string   $string
     * @param  string   $operation
     * @param  string   $key
     * @param  int      $expiry
     * @return string	crypt value
	 */
	public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

		$ckey_length = 4;
		$key = md5($key ? $key : Config_App::getCookieKey());
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);

		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);

		$result = '';
		$box = range(0, 255);

		$rndkey = array();
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}

		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}

		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			return $keyc.str_replace('=', '', base64_encode($result));
		}

	}

	// 获取系统默认的sessionid，redis key
	public static function getSessionId() {
		return session_id();
	}

	public static function destroy() {
		return session_destroy();
	}
}
?>
