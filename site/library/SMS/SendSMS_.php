<?php
/**
 * 短信发送基类
 *
 * @auth jerry.cao(14033184)
 * @date 2016-09-18
 */

set_time_limit(0);

class SMS_SendSMS_TEST
{
    private static $wsdlWebservicesUrl = 'http://openapi.mdtechcorp.com:21080/OpenAPI/sms?wsdl';
    private static $wsdlHttpUrl 	   = 'http://openapi.mdtechcorp.com:20000/openapi';

    public static function send($to, $message)
    {
        global $global;
        $db = new DB(DB_DRIVER, $global['dsn_w'], $global['dsn_r']);

        // Store
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`ssl`, 'www.', '') = '" . $db->escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
        } else {
            $store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'www.', '') = '" . $db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
        }

        if ($store_query->num_rows) {
            $config_store_id = $store_query->row['store_id'];
        } else {
            $config_store_id = 0;
        }

        $query = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)$config_store_id . "' ORDER BY store_id ASC");
        $result = $query->rows;

        $config = array();
        foreach ($query->rows as $result) {
            if (!$result['serialized']) {
                $config[$result['key']] = $result['value'];
            } else {
                $config[$result['key']] = unserialize($result['value']);
            }
        }

        $languages = array();
        $query = $db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE status = '1'");
        foreach ($query->rows as $result) {
            $languages[$result['code']] = $result;
        }
        $session = new Session();
        $request = new Request();
        if (isset($session->data['language']) && array_key_exists($session->data['language'], $languages)) {
            $code = $session->data['language'];
        } elseif (isset($request->cookie['language']) && array_key_exists($request->cookie['language'], $languages)) {
            $code = $request->cookie['language'];
        } else {
            $detect = '';
            if (isset($request->server['HTTP_ACCEPT_LANGUAGE']) && $request->server['HTTP_ACCEPT_LANGUAGE']) {
                $browser_languages = explode(',', $request->server['HTTP_ACCEPT_LANGUAGE']);
                foreach ($browser_languages as $browser_language) {
                    foreach ($languages as $key => $value) {
                        if ($value['status']) {
                            $locale = explode(',', $value['locale']);
                            if (in_array($browser_language, $locale)) {
                                $detect = $key;
                                break 2;
                            }
                        }
                    }
                }
            }
            $code = $detect ? $detect : $config['config_language'];
        }

        $language = new Language($languages[$code]['directory']);
        $lang = $language->load('account/register');

        if (!isset($config['config_sms_username']) || !isset($config['config_sms_password'])) {
            return array('success' => '0', 'msg' => $lang['error_smscaptcha_fail']);
        }

        $log = new Log($config['config_error_filename']);

        //if (SYS_VERSION != 'PRD') {
        $params = array(
            'username' 			 => trim($config['config_sms_username']),
            'password' 			 => trim($config['config_sms_password']),
            'originatingAddress' => trim($config['config_sms_username']),
            'destinatingAddress' => $to,
            'sms' 				 => urlencode(self::getUTF8($message)),
            'returnMode' 		 => 1, // 1:新返回码，返回接口交易ID为正确，0:老返回码，返回0为正确
            'type' 				 => 1
        );

        $serverUrl = !empty($config['config_sms_server_url']) ? trim($config['config_sms_server_url']) : self::$wsdlHttpUrl;
        $url = $serverUrl . (substr($serverUrl, -1) === '/' ? '?' : '/?') . http_build_query($params);

        try {
            $result = self::curlRequest($url);
            if (!$result['success']) {
                @$log->write("sms_error : " . $result['result']);
                return array('success' => '0', 'msg' => $lang['error_smscaptcha_fail']);
            }

            if ($result['result'] > 0) {
                return array('success' => '1', 'msg' => $lang['error_smscaptcha_success']);
            } else {
                if (is_numeric($result['result'])) {
                    $error = self::getSmsError($result['result']);
                    @$log->write("sms_error : " . $error);
                }
                return array('success' => '0', 'msg' => $lang['error_smscaptcha_fail']);
            }
        } catch (Exception $e) {
            @$log->write("sms_error : " . $e->getMessage());
            return array('success' => '0', 'msg' => $lang['error_smscaptcha_fail']);
        }
        /*} else {
            $params = array(
                'destination' => $to, 	   							   // Destination Address of this transaction
                'username' 	  => trim($config['config_sms_username']), // Username of your account
                'password' 	  => trim($config['config_sms_password']), // Password of your account
                'SMS' 		  => urlencode(self::getUTF8($message)),   // SMS content
                'origination' => trim($config['config_sms_username']), // Message originating address
                'type' 		  => 1,		 							   // Transaction type.
                'returnMode'  => 1,									   // Accept value 1 or 0
                'sentDirect'  => '1' 								   // Accept value 1 or 0.
            );

            ini_set('default_socket_timeout', 5); // 设置超时时间

            try {
                //实例化对象
                $client = new SoapClient(self::$wsdlWebservicesUrl);
                $result = $client->send($params);
                if (!$result['success']) {
                    @$log->write("sms_error : " . $result['result']);
                    return array('success' => '0', 'msg' => $lang['error_smscaptcha_fail']);
                }

                if ($result['result'] > 0) {
                    return array('success' => '1', 'msg' => $lang['error_smscaptcha_success']);
                } else {
                    if (is_numeric($result['result'])) {
                        $error = self::getSmsError($result['result']);
                        @$log->write("sms_error : " . $error);
                    }
                    return array('success' => '0', 'msg' => $lang['error_smscaptcha_fail']);
                }
            } catch (Exception $e) {
                @$log->write("sms_error : " . $e->getMessage());
                return array('success' => '0', 'msg' => $lang['error_smscaptcha_fail']);
            }
        }*/
    }


    public static function getSmsError($code)
    {
        $msg = '';
        switch ($code) {
            case "0":
                $msg = 'Success';
                break;
            case "-1":
                $msg = 'Wrong Destination Address';
                break;
            case "-2":
                $msg = 'Account Information/username/IP/password incorrect';
                break;
            case "-3":
                $msg = 'Content ID incorrent';
                break;
            case "-4":
                $msg = 'Destination Number not all correct';
                break;
            case "-8":
                $msg = 'Wrong Origination Address';
                break;
            case "-100":
                $msg = 'Internal Error please contact support';
                break;
            default:
                $msg = 'Unknown error';
        }

        return $msg;
    }


    /****************************** HTTP或HTTPS方式 相关方法  ***********************************/

    public static function he2str($he)
    {
        $tmpHe = $he;
        $output = "";
        for($i=0; $i<strlen($tmpHe); $i=$i+1){
            if($i < strlen($tmpHe)-6){
                $tmpCh = substr($tmpHe, $i, 2);
            }else{
                $tmpCh="";
            }
            if($tmpCh == "%u"){
                $output = $output."&#x".substr($tmpHe, $i+2, 4).";";
                $i=$i+5;
            }else{
                $output = $output.substr($tmpHe, $i, 1);
            }
        }

        return $output;
    }

    public static function html_entity_decode_utf8($string)
    {
        static $trans_tbl;

        // replace numeric entities
        $string = preg_replace('~&#x([0-9a-f]+);~ei', 'code2utf(hexdec("\\1"))', $string);
        $string = preg_replace('~&#([0-9]+);~e', 'code2utf(\\1)', $string);

        // replace literal entities
        if (!isset($trans_tbl))
        {
            $trans_tbl = array();

            foreach (get_html_translation_table(HTML_ENTITIES) as $val=>$key)
                $trans_tbl[$key] = utf8_encode($val);
        }

        return strtr($string, $trans_tbl);
    }

    public static function code2utf($num)
    {
        if ($num < 128) return chr($num);
        if ($num < 2048) return chr(($num >> 6) + 192) . chr(($num & 63) + 128);
        if ($num < 65536) return chr(($num >> 12) + 224) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
        if ($num < 2097152) return chr(($num >> 18) + 240) . chr((($num >> 12) & 63) + 128) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
        return '';
    }

    public static function uniord($ch)
    {
        $n = ord($ch{0});
        if ($n < 128) {
            return $n; // no conversion required
        }

        if ($n < 192 || $n > 253) {
            return false; // bad first byte || out of range
        }

        $arr = array(
            1 => 192, // byte position => range from
            2 => 224,
            3 => 240,
            4 => 248,
            5 => 252,
        );

        foreach ($arr as $key => $val) {
            if ($n >= $val) { // add byte to the 'char' array
                $char[] = ord($ch{$key}) - 128;
                $range  = $val;
            } else {
                break; // save some e-trees
            }
        }

        $retval = ($n - $range) * pow(64, sizeof($char));

        foreach ($char as $key => $val) {
            $pow = sizeof($char) - ($key + 1); // invert key
            $retval += $val * pow(64, $pow);   // dark magic
        }

        return $retval;
    }

    public static function fillZero($str)
    {
        if(strlen($str) < 1){
            return "0000";
        }else if(strlen($str) < 2){
            return "000".$str;
        }else if(strlen($str) < 3){
            return "00".$str;
        }else if(strlen($str) < 4){
            return "0".$str;
        }else{
            return $str;
        }
    }

    public static function getUTF8($str)
    {
        $output = "";
        $encStr = $str;
        for($i=0; $i<strlen($str); $i=$i+1){
            $tmpCh = self::uniord($encStr);
            if($tmpCh){
                if($tmpCh > 254){
                    $encStr = substr($encStr, 3, strlen($encStr)-3);
                    $i = $i + 2;
                }else{
                    $encStr = substr($encStr, 1, strlen($encStr)-1);
                }
                $tmpCh = strtoupper(dechex($tmpCh));
                $tmpCh = self::fillZero($tmpCh);
                $output = $output."&#x".$tmpCh.";";
            }else{ //Unknown charaters
                $output = $output.substr($encStr, 0, 1);
                $encStr = substr($str, 1, strlen($encStr)-1);
            }
        }
        return $output;
    }

    public static function curlRequest($url, $proxy = '') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 		 // 地址
        curl_setopt($ch, CURLOPT_HEADER, 0);         // 1:返回内容中包含 HTTP 头
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // 在发起连接前等待的时间，如果设置为0，则无限等待
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);		 // 设置cURL允许执行的最长秒数
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过ssl认证
        if (!empty($proxy)) {
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }

        $curlData  = curl_exec($ch); // 获得返回值
        $curlErrno = curl_errno($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlErrno > 0) {
            return array(
                'success' => '0',
                'result'  => $curlError . '(' . $curlErrno . ')'
            );
        }

        return array(
            'success' => '1',
            'result'  => $curlData
        );
    }
}
