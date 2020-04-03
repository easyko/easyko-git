<?php
/**
 * FilterInput is a class for filtering input from any data source
 *
 * @category   Fuse
 * @package    Fuse_Filter_Input
 * @copyright  Copyright (c) 2010-now 75.cn (http://cms.e75.cn)
 * @author Gary wang(qq:465474550,msn:wangbaogang123@hotmail.com)
 */
class Fuse_Filter_Input 
{
	public static function clean($source, $type='string')
	{
		// Handle the type constraint
		switch (strtoupper($type))
		{
			case 'INT' :
			case 'INTEGER' :
				// Only use the first integer value
				preg_match('/-?[0-9]+/', (string) $source, $matches);
				$result = @ (int) $matches[0];
				break;

			case 'FLOAT' :
			case 'DOUBLE' :
				// Only use the first floating point value
				preg_match('/-?[0-9]+(\.[0-9]+)?/', (string) $source, $matches);
				$result = @ (float) $matches[0];
				break;

			case 'BOOL' :
			case 'BOOLEAN' :
				$result = (bool) $source;
				break;

			case 'WORD' :
				$result = (string) preg_replace( '/[^A-Z_]/i', '', $source );
				break;

			case 'ALNUM' :
				$result = (string) preg_replace( '/[^A-Z0-9]/i', '', $source );
				break;

			case 'CMD' :
				$result = (string) preg_replace( '/[^A-Z0-9_\.-]/i', '', $source );
				$result = ltrim($result, '.');
				break;

			case 'BASE64' :
				$result = (string) preg_replace( '/[^A-Z0-9\/+=]/i', '', $source );
				break;

			case 'STRING' :
				$result = (string) self::checkhtml((string)$source);
				break;
				
			case 'NOHTML' :
				$result = (string) self::cleanHtml((string)$source);
				break;

			case 'ARRAY' :
				$result = (array) $source;
				break;

			case 'PATH' :
				$pattern = '/^[A-Za-z0-9_-]+[A-Za-z0-9_\.-]*([\\\\\/][A-Za-z0-9_-]+[A-Za-z0-9_\.-]*)*$/';
				preg_match($pattern, (string) $source, $matches);
				$result = @ (string) $matches[0];
				break;

			case 'USERNAME' :
				$result = (string) preg_replace( '/[\x00-\x1F\x7F<>"\'%&]/', '', $source );
				break;

			default :
				if(is_array($source)){
					$result = $source;
				}else{
					$result = self::shtmlspecialchars($source);
				}
				break;
		}
		return $result;
	}
	
	/**
	 * Clean html
	 * @param	string	$content
	 * @return	string	string 
	 */
	public static function cleanHtml($content)
	{
		$content = stripslashes($content);
		$content = str_replace("\n",'',$content);
		$content = str_replace("\r",'',$content);
		$content = preg_split('/<[^>]+>/iU',$content);
		//return str_replace('"', '&quot;', implode("",$content));
		return addslashes(implode("",$content));
		
	}
	
	/**
	 * Filter html
	 * @param	string	$html
	 * @return	string	string
	 */
	public static function checkhtml($html) {
		$html = stripslashes($html);
		preg_match_all("/\<([^\<]+)\>/is", $html, $ms);

		$searchs[] = '<';
		$replaces[] = '&lt;';
		$searchs[] = '>';
		$replaces[] = '&gt;';
		
		if($ms[1]) {
			$allowtags = 'img|a|font|div|table|tbody|caption|tr|td|th|br|p|b|strong|i|u|em|span|ol|ul|li|blockquote|object|param|embed';//允许的标签
			$ms[1] = array_unique($ms[1]);
			foreach ($ms[1] as $value) {
				$searchs[] = "&lt;".$value."&gt;";
				$value = self::shtmlspecialchars($value);
				$value = str_replace(array('\\','/*'), array('.','/.'), $value);
				$value = preg_replace(array("/(javascript|script|eval|behaviour|expression)/i", "/(\s+|&quot;|')on/i"), array('.', ' .'), $value);
				if(!preg_match("/^[\/|\s]?($allowtags)(\s+|$)/is", $value)) {
					$value = '';
				}
				$replaces[] = empty($value)?'':"<".str_replace('&quot;', '"', $value).">";
			}
		}
		$html = str_replace($searchs, $replaces, $html);
		
		$html = addslashes($html);
		
		return $html;
	}
	
	/**
	 * Disnable html
	 * @param	string	$string html
	 * @return	string	string
	 */
	public static function shtmlspecialchars($string) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = self::shtmlspecialchars($val);
			}
		} else {
			$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
				str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
		}
		return $string;
	}
	
	/**
	 * Decode unicode url
	 * @param	string	$url 
	 * @return	string	url
	 */
	public static function unicode_urldecode($url){
	   preg_match_all('/%u([[:alnum:]]{4})/', $url, $a);
	   foreach ($a[1] as $uniord){
	       $dec = hexdec($uniord);
	       $utf = '';
	       
	       if ($dec < 128)
	       {
	           $utf = chr($dec); 
	       }
	       else if ($dec < 2048)
	       { 
	           $utf = chr(192 + (($dec - ($dec % 64)) / 64)); 
	           $utf .= chr(128 + ($dec % 64)); 
	       }
	       else
	       { 
	           $utf = chr(224 + (($dec - ($dec % 4096)) / 4096)); 
	           $utf .= chr(128 + ((($dec % 4096) - ($dec % 64)) / 64)); 
	           $utf .= chr(128 + ($dec % 64)); 
	       }
	       
	       $url = str_replace('%u'.$uniord, $utf, $url);
	   }
	   
	   return urldecode($url);
	}
	
}
?>
