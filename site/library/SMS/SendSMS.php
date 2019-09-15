<?php

ini_set("display_errors", "on");

require_once __DIR__ . '/api_sdk/vendor/autoload.php';

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendBatchSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

// 加载区域结点配置
Config::load();

/**
 * Class SmsDemo
 *
 * 这是短信服务API产品的DEMO程序，直接执行此文件即可体验短信服务产品API功能
 * (只需要将AK替换成开通了云通信-短信服务产品功能的AK即可)
 * 备注:Demo工程编码采用UTF-8
 */
class SMS_SendSMS
{

    static $acsClient = null;

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public static function getAcsClient() {
        //产品名称:云通信短信服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = "LTAIg6mQHHGgPG2D"; // AccessKeyId

        $accessKeySecret = "mL5qbEUjnuSV2xoTzKcbegzFDgJEcn"; // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";


        if(static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }

    /**
     * 发送短信
     * @return stdClass
     */
    public static function sendSms() {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填，设置短信接收号码
        $request->setPhoneNumbers("13809043282");

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName("短信签名");

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode("SMS_0000001");

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode(array(  // 短信模板中字段的值
            "code"=>"12345",
            "product"=>"dsd"
        ), JSON_UNESCAPED_UNICODE));

        // 可选，设置流水号
        $request->setOutId("yourOutId");

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        $request->setSmsUpExtendCode("1234567");

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }

    /**
     * 批量发送短信
     * @return stdClass
     */
    public static function sendBatchSms() {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendBatchSmsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填:待发送手机号。支持JSON格式的批量调用，批量上限为100个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
        $request->setPhoneNumberJson(json_encode(array(
            "1500000000",
            "1500000001",
        ), JSON_UNESCAPED_UNICODE));

        // 必填:短信签名-支持不同的号码发送不同的短信签名
        $request->setSignNameJson(json_encode(array(
            "云通信",
            "云通信"
        ), JSON_UNESCAPED_UNICODE));

        // 必填:短信模板-可在短信控制台中找到
        $request->setTemplateCode("SMS_1000000");

        // 必填:模板中的变量替换JSON串,如模板内容为"亲爱的${name},您的验证码为${code}"时,此处的值为
        // 友情提示:如果JSON中需要带换行符,请参照标准的JSON协议对换行符的要求,比如短信内容中包含\r\n的情况在JSON中需要表示成\\r\\n,否则会导致JSON在服务端解析失败
        $request->setTemplateParamJson(json_encode(array(
            array(
                "name" => "Tom",
                "code" => "123",
            ),
            array(
                "name" => "Jack",
                "code" => "456",
            ),
        ), JSON_UNESCAPED_UNICODE));

        // 可选-上行短信扩展码(扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段)
        // $request->setSmsUpExtendCodeJson("[\"90997\",\"90998\"]");

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }

    /**
     * 短信发送记录查询
     * @return stdClass
     */
    public static function querySendDetails() {

        // 初始化QuerySendDetailsRequest实例用于设置短信查询的参数
        $request = new QuerySendDetailsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填，短信接收号码
        $request->setPhoneNumber("12345678901");

        // 必填，短信发送日期，格式Ymd，支持近30天记录查询
        $request->setSendDate("20170718");

        // 必填，分页大小
        $request->setPageSize(10);

        // 必填，当前页码
        $request->setCurrentPage(1);

        // 选填，短信发送流水号
        $request->setBizId("yourBizId");

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }

    /**
     * 封装发送短信
     * @return stdClass
     */
    public static function send($to, $message, $signName, $templateCode)
    {
        /*
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
        */

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($to);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName($signName);

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode($templateCode);

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode(array("code"=>"$message"), JSON_UNESCAPED_UNICODE));

        // 可选，设置流水号
        $request->setOutId("yourOutId");

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        $request->setSmsUpExtendCode("1234567");

        try {
//            $result = self::curlRequest($url);
            // 发起访问请求
            $result = static::getAcsClient()->getAcsResponse($request);

            if ($result->Code == 'OK') {
//                @$log->write("sms_error : " . $result['result']);
                return array('status' => '1', 'msg' => $result->Message, 'requestId' => $result->RequestId, 'code' => $result->Code, 'bizId' => $result->BizId);
            }
            return array('status' => '0', 'msg' => $result->Message, 'requestId' => $result->RequestId, 'code' => $result->Code);
        } catch (Exception $e) {
//            @$log->write("sms_error : " . $e->getMessage());
//            return array('success' => '0', 'msg' => $lang['error_smscaptcha_fail']);
            return $e->getMessage();
        }

    }
}

/*
// 调用示例：
set_time_limit(0);
header('Content-Type: text/plain; charset=utf-8');

$response = SmsDemo::sendSms();
echo "发送短信(sendSms)接口返回的结果:\n";
print_r($response);
exit;
sleep(2);

$response = SmsDemo::sendBatchSms();
echo "批量发送短信(sendBatchSms)接口返回的结果:\n";
print_r($response);

sleep(2);

$response = SmsDemo::querySendDetails();
echo "查询短信发送情况(querySendDetails)接口返回的结果:\n";
print_r($response);
*/
