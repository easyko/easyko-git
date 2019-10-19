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
	}

	/**
	 * 项目任务单号列表
	 */
	public function taskList()
	{
		$projectId = Fuse_Request::getFormatVar($this->params, 'projectId', 1);
		if (empty($projectId)) {
			die(json_encode(array('code'=> '1111', 'message' => '参数为空', 'data' => '')));
		}
		
		$data = $this->model->getProjectTaskInfo($this->companyId, $projectId);
		if (empty($data)) {
			die(json_encode(array('code'=> '2222', 'message' => '项目不存在', 'data' => '')));
		}
		
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	
	/**
	 * 项目任务单号明细
	 */
	public function taskAttachList()
	{
		$projectId = Fuse_Request::getFormatVar($this->params, 'projectId', 1);
        $taskNo = Fuse_Request::getFormatVar($this->params, 'taskNo');

        if (empty($projectId) || $taskNo == '') {
			die(json_encode(array('code'=> '1111', 'message' => '参数为空', 'data' => '')));
		}

		$detail = $this->model->getProjectTaskInfo($this->companyId, $projectId, $taskNo);
		if (empty($detail)) {
			die(json_encode(array('code'=> '2222', 'message' => '项目不存在', 'data' => '')));
		}

		$attachment = (isset($detail[0]['attachment']) && !empty($detail[0]['attachment'])) ? $detail[0]['attachment'] : array();

		/*$taskDataList = array();
		$taskDir = Config_App::rootdir() . '/temp/' . $projectNo . '/';
		if (count($detail['taskUsersList']) > 0) {
			foreach ($detail['taskUsersList'] as $task) {
				$taskDir .= $task['task_no'] . '/';
				if (!is_dir($taskDir)) {
					@mkdir($taskDir, 0777, true);
					@chmod($taskDir, 0777);
				}

				if (Fuse_Tool::isWinOs()) {
					$taskDir = iconv('UTF-8', 'GB2312', $taskDir);
				}

				$taskDataList[$task['task_no']] = Fuse_Tool::getDir($taskDir);
			}
		}*/

        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $attachment)));
	}

	
}
