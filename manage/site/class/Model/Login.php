<?php
class Model_Login extends Fuse_Model
{
	public $table = array('name'=>'ek_admin_user', 'key'=>'user_id');

	public function __construct($options = array())
	{
		parent::__construct($options);
	}

	/**
	 * login check
	 */
	function getLogin($username)
	{
		$list = array();

		$sql = "SELECT user_id userId, group_id groupId, username, login_name loginName, 
					mobile, email, valid, password, rand_str randStr
				FROM `".$this->table['name']."` 
				WHERE `login_name` = ? OR `mobile` = ?";
		$stmt = $this->db->query($sql,array($username, $username));
		if ($row = $stmt->fetch()) {
			$list = $row;
		}

		return $list;
	}
	
	function getLoginById($userId)
	{
		$list = array();

		$sql = "SELECT * FROM `".$this->table['name']."` WHERE `" . $this->table['key'] . "` = ?";
		$stmt = $this->db->query($sql, array($userId));
		if ($row = $stmt->fetch()) {
			$list = $row;
		}

		return $list;
	}
	
	function getTableName()
	{
		return $this->table['name'];
	}
}
?>