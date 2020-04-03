<?php
/**
 *
 * Controller for egister
 *
 * @package		classes
 * @subpackage	index
 * @author		jerry.cao (caowlong163@163.com)
 *
 */

class RegisterController extends Fuse_Controller
{
    private $error 	 	   = array();
    private $request = array();
    protected $language = array(
        'error_illegal' => '非法提交',
        'error_tel_empty' => '手机号码为空',
        'error_telephone' => '手机号码格式错误',
        'error_exists_tel' => '手机号已存在',
        'error_smscode_empty' => '短信验证码为空',
        'error_smscode' => '短信验证码出错',
        'error_smscaptcha_fail2' => '验证码异常',
        'error_smscaptcha_fail3' => '验证码接收异常',
        'text_register_sms' => '注册验证码：%s',
        'error_username_empty' => '登录名为空',
        'error_password_length' => '密码长度错误',
        'error_password' => '密码使用了非法字符',
        'error_company_empty' => '公司名称为空',
        'error_login' => '手机号或密码错误',
        'config_name' => '易酷',
        'title_name' => '注册'
    );
    private $model = '';
    private $log = '';

	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('index', 'index');
        $this->registerTask('prevalidate', 'preValidate');
        $this->registerTask('register', 'register');
        $this->registerTask('validate', 'validate');
        $this->registerTask('sendsms', 'sendsms');

        $this->session = Fuse_Session::getInstance();

        $this->log = Fuse_Log::getInstance( Config_Global::$config_error_filename );

        // 登录检查
        if (Fuse_Customer::getInstanceCustomer()->isLogged()) {
            Fuse_Response::redirect('/');
        }
        $this->model = $this->createModel('Model_Register', dirname( __FILE__ ));
	}

	public function display()
	{
		die('error');
	}

	/**
	 * 注册
	 */
    public function index()
    {
        if (Fuse_Request::getVar('REQUEST_METHOD', 'server') == 'POST') {
            // 注册时的会员等级限定为默认会员等级
            //$this->request->post['customer_group_id'] = $this->config->get('config_customer_group_id');

            if ($this->preValidate()) {
                // 暂存第一步注册信息，后期改为redis
                $this->session->data['telephone'] = $this->request['telephone'];
                $this->session->data['smscode'] = $this->request['smscode'];
                $this->session->data['formhash'] = $this->request['formhash'];
                die(json_encode(array('status' => 'LOGIN_SUCCESS')));
                //Fuse_Response::redirect('/'); return;
            }
        }

        if (isset($this->error['illegal'])) {
            die(json_encode(array('status'=> 'INVALID_INPUT', 'msg' => $this->error['illegal'], 'key' => 'password')));
        }

        if (isset($this->error['telephone'])) {
            die(json_encode(array('status'=> 'INVALID_INPUT', 'msg' => $this->error['telephone'], 'key' => 'password')));
        }

        if (isset($this->error['smscode'])) {
            die(json_encode(array('status'=> 'INVALID_INPUT', 'msg' => $this->error['smscode'], 'key' => 'password')));
        }

        $view = $this->createView();
        $view->formhash = Config_App::formhash('register');
        $view->title = $this->language['title_name'];
        $view->display('../login/signup.html');
        return;
    }

    /**
     * 下一步注册
     */
    public function register()
    {
        if (Fuse_Request::getVar('REQUEST_METHOD', 'server') == 'POST' && $this->validate()) {
            // 存储注册用户数据
            $this->request['phone'] = $this->session->data['telephone'];
            $model = $this->createModel('Model_Register', dirname( __FILE__ ));
            if (!$model->addUser($this->request)) {
                die(json_encode(array('status' => 0, 'msg' => $this->language['error_smscaptcha_fail2'])));
            }
            if (!Fuse_Customer::getInstanceCustomer()->login($this->session->data['telephone'], $this->request['password'], false, false)) {
                die(json_encode(array('status' => 'FAILED', 'msg' => $this->language['error_login'])));
            }
            /*
            $customer_id = $this->model_account_customer->addCustomer($this->request->post);

            if ($customer_id) {
                // 修改验证码为已使用状态
                // $this->model_account_customer->setSmsContentByTelphoneUsed($this->request->post['telephone']);

                // 防止表记录过多，验证通过即删除
                $this->model_account_customer->delSmsContentByTelphone($this->request->post['telephone']);
            }

            // Clear any previous login attempts for unregistered accounts.
            $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

            $this->customer->login($this->request->post['email'], $this->request->post['password']);

            unset($this->session->data['guest']);
            $this->session->data[CAT_TOKEN] = md5(mt_rand());
            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = array(
                'customer_id' => $customer_id,
                'name'        => $this->customer->getUserName()
            );
            $this->model_account_activity->addActivity('register', $activity_data);

            $this->response->redirect($this->url->link('account/success', 'token=' . $this->session->data[CAT_TOKEN], 'SSL'));
            */
            die(json_encode(array('status' => 'REGISTER_SUCCESS')));
        }

        if (isset($this->error['username'])) {
            die(json_encode(array('status'=> 'INVALID_INPUT1', 'msg' => $this->error['username'])));
        }

        if (isset($this->error['password'])) {
            die(json_encode(array('status'=> 'INVALID_INPUT2', 'msg' => $this->error['password'])));
        }

        if (isset($this->error['company'])) {
            die(json_encode(array('status'=> 'INVALID_INPUT3', 'msg' => $this->error['company'])));
        }

        $view = $this->createView();
        $view->formhash = Config_App::formhash('register');
        $view->title = $this->language['title_name'];
        $view->display('../login/setup.html');
        return;
    }

    public function preValidate() {
        $this->request['telephone'] = Fuse_Request::getVar('phone', 'post');
        $this->request['smscode']   = Fuse_Request::getVar('verCode', 'post');
        $this->request['formhash'] = Fuse_Request::getVar('formhash', 'post');
        $this->request['forward']  = Fuse_Request::getVar('forward');

        $checkFormhash = Config_App::formhash('register');
        if ($checkFormhash !=  $this->request['formhash']) {
            $this->error['illegal'] = $this->language['error_illegal'];
        }

        if ($this->request['telephone'] == '') {
            $this->error['telephone'] = $this->language['error_tel_empty'];
        } else if ((mb_strlen($this->request['telephone']) != 11) || !is_numeric(trim($this->request['telephone']))) {
            $this->error['telephone'] = $this->language['error_telephone'];
        }

        // 判断手机号是否已注册过用户
        $hasExists = $this->model->getUserByTelephone($this->request['telephone']);
        if ($hasExists) {
            $this->error['telephone'] = $this->language['error_exists_tel'];
        }

        // 判断手机号是否已注册过公司
        $hasExists = $this->model->getCompanyByTelephone($this->request['telephone']);
        if ($hasExists) {
            $this->error['telephone'] = $this->language['error_exists_tel'];
        }

        // 验证码为空检查
        if($this->request['smscode'] == '' ) {
            $this->error['smscode'] = $this->language['error_smscode_empty'];
        }

        // 验证码正确性检查
        if ($this->error || !($this->model->smsLogin($this->request['telephone'], $this->request['smscode'], $this->session->data['request_id']))) {
            $this->error['smscode'] = $this->language['error_smscode'];
        }

        /*
        // 验证手机号是否注册过
        if ($this->model_account_customer->getTotalCustomersByTelephone($this->request->post['telephone'])) {
            $this->error['warning'] = $this->language->get('error_exists_tel');
        }

        //验证SMS有效时间
        $smsInfo = $this->model_account_customer->getSmsContentByTelphone($this->request->post['telephone']);
        $smsValidTimes = 1800;
        $verifyKey = self::SMS_VERIFY_TIMES_PREFIX.$this->request->post['telephone'];
        $verifyTimes = $this->redisW->get($verifyKey);
        $verifyTimes = intval($verifyTimes);
        if (empty($this->request->post['smscaptcha'])) {
            $this->error['smscaptcha'] = $this->language->get('error_smscaptcha_empty');
        }
        else if ($verifyTimes > $this->smsErrorLimit){
            // 超过5次验证失败需要重新获取验证码
            $this->error['smscaptcha'] = $this->language->get('error_smscaptcha_resend');
        }
        else if (!is_numeric($this->request->post['smscaptcha']) || utf8_strlen($this->request->post['smscaptcha']) != 6 || !$smsInfo || ($smsInfo && $this->request->post['smscaptcha'] != $smsInfo['code'])) {
            //验证码不正确, 错误尝试次数+1
            if ($smsInfo && $this->request->post['smscaptcha'] != $smsInfo['code']) {
                $this->redisW->set($verifyKey, ++$verifyTimes, $smsValidTimes + 200);
            }
            $this->error['smscaptcha'] = $this->language->get('error_smscaptcha');

        } else if ($smsInfo && ((time() - strtotime($smsInfo['time'])) > $smsValidTimes)) {
            $this->error['smscaptcha'] = $this->language->get('error_smscaptcha_out');
        } else if ($smsInfo && $smsInfo['is_used'] == 1) {
            $this->error['smscaptcha'] = $this->language->get('error_smscaptcha_used');
        }

        // 验证码简单判断
        if(strtoupper($this->session->data['captcha']) != strtoupper($this->request->post['captcha']) ) {
            $this->error['captcha'] = $this->language->get('error_captcha');
        }*/

        return !$this->error;
    }


    public function validate() {
        $this->request['username'] = Fuse_Request::getVar('username', 'post');
        $this->request['password']   = Fuse_Request::getVar('password', 'post');
//        $this->request['confirm']  = Fuse_Request::getVar('confirm', 'post');
        $this->request['company']  = Fuse_Request::getVar('company', 'post');
        $this->request['formhash'] = Fuse_Request::getVar('formhash', 'post');

        $checkFormhash = Config_App::formhash('register');
        if ($checkFormhash !=  $this->request['formhash']) {
            $this->error['illegal'] = $this->language['error_illegal'];
        }

        if ($this->request['username'] == '') {
            $this->error['username'] = $this->language['error_username_empty'];
        }

        if (utf8Strlen($this->request['password']) < 6 || utf8Strlen($this->request['password']) > 20) {
            $this->error['password'] = $this->language['error_password_length'];
        }elseif (!isPassword($this->request['password'])) {
            $this->error['password'] = $this->language['error_password'];
        }

        // 确认密码
//        if ($this->request['confirm'] != $this->request['password']) {
//            $this->error['confirm'] = $this->language['error_confirm'];
//        }

        if ($this->request['company'] == '') {
            $this->error['company'] = $this->language['error_company_empty'];
        }

        return !$this->error;
    }

    public function sendsms()
    {
        $telephone = Fuse_Request::getVar('phone', 'post');
        $type = Fuse_Request::getVar('smstype', 'post');
        if ( $type == 1 ) {
            $tempCode = Config_Global::$smsTempCodeByRegister;
        } else if ( $type == 2 ) {
            $tempCode = Config_Global::$smsTempCodeByLogin;
        } else if ( $type == 3 ) {
            $tempCode = Config_Global::$smsTempCodeByBussNotice;
        } else if ( $type == 4 ) {
            $tempCode = Config_Global::$smsTempCodeByAccountEffective;
        } else if ( $type == 5 ) {
            $tempCode = Config_Global::$smsTempCodeByAccountExpiration;
        }
        if ($telephone == '') {
            die(json_encode(array('status' => 0, 'msg' => $this->language['error_tel_empty'])));
        }

        if ((mb_strlen($telephone) != 11) || !is_numeric($telephone)) {
            die(json_encode(array('status' => 0, 'msg' => $this->language['error_telephone'])));
        }
/*
        // 限制短信每分钟发1次
        if (isset($_SESSION['user_register']['time'])) {
            if ((time() - $_SESSION['user_register']['time']) < 60) {
                die(json_encode(array('success' => 0, 'msg' => $this->language->get('error_smscaptcha_limit'))));
            }
        }

        // 限制短信每天发送次数
        $smsInfo = $this->model_account_customer->getSmsContentByTelphone($this->request->get['telephone']);
        if ($smsInfo['sendtimes'] >= $this->smsDayLimit) {
            die(json_encode(array('success' => 0, 'msg' => $this->language->get('error_smscaptcha_limit_day'))));
        }
        */

        $code = getRandStr('int');
        //$content = sprintf($this->language['text_register_sms'], $code) . '【' . $this->language['config_name'] . '】';
        //$this->load->ventor('SendSMS');
        $result = SMS_SendSMS::send($telephone, $code, Config_Global::$smsSignName, $tempCode);

        if ( $result['code'] != 'OK' ) {
            $this->log->write(json_encode($result));
            die(json_encode(array('status' => 0, 'msg' => $this->language['error_smscaptcha_fail3'], 'msgs' => $result['msg'])));
        }

        $this->session->data['request_id'] = $result['requestId'];
        //$_SESSION['user_register']['time'] = time();

        // 记入表
        $result['phone'] = $telephone;
        $result['verification_code'] = $code;
        $result['type'] = $type;
        $model = $this->createModel('Model_Register', dirname( __FILE__ ));
        if (!$model->saveSmsContent($result)) {
            die(json_encode(array('success' => 0, 'msg' => $this->language['error_smscaptcha_fail2'])));
        }
        /*
        //已成功重新推送短信, 删除短信错误尝试次数记录
        $verifyTimesKey = self::SMS_VERIFY_TIMES_PREFIX.$telephone;
        if ($this->redisW->exists($verifyTimesKey)){
            $this->redisW->del($verifyTimesKey);
        }
        */
        die(json_encode(array('status' => '1')));
    }

    /**
     * 手机注册
     *
     */
    public function preRegister()
    {
        $phone 	  = Fuse_Request::getVar('phone', 'post');
        $passwd   = Fuse_Request::getVar('verCode', 'post');
        $formhash = Fuse_Request::getVar('formhash', 'post');
        $forward  = Fuse_Request::getVar('forward');

        if (empty($forward)) {
            $forward = Fuse_Request::getVar('HTTP_REFERER', 'server');
        }

        $checkFormhash = Config_App::formhash('register');
        if ($checkFormhash != $formhash) {
            die(json_encode(array('status'=> 'INVALID_FORM', 'msg' => '非法提交', 'key' => 'password')));
        }



        if (!empty($this->companyId)) {
            Fuse_Cookie::getInstance()->companyId   = '';
            Fuse_Cookie::getInstance()->mobilePhone = '';
            Fuse_Cookie::getInstance()->companyName = '';
        }




        exit;

        $model = $this->createModel('Model_Register', dirname( __FILE__ ));
        $row = $model->getLogin($phone);

        if (empty($row)) {
            die(json_encode(array('status'=> 'USERNAME_NOEXISTS', 'msg' => '手机号或验证码错误', 'key' => 'phone')));
        }

        if (!$row['valid']) {
            die(json_encode(array('status'=> 'USERNAME_DELETE', 'msg' => '此用户已被删除', 'key' => 'phone')));
        }

        if ($passwd != $row['password']) {
            die(json_encode(array('status'=> 'PASSWORD_ERROR', 'msg' => '密码输入有误', 'key' => 'password')));
        }

        $model->update(
            $this->table['name'],
            array('last_login_ip' => Config_App::getIP(), 'last_login_time' => Config_App::getTime()),
            " `company_id` = '{$row['companyId']}' "
        );

        Fuse_Cookie::getInstance()->companyId   = $row['companyId'];
        Fuse_Cookie::getInstance()->mobilePhone = $row['contactPhone'];
        Fuse_Cookie::getInstance()->companyName = $row['companyName'];

        die(json_encode(array('status'=> 'LOGIN_SUCCESS', 'msg' => '登录成功', 'redirect' => '/')));

    }

}
