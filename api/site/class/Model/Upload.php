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
	// 项目文件模块类型
	private $fileUploadTypeList = array();
	// 文件上传临时目录
	private $uploadTempDirName = '';

	private $tableProject = array('name' => 'ek_project', 'key' => 'project_id');
	private $tableUser 	  = array('name' => 'ek_user', 'key' => 'user_id');
	private $tableTask    = array('name' => 'ek_project_task', 'key' => 'task_id');
	private $tableFile    = array('name' => 'ek_project_file', 'key' => 'file_id');

	public function __construct($config=array())
	{
		parent::__construct($config);

	}
	
	/**
	 * 获取项目下的临时列表
	 */
	public function getTempFileList($companyId, $projectNo)
	{
		$list = array();
		$sql = "SELECT *
				FROM `{$this->tableFile['name']}` 
				WHERE `company_id` = '{$companyId}' AND `status` = 0 AND `valid` = 1
					AND `project_no` = '{$projectNo}'
				ORDER BY `{$this->tableFile['key']}` ASC";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$list[] = $row;
			}
		}
		
		return $list;
	}

	/**
	 * 获取项目下的文件列表
	 */
	public function getTaskFileList($companyId, $projectNo, $taskNo = '', $type = '')
	{
		$list = array();
		$sql = "SELECT file_id AS fielId, filename, file_url AS fileUrl, file_size AS fileSize
				FROM `{$this->tableFile['name']}` 
				WHERE `company_id` = '{$companyId}' 
					AND `status` = 1 AND `valid` = 1
					AND `project_no` = '{$projectNo}'";
					
		if ($taskNo != '') {
			$sql .= " AND `task_no` = '{$taskNo}'";
		}
		if ($type != '') {
			$sql .= " AND `type` = '{$type}'";
		}
			
		$sql .= " ORDER BY `{$this->tableFile['key']}` ASC";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$list[] = $row;
			}
		}
		
		return $list;
	}
	
	public function getProjectFileList($companyId, $projectNo)
	{
		$list = array();
		$sql = "SELECT project_no AS projectNo, type
				FROM `{$this->tableFile['name']}` 
				WHERE `company_id` = '{$companyId}' 
					AND `status` = 1 AND `valid` = 1 AND `type` != 5
					AND `project_no` = '{$projectNo}'
				GROUP BY `type`
				ORDER BY `{$this->tableFile['key']}` ASC";

		if ($stmt = $this->db->query($sql)) {
			while ($row = $stmt->fetch()) {
				$list[] = $row;
			}
		}
		
		return $list;
	}
	
	public function getFileNameByType($type)
	{
		$name = 0;
		foreach ($this->fileUploadTypeList as $val) {
			if ($val['id'] == $type) {
				$name = $val['name'];
				break;
			}
		}

		return $name;
	}
	
	public function getFileTypeByName($name)
	{
		$type = 0;
		foreach ($this->fileUploadTypeList as $val) {
			if ($val['key'] == $name) {
				$type = $val['id'];
				break;
			}
		}

		return $type;
	}
	
	public function checkFileUploadTypeValid($name)
	{
		if ($name == '') {
			return false;
		}
		
		$valid = false;
		foreach ($this->fileUploadTypeList as $val) {
			if ($val['key'] == $name) {
				$valid = true;
				break;
			}
		}
		
		return $valid;
	}
	
	public function setFileUploadTypeList($fileUploadTypeList)
	{
		$this->fileUploadTypeList = $fileUploadTypeList;
	}

    public function setUploadTempDirName($uploadTempDirName)
    {
        $this->uploadTempDirName = $uploadTempDirName;
    }

    public function getUploadTempDirName()
    {
        return $this->uploadTempDirName;
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
