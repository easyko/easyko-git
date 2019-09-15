<?php
/**
 * Set the available masks for cleaning variables
 */
define( 'REQUEST_NOTRIM'   , 1 );
define( 'REQUEST_ALLOWRAW' , 2 );
define( 'REQUEST_ALLOWHTML', 4 );

/**
 * 
 * This class serves to provide the Imag Framework with a common interface to access
 * request variables.  This includes $_POST, $_GET, and naturally $_REQUEST.  Variables
 * can be passed through an input filter to avoid injection or returned raw.
 * 
 * @category   Fuse
 * @package    Fuse_Request
 * @copyright  Copyright (c) 2010-now 75.cn (http://cms.e75.cn)
 * @author Gary wang(qq:465474550,msn:wangbaogang123@hotmail.com)
 *
 */
class Fuse_Request
{
	/**
	 * Fetches and returns a given variable.
	 *
	 * The default behaviour is fetching variables depending on the
	 * current request method: GET and HEAD will result in returning
	 * an entry from $_GET, POST and PUT will result in returning an
	 * entry from $_POST.
	 *
	 * You can force the source by setting the $hash parameter:
	 *
	 *   post		$_POST
	 *   get		$_GET
	 *   files		$_FILES
	 *   cookie		$_COOKIE
	 *   env		$_ENV
	 *   server		$_SERVER
	 *   default	$_REQUEST
	 *
	 * @static
	 * @param	string	$name		Variable name
	 * @param	string	$hash		Where the var should come from (POST, GET, FILES, COOKIE)
	 * @param	string	$type		Return type for the variable, for valid values see {@link FilterInput::clean()}
	 * @param	int		$mask		Filter mask for the variable
	 * @return	mixed	Requested variable
	 */
	public static function getVar($name, $hash = 'default', $type = 'none', $mask = 0)
	{
		$hash = strtoupper($hash);

		// Get the input hash
		switch ($hash)
		{
			case 'GET' :
				$input = $_GET;
				break;
			case 'POST' :
				$input = $_POST;
				break;
			case 'FILES' :
				$input = $_FILES;
				break;
			case 'COOKIE' :
				$input = $_COOKIE;
				break;
			case 'ENV'    :
				$input = $_ENV;
				break;
			case 'SERVER'    :
				$input = $_SERVER;
				break;
			default:
				$input = $_REQUEST;
				$hash = 'REQUEST';
				break;
		}

		$var = null;

		if (isset($input[$name]) && $input[$name] !== null) {

			// Get the variable from the input hash and clean it
			$var = self::cleanVar($input[$name], $mask, $type);
		}
		
		return $var;
	}
	
	/**
	 * Clean up an input variable.
	 *
	 * @param mixed The input variable.
	 * @param int Filter bit mask. 
	 * 1=no trim: If this flag is cleared and the input is a string, 
	 *            the string will have leading and trailing whitespace trimmed. 
	 * 2=allow_raw: If set, no more filtering is performed, higher bits are ignored. 
	 * 4=allow_html: HTML is allowed, but passed through a safe
	 * 				 HTML filter first. If set, no more filtering is 
	 * 				 performed. If no bits other than the 1 bit is set, 
	 *               a strict filter is applied.
	 * @param string The variable type {@see FilterInput::clean()}.
	 * @return	mixed	Clean variable 
	 */
	public static function cleanVar($var, $mask = 0, $type=null)
	{

		// If the no trim flag is not set, trim the variable
		if (!($mask & 1) && is_string($var)) {
			$var = trim($var);
		}

		// Now we handle input filtering
		if ($mask & 2)
		{
			// If the allow raw flag is set, do not modify the variable
			$var = $var;
		}
		elseif ($mask & 4)
		{
			/**safe html*/
			$var = Fuse_Filter_Input::clean($var, 'STRING');
		}
		else
		{
			/**no html*/
			$var = Fuse_Filter_Input::clean($var, $type);
		}
		return $var;
	}
	
	/**
	 * Get forward
	 * @param string The forward variable.
	 * @return	string	
	 */
	public static function getForward($key="forward",$request=null){
		$forward = self::getVar($key,'request');
		if(empty($forward)){
			$forward = self::getVar("HTTP_REFERER",'server');
		}
		return $forward;
	}
	
	/**
	 * Get value skip empty
	 * @param mixed Object or Array
	 * @return	mixed	Object
	 */
	public static function getObject($list,$type){
		$object = new stdClass();
		foreach ($list as $key) {
			$value = self::getVar($key,$type);
			if(!empty($value)){
				$object->$key = $value;
			}
		}
		return $object;
	}
	
	/**
	 * Check object is null
	 * @param mixed Object or Array.
	 * @return boolean is null
	 */
	public static function checkObject($object){
		
		foreach ($object as $key=>$value) {
			if(empty($value)){
				$object = null;
				break;
			}
		}
		return $object==null;
		
	}
	
}
?>
