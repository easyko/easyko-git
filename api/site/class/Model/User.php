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

	private $tableProject  = array('name' => 'ek_project_list', 'key' => 'project_id');
	private $tableUser 	   = array('name' => 'ek_user', 'key' => 'user_id');
	private $tableExecUser = array('name' => 'ek_project_exec_users', 'key' => 'execuser_id');


	public function __construct($config=array())
	{
		parent::__construct($config);
	}

	public function getList($userId, $typeList)
	{
		$list = array();

		$sql = "SELECT u.`project_id` as projectId, u.`user_id` AS userId, u.`job_no` as jobNo, 
					u.`is_read` AS isRead, u.`type`, p.project_no AS projectNo, 
					p.project_name AS projectName, p.customer_name AS customerName, 
					p.project_desc AS projectDesc, p.start_date AS startDate
				FROM `{$this->tableExecUser['name']}` as u
				LEFT JOIN `{$this->tableProject['name']}` as p ON u.`project_id` = p.`project_id`
				WHERE u.`user_id`= '{$userId}' AND u.`valid` = '1' 
				AND u.`finished_time` = '0000-00-00 00:00:00'
				ORDER BY u.`time` DESC";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$row['type'] = $typeList[$row['type']];
				$row['projectDesc'] = str_replace("\n", "<br />", $row['projectDesc']);


				$dir = $row['projectNo'] . '/project_info/';
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
				$row['attachment'] = implode(',', $row['attachment']);

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
