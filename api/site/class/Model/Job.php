<?php
/**
 * Job model
 *
 * @desc	职位管理
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2016-03-24 22:59:45
 */

class Model_Job extends Fuse_Model
{
	private $tableJob = array('name' => 'ek_job', 'key' => 'job_id');


	public function __construct($config=array())
	{
		parent::__construct($config);
	}
	
	/**
	 * 获取列表
	 */
	public function getList($start=0, $perPage=10, $where=1)
	{
		$list = array();

		$sql = "SELECT job_id AS jobId, job_name AS jobName, create_time AS createTime
				FROM `{$this->tableJob['name']}`
				WHERE {$where}
				ORDER BY `{$this->tableJob['key']}` ASC";
				
		if ($start>=0 && !empty($perPage)) {
			$sql .= " LIMIT {$start},{$perPage}";
		}

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$list[] = $row;
			}
		}

		return $list;
	}
	
	/**
	 * 获取总记录数
	 */
	public function getTotal($where)
	{
		$info = 0;

		$sql = "SELECT COUNT(`{$this->tableJob['key']}`) AS count
				FROM `{$this->tableJob['name']}`
				WHERE {$where}";
		if ($stmt = $this->db->query($sql)) {
			if ($row = $stmt->fetch()) {
				$info = $row['count'];
			}
		}

		return $info;
	}
	
	/**
	 * 删除
	 */
	public function delete($companyId, $id)
	{
		$sql = "UPDATE `{$this->tableJob['name']}`
				SET `valid` = '0'
				WHERE `{$this->tableJob['key']}` = '{$id}' 
				AND `company_id` = '{$companyId}'
				AND `valid` = '1'";
		$this->db->query($sql);
	}
	
	public function getTableJobName()
	{
		return $this->tableJob['name'];
	}
}
?>
