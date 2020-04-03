<?php
/**
 *
 * Controller for Index
 *
 * @desc	首页
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-08-15 00:42:55
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

		$this->model = $this->createModel('Model_User', dirname( __FILE__ ));
	}

	public function display()
	{
		die('error');
	}

	/**
	 * 获取菜单列表
	 */
	public function getmenu()
	{
		// 获取登录用户对应菜单列表
		$privilege = $this->_getPrivilegeList($this->groupId);

		if (empty($privilege)) {
			die(json_encode(array('code'=> '1111', 'message' => '权限查询失败，无结果', 'data' => '')));
		}
		
		$data = array(
			'naviList' => $privilege,
			'username' => $this->username
		);

		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	
	/**
	 * 查询用户权限
	 */
	private function _getPrivilegeList($groupId = 0)
	{
		if (empty($groupId)) {
			$privilege = array();
		} else {
			$privilege = $this->model->getPrivilege($groupId);
		}
		$list = $this->model->getNaviList();

		$rowset = array();
		for ($i=0; $i<count($list); $i++) {
			$check = false;
			$row = array();
			for ($q=0; $q<count($list[$i]["list"]); $q++) {
				if (array_search($list[$i]["list"][$q]["action"], $privilege)) {
					$row[] = $list[$i]["list"][$q];
					$check = true;
				}
			}
			if ($check) {
				$list[$i]["list"] = $row;
				$rowset[] = $list[$i];
			}
		}

		return $rowset;
	}

}
