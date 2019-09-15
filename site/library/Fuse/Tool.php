<?php
/*
 * Created on 2012-12-07
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class Fuse_Tool{

 	public static function getFolderDir($level,$id)
	{
		if($level==null){
			return "";
		}

		if(!(is_array($level))){
			return "";
		}

		if(count($level)<2){
			return "";
		}

		//层级为1
		if($level[0]==1){
			return $id%$level[1]+1;
		}

		//层级为2
		$list = array();
		$list['first']  = ceil($id/$level[1])%$level[1]+1;
		$list['second'] = $id%$level[1]+1;
		$folder = $list['first']."/".$list['second'];
		return $folder;

	}

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

	public static function arrayString($a){
		if(!is_array($a)){
			return "";
		}
		$s = 'array('."\n";
		$i = 0;
		foreach ($a as $k => $v)
		{
			$s .= ($i) ? ', ' : '';
			$s .= '"'.$k.'" => ';
			if (is_array($v)) {
				$s .= Tool::arrayString($v);
			} else {
				$s .= '"'.addslashes($v).'"'."\n";
			}
			$i++;
		}
		$s .= ')'."\n";
		return $s;
	}

	/**
	 * 屏蔽html origin code:
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
	 * 取消HTML代码
	 * @param	string	$string
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
	 * rewrite链接
	 */
	public static function rewrite_url($pre, $para) {
		$para = str_replace(array('&','='), array('-', '-'), $para);
		return '<a href="'.$pre.$para.'.html"';
	}

	/**
	 * 处理搜索关键字
	 */
	public static function stripsearchkey($string) {
		$string = trim($string);
		$string = str_replace('*', '%', addcslashes($string, '%_'));
		$string = str_replace('_', '\_', $string);
		return $string;
	}


	/**
	 * 获取字符串
	 * @param	string	$string 要截取的字符
	 * @param	string	$length 截取长度
	 * @param	int	$in_slashes 是否转义
	 * @param	int	$out_slashes 是否输出转义
	 * @param	int	$html 是否过滤html
	 * @param	string	$charset 编码
	 * @return	string	string
	 */
	public static function getstr($string, $length, $in_slashes=0, $out_slashes=0, $html=0 ,$charset='utf-8') {

		$string = trim($string);

		if($in_slashes) {
			//传入的字符有slashes
			$string = stripslashes($string);
		}
		if($html==0) {
			//转换html标签
			$string = self::shtmlspecialchars($string);
		} elseif($html==1) {
			//去掉html标签
			$string = preg_replace("/(\<[^\<]*\>|\r|\n|\s|\[.+?\])/is", ' ', $string);
			$string = self::shtmlspecialchars($string);
		}

		if($length && strlen($string) > $length) {
			//截断字符
			$wordscut = '';
			if(strtolower($charset) == 'utf-8') {
				//utf8编码
				$n = 0;
				$tn = 0;
				$noc = 0;
				while ($n < strlen($string)) {
					$t = ord($string[$n]);
					if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
						$tn = 1;
						$n++;
						$noc++;
					} elseif(194 <= $t && $t <= 223) {
						$tn = 2;
						$n += 2;
						$noc += 2;
					} elseif(224 <= $t && $t < 239) {
						$tn = 3;
						$n += 3;
						$noc += 2;
					} elseif(240 <= $t && $t <= 247) {
						$tn = 4;
						$n += 4;
						$noc += 2;
					} elseif(248 <= $t && $t <= 251) {
						$tn = 5;
						$n += 5;
						$noc += 2;
					} elseif($t == 252 || $t == 253) {
						$tn = 6;
						$n += 6;
						$noc += 2;
					} else {
						$n++;
					}
					if ($noc >= $length) {
						break;
					}
				}
				if ($noc > $length) {
					$n -= $tn;
				}
				$wordscut = substr($string, 0, $n);
			} else {
				for($i = 0; $i < $length - 1; $i++) {
					if(ord($string[$i]) > 127) {
						$wordscut .= $string[$i].$string[$i + 1];
						$i++;
					} else {
						$wordscut .= $string[$i];
					}
				}
			}
			$string = $wordscut;
		}

		if($out_slashes) {
			$string = addslashes($string);
		}
		return trim($string);
	}

	/**
     +----------------------------------------------------------
     * 字符串截取，支持中文和其他编码
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $charset 编码格式
     * @param string $suffix 截断显示字符
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
        if(function_exists("mb_substr")){
	        if($suffix)
	          return mb_substr($str, $start, $length, $charset)."...";
	        else
	          return mb_substr($str, $start, $length, $charset);
        }elseif(function_exists('iconv_substr')) {
           if($suffix)
           return iconv_substr($str,$start,$length,$charset)."...";
           else
           return iconv_substr($str,$start,$length,$charset);
        }
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
        if($suffix) return $slice."…";
        return $slice;
    }

    /**
     * 计算中文字符串长度
     *
	 * @param	string	$string
	 * @return	int
     */
	function utf8Strlen($string = null) {
		// 将字符串分解为单元
		preg_match_all("/./us", $string, $match);

		// 返回单元个数
		return count($match[0]);
	}

	/**
	 * 返回给定年对应的月的天数
	 *
	 * @param   int		$month
	 * @param   int		$year
	 * @return  string
	 */
	function getDaysInMonth($month, $year = '') {
		if (empty($year)) {
			$year = date('Y');
		}
		$month = intval($month);
		return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
	}
 }
?>
