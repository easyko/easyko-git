<?php
class Model_Login extends Fuse_Model
{
	public $table = array('name'=>'ek_user', 'key'=>'user_id');

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

		$sql = "SELECT user_id userId, company_id companyId, user_no userNo, username,
					login_name loginName, mobile, password, rand_str randStr, email,
					role_id roleId, department_id departmentId, job_id jobId, valid
				FROM `".$this->table['name']."` WHERE `login_name` = ? OR `mobile` = ?";
		$stmt = $this->db->query($sql,array($username, $username));
		if ($row = $stmt->fetch()) {
			$list = $row;
		}

		return $list;
	}
	
	function getLoginById($userId)
	{
		$list = array();

		$sql = "SELECT user_id userId, company_id companyId, user_no userNo, username,
					login_name loginName, mobile, password, rand_str randStr, email,
					role_id roleId, department_id departmentId, job_id jobId, valid
				FROM `".$this->table['name']."` WHERE `" . $this->table['key'] . "` = ?";
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