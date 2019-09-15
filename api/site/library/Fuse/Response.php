<?php
/**
 * Fuse response
 *
 * @category   Fuse
 * @package    Fuse_Response
 * @copyright  Copyright (c) 2010-now 75.cn (http://cms.e75.cn)
 * @author Gary wang(qq:465474550,msn:wangbaogang123@hotmail.com)
 *
 */
class Fuse_Response
{
	/**
	 * headers
	 */
	private static $headers = array();

	/**
	 * Set a header
	 *
	 * If $replace is true, replaces any headers already defined with that
	 * $name.
	 *
	 * @param string 	$name
	 * @param string 	$value
	 * @param boolean 	$replace
	 * @return	void
	 */
	public static function setHeader($name, $value, $replace = false)
	{
		$name	= (string) $name;
		$value	= (string) $value;

		if ($replace)
		{
			foreach (self::$headers as $key => $header) {
				if ($name == $header['name']) {
					unset(self::$headers[$key]);
				}
			}
		}

		self::$headers[] = array(
			'name'	=> $name,
			'value'	=> $value
		);
	}

	/**
	 * Return array of headers;
	 *
	 * @return array
	 */
	public static function getHeaders() {
		return  self::$headers;
	}

	/**
	 * Clear headers
	 *
	 * @return	void
	 */
	public static function clearHeaders() {
		self::$headers = array();
	}

	/**
	 * Send all headers
	 *
	 * @return	void
	 */
	public static function sendHeaders()
	{
		if (!headers_sent())
		{
			foreach (self::$headers as $header)
			{
				if ('status' == strtolower($header['name']))
				{
					// 'status' headers indicate an HTTP status, and need to be handled slightly differently
					header(ucfirst(strtolower($header['name'])) . ': ' . $header['value'], null, (int) $header['value']);
				} else {
					header($header['name'] . ': ' . $header['value']);
				}
			}
		}
	}


	/**
	 * Sends all headers prior to returning the string
	 *
	 * @return	void
	 *
	 */
	public static function noCache()
	{

		self::setHeader( 'Expires', 'Mon, 1 Jan 2001 00:00:00 GMT', true ); 				// Expires in the past
		self::setHeader( 'Last-Modified', gmdate("D, d M Y H:i:s") . ' GMT', true ); 		// Always modified
		self::setHeader( 'Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0', false );
		self::setHeader( 'Pragma', 'no-cache' ); 											// HTTP 1.0

		self::sendHeaders();

	}

	/**
	 * Redirects the browser or returns false if no redirect is set.
	 *
	 * @param  string                   $url
     * @param  string                   $msg
	 * @return	void
	 *
	 */
	public static function redirect($url,$msg='')
	{
		$url = urldecode($url);
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n<head>\n";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n</head><body>";
		if(empty($msg)){
			echo "<script>window.location.href='$url';</script>";
		}else{
			echo "<script>alert('$msg');window.location.href='$url';</script>";
		}
		echo "</body></html>";
		exit;

	}

	/**
	 * Write to browser
     * @param  string                   $msg
	 * @return void
	 */
	public static function write($msg)
	{
		echo $msg;
		exit();

	}
	
	/**
	 * Get forward
	 * @param string The forward variable.
	 * @return	string
	 */
	public static function getForward($key="forward",$request=null){
		$forward = "/";
		if($request){
			$forward = $request->getParam($key);
		}
		if(empty($forward)){
			if(isset($_SERVER["HTTP_REFERER"])){
				$forward = $_SERVER["HTTP_REFERER"];
			}
		}
		return $forward;
	}

	/**
	 * Write to browser by type
     * @param  string                   $msg
     * @param  string                   $type
     * @param  string                   $forward
	 * @return void
	 */
	public static function writeByType($msg,$type,$forward=''){
		if(empty($forward)){
			$forward = Fuse_Request::getForward("forward");
		}
		if(is_array($msg)){
			$msg = $msg[$type];
		}
		if($type == "json"){
	       	self::write("{result:'$msg'}");
	    }elseif($type == "redirect"){
			self::redirect($forward,$msg);
		}else{
			self::write($msg);
		}
	}
}
?>
