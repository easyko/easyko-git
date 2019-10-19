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
	private $locationRegular = '/^(SH|GZ|LD)\d{7}$/';

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
		$this->registerTask('projectNo', 'projectNo');
		$this->registerTask('detailInfo', 'detailInfo');
		$this->registerTask('detailTask', 'detailTask');
		$this->registerTask('taskNo', 'taskNo');
		$this->registerTask('taskCreate', 'taskCreate');

		// 配置文件
		include_once(dirname(__file__) . '/config.php');

		// 项目区域
		$this->locationList = $locationList;

		// 项目状态
		$this->projectStatList = $projectStatList;

		// 任务类型
		$this->projectTypeList = $typeList;

		$this->fileUploadList = $fileUploadList;

		$this->projectDescLen  = 1000;
		$this->commentLen 	   = 120;
		$this->customerNameLen = 6;
		$this->feedbackLen	   = 1000;
		
		$this->model = $this->createModel('Model_Project', dirname( __FILE__ ));
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
			$itemList = $this->model->getList(0, 0, $where, $this->projectStatList);
			$this->export($itemList);
		}

		$totalitems = $this->model->getTotal($where);
		$totalPage = ceil($totalitems / $perpage);
		if ($page > $totalPage && $totalPage > 0) $page = $totalPage;

		$paginator = new Fuse_Paginator($totalitems, $page, $perpage, 10);
		$limit     = $paginator->getLimit();
		$itemList  = $this->model->getList($limit['start'], $limit['offset'], $where, $this->projectStatList);

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
		$managerId 	  = Fuse_Request::getFormatVar($this->params, 'managerId', '1');
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

		$projectFileList = explode(',', Fuse_Request::getFormatVar($this->params, 'projectFile'));
		if (empty($projectFileList)) {
			die(json_encode(array('code'=> '6666', 'message' => '请上传项目资料', 'data' => '')));
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

		$model = new Fuse_Model();
		if ($projectId && $projectNo) {
			$model->update($this->model->getTableProjectName(), $object, " `project_no` = '{$projectNo}' ");
			$returnId = $projectNo;
		} else {
			$returnId = $model->store($this->model->getTableProjectName(), $object);
			if (!$returnId) {
				die(json_encode(array('code'=> '7777', 'message' => '创建失败', 'data' => '')));
			}
		}

		die(json_encode(array('code'=> '0000', 'message' => '创建成功', 'data' => '')));
	}

	/**
	 * 上传文件
	 */
	public function upload()
	{
		$projectNo = Fuse_Request::getFormatVar($this->params, 'no');
		if (empty($projectNo)) {
			die(json_encode(array('code'=> '1111', 'message' => '请先填写项目编号', 'data' => '')));
		}

		$name = Fuse_Request::getFormatVar($this->params, 'name');
		if (!in_array($name, $this->fileUploadList)) {
			die(json_encode(array('code'=> '2222', 'message' => '上传失败，参数错误', 'data' => '')));
		}

		// 判断项目编号是否重复
		$model = new Fuse_Model();
		$info = $model->getRow("SELECT * FROM `{$this->model->getTableProjectName()}`
								WHERE `project_no` = '{$projectNo}' 
								AND `company_id` = '{$this->companyId}'");
		if (!empty($info)) {
			die(json_encode(array('code'=> '3333', 'message' => '项目编号重复，请刷新页面重试', 'data' => '')));
		}

		$rootDir = Config_App::rootdir();
		$filePrefixDir = '/temp/projectUpoadFile/' . $projectNo . '/';
		$saveDir = $rootDir . $filePrefixDir;

		$fileKey = '';
		if ($name == 'projectFile') { // 项目资料
			$fileDir = 'project_info/';
			$fileKey = $info['attachment'];
		} else if ($name == 'projectContractFile') { // 合同
			$fileDir = 'contract_info/';
			$fileKey = $info['contract_attachment'];
		} else if ($name == 'projectProposalFile') { // 项目提案资料
			$fileDir = 'proposal_info/';
			$fileKey = $info['proposal_attachment'];
		} else if ($name == 'projectMeetingNoteFile') { // 会议纪要
			$fileDir = 'meetingnote_info/';
			$fileKey = $info['meetingnote_attachment'];
		}

		$saveDir .= $fileDir;
		if (!is_dir($saveDir)) {
			@mkdir($saveDir, 0777, true);
			@chmod($saveDir, 0777);
		}

		require_once dirname(__FILE__) . '/upload/handler.php';
		$uploader = new UploadHandler();

		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == 'POST' && !isset($_POST['uuid'])) {
			$uploader->sizeLimit = 1 * (1024 * 1024);
			$uploader->inputName = 'qqfile';
			$uploader->notAllowedExtensions = array('exe', 'js', 'css');
			$result = $uploader->handleUpload($saveDir);
			if (isset($result['error'])) {
				die(json_encode(array('code'=> '1111', 'message' => $result['error'])));
			}
			
			$result['code'] = '0000';
			$result['uploadName'] = $uploader->getUploadName();
			@chmod($saveDir . '/' . $result['uploadName'], 0777);
			$result['uploadFile'] = Config_App::homeurl() . '/api' . $filePrefixDir . $fileDir . $uploader->getUploadName();
			echo json_encode($result);
		} else if (isset($_POST['uuid'])) {
			$file = Fuse_Request::getFormatVar($this->params, 'uuid');
			$file = Fuse_Tool::strToUtf8($file);
			if (strpos($saveDir, '.') !== false || strpos($saveDir, '..') !== false) {
				die(json_encode(array('code'=> '2222', 'message' => '文件非法', 'uuid' => $file)));
			}

			// 判断越权
			$keyExists = false;
			if ($fileKey != '') {
				$keyList = explode(',', $fileKey);
				foreach ($keyList as $key) {
					$key = Fuse_Tool::strToUtf8($key);
					if (strpos($key, $file) == FALSE) {
						$keyExists = true;
						break;
					}
				}
				
				if (!$keyExists) {
					die(json_encode(array('code'=> '3333', 'message' => '文件不存在')));
				}	
			}
			
			$file = str_replace('/', '', $file);
			$file1 = iconv('UTF-8', 'GBK', $file);
			$delDir = $saveDir . $file1;
			$result = $uploader->handleDelete($delDir);
			if (isset($result['error'])) {
				die(json_encode(array('code'=> '4444', 'message' => '删除失败')));
			}
			$result['code'] = '0000';
			echo json_encode($result);
		} else {
			header('HTTP/1.0 405 Method Not Allowed');
		}
		exit();
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
		$detail = $this->model->getProjectDetail($this->companyId, $projectNo, $this->projectTypeList);
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
			'statusList'  => $this->projectStatList,
			'typeList' 	  => $this->projectTypeList,
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
		
		$itemList = $this->model->getTaskList($this->companyId, $projectNo, $this->projectTypeList);

		$itemTitle = array(
			'taskFile' => '',
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
		$startTime = Fuse_Request::getFormatVar($this->params, 'startTime');
		$endTime = Fuse_Request::getFormatVar($this->params, 'endTime');
		$planScore = Fuse_Request::getFormatVar($this->params, 'planScore');
		
		if (empty($projectId) || empty($userId) || empty($taskType)
			|| $taskNo == '' || $taskDesc == '' || $startTime == ''
			|| $endTime == '' || $planScore == ''
		) {
			die(json_encode(array('code'=> '1111', 'message' => '必填参数为空，请仔细检查', 'data' => '')));
		}
		
		$object = array(
			'project_id' => $projectId,
			'company_id' => $this->companyId,
			'user_id' 	 => $userId,
			'task_desc'  => $taskDesc,
			'task_no' 	 => $taskNo,
			'type' 		 => $taskType,
			'start_time' => $startTime,
			'endTime'    => $endTime,
			'plan_score' => $planScore,
			'ip' 		 => $ip,
			'time' 		 => $time,
			'valid'		 => '1',
			'is_read'    => 0
		);
		
		$model = new Fuse_Model();
		$model->update($this->model->getTableTaskName(), $object, " `task_id` = '{$taskId}' AND `task_no` = '{$taskNo}' ");

		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => '')));
	}
}
