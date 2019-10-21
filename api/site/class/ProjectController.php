<?php
/**
 *
 * Controller for Project
 *
 * @desc	项目相关
 * @author	jerry.cao (caowlong163@163.com)
 * $date    2019-07-27 11:27:52
 */

class ProjectController extends CommonController
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

		$this->registerTask('index', 'projectList');
		$this->registerTask('create', 'create');
		$this->registerTask('upload', 'upload');
		$this->registerTask('uploadTask', 'uploadTask');
		$this->registerTask('projectNo', 'projectNo');
		$this->registerTask('detailInfo', 'detailInfo');
		$this->registerTask('detailTask', 'detailTask');
		$this->registerTask('taskNo', 'taskNo');
		$this->registerTask('taskCreate', 'taskCreate');
		$this->registerTask('projectTypeList', 'projectTypeList');

		$this->projectDescLen  = 1000;
		$this->commentLen 	   = 120;
		$this->customerNameLen = 6;
		$this->feedbackLen	   = 1000;
		
		$this->model = $this->createModel('Model_Project', dirname( __FILE__ ));
		$this->modelUpload = $this->createModel('Model_Upload', dirname( __FILE__ ));
		
		// 配置文件
		include_once(dirname(__file__) . '/config.php');
		// 任务类型
		$this->model->setProjectTypeList($projectTypeList);
		// 项目状态
		$this->model->setProjectStatusList($projectStatusList);
		
		$this->tempDirName = '/temp';
		$this->rootDir = Config_App::webdir();
	}

	/**
	 * 获取项目编号
	 */
	public function projectNo() {
		$projectNo = 'M' . date('ym') . Fuse_Tool::getRandStr(8);
		die(json_encode(array('code'=> '0000', 'message' => '获取项目编号成功', 'data' => array('projectNo' => $projectNo))));
	}

	/**
	 * 列表
	 */
	public function projectList()
	{
		$name = Fuse_Request::getFormatVar($this->params, 'name');
		$size = Fuse_Request::getFormatVar($this->params, 'size', '1');
        $page = Fuse_Request::getFormatVar($this->params, 'page', '1');

        if (empty($page)) { $page = 1; }
		$where = " tp.`company_id` = '{$this->companyId}' ";
		$perpage = !empty($size) ? $size : 10;

		if ($name != '') {
			$name = Fuse_Tool::paramsCheck($name);
			$where .= " AND (tp.`project_name` LIKE '%{$name}%'
						OR tp.`customer_name` LIKE '%{$name}%'
						OR tp.`project_no` = '{$name}'
						OR tp.`contract_no` = '{$name}'";

			$row = $this->model->getRow("SELECT `user_id` FROM `{$this->model->getTableUserName()}` WHERE `username`=? AND `company_id`=? AND valid=1", array($this->companyId, urldecode($name)));
			if (isset($row['user_id'])) {
				$where .= " OR tp.`project_manager_id` = '{$row['user_id']}'";
				$where .= " OR FIND_IN_SET('{$row['user_id']}', tp.`task_users`)) ";
			}

			$where .= ") ";
		}

		if (isset($_GET['export'])) {
			$itemList = $this->model->getList(0, 0, $where);
			$this->export($itemList);
		}

		$totalitems = $this->model->getTotal($where);
		$totalPage = ceil($totalitems / $perpage);
		if ($page > $totalPage && $totalPage > 0) $page = $totalPage;

		$paginator = new Fuse_Paginator($totalitems, $page, $perpage, 10);
		$limit     = $paginator->getLimit();
		$itemList  = $this->model->getList($limit['start'], $limit['offset'], $where);

        $itemTitle = array(
			'itemNo'   	   => '序号',
			'projectNo'    => '项目编号',
			'projectName'  => '项目名称',
			'customerName' => '客户'
		);
		$itemTitle['managerName'] = '项目经理';
		$itemTitle['execUsersIds'] = '参与人员';
		$itemTitle['startDate'] = '创建日期';
		$itemTitle['finishDate'] = '完成日期';
		$itemTitle['taskNums'] = '任务数';
		$itemTitle['projectTime'] = '项目时长';
		$itemTitle['workHours'] = '项目工时';
		$itemTitle['status'] = '项目状态';

		if (!empty($itemList)) {
			foreach ($itemList as $key => &$value) {
				$value['itemNo'] = ($page - 1) * $perpage + 1 + $key;
				$value['status'] = $this->model->getProjectStatusById($value['status']);
			}
		}

        $data = array(
			'pageInfo' => array(
				'page'  => $page,
				'total' => $totalPage,
				'size'  => $perpage
			),
			'itemTitle' => $itemTitle,
			'itemList'  => $itemList
        );

        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	
	/**
	 * 创建项目
	 */
	public function create()
	{
		$projectId    = Fuse_Request::getFormatVar($this->params, 'projectNo', 1);
		$projectNo    = Fuse_Request::getFormatVar($this->params, 'projectNo');
		$projectName  = Fuse_Request::getFormatVar($this->params, 'projectName');
		$customerName = Fuse_Request::getFormatVar($this->params, 'customerName');
		$startDate 	  = Fuse_Request::getFormatVar($this->params, 'startDate');
		$endDate 	  = Fuse_Request::getFormatVar($this->params, 'endDate');
		$managerId 	  = Fuse_Request::getFormatVar($this->params, 'managerId', 1);
		$projectDesc  = Fuse_Request::getFormatVar($this->params, 'projectDesc');
		$attachment   = Fuse_Request::getFormatVar($this->params, 'attachment');

		if ($projectName == '' || $customerName == '' || $startDate == '' || $endDate == ''
			|| $managerId == '0' || $projectDesc == '' || $projectNo == '' || $attachment == '') {
			die(json_encode(array('code'=> '1111', 'message' => '请检查表单必填项是否有空', 'data' => '')));
		}

		if (strtotime($startDate) > strtotime($endDate)) {
			die(json_encode(array('code'=> '2222', 'message' => '开始时间应该早于结束时间', 'data' => '')));
		}

		$projectNo = Fuse_Tool::paramsCheck($projectNo);
		$projectName = Fuse_Tool::paramsCheck($projectName);
		$projectDesc = Fuse_Tool::paramsCheck($projectDesc);
		$attachment = Fuse_Tool::paramsCheck($attachment);

		// 判断项目编号是否重复
		$model = new Fuse_Model();
		$info = $model->getRow("SELECT * FROM `{$this->model->getTableProjectName()}`
								WHERE `project_no` = '{$projectNo}' 
								AND `company_id` = '{$this->companyId}'");
		if (!empty($info)) {
			die(json_encode(array('code'=> '3333', 'message' => '项目编号重复', 'data' => '')));
		}

		if ($customerName != '') {
			$customerNameLen = Fuse_Tool::utf8Strlen($customerName);
			if ($customerNameLen > $this->customerNameLen) {
				die(json_encode(array('code'=> '4444', 'message' => '客户名称不能超过' . $this->customerNameLen . '个字符', 'data' => '')));
			}
		}

		if ($projectDesc != '') {
			$projectDescLen = Fuse_Tool::utf8Strlen(strip_tags($projectDesc));
			if ($projectDescLen > $this->projectDescLen) {
				die(json_encode(array('code'=> '5555', 'message' => '项目描述不能超过' . $this->projectDescLen . '个字符', 'data' => '')));
			}
		}

		$time = Config_App::getTime();
		$ip   = Config_App::getIP();
		$object = array(
			'project_no' 			 => $projectNo,
			'project_name'		 	 => $projectName,
			'customer_name'			 => $customerName,
			'start_date' 		 	 => $startDate,
			'finished_date' 		 => $endDate,
			'project_manager_id' 	 => $managerId,
			'attachment'		 	 => $attachment,
			'project_desc' 		 	 => $projectDesc,
			'last_mod_user_id' 	 	 => $this->userId,
			'last_mod_time' 	 	 => $time,
			'ip' 				 	 => $ip,
			'time' 				 	 => $time,
			'valid'			 		 => '1',
			'company_id' 			 => $this->companyId
		);

		// $model = new Fuse_Model();
		if ($projectId && $projectNo) {
			$this->model->update($this->model->getTableProjectName(), $object, " `company_id` = '{$this->companyId}' AND `project_no` = '{$projectNo}' ");
			$returnId = $projectNo;
		} else {
			$returnId = $this->model->store($this->model->getTableProjectName(), $object);
			if (!$returnId) {
				die(json_encode(array('code'=> '6666', 'message' => '创建失败', 'data' => '')));
			}

			// 移动文件到正式目录
			$this->moveFile($projectNo);
		}

		die(json_encode(array('code'=> '0000', 'message' => '创建成功', 'data' => '')));
	}
	
	/**
	 * 移动文件到正式目录
	 */
	private function moveFile($projectNo) 
	{
		$modelUpload = $this->createModel('Model_Upload', dirname( __FILE__ ));
		$tempList = $modelUpload->getTempFileList($this->companyId, $projectNo);
		if (!empty($tempList)) {
			foreach ($tempList as $temp) {
				$attach = $temp['file_url'];

				$pos = strpos($attach, $this->tempDirName);
				$tempFile = substr($attach, $pos + strlen($this->tempDirName));
				$path = $this->rootDir . $this->tempDirName . $tempFile;
				$newPath = $this->rootDir . $tempFile;

				if (!file_exists($path)) {
					continue;
				}

				if (!is_dir(dirname($newPath))) {
					@mkdir(dirname($newPath), 0777, true);
					@chmod(dirname($newPath), 0777);
				}

				if (rename($path, $newPath)) {						
					$obj = array(
						'status' => 1,
						'file_url' => str_replace($this->tempDirName, '', $temp['file_url'])
					);
					$this->model->update($modelUpload->getTableFileName(), $obj, " `company_id` = '{$this->companyId}' AND `file_id` = '{$temp['file_id']}' ");
				}
				
				// 删除空目录
				Fuse_Tool::rmEmptyDir(dirname(dirname(dirname($path))));
			}
		}
	}
	
	/**
	 * 详情-公共信息
	 */
	public function detailInfo()
	{
		$projectNo = Fuse_Request::getFormatVar($this->params, 'projectNo');
		if (empty($projectNo)) {
			die(json_encode(array('code'=> '1111', 'message' => '参数丢失', 'data' => '')));
		}

		// 详情
		$detail = $this->model->getProjectDetail($this->companyId, $projectNo);
		if (empty($detail)) {
			die(json_encode(array('code'=> '2222', 'message' => '项目不存在', 'data' => '')));
		}

		$data = array(
			'projectId'   => $detail['project_id'],
			'projectName' => $detail['project_name'],
			'managerName' => $detail['managerName'], 
			'users' 	  => $detail['taskUsers'], 
			'workHours'   => $detail['workTime'] . '人天', 
			'projectTime' => $detail['projectTime'],
			'statusList'  => $this->model->projectStatusList(),
			'status' 	  => '1'
		);
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	
	/**
	 * 详情-任务列表
	 */
	public function detailTask()
	{
		$projectNo = Fuse_Request::getFormatVar($this->params, 'projectNo');
		if (empty($projectNo)) {
			die(json_encode(array('code'=> '1111', 'message' => '参数丢失', 'data' => '')));
		}
		
		$itemList = $this->model->getTaskList($this->companyId, $projectNo);

		$itemTitle = array(
			'taskFile' => '成果文件',
			'taskNo' => '任务工单',
			'taskDesc' => '任务描述',
			'type' => '任务类型',
			'userName' => '执行人',
			'taskTime' => '计划日期',
			'finishedTime' => '实际完成',
			'workHours' => '工时(人/天)',
			'planScore' => '计划分值',
			'realScore' => '实际分值'
		);
		
		$data = array(
			'itemTitle' => $itemTitle,
			'itemList'  => $itemList
        );

		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}
	
	/**
	 * 获取任务单号
	 */
	public function taskNo()
	{
		$list = $this->model->getTaskNo($this->companyId);
		if (empty($list['no'])) {
			die(json_encode(array('code'=> '1111', 'message' => '生成单号失败，请重新生成', 'data' => '')));
		}
		
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => array('task_no' => $list['no'], 'task_id' => $list['id']))));
	}
	
	/**
	 * 创建任务工单
	 */
	public function taskCreate()
	{
		$projectId = Fuse_Request::getFormatVar($this->params, 'projectId', 1);
		$taskId = Fuse_Request::getFormatVar($this->params, 'taskId', 1);
		$userId = Fuse_Request::getFormatVar($this->params, 'userId', 1);
		$taskNo = Fuse_Request::getFormatVar($this->params, 'taskNo');     
		$taskDesc = Fuse_Request::getFormatVar($this->params, 'taskDesc');
		$taskType = Fuse_Request::getFormatVar($this->params, 'taskType', 1);
		$projectTime = Fuse_Request::getFormatVar($this->params, 'projectTime');
		$planScore = Fuse_Request::getFormatVar($this->params, 'planScore');
		
		if (empty($projectId) || empty($userId) || empty($taskType)
			|| $taskNo == '' || $taskDesc == '' || $projectTime == '' || $planScore == ''
		) {
			die(json_encode(array('code'=> '1111', 'message' => '必填参数为空，请仔细检查', 'data' => '')));
		}
		
		$projectTimeArr = explode('-', str_replace(' ', '', $projectTime)); 
		if (count($projectTimeArr) != 2) {
			die(json_encode(array('code'=> '2222', 'message' => '计划开始和结束时间有误，请仔细检查', 'data' => '')));
		}

		$object = array(
			'project_id' => $projectId,
			'company_id' => $this->companyId,
			'user_id' 	 => $userId,
			'task_desc'  => $taskDesc,
			'task_no' 	 => $taskNo,
			'type' 		 => $taskType,
			'start_time' => str_replace('/', '-', $projectTimeArr['0']),
			'end_time'    => str_replace('/', '-', $projectTimeArr['1']),
			'plan_score' => $planScore,
			'ip' 		 => Config_App::getIP(),
			'time' 		 => Config_App::getTime(),
			'valid'		 => '1',
			'is_read'    => 0
		);
		
		if (strtotime($object['start_time']) > strtotime($object['end_time'])) {
			die(json_encode(array('code'=> '3333', 'message' => '开始时间应该早于结束时间', 'data' => '')));
		}

		$model = new Fuse_Model();
		if (!$model->update($this->model->getTableTaskName(), $object, " `task_id` = '{$taskId}' AND `task_no` = '{$taskNo}' ")) {
			die(json_encode(array('code'=> '4444', 'message' => '新增失败', 'data' => '')));
		}

		die(json_encode(array('code'=> '0000', 'message' => '新增成功', 'data' => '')));
	}
	
	/**
	 * 任务单类型
	 */
	public function projectTypeList()
	{
		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $this->model->getProjectTypeList())));
	}
}
