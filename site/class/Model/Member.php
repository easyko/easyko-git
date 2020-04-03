<?php
/**
 * Member model
 *
 * @desc	人员管理
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2016-03-24 22:59:45
 */

class Model_Member extends Fuse_Model
{

	private $tableProject  = array('name' => 'project_list', 'key' => 'project_id');
	private $tableUser 	   = array('name' => 'user_list', 'key' => 'user_id');
	private $tableExecUser = array('name' => 'project_exec_users', 'key' => 'execuser_id');


	public function __construct($config=array())
	{
		parent::__construct($config);
	}

	public function getMemberList($roleId = 1)
	{
		$list = array();

		$sql = "SELECT * FROM `{$this->tableUser['name']}`
				WHERE `role_id` = '{$roleId}' AND `is_used` = '1'
				ORDER BY `{$this->tableUser['key']}` ASC";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$list[] = $row;
			}
		}

		return $list;
	}

	public function getInfo($userId)
	{
		$list = array();

		$sql = "SELECT * FROM `{$this->tableUser['name']}` WHERE `user_id` = '{$userId}' LIMIT 1";

		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$list = $row;
			}
		}

		return $list;
	}

	public function delUser($ids)
	{
		$sql = "DELETE FROM `{$this->tableUser['name']}` WHERE `user_id` IN ({$ids})";

		return $stmt = $this->db->query($sql);
	}

	public function checkUnique($field, $value, $myId)
	{
		$list = array();

		$sql = "SELECT `user_id`,`username` FROM `{$this->tableUser['name']}`
				WHERE `user_id` != '{$myId}' AND `{$field}` = '{$value}' AND `is_used` = '1' LIMIT 1";

		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$list = $row;
			}
		}

		if (!empty($list)) {
			return false;
		}

		return true;
	}

	/**
	 * 项目状态 1:进行中 2:已完成 3:已取消
	 *
	 */
	public function projectStat($stat)
	{
		if (empty($stat)) return '';

		switch ($stat) {
			case '1':
				$statStr = '进行中';
				break;
			case '2':
				$statStr = '已完成';
				break;
			case '3':
				$statStr = '已取消';
				break;
		}

		return $statStr;
	}

	public function formatMoney($money)
	{
		return number_format($money, 2, '.', ',');
	}
}
?>
