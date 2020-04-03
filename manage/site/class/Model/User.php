<?php
/**
 * User model
 *
 * @desc	用户
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-08-15 00:26:19
 */

class Model_User extends Fuse_Model
{
	private $tableUser = array('name' => 'ek_admin_user', 'key' => 'user_id');
	private $tableGroup = array('name' => 'ek_admin_group', 'key' => 'group_id');
	private $tableMenu = array('name' => 'ek_admin_menu', 'key' => 'menu_id');

	public function __construct($config=array())
	{
		parent::__construct($config);
	}

	/**
	 * Check catalog privilege
	 */
	function checkPrivilege($action, $userId){
		$sql = "SELECT `group_id` groupId FROM `{$this->tableUser['name']}` WHERE `{$this->tableUser['key']}` = ?";
		$row = $this->getRow($sql, array($userId));

		$privilege = $this->getPrivilege($row["groupId"]);
		return in_array($action, $privilege);
	}

	/**
	 * Get user privileges
	 * return string
	 */
	function getPrivilege($groupId = 0)
	{
		$result = array();
		$sql = "SELECT * FROM `{$this->tableGroup['name']}` WHERE `{$this->tableGroup['key']}` = ?";
		$row = $this->getRow($sql, array($groupId));
		if (!empty($row["privilege"])) {
			$result = unserialize($row["privilege"]);
		}

		return $result;
	}

	function getNaviList()
	{
		$sql = "SELECT *, menu_id menuId FROM `{$this->tableMenu['name']}` WHERE `parent_menu_id`='0' AND `valid`='1' ORDER BY `sort_id` ASC";
		$list = array();
		$navilist = $this->getRowSet($sql);
		for ($i=0; $i<count($navilist); $i++) {
			$sql = "SELECT * FROM `{$this->tableMenu['name']}` WHERE `parent_menu_id`='{$navilist[$i]['menuId']}' AND `valid`='1' ORDER BY `sort_id` ASC";
			$navilist[$i]["list"] = $this->getRowSet($sql);
		}

		return $navilist;
	}

	public function getTableUserName()
	{
		return $this->tableUser['name'];
	}
	
	public function getTableUserKey()
	{
		return $this->tableUser['key'];
	}
}
?>
