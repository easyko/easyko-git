<?php
/**
 *
 * Controller for ProjectTask
 *
 * @desc	项目任务单相关
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-10-19 11:27:52
 */

class ProjectTaskController extends CommonController
{
	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		//parent::checkLoginValid();

		$this->registerTask('index', 'taskList');
		$this->registerTask('taskAttachList', 'taskAttachList');
		$this->registerTask('upload', 'upload');

		$this->model = $this->createModel('Model_Project', dirname( __FILE__ ));
		$this->modelUpload = $this->createModel('Model_Upload', dirname( __FILE__ ));
		
		// 配置文件
		include_once(dirname(__file__) . '/config.php');
		// 项目文件模块类型
		$this->modelUpload->setFileUploadTypeList($fileUploadTypeList);
	}

	/**
	 * 项目任务单号列表
	 */
	public function taskList()
	{
		$projectNo = Fuse_Request::getFormatVar($this->params, 'projectNo');
		if (empty($projectNo)) {
			die(json_encode(array('code'=> '1111', 'message' => '参数为空', 'data' => '')));
		}
		
		// 查询当前项目下的除工单文件外的其它文件，如项目文件、合同文件
		$list = $this->modelUpload->getProjectFileList($this->companyId, $projectNo);
		if ($list) {
			foreach ($list as &$val) {
				$val['name'] = $this->modelUpload->getFileNameByType($val['type']);
				$val['params'] = 'projectNo=' . $val['projectNo'] . '&type=' . $val['type'];
				unset($val);
			}
		}
	
		// 查询当前项目下所有任务工单
		$data = $this->model->getProjectTaskInfo($this->companyId, $projectNo);
		if (empty($data)) {
			die(json_encode(array('code'=> '2222', 'message' => '项目不存在', 'data' => '')));
		}
		if ($data) {
			foreach ($data as &$val) {
				$val['params'] = 'projectNo=' . $val['projectNo'] . '&taskNo=' . $val['taskNo'];
				$list[] = $val;
				unset($val);
			}
			unset($data);
		}		
				
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $list)));
	}
	
	/**
	 * 项目任务单号明细
	 */
	public function taskAttachList()
	{
		$projectNo = Fuse_Request::getFormatVar($this->params, 'projectNo');
        $taskNo = Fuse_Request::getFormatVar($this->params, 'taskNo');
        $type = Fuse_Request::getFormatVar($this->params, 'type');

		if (!empty($type)) {
			$this->taskProjectFileListByType($this->companyId, $projectNo, $type);
		}

        if ($projectNo == '' || $taskNo == '') {
			die(json_encode(array('code'=> '1111', 'message' => '参数为空', 'data' => '')));
		}

		$list = $this->modelUpload->getTaskFileList($this->companyId, $projectNo, $taskNo);
		if (empty($list)) {
			die(json_encode(array('code'=> '2222', 'message' => '项目不存在', 'data' => '')));
		}

        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $list)));
	}

	/**
	 * 查询除项目单文件以外的其它文件，如项目资料
	 * 
	 * projectFile|projectContractFile|projectProposalFile|projectMeetingNoteFile|projectTaskFile
 	 * 项目资料|合同|项目提案资料|会议纪要|项目任务单资料
	 */ 
	private function taskProjectFileListByType($companyId, $projectNo, $type)
	{
		if (empty($projectNo) || empty($companyId)) {
			die(json_encode(array('code'=> '1111', 'message' => '参数为空', 'data' => '')));
		}
		
		$list = $this->modelUpload->getTaskFileList($this->companyId, $projectNo, '', intval($type));
		if (empty($list)) {
			die(json_encode(array('code'=> '2222', 'message' => '项目不存在', 'data' => '')));
		}

        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $list)));
	}
}
