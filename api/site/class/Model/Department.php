<?php
/**
 * Job model
 *
 * @desc	部门管理
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2016-03-24 22:59:45
 */

class Model_Department extends Fuse_Model
{
	private $tableDepartment = array('name' => 'ek_department', 'key' => 'department_id');


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

		$sql = "SELECT department_id AS departmentId, 
					department_name AS departmentName, 
					create_time AS createTime
				FROM `{$this->tableDepartment['name']}`
				WHERE {$where}
				ORDER BY `{$this->tableDepartment['key']}` ASC";

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

		$sql = "SELECT COUNT(`{$this->tableDepartment['key']}`) AS count
				FROM `{$this->tableDepartment['name']}`
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
		$sql = "UPDATE `{$this->tableDepartment['name']}`
				SET `valid` = '0'
				WHERE `{$this->tableDepartment['key']}` = '{$id}' 
				AND `company_id` = '{$companyId}'
				AND `valid` = '1'";
		$this->db->query($sql);
	}
	
	public function getTableDepartmentName()
	{
		return $this->tableDepartment['name'];
	}
}
?>
