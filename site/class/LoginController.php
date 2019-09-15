<?php
/**
 *
 * Controller for Login
 *
 * @package		classes
 * @subpackage	index
 * @author		jerry.cao (caowlong163@163.com)
 *
 */

class LoginController extends Fuse_Controller
{
    protected $language = array(
        'error_illegal' => '非法提交',
        'error_tel_empty' => '手机号码为空',
        'error_telephone' => '手机号码格式错误',
        'error_password_empty' => '密码为空',
        'error_password' => '密码使用了非法字符',
        'error_login' => '手机号或密码错误',
        'error_smscode_empty' => '短信验证码为空',
        'error_smscode' => '短信验证码出错',
        'config_name' => '易酷',
        'title_name' => '登录'
    );

	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('index', 'index');
		$this->registerTask('login', 'login');
		$this->registerTask('logout', 'logout');

		$this->companyId = Fuse_Cookie::getInstance()->companyId;
		$this->companyName = Fuse_Cookie::getInstance()->companyName;

		$this->error = array();

        $this->model = $this->createModel('Model_Login', dirname( __FILE__ ));
        $this->session = Fuse_Session::getInstance();
	}

	public function display()
	{
		die('error');
	}

	/**
	 * 登录
	 */
	public function index()
	{
        if (Fuse_Customer::getInstanceCustomer()->isLogged()) {
            Fuse_Response::redirect('/');
        }

        if (Fuse_Request::getVar('REQUEST_METHOD', 'server') == 'POST' && $this->validate()) {
            die(json_encode(array('status'=> 'LOGIN_SUCCESS')));
        }

        if (isset($this->error['illegal'])) {
            die(json_encode(array('status'=> 'INVALID_INPUT1', 'msg' => $this->error['illegal'], 'key' => 'password')));
        }

        if (isset($this->error['account'])) {
            die(json_encode(array('status'=> 'INVALID_INPUT2', 'msg' => $this->error['account'], 'key' => 'password')));
        }

        if (isset($this->error['password'])) {
            die(json_encode(array('status'=> 'INVALID_INPUT3', 'msg' => $this->error['password'], 'key' => 'password')));
        }

        if (isset($this->error['warning'])) {
            die(json_encode(array('status'=> 'INVALID_INPUT4', 'msg' => $this->error['warning'], 'key' => 'password')));
        }

        if (isset($this->error['telephone'])) {
            die(json_encode(array('status'=> 'INVALID_INPUT5', 'msg' => $this->error['telephone'], 'key' => 'password')));
        }

        if (isset($this->error['smscode'])) {
            die(json_encode(array('status'=> 'INVALID_INPUT6', 'msg' => $this->error['smscode'], 'key' => 'password')));
        }


        $view = $this->createView();
        $view->formhash = Config_App::formhash('login');
        $view->title = $this->language['title_name'];
        $view->display('../login/signin.html');
        return;
		
		Fuse_Response::redirect('/');
	}

    /**
     * 验证登录
     *
     */
	public function validate()
	{
        $this->request['type'] = Fuse_Request::getVar('logintype', 'post');
        $this->request['telephone']    = Fuse_Request::getVar('phone', 'post');
        $this->request['password']   = Fuse_Request::getVar('password', 'post');
        $this->request['account']    = Fuse_Request::getVar('account', 'post');
        $this->request['smscode']   = Fuse_Request::getVar('smscode', 'post');
        $this->request['formhash'] = Fuse_Request::getVar('formhash', 'post');
        $this->request['forward']  = Fuse_Request::getVar('forward');

		if (empty($forward)) {
			$forward = Fuse_Request::getVar('HTTP_REFERER', 'server');
		}

		$checkFormhash = Config_App::formhash('login');
		if ($checkFormhash != $this->request['formhash']) {
            $this->error['illegal'] = $this->language['error_illegal'];
		}

		if ($this->request['type']) { // 密码登录
            if ($this->request['account'] == '') {
                $this->error['account'] = $this->language['error_tel_empty'];
            } else if ((mb_strlen($this->request['account']) != 11) || !is_numeric(trim($this->request['account']))) {
                $this->error['account'] = $this->language['error_telephone'];
            }

            if ($this->request['password'] == '') {
                $this->error['password'] = $this->language['error_password_empty'];
            }

            if (!$this->error) {
                if (!Fuse_Customer::getInstanceCustomer()->login($this->request['account'], $this->request['password'], false, false)) {
                    $this->error['warning'] = $this->language['error_login'];
                }
            }
        } else {    // 验证码登录
            if ($this->request['telephone'] == '') {
                $this->error['telephone'] = $this->language['error_tel_empty'];
            } else if ((mb_strlen($this->request['telephone']) != 11) || !is_numeric(trim($this->request['telephone']))) {
                $this->error['telephone'] = $this->language['error_telephone'];
            }

            // 验证码为空检查
            if($this->request['smscode'] == '' ) {
                $this->error['smscode'] = $this->language['error_smscode_empty'];
            }

            // 验证码正确性检查
            if ($this->error || !($this->model->smsLogin($this->request['telephone'], $this->request['smscode'], $this->session->data['request_id']))) {
                $this->error['smscode'] = $this->language['error_smscode'];
            }

            if (!$this->error) {
                if (!Fuse_Customer::getInstanceCustomer()->login($this->request['telephone'], $this->request['password'], true, false)) {
                    $this->error['warning'] = $this->language['error_login'];
                }
            }
        }

        return !$this->error;

        /*
		if (!empty($this->companyId)) {
			Fuse_Cookie::getInstance()->companyId   = '';
			Fuse_Cookie::getInstance()->mobilePhone = '';
			Fuse_Cookie::getInstance()->companyName = '';
		}

		$model = $this->createModel('Model_Login', dirname( __FILE__ ));
		$row = $model->getLogin($phone);
		if (empty($row)) {
			die(json_encode(array('status'=> 'USERNAME_NOEXISTS', 'msg' => '手机号或密码错误', 'key' => 'phone')));
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
        */
	}

	/**
	 * 退出登录
	 */
	public function logout()
	{
		if (isset($this->companyId)) {
			Fuse_Cookie::getInstance()->companyId   = '';
			Fuse_Cookie::getInstance()->mobilePhone = '';
			Fuse_Cookie::getInstance()->companyName = '';
		}

		if (Fuse_Customer::getInstanceCustomer()->isLogged()) {
            Fuse_Customer::getInstanceCustomer()->logout();
            unset($this->sessioni->data);
        }
        die(json_encode(array('status'=> 'LOGOUT_SUCCESS')));
	}
}
