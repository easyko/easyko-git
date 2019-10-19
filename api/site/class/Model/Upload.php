<?php
/**
 * Upload model
 *
 * @desc	上传
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-10-20 22:45:45
 */

class Model_Upload extends Fuse_Model
{

	private $projectTypeList = array();
	private $projectStatusList = array();

	private $tableProject = array('name' => 'ek_project', 'key' => 'project_id');
	private $tableUser 	  = array('name' => 'ek_user', 'key' => 'user_id');
	private $tableTask    = array('name' => 'ek_project_task', 'key' => 'task_id');
	private $tableFile    = array('name' => 'ek_project_file', 'key' => 'file_id');

	public function __construct($config=array())
	{
		parent::__construct($config);

	}
	
	public function getTempFileList($copanyId, $projectNo)
	{
		$list = array();
		$sql = "SELECT *
				FROM `{$this->tableFile['name']}` 
				WHERE `status` = 0 AND `valid` = '1' 
				ORDER BY `{$this->tableFile['key']}` ASC";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$list[] = $row;
			}
		}
		
		return $list;
	}
	
	public function moveProjectFile($projectNo)
	{
		
	}
	
	public function getTableProjectName()
	{
		return $this->tableProject['name'];
	}
	
	public function getTableTaskName()
	{
		return $this->tableTask['name'];
	}
	
	public function getTableUserName()
	{
		return $this->tableUser['name'];
	}
	
	public function getTableFileName()
	{
		return $this->tableFile['name'];
	}
}
?>
