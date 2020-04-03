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

	/**
	 * 返回2个指定日期相隔的天数
	 */
	function getDaysBetweenTwoDays($start, $end) {
		return abs(strtotime($start) - strtotime($end)) /3600/24;
	}

	/**
	 * $operation：DECODE表示解密，其它表示加密；$key：密匙；$expiry：密文有效期
	 */
	public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {  
		// 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙   
		$ckey_length = 4;   
		   
		// 密匙   
		$key = md5($key ? $key : $GLOBALS['discuz_auth_key']);   
		   
		// 密匙a会参与加解密   
		$keya = md5(substr($key, 0, 16));   
		// 密匙b会用来做数据完整性验证   
		$keyb = md5(substr($key, 16, 16));   
		// 密匙c用于变化生成的密文   
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';   
		// 参与运算的密匙   
		$cryptkey = $keya.md5($keya.$keyc);   
		$key_length = strlen($cryptkey);   
		// 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)， 
		// 解密时会通过这个密匙验证数据完整性   
		// 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确   
		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;   
		$string_length = strlen($string);   
		$result = '';   
		$box = range(0, 255);   
		$rndkey = array();   
		// 产生密匙簿   
		for($i = 0; $i <= 255; $i++) {   
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);   
		}   
		// 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度   
		for($j = $i = 0; $i < 256; $i++) {   
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;   
			$tmp = $box[$i];   
			$box[$i] = $box[$j];   
			$box[$j] = $tmp;   
		}   
		// 核心加解密部分   
		for($a = $j = $i = 0; $i < $string_length; $i++) {   
			$a = ($a + 1) % 256;   
			$j = ($j + $box[$a]) % 256;   
			$tmp = $box[$a];   
			$box[$a] = $box[$j];   
			$box[$j] = $tmp;   
			// 从密匙簿得出密匙进行异或，再转成字符   
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));   
		}   
		if($operation == 'DECODE') {  
			// 验证数据有效性，请看未加密明文的格式   
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {   
				return substr($result, 26);   
			} else {   
				return '';   
			}   
		} else {   
			// 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因   
			// 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码   
			return $keyc.str_replace('=', '', base64_encode($result));   
		}   
	}
	
	public static function encrypt($data, $key = 'qcsdmmdysawqnmlgb') {
		return self::authcode($data, 'ENCODE', $key, 0);
	}
	
	public static function decrypt($data, $key = 'qcsdmmdysawqnmlgb') {
		return self::authcode($data, 'DECODE', $key, 0);
	}
	
	public static function getUserInfo($data) {
		$data = self::decrypt($data);
		$data = unserialize($data);

		return $data;
	}
	
	/**
	 * 格式化数字计算使用
	 * 
	 */
	public static function getFormatMoneyInt($number, $len = 2) {
		return self::getFormatMoneyDisplay($number, $len, '.', '');
	}
	
	/**
	 * 格式化数字展示使用
	 * 
	 * number 你要格式化的数字
	 * decimals 要保留的小数位数
	 * decPoint 指定小数点显示的字符
	 * thousandsSep 指定千位分隔符显示的字符 
	 * 
	 * $number = 1234.5678;
	 * $english_format_number = number_format($number, 2, '.', ',');
	 * 1,234.57
	 */
	public static function getFormatMoneyDisplay($number, $decimals = 2, $decPoint = '.', $thousandsSep = ',') {
		return number_format($number, $decimals, $decPoint, $thousandsSep);
	}
	
	/**
	 * 获取本周所有日期
	 */
	public static function getCurrWeek($time = '', $format='Y-m-d') {
		$time = $time != '' ? $time : time();
		// 获取当前周几
		$week = date('w', $time);
		$date = [];
		for ($i=1; $i<=7; $i++){
			$date[$i] = date($format ,strtotime( '+' . $i-$week .' days', $time));
		}
		return $date;
	}
	
	/**
	 * 获取最近七天所有日期
	 */
	public static function getLastWeek($time = '', $format='Y-m-d') {
		$time = $time != '' ? $time : time();
		// 组合数据
		$date = [];
		for ($i=1; $i<=7; $i++){
			$date[$i] = date($format ,strtotime( '+' . $i-7 .' days', $time));
		}
		return $date;
	}
	
	/**
	 * 获取本周日期开始和结束日期
	 */
	public static function getCurrWeekBetween() {
		$start = date("Y-m-d H:i:s", mktime(0, 0 , 0, date("m"),date("d")-date("w")+1, date("Y"))); 
		$end = date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d")-date("w")+7, date("Y"))); 
		
		return array(
			'start' => $start,
			'end' => $end
		);
	}
	
	/**
	 * 获取本月日期开始和结束日期
	 */
	public static function getCurrMonthBetween() {
		$start = date("Y-m-d H:i:s", mktime(0, 0 , 0,date("m"), 1, date("Y")));
		$end = date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("t"), date("Y")));
		
		return array(
			'start' => $start,
			'end' => $end
		);
	}
	
	/**
	 * 取随机指定位数长度字符串
	 */
	public static function getRandStr($len = 10) {
		$strs = 'QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm';
		return substr(str_shuffle($strs), mt_rand(0, strlen($strs)-11), $len);
	}
	
	/**
	 * 字符转utf-8
	 */
	public static function strToUtf8($str) {
		$encode = mb_detect_encoding($str, array('ASCII', 'UTF-8', 'GB2312', 'GBK','BIG5'));
		if ($encode == 'UTF-8') {
			return $str;
		}
		
		return mb_convert_encoding($str, 'UTF-8', $encode);
	}
	
	/**
	 * 防止sql注入
	 */
	public static function paramsCheck($value) {
		if ($value == '') {
			return $value;
		}
		
		if (!get_magic_quotes_gpc()) {
			// 进行过滤  
			$value = addslashes($value);
		} 

		$value = str_replace("'", "\'", $value);
		$value = str_replace("_", "\_", $value);
		$value = str_replace("%", "\%", $value);
		$value = nl2br($value); 
		$value = htmlspecialchars($value); 
		return $value; 
	}
	
	/**
	 * 格式化日期
	 * 
	 * 2015-03-07,2015-10-28
	 * to "03.07-10.28"
	 */
	public static function formatDateToStr($startDate, $endDate)
	{
		$startDate = substr($startDate, 5, 5);
		$endDate = substr($endDate, 5, 5);
		
		return str_replace('-', '.', $startDate) . '-' . str_replace('-', '.', $endDate);
	}
	
	/**
	 * 读取目录内容
	 *
	 */
	public static function getDir($dir)
	{
		$files = array();
		 if ($handle = opendir($dir)) {
			 while (($file = readdir($handle)) !== false) {
				 if ($file != ".." && $file != ".") {
					 if (is_dir($dir . "/" . $file)) {
						 $files[$file] = scandir($dir . "/" . $file);
					 } else {
						 $file = iconv('GBK', 'UTF-8', $file);
						 $files[] = $file;
					 }
				 }
			 }

			 closedir($handle);
			 return $files;
		 }
	}
	
	/** 删除所有空目录 
	 * @param String $path 目录路径 
	 */
	public static function rmEmptyDir($path)
	{ 
		if (is_dir($path) && ($handle = opendir($path)) !== false) { 
			while (($file = readdir($handle)) !== false) { // 遍历文件夹 
				if ($file != '.' && $file != '..'){ 
					$curfile = $path.'/'.$file; // 当前目录 
					if (is_dir($curfile)) { // 目录 
						self::rmEmptyDir($curfile); // 如果是目录则继续遍历 
						if (count(scandir($curfile)) == 2) { // 目录为空,=2是因为.和..存在
							rmdir($curfile); // 删除空目录 
						}
					}
				}
			}
			closedir($handle); 
		} 
	}
	
	/**
	 * 判断是否是windows机器
	 */
	public static function isWinOs()
	{
		return  strtoupper(substr(PHP_OS,0,3)) === 'WIN' ? true : false;
	}
}
?>
