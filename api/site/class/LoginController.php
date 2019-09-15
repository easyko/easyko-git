<?php
/**
 *
 * Controller for Login
 *
 * @package		classes
 * @subpackage	login
 * @author		jerry.cao (caowlong163@163.com)
 *
 */

class LoginController extends CommonController
{
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
		
		$this->model = $this->createModel('Model_Login', dirname( __FILE__ ));
	}

	public function display()
	{
		die('error');
	}

	/**
     * 验证登录
     *
     */
	public function login()
	{
		$username = Fuse_Request::getFormatVar($this->params, 'name');
		$passwd   = Fuse_Request::getFormatVar($this->params, 'passwd');

		if (empty($username) || empty($passwd)) {
			die(json_encode(array('code'=> '1111', 'message' => '账号错误', 'data' => '')));
		}

		$row = $this->model->getLogin($username);
		if (empty($row)) {
			die(json_encode(array('code'=> '2222', 'message' => '用户名不存在', 'data' => '')));
		}
	
		// 判断对应公司有效期
		$modelCompany = $this->createModel('Model_Company', dirname( __FILE__ ));
		$companyInfo = $modelCompany->getCompanyById($row['companyId']);
		if (empty($companyInfo)) {
			die(json_encode(array('code'=> '3333', 'message' => '公司不存在', 'data' => '')));
		}

		if ($companyInfo['isPayed'] && strtotime($companyInfo['expireDate']) < strtotime(date('Y-m-d'))) {
			die(json_encode(array('code'=> '4444', 'message' => '公司续约已到期，请联系客服人员', 'data' => '')));
		}

		if (!$row['valid']) {
			die(json_encode(array('code'=> '5555', 'message' => '此用户已被删除', 'data' => '')));
		}

		if ((md5($passwd . $row['randStr'])) != $row['password']) {
			die(json_encode(array('code'=> '6666', 'message' => '密码输入有误', 'data' => '')));
		}

		$this->model->update(
			$this->model->getTableName(),
			array('last_login_ip' => Config_App::getIP(), 'last_login_time' => Config_App::getTime()),
			" `user_id` = '{$row['userId']}' "
		);

		unset($row['password']);
		unset($row['randStr']);
		unset($row['valid']);
		$data = array(
			'info' => $row
		);

		die(json_encode(array('code'=> '0000', 'message' => '登录成功', 'data' => array(), 'signature' => Fuse_Tool::encrypt(serialize($data)))));
	}
}
