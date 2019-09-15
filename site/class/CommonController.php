<?php
/**
 *
 * Controller for Common
 *
 * @package		classes
 * @subpackage	index
 * @author		jerry.cao (caowlong163@163.com)
 *
 */

class CommonController extends Fuse_Controller
{
	protected $userId   = 0;
	protected $roleId   = 0;
	protected $url 	 	= '';
	protected $menuName = '';

	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->userId = Fuse_Cookie::getInstance()->user_id;
		$this->roleId = Fuse_Cookie::getInstance()->role_id;

		$this->checkLoginValid();

		$this->checkUser();

		$this->menuName = str_replace('.php', '', str_replace('/', '', $_SERVER['SCRIPT_NAME']));
		$this->url = str_replace('/', '', $_SERVER['SCRIPT_NAME']);
	}

    /**
     * 验证登录超时
     *
     */
	public function checkLoginValid()
	{
		if (empty($this->userId) || $this->roleId == '') {
			//$languageId = Fuse_Cookie::getInstance()->language_id;
			//if ($languageId == '1') {
				$forward = '/';
			//} else {
			//	$forward = '/en/';
			//}
			Fuse_Response::redirect($forward);
		}
	}

    /**
     * 验证登录权限
     *
     */
	public function checkUser()
	{
/*
		$model = $this->createModel('Model_Member', dirname( __FILE__ ));
		$userInfo = $model->getInfo($this->userId);
		Fuse_Cookie::getInstance()->role_id = $userInfo['role_id'];
		if ($userInfo['role_id'] == '3') {
			Fuse_Response::redirect('/');
		}
*/
	}
}