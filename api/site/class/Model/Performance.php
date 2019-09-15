<?php
/**
 * Weekly model
 *
 * @desc	绩效考核
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2016-06-18 16:18:51
 */

class Model_Performance extends Fuse_Model
{
	private $tableProject  = array('name' => 'ek_project_list', 'key' => 'project_id');
	private $tableUser 	   = array('name' => 'ek_user', 'key' => 'user_id');
	private $tableExecUser = array('name' => 'ek_project_exec_users', 'key' => 'execuser_id');


	public function __construct($config=array())
	{
		parent::__construct($config);

		$this->userId = Fuse_Cookie::getInstance()->user_id;
		$this->roleId = Fuse_Cookie::getInstance()->role_id;
	}

	public function getList($start=0, $per_page=10, $where=1, $statList=array(), $total=false, $excel=false)
	{
		$list = array();

		$sql = "SELECT pl.project_no AS projectNo, pl.project_name AS projectName, ul.username,
					pe.job_no AS jobNo, pe.type, pe.start_time AS startTime, pe.end_time AS endTime,
					pe.work_unit AS workUnit, pe.plan_score AS planScore,pe.real_score AS realScore,
					pe.attachment, pe.`finished_time` AS finishedTime
				FROM `{$this->tableProject['name']}` pl
				LEFT JOIN `{$this->tableExecUser['name']}` pe on pl.`project_id` = pe.`project_id`
				LEFT JOIN `{$this->tableUser['name']}` ul on pe.`user_id` = ul.`user_id`
				WHERE {$where} AND pe.`valid` = '1'
				ORDER BY pl.`project_id` DESC,ul.`user_id` DESC";

		if ($start>=0 && !empty($per_page)) {
			$sql .= " LIMIT {$start},{$per_page}";
		}

		$totalList = array(
			'work_unit'  => 0,
			'plan_score' => 0,
			'real_score' => 0
		);

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
                // 截取
				// $row['name']  = Config_App::getStr($row['name'], 40);

				// 格式化
				$row['startTime'] 	  = substr($row['startTime'], 0, 10);
				$row['endTime'] 	  = substr($row['endTime'], 0, 10);
				$row['finishedTime'] = substr($row['finishedTime'], 0, 10);
				$row['finishedTime'] = $row['finishedTime'] == '0000-00-00' ? '' : $row['finishedTime'];

				$row['type'] = $statList[$row['type']];

                $list[] = $row;

                $totalList['workUnit']  += $row['workUnit'] * 1;
				$totalList['planScore'] += $row['planScore'] * 1;
				$totalList['realScore'] += $row['realScore'] * 1;
			}
		}

		if ($total) {
			return $totalList;
		}

		if ($excel) {
			return array(
				'list'  => $list,
				'total' => $totalList
			);
		}

		$newList = array();
		if (!empty($list)) {
			foreach ($list as $key => $value) {
				if (!isset($newList[$value['projectNo']])) {
					$newList[$value['projectNo']] = array(
						'username'	  => $value['username'],
						'projectNo'   => $value['projectNo'],
						'projectName' => $value['projectName'],
					);
				}

				$attachment = array();
				if (!empty($value['attachment'])) {
					foreach (unserialize($value['attachment']) as $k => $v) {
						$attachment[] = $value['projectNo'] . '/' . $value['job_no'] . '/' . $v;
					}
				}
				$attachment = implode(',', $attachment);

				$newList[$value['projectNo']]['list'][] = array(
					'jobNo' 	   => $value['jobNo'],
					'type'   	   => $value['type'],
					'startTime'    => $value['startTime'],
					'endTime' 	   => $value['endTime'],
					'finishedTime' => $value['finishedTime'],
					'workUnit' 	   => $value['workUnit'],
					'planScore'    => $value['planScore'],
					'realScore'    => $value['realScore'],
					'attachment'   => $attachment
				);
			}
		}

		return $newList;
	}

	public function getTotal($where = 1)
	{
		$total = 0;

		$list = array();
		$sql = "SELECT count(DISTINCT pl.project_no) as total FROM `{$this->tableProject['name']}` pl
				LEFT JOIN `{$this->tableExecUser['name']}` pe on pl.`project_id` = pe.`project_id`
				LEFT JOIN `{$this->tableUser['name']}` ul on pe.`user_id` = ul.`user_id`
				WHERE {$where} AND pe.`valid` = '1'";

		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$total = $row['total'];
			}
		}

		return $total;
	}

	public function getRowByJobNo($jobNo)
	{
		$list = array();

		$sql = "SELECT * FROM `{$this->tableExecUser['name']}` WHERE `job_no` = '{$jobNo}' LIMIT 1";

		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$list = $row;
			}
		}

		return $list;
	}
}
?>
