<?php

/*常用函数*/
function isEmail($email)
{
    // $myreg = "/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/";
    $myreg = '/^([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_\.]?[a-z0-9]+)+[\.][a-z]{2,10}([\.][a-z]{2,10})?$/i';
    if (preg_match($myreg, $email) > 0)
        return true;
    else
        return false;
}

function isPhone($tel)
{
    // $reg = "/1[3458]{1}\d{9}$/";
    $reg = "/^\d{8}$/";
    if (preg_match($reg, $tel))
        return true;
    else
        return false;
}

function isPassword($password)
{
    if (utf8Strlen($password) < 6 || utf8Strlen($password) > 20) {
        return false;
    }

    $typeList = array(
        'number' => 0,
        'letter' => 0,
        'underline' => 0
    );

    if ($password) {
        for ($i = 0; $i <= strlen($password) - 1; $i++) {
            if (!preg_match('/[a-zA-Z0-9_]/', $password[$i])) {
                return false;
                break;
            }
            if (preg_match('/\d/', $password[$i]) && !$typeList['number']) {
                $typeList['number']++;
                continue;
            }
            if (preg_match('/[a-zA-Z]/', $password[$i]) && !$typeList['letter']) {
                $typeList['letter']++;
                continue;
            }
            if (preg_match('/\_/', $password[$i]) && !$typeList['underline']) {
                $typeList['underline']++;
                continue;
            }
        }
    }

    $num = $typeList['number'] + $typeList['letter'] + $typeList['underline'];
    if ($num < 2) {
        return false;
    }

    return true;
}

/**
 * 获取服务器端IP地址
 *
 * @param void
 * @return    string
 */
function getServerIp()
{
    if (isset($_SERVER)) {
        if ($_SERVER['SERVER_ADDR']) {
            $serverIp = $_SERVER['SERVER_ADDR'];
        } else {
            $serverIp = $_SERVER['LOCAL_ADDR'];
        }
    } else {
        $serverIp = getenv('SERVER_ADDR');
    }

    return $serverIp;
}

/**
 * 获取客户端IP地址
 *
 * @param void
 * @return    string
 */
function GetIP()
{
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    //$proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_CDN_SRC_IP']))
        $proxy_ip = $_SERVER['HTTP_CDN_SRC_IP'];

    if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $proxy_ip, $matches)) {
        foreach ($matches[0] AS $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    }
    return $ip;
}

/**
 * 此函数可以截取带有中文的字符
 * @param $sourcestr 原字符串
 * @param $cutlength 截取长度
 * @param bool $isEllipsis 是否加省略号
 * @return string
 */
function cutStr($sourcestr, $cutlength, $isEllipsis = false)
{
    $returnstr = '';
    $i = 0;
    $n = 0;
    $str_length = strlen($sourcestr);//字符串的字节数
    while (($n < $cutlength) and ($i <= $str_length)) {
        $temp_str = substr($sourcestr, $i, 1);
        $ascnum = Ord($temp_str);//得到字符串中第$i位字符的ascii码
        if ($ascnum >= 224)    //如果ASCII位高于224，
        {
            $returnstr = $returnstr . substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
            $i = $i + 3;            //实际Byte计为3
            $n++;            //字串长度计1
        } elseif ($ascnum >= 192) //如果ASCII位高与192，
        {
            $returnstr = $returnstr . substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
            $i = $i + 2;            //实际Byte计为2
            $n++;            //字串长度计1
        } elseif ($ascnum >= 65 && $ascnum <= 90) //如果是大写字母，
        {
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1;            //实际的Byte数仍计1个
            $n++;            //但考虑整体美观，大写字母计成一个高位字符
        } else                //其他情况下，包括小写字母和半角标点符号，
        {
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1;            //实际的Byte数计1个
            $n = $n + 0.5;        //小写字母和半角标点等与半个高位字符宽...
        }
    }
    if ($str_length > $cutlength && $isEllipsis) {
        $returnstr = $returnstr . "...";//超过长度时在尾处加上省略号
    }
    return $returnstr;
}

/**
 * 处理短信内容长度
 *        中文、英文、标点符号都算一个字符长度
 *
 * @param string $string
 * @return    int
 */
function utf8Strlen($string = null)
{
    // 将字符串分解为单元
    preg_match_all("/./us", $string, $match);
    // 返回单元个数
    return count($match[0]);
}

/**
 * 对指定数组指定键进行排序
 *
 * @param array $array the array you want to sort
 * @param string $by the associative array name that is one level deep
 * @param string $order ASC or DESC
 * @param string $type NUM or STR
 * @return    array
 */
function dataSort($array, $by, $order, $type)
{
    if ($by == '') {
        return $array;
    }
    $sortby = "sort$by";
    $firstval = current($array);
    $vals = array_keys($firstval);

    foreach ($vals as $init) {
        $keyname = "sort$init";
        $$keyname = array();
    }

    foreach ($array as $key => $row) {
        foreach ($vals as $names) {
            $keyname = "sort$names";
            $test = array();
            $test[$key] = $row[$names];
            $$keyname = array_merge($$keyname, $test);
        }
    }

    if ($order == "DESC") {
        if ($type == "NUM") {
            array_multisort($$sortby, SORT_DESC, SORT_NUMERIC, $array);
        } else {
            array_multisort($$sortby, SORT_DESC, SORT_STRING, $array);
        }
    } else {
        if ($type == "NUM") {
            array_multisort($$sortby, SORT_ASC, SORT_NUMERIC, $array);
        } else {
            array_multisort($$sortby, SORT_ASC, SORT_STRING, $array);
        }
    }

    return $array;
}

/**
 * 根据指定URL获取内容
 *
 * @params    string    $url
 * @return    string
 */
function getFile($url)
{
    $time = 5;
    $ctx = stream_context_create(
        array(
            'http' => array(
                'timeout' => $time // 设置一个超时时间，单位为秒
            )
        )
    );

    return @file_get_contents($url, 0, $ctx);
}

// 解密
function decrypt($passwd, $salt = 'aqcsdmmdyswqnmlgb')
{
    $tmp_arr = str_split($passwd, 2);
    $len = count($tmp_arr);

    $tmp_key_arr = str_split($salt);
    foreach ($tmp_key_arr as $k => $v) {
        $tmp_key_arr[$k] = ord($v);
    }

    if ($len > 0) {
        $lack_arr = array();
        for ($i = 0; $i < $len; $i++) {
            if ($i < count($tmp_key_arr)) {
                $lack_arr[] = $tmp_key_arr[$i];
            } else {
                $tmp_key = $i % count($tmp_key_arr);
                $lack_arr[] = $tmp_key_arr[$tmp_key];
            }
        }
    }

    $pwd = '';
    foreach ($tmp_arr as $key => $val) {
        $val = hexdec($val);
        $pwd .= chr($val ^ $lack_arr[$key]);
    }

    return $pwd;
}

/**
 * 返回随机产生的字符
 *
 * $param    string    $type
 * $param    int        $length
 * @return    string
 */
function getRandStr($type = 'int', $length = 6)
{
    if ($type == 'all') {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    } else if ($type == 'int') {
        $chars = '0123456789';
    } else if ($type == 'letter') {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
    $newStr = '';
    for ($i = 1; $i <= $length; $i++) {
        $pos = mt_rand(1, strlen($chars));
        $newStr .= $chars[$pos - 1];
    }
    return $newStr;
}

/**
 * 求两个日期之间相差的天数
 * (针对1970年1月1日之后，求之前可以采用泰勒公式)
 *
 * @param string $day1
 * @param string $day2
 * @return number
 */
function diffBetweenTwoDays($day1, $day2)
{
    $second1 = strtotime($day1);
    $second2 = strtotime($day2);

    if ($second1 < $second2) {
        $tmp = $second2;
        $second2 = $second1;
        $second1 = $tmp;
    }

    return ($second1 - $second2) / 86400;
}

/**
 * 返回给定年对应的月的天数
 *
 * @param int $month
 * @param int $year
 * @return  string
 */
function getDaysInMonth($month, $year = '')
{
    if (empty($year)) {
        $year = date('Y');
    }
    $month = intval($month);
    return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
}

/**
 * 获取文件大小
 *
 * @param string $file
 * @return    string（MB或KB）
 */
function getFileSize($file)
{
    if (!file_exists($file)) return '0';
    $filesize = filesize($file) / 1024;
    if ($filesize >= 1024) {
        return number_format($filesize / 1024, 2, '.', '') . 'MB';
    }
    return number_format($filesize, 2, '.', '') . 'KB';
}

/**
 * 判断数据是否已经序列化
 * @param unknown_type $data
 * @return boolean
 */
function is_serialized($data)
{
    $data = trim($data);
    if ('N;' == $data)
        return true;
    if (!preg_match('/^([adObis]):/', $data, $badions))
        return false;
    switch ($badions[1]) {
        case 'a' :
        case 'O' :
        case 's' :
            if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data))
                return true;
            break;
        case 'b' :
        case 'i' :
        case 'd' :
            if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data))
                return true;
            break;
    }
    return false;
}