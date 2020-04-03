<?php
/**
 * Weekly model
 *
 * @desc	工作周报
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2016-06-13 21:01:45
 */

class Model_Weekly extends Fuse_Model
{

	private $table     = array('name' => 'ek_weekly_report', 'key' => 'weekly_id');
	private $tableUser = array('name' => 'ek_user', 'key' => 'user_id');


	public function __construct($config=array())
	{
		parent::__construct($config);

		$this->userId = Fuse_Cookie::getInstance()->user_id;
		$this->roleId = Fuse_Cookie::getInstance()->role_id;
	}

	public function getList($start=0,$per_page=10,$where=1)
	{
		$list = array();
		$sql = "SELECT w.weekly_id AS weeklyId,w.name AS fileName,
					w.tu.`username` AS managerName,w.time AS date
				FROM `{$this->table['name']}` as w
				LEFT JOIN `{$this->tableUser['name']}` as tu
				ON w.`user_id` = tu.`user_id`
				WHERE {$where}";

		if ($this->roleId == '2') {
			$sql .= " AND w.`user_id` = '{$this->userId}'";
		}

		$sql .= " ORDER BY w.`{$this->table['key']}` DESC";

		if ($start>=0 && !empty($per_page)) {
			$sql .= " LIMIT {$start},{$per_page}";
		}

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
                // 截取
				// $row['name']  = Config_App::getStr($row['name'], 40);

				// 格式化
				$row['date'] = substr($row['date'], 0, 10);

				// $row['name_url'] = '/weekly_report/'. $row['user_id'] . '/' . $row['name'];

                $list[] = $row;
			}
		}

		return $list;
	}

	public function getTotal($where = 1)
	{
		$total = 0;

		$list = array();
		$sql = "SELECT count(*) as total
				FROM `{$this->table['name']}` as w
				LEFT JOIN `{$this->tableUser['name']}` as tu
				ON w.`user_id` = tu.`user_id`
				WHERE {$where}";

		if ($this->roleId == '2') {
			$sql .= " AND w.`user_id` = '{$this->userId}'";
		}

		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$total = $row['total'];
			}
		}

		return $total;
	}

	public function getWeeklyById($weeklyId)
	{
		$list = array();

		$sql = "SELECT * FROM `{$this->table['name']}` WHERE `{$this->table['key']}` = '{$weeklyId}' LIMIT 1";

		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$list = $row;
			}
		}

		return $list;
	}

	public function delWeeklyById($weeklyId)
	{
		$list = array();

		$sql = "DELETE FROM `{$this->table['name']}` WHERE `{$this->table['key']}` = '{$weeklyId}' LIMIT 1";

		return $this->db->query($sql);
	}
}
?>
