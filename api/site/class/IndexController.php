<?php
/**
 *
 * Controller for Index
 *
 * @desc	首页
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-04-20 23:37:45
 *
 */
class IndexController extends CommonController
{
	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		parent::checkLoginValid();

		$this->registerTask('index', 'index');
		$this->registerTask('getmenu', 'getmenu');
		
		// 配置文件
		include_once(dirname(__file__) . '/config.php');
	}

	public function display()
	{
		die('error');
	}

	/**
	 * 登录
	 */
/*
	public function index()
	{
		if (empty($this->userId) || empty($this->roleId)) {
			$view = $this->createView();
			$view->formhash = Config_App::formhash('login');

			if ($this->languageId == '1') {
				$title = '登录';
				$html = 'login.html';
			} else {
				$title = 'Login';
				$html = 'en/login.html';
			}

			$view->title = $title;
			$view->display($html);
		} else {
			if ($this->roleId == '3') {
				Fuse_Response::redirect($this->role_3);
			} else {
				Fuse_Response::redirect($this->role_1_2);
			}
		}
	}
*/


	
	/**
	 * 获取菜单列表
	 */
	public function getmenu()
	{
		// 查询登录用户对应角色及菜单权限
		$modelMember = $this->createModel('Model_Member', dirname( __FILE__ ));
		$menuInfo = $modelMember ->getRoleInfo($this->companyId, $this->roleId);
		if (empty($menuInfo) || !isset($menuInfo['menus']) || empty($menuInfo['menus'])) {
			die(json_encode(array('code'=> '1111', 'message' => '角色信息异常', 'data' => '')));
		}
		
		// 获取菜单权限列表
		$menuList = $modelMember->getCurrMenuListByRoleId($this->companyId, $this->roleId, $menuInfo['menus']);
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $menuList)));
	}
}
