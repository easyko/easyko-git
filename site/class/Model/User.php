<?php
/**
 * User model
 *
 * @desc	执行人员
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2016-03-30 22:59:45
 */

class Model_User extends Fuse_Model
{

	private $tableProject  = array('name' => 'project_list', 'key' => 'project_id');
	private $tableUser 	   = array('name' => 'user_list', 'key' => 'user_id');
	private $tableExecUser = array('name' => 'project_exec_users', 'key' => 'execuser_id');


	public function __construct($config=array())
	{
		parent::__construct($config);
	}

	public function getList($userId, $typeList)
	{
		$list = array();

		$sql = "SELECT u.`project_id`, u.`user_id`, u.`job_no`, u.`is_read`, u.`type`, p.*
				FROM `{$this->tableExecUser['name']}` as u
				LEFT JOIN `{$this->tableProject['name']}` as p ON u.`project_id` = p.`project_id`
				WHERE u.`user_id`= '{$userId}' AND u.`finished_time` = '0000-00-00 00:00:00' AND u.`is_used` = '1' ORDER BY u.`time` DESC";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$row['type'] = $typeList[$row['type']];
				$row['project_desc'] = str_replace("\n", "<br />", $row['project_desc']);


				$dir = $row['project_no'] . '/project_info/';
				$attachment = unserialize($row['attachment']);
				if (!empty($attachment)) {
					$newList = array();
					foreach ($attachment as $v) {
						$newList[] = array(
							'name' => $v,
							'url' => $dir . $v
						);
					}
					unset($row['attachment']);
					$row['attachment'] = $newList;
				} else {
					$row['attachment'] = array();
				}

				$list[] = $row;
			}
		}

		return $list;
	}

	public function setReadByJobNo($jobNo, $userId)
	{
		$sql = "UPDATE `{$this->tableExecUser['name']}`
				SET `is_read` = '1'
				WHERE `job_no` = '{$jobNo}' AND `user_id` = '{$userId}'";

		return $this->db->query($sql);
	}
}
?>
