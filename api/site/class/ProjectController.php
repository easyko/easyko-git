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
		$this->registerTask('login', 'login');
		$this->registerTask('logout', 'logout');
		$this->registerTask('export', 'export');
		$this->registerTask('create', 'create');
		$this->registerTask('insert', 'insert');
		$this->registerTask('update', 'update');
		$this->registerTask('detail', 'detail');
		$this->registerTask('upload', 'upload');
		$this->registerTask('get_projectno', 'getProjectNo');
		$this->registerTask('get_userno', 'getUserNo');
		$this->registerTask('del_user', 'delUser');
		$this->registerTask('del_feedback', 'delFeedback');

		// 配置文件
		include_once(dirname(__file__) . '/config.php');

		// 项目区域
		$this->locationList = $locationList;

		// 项目状态
		$this->projectStatList = $projectStatList;

		// 任务类型
		$this->typeList = $typeList;

		$this->fileUploadList = $fileUploadList;

		$this->projectDescLen  = 1000;
		$this->commentLen 	   = 120;
		$this->customerNameLen = 6;
		$this->feedbackLen	   = 1000;
		
		$this->model = $this->createModel('Model_Project', dirname( __FILE__ ));
	}

	/**
	 * 列表
	 */
	public function projectList()
	{
		$type = Fuse_Request::getFormatVar($this->params, 'type', '1');
		$name = Fuse_Request::getFormatVar($this->params, 'name');
		$projectName = Fuse_Request::getFormatVar($this->params, 'projectName');
		$managerId = Fuse_Request::getFormatVar($this->params, 'managerId', '1');
		$userId = Fuse_Request::getFormatVar($this->params, 'userId', '1');
		$statusId = Fuse_Request::getFormatVar($this->params, 'statusId', '1');
		$size = Fuse_Request::getFormatVar($this->params, 'size', '1');

        $page = Fuse_Request::getFormatVar($this->params, 'page', '1');
        if (empty($page)) { $page = 1; }
		$where = ' 1 ';
		$perpage = !empty($size) ? $size : 10;

		$modelMember = $this->createModel('Model_Member', dirname( __FILE__ ));
		
		// 项目经理
		$managerList = array(); // $modelMember->getMemberList(2);
		
		// 执行人员
		$userList = array(); // $modelMember->getMemberList(3);



		// 模糊搜索
		if ($type == 1) {
			if ($name != '') {
				$where .= " AND (tp.`project_name` LIKE '%{$name}%'
							OR tp.`customer_name` LIKE '%{$name}%'
							OR tp.`project_no` = '{$name}'
							OR tp.`contract_no` = '{$name}'";

				$row = $this->model->getRow('SELECT `user_id` FROM `user_list` WHERE `username`=? AND `role_id`=?', array(urldecode($name), '2'));
				if (isset($row['user_id'])) {
					$where .= " OR tp.`project_manager_id` = '{$row['user_id']}'";
				}

				$row = $this->model->getRow('SELECT `user_id` FROM `user_list` WHERE `username`=? AND `role_id`=?', array(urldecode($name), '3'));
				if (isset($row['user_id'])) {
					$where .= " OR FIND_IN_SET('{$row['user_id']}', tp.`exec_users_ids`)) ";
				} else {
					$where .= ") ";
				}

				$statList = array();
				foreach ($this->projectStatList as $k => $v) {
					$statList[$k] = $v['statusName'];
				}
				if (in_array($name, $statList)) {
					$statusK = '';
					foreach ($this->projectStatList as $k => $v) {
						if ($v['statusName'] == $name) {
							$statusK = $k;
							break;
						}
					}

					!empty($statusK) && $where .= " OR tp.`status` = '{$statusK}' ";
				}
			}
		}
		// 精确搜索
		else {
			if ($projectName != '') {
				$where .= " AND tp.`project_name` LIKE '%{$projectName}%'";
			}

			if ($managerId != '') {
				$where .= " AND tp.`project_manager_id` = '{$managerId}'";
			}

			if ($userId != '') {
				$where .= " AND FIND_IN_SET('{$userId}', tp.`exec_users_ids`) ";
			}

			if ($statusId != '') {
				$where .= " AND tp.`status` = '{$statusId}' ";
			}
		}

		$statList = array();
		foreach ($this->projectStatList as $k => $v) {
			$statList[$k] = $v['statusName'];
		}

		if (isset($_GET['export'])) {
			$itemList = $this->model->getList(0, 0, $where, $statList);
			$this->export($itemList);
		}

		$totalitems = $this->model->getTotal($where);
		$totalPage = ceil($totalitems / $perpage);
		if ($page > $totalPage && $totalPage > 0) $page = $totalPage;

		$paginator = new Fuse_Paginator($totalitems, $page, $perpage, 10);
		$limit     = $paginator->getLimit();
		$itemList  = $this->model->getList($limit['start'], $limit['offset'], $where, $statList);

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
		/*if ($this->roleId == 0 || $this->roleId == 2) {
			$itemTitle['contractNo'] 	 = '合同号';
			$itemTitle['contractAmount'] = '合同金额';
			$itemTitle['realAmount'] 	 = '实际收款';
			$itemTitle['otherAmount'] 	 = '第三方费用';
		}*/
		

		foreach ($itemList as $key => &$value) {
			/*if (!($this->roleId == 0 || $this->roleId == 1)) {
				unset($value['managerName']);
			}
			if (!($this->roleId == 0 || $this->roleId == 2)) {
				unset($value['contractNo']);
				unset($value['contractAmount']);
				unset($value['realAmount']);
				unset($value['otherAmount']);
			}*/
		}

        $data = array(
			'pageInfo' => array(
				'page'  => $page,
				'total' => $totalPage,
				'size'  => $perpage
			),
			'itemTitle' 	  => $itemTitle,
			'itemList' 		  => $itemList,
			'managerList' 	  => $managerList, 			// 项目经理
			'userList' 		  => $userList, 			// 执行人员
			'projectStatList' => $this->projectStatList // 项目状态
        );
        
        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}

	public function export($itemList)
	{

		ini_set('memory_limit', '500M');
		set_time_limit(0);

		include_once('Excel/PHPExcel.php');

		$objPHPExcel = new PHPExcel();

		// 设置列名称
		if ($this->roleId == '1') {
			$objPHPExcel->getActiveSheet()->setCellValue('A1', '项目编号');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '项目名称');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '客户名称');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '开始日期');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '项目经理');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '执行人员');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '项目状态');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '合同号');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '合同金额');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '实际收款');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', '第三方费用');
		} else {
			$objPHPExcel->getActiveSheet()->setCellValue('A1', '项目编号');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '项目名称');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '客户名称');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '开始日期');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '执行人员');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '项目状态');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '合同号');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '合同金额');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '实际收款');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '第三方费用');
		}

		// 加粗
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);
		if ($this->roleId == '1') {
			$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);
		}

		$objPHPExcel->getActiveSheet()->setTitle('项目列表');
		$objPHPExcel->setActiveSheetIndex(0);

		foreach ($itemList as $key => $info) {
			$row=$key+2;
			if ($this->roleId == '1') {
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $info['project_no']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $info['project_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $info['customer_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $info['start_date']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $info['manager_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $info['exce_users']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $info['stat']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $info['contract_no']);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $info['contract_amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$row, $info['real_amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$row, $info['other_amount']);
			} else {
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $info['project_no']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $info['project_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $info['customer_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $info['start_date']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $info['exce_users']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $info['stat']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $info['contract_no']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $info['contract_amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $info['real_amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$row, $info['other_amount']);
			}
		}

		// 保存
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // Excel5=>.xls、Excel2007=>xlsx
		$outputFileName = "项目列表-".date('Ymd').".xls";
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename={$outputFileName}");
		// $objWriter->save($outputFileName); // 保存到指定目录
		$objWriter->save('php://output');	  // 保存到浏览器输出
	}

/*
	public function create()
	{
		$this->initFileList();

		$managerList = $this->model->getMemberList('2');
		$userList = $this->model->getMemberList('3');

		$statList = array();
		foreach ($this->projectStatList as $k => $v) {
			$statList[$k] = $v['statusName'];
		}

		$lenLimitList = array(
			'projectDescLen'  => $this->projectDescLen,
			'commentLen' 	  => $this->commentLen,
			'customerNameLen' => $this->customerNameLen,
			'feedbackLen' 	  => $this->feedbackLen
		);

		$view 		  	    = $this->createView();
		$view->title  	    = '创建项目';
		$view->userList     = $userList;
		$view->managerList  = $managerList;
		$view->userId 	    = $this->userId;
		$view->roleId 	    = $this->roleId;
		$view->menuName 	= $this->menuName;
		$view->username     = Fuse_Cookie::getInstance()->username;
		$view->projectStat  = $statList;
		$view->typeList     = json_encode($this->typeList);
		$view->locationList = $this->locationList;
		$view->lenLimitList = $lenLimitList;
		$view->formhash     = Config_App::formhash('project');
        $view->display('project_create.html');
	}
*/

	public function update()
	{
		$projectId 		= Fuse_Request::getFormatVar($this->params, 'projectId', '1');
		$projectNo 		= Fuse_Request::getFormatVar($this->params, 'projectNo');
		$projectName 	= Fuse_Request::getFormatVar($this->params, 'projectName');
		$customerName 	= Fuse_Request::getFormatVar($this->params, 'customerName');
		$managerId 		= Fuse_Request::getFormatVar($this->params, 'managerId', '1');
		$status 		= Fuse_Request::getFormatVar($this->params, 'status', '1');
		$comment 		= Fuse_Request::getFormatVar($this->params, 'comment');
		$projectDesc 	= Fuse_Request::getFormatVar($this->params, 'projectDesc');
		$contractNo 	= Fuse_Request::getFormatVar($this->params, 'contractNo');
		$contractAmount = Fuse_Request::getFormatVar($this->params, 'contractAmount');
		$realAmount 	= Fuse_Request::getFormatVar($this->params, 'realAmount');
		$otherAmount 	= Fuse_Request::getFormatVar($this->params, 'otherAmount');
		$personnelList 	= Fuse_Request::getFormatVar($this->params, 'personnelList');
		$feedback 		= Fuse_Request::getFormatVar($this->params, 'feedback');

		if (empty($projectId) || empty($projectNo) || $projectName == '' || $customerName == '' || $managerId == ''|| $managerId == '0' || $status == '') {
			die(json_encode(array('code'=> '1111', 'message' => '请检查表单必填项是否有空', 'data' => '')));
		}

		if (!preg_match($this->locationRegular, $projectNo)) {
			die(json_encode(array('code'=> '2222', 'message' => '项目编号格式错误，请重新生成', 'data' => '')));
		}

		$statList = array();
		foreach ($this->projectStatList as $k => $v) {
			$statList[] = $k;
		}
		if (!in_array($status, $statList)) {
			die(json_encode(array('code'=> '3333', 'message' => '请选择项目状态', 'data' => '')));
		}

		/*if ($comment != '') {
			$commentLen = Fuse_Tool::utf8Strlen($comment);
			if ($commentLen > $this->commentLen) {
				die(json_encode(array('code'=> '4444', 'message' => '备注不能超过' . $this->commentLen . '个字符', 'data' => '')));
			}
		}

		if ($projectDesc != '') {
			$projectDescLen = Fuse_Tool::utf8Strlen(strip_tags($projectDesc));
			if ($projectDescLen > $this->projectDescLen) {
				die(json_encode(array('code'=> '5555', 'message' => '项目描述不能超过' . $this->projectDescLen . '个字符', 'data' => '')));
			}
		}*/

		/*if ($feedback != '') {
			$feedbackLen = Fuse_Tool::utf8Strlen(strip_tags($feedback));
			if ($feedbackLen > $this->feedbackLen) {
				die(json_encode(array('code'=> '6666', 'message' => '修改反馈不能超过' . $this->feedbackLen . '个字符', 'data' => '')));
			}
		}*/

		$detail = $this->model->getProjectDetail($projectNo, $this->typeList);

		/*$attachList = Fuse_Cookie::getInstance()->projectFile;
		if (!empty($attachList)) {
			$projectFile = array();
			foreach ($detail['attachment'] as $vAtt) {
				$projectFile[] = $vAtt['name'];
			}
			$attachList = unserialize($attachList);
			$attachList = array_merge($projectFile, $attachList);
			$attachList = array_unique($attachList);
		} else {
			$projectFile = array();
			foreach ($detail['attachment'] as $vAtt) {
				$projectFile[] = $vAtt['name'];
			}
			$attachList = $projectFile;
		}*/

		if (!empty($_SESSION['projectFile'])) {
			$projectFile = array();
			if (!empty($detail['attachment'])) {
				foreach ($detail['attachment'] as $vAtt) {
					$projectFile[] = $vAtt['name'];
				}
			}
			$attachList = $_SESSION['projectFile'];
			$attachList = array_merge($projectFile, $attachList);
			$attachList = array_unique($attachList);
		} else {
			$projectFile = array();
			if (!empty($detail['attachment'])) {
				foreach ($detail['attachment'] as $vAtt) {
					$projectFile[] = $vAtt['name'];
				}
			}
			$attachList = $projectFile;
		}

		/*$contractAttachList = Fuse_Cookie::getInstance()->projectContractFile;
		if (!empty($contractAttachList)) {
			$projectContractFile = array();
			foreach ($detail['contract_attachment'] as $vAtt) {
				$projectContractFile[] = $vAtt['name'];
			}
			$contractAttachList = unserialize($contractAttachList);
			$contractAttachList = array_merge($projectContractFile, $contractAttachList);
			$contractAttachList = array_unique($contractAttachList);
		} else {
			$projectContractFile = array();
			foreach ($detail['contract_attachment'] as $vAtt) {
				$projectContractFile[] = $vAtt['name'];
			}
			$contractAttachList = $projectContractFile;
		}*/

		if (in_array($this->roleId, array('0', '2'))) {
			if (!empty($_SESSION['projectContractFile'])) {
				$projectContractFile = array();
				if (!empty($detail['contract_attachment'])) {
					foreach ($detail['contract_attachment'] as $vAtt) {
						$projectContractFile[] = $vAtt['name'];
					}
				}
				$contractAttachList = $_SESSION['projectContractFile'];
				$contractAttachList = array_merge($projectContractFile, $contractAttachList);
				$contractAttachList = array_unique($contractAttachList);
			} else {
				$projectContractFile = array();
				if (!empty($detail['contract_attachment'])) {
					foreach ($detail['contract_attachment'] as $vAtt) {
						$projectContractFile[] = $vAtt['name'];
					}
				}
				$contractAttachList = $projectContractFile;
			}
		}

		if (!empty($_SESSION['projectProposalFile'])) {
			$projectProposalFile = array();
			if (!empty($detail['proposal_attachment'])) {
				foreach ($detail['proposal_attachment'] as $vAtt) {
					$projectProposalFile[] = $vAtt['name'];
				}
			}
			$proposalAttachList = $_SESSION['projectProposalFile'];
			$proposalAttachList = array_merge($projectProposalFile, $proposalAttachList);
			$proposalAttachList = array_unique($proposalAttachList);
		} else {
			$projectProposalFile = array();
			if (!empty($detail['proposal_attachment'])) {
				foreach ($detail['proposal_attachment'] as $vAtt) {
					$projectProposalFile[] = $vAtt['name'];
				}
			}
			$proposalAttachList = $projectProposalFile;
		}

		if (!empty($_SESSION['projectMeetingNoteFile'])) {
			$projectMeetingNoteFile = array();
			if (!empty($detail['meetingnote_attachment'])) {
				foreach ($detail['meetingnote_attachment'] as $vAtt) {
					$projectMeetingNoteFile[] = $vAtt['name'];
				}
			}
			$MeetingNoteList = $_SESSION['projectMeetingNoteFile'];
			$MeetingNoteList = array_merge($projectMeetingNoteFile, $MeetingNoteList);
			$MeetingNoteList = array_unique($MeetingNoteList);
		} else {
			$projectMeetingNoteFile = array();
			if (!empty($detail['meetingnote_attachment'])) {
				foreach ($detail['meetingnote_attachment'] as $vAtt) {
					$projectMeetingNoteFile[] = $vAtt['name'];
				}
			}
			$MeetingNoteList = $projectMeetingNoteFile;
		}

		$time = Config_App::getTime();
		$object = array(
			'project_name'		 	 => $projectName,
			'customer_name'		 	 => $customerName,
			'project_manager_id' 	 => $managerId,
			'status' 			 	 => $status,
			'attachment'		 	 => serialize($attachList),
			// 'contract_attachment'	 => serialize($contractAttachList),
			'proposal_attachment'    => serialize($proposalAttachList),
			'meetingnote_attachment' => serialize($MeetingNoteList),
			// 'contract_no' 		 	 => $contractNo,
			// 'contract_amount' 	 	 => $contractAmount,
			// 'real_amount' 		 	 => $realAmount,
			// 'other_amount' 		 	 => $otherAmount,
			'project_desc' 		 	 => $projectDesc,
			'comment' 			 	 => $comment,
			'last_mod_user_id' 	 	 => $this->userId,
			'last_mod_time' 	 	 => $time
		);
		
		// 项目完成日期
		if ($status == 2) {
			$object['finished_date'] = Config_App::getDate();
		}
		// 项目取消日期 
		else if ($status == 3) {
			$object['cancel_date'] = Config_App::getDate();
		}

		if (in_array($this->roleId, array('0', '2'))) {
			$object['contract_attachment'] = serialize($contractAttachList);
			$object['contract_no'] 	   	   = $contractNo;
			$object['contract_amount']     = $contractAmount;
			$object['real_amount'] 	       = $realAmount;
			$object['other_amount']        = $otherAmount;
		}

		/*$execUsersIds = array();
		if (!empty($personnelList)) {
			foreach ($personnelList as $v) {
				if (empty($v['user_id']) || empty($v['user_type']) || empty($v['user_no'])
					|| empty($v['start_time']) || empty($v['end_time'])
					|| empty($v['work_unit']) || empty($v['plan_score'])) {
					continue;
				}
				$execUsersIds[] = $v['user_id'];
			}
		}

		if (!empty($execUsersIds)) {
			if (!empty($detail['exec_users_ids'])) {
				$arr1 = explode(',', $detail['exec_users_ids']);
				$object['exec_users_ids'] = array_merge($arr1, $execUsersIds);
			} else {
				$object['exec_users_ids'] = array_values($execUsersIds);
			}
			$object['exec_users_ids'] = array_unique($object['exec_users_ids']);
			$object['exec_users_ids'] = implode(',', $object['exec_users_ids']);
		} else {
			if (!empty($detail['exec_users_ids'])) {
				$object['exec_users_ids'] = $detail['exec_users_ids'];
			} else {
				$object['exec_users_ids'] = '';
			}
		}*/

		$this->model->update($this->model->getTableProjectName(), $object, " `project_no` = '{$projectNo}' ");

		// 保存修改反馈
		/*if (!empty($feedback)) {
			$objArray = array(
				'project_id' => $projectId,
				'content' 	 => $feedback,
				'user_id' 	 => $this->userId,
				'ip' 	  	 => Config_App::getIP(),
				'time' 	  	 => $time
			);
			$this->model->store('project_feedback', $objArray);
		}*/

		if (!empty($personnelList)) {
			$personnelList = json_decode($personnelList, true);
			if (!empty($personnelList)) {
				foreach ($personnelList as $v) {				
					if (empty($v['userNoId']) || empty($v['userId']) || empty($v['userType']) 
						|| empty($v['userNo']) || empty($v['startTime']) || empty($v['endTime'])
						|| $v['workUnit'] == '' || $v['planScore'] == '') {
						continue;
					}	
					
					$obj = array(
						'project_id' => $projectId,
						'user_id' 	 => $v['userId'],
						'type'       => $v['userType'],
						'start_time' => $v['startTime'] == '--' ? '' : $v['startTime'],
						'end_time'   => $v['endTime'] == '--' ? '' : $v['endTime'],
						'work_unit'  => $v['workUnit'] * 1,
						'plan_score' => $v['planScore'] * 1,
						'real_score' => $v['realScore'],
						'valid'      => '1'
					);
					
					// 项目经理不能修改实际分值
					if ($this->roleId == '2') {
						unset($obj['real_score']);
					}
					$this->model->update($this->model->getTableExecUserName(), $obj, " `job_no` = '{$v['userNo']}' AND `execuser_id` = '{$v['userNoId']}' ");

					// 生成工单号对应的文件夹
					$rootDir = Config_App::rootdir();
					$saveDir = $rootDir . '/' . $projectNo . '/';
					if (!is_dir($saveDir . $v['userNo'])) {
						@mkdir($saveDir . $v['userNo'], 0777, true);
					}
				}
			}

			$this->model->updateProjectUserData($projectId);
		}

		// $this->initFileList();
		die(json_encode(array('code'=> '0000', 'message' => '修改成功', 'data' => '')));
	}

	public function insert()
	{
		$projectId 		= Fuse_Request::getFormatVar($this->params, 'projectId', '1');
		$projectNo 		= Fuse_Request::getFormatVar($this->params, 'projectNo');
		$location 		= Fuse_Request::getFormatVar($this->params, 'location');
		$projectName 	= Fuse_Request::getFormatVar($this->params, 'projectName');
		$customerName 	= Fuse_Request::getFormatVar($this->params, 'customerName');
		$startDate 		= Fuse_Request::getFormatVar($this->params, 'startDate');
		$managerId 		= Fuse_Request::getFormatVar($this->params, 'managerId', '1');
		$status 		= Fuse_Request::getFormatVar($this->params, 'status', '1');
		$comment 		= Fuse_Request::getFormatVar($this->params, 'comment');
		$projectDesc 	= Fuse_Request::getFormatVar($this->params, 'projectDesc');
		$contractNo 	= Fuse_Request::getFormatVar($this->params, 'contractNo');
		$contractAmount = Fuse_Request::getFormatVar($this->params, 'contractAmount');
		$realAmount 	= Fuse_Request::getFormatVar($this->params, 'realAmount');
		$otherAmount 	= Fuse_Request::getFormatVar($this->params, 'otherAmount');
		$personnelList 	= Fuse_Request::getFormatVar($this->params, 'personnelList');
		$feedback 		= Fuse_Request::getFormatVar($this->params, 'feedback');

		if ($projectNo == '' || $projectName == '' || $customerName == '' || $startDate == '' || $managerId == ''|| $managerId == '0' || $status == '') {
			die(json_encode(array('code'=> '1111', 'message' => '请检查表单必填项是否有空', 'data' => '')));
		}

		if (!preg_match($this->locationRegular, $projectNo)) {
			die(json_encode(array('code'=> '2222', 'message' => '项目编号格式错误，请重新生成', 'data' => '')));
		}

		if ($this->model->checkProjectNoExists($projectNo)) {
			die(json_encode(array('code'=> '3333', 'message' => '项目编号已存在，请重新生成', 'data' => '')));
		}

		$locaList = array_keys($this->locationList);
		if (!in_array($location, $locaList)) {
			die(json_encode(array('code'=> '4444', 'message' => '请选择创建项目区域', 'data' => '')));
		}

		if ($customerName != '') {
			$customerNameLen = Fuse_Tool::utf8Strlen($customerName);
			if ($customerNameLen > $this->customerNameLen) {
				die(json_encode(array('code'=> '5555', 'message' => '客户名称不能超过' . $this->customerNameLen . '个字符', 'data' => '')));
			}
		}

		$statList = array();
		foreach ($this->projectStatList as $k => $v) {
			$statList[$k] = $k;
		}
		if (!in_array($status, $statList)) {
			die(json_encode(array('code'=> '6666', 'message' => '请选择项目状态', 'data' => '')));
		}

		/*if ($comment != '') {
			$commentLen = Fuse_Tool::utf8Strlen($comment);
			if ($commentLen > $this->commentLen) {
				die(json_encode(array('code'=> '7777', 'message' => '备注不能超过' . $this->commentLen . '个字符', 'data' => '')));
			}
		}

		if ($projectDesc != '') {
			$projectDescLen = Fuse_Tool::utf8Strlen(strip_tags($projectDesc));
			if ($projectDescLen > $this->projectDescLen) {
				die(json_encode(array('code'=> '8888', 'message' => '项目描述不能超过' . $this->projectDescLen . '个字符', 'data' => '')));
			}
		}*/

		/*$projectFileList = $_SESSION['projectFile']; // Fuse_Cookie::getInstance()->projectFile;
		if (empty($projectFileList)) {
			die(json_encode(array('code'=> '9999', 'message' => '请上传项目资料', 'data' => '')));
		}

		$projectContractFileList = $_SESSION['projectContractFile']; // Fuse_Cookie::getInstance()->projectContractFile;

		$projectProposalFileList = $_SESSION['projectProposalFile'];
		$projectMeetingNoteFileList = $_SESSION['projectMeetingNoteFile'];*/


		$projectFileList = explode(',', Fuse_Request::getFormatVar($this->params, 'projectFile'));
		if (empty($projectFileList)) {
			die(json_encode(array('code'=> '9999', 'message' => '请上传项目资料', 'data' => '')));
		}

		$projectContractFileList = explode(',', Fuse_Request::getFormatVar($this->params, 'projectContractFile'));
		$projectProposalFileList = explode(',', Fuse_Request::getFormatVar($this->params, 'projectProposalFile'));
		$projectMeetingNoteFileList = explode(',', Fuse_Request::getFormatVar($this->params, 'projectMeetingNoteFile'));

		if (!empty($projectContractFileList)) {
			if ($contractNo == '') {
				// die(json_encode(array('code'=> '1000', 'message' => '请填写合同号', 'data' => '')));
			}
		}

		/*if ($feedback != '') {
			$feedbackLen = Fuse_Tool::utf8Strlen($feedback);
			if ($feedbackLen > $this->feedbackLen) {
				die(json_encode(array('code'=> '1001', 'message' => '修改反馈不能超过' . $this->feedbackLen . '个字符', 'data' => '')));
			}
		}*/

		$time = Config_App::getTime();
		$ip   = Config_App::getIP();
		$object = array(
			'location'			 	 => $location,
			'project_no'		 	 => $projectNo,
			'project_name'		 	 => $projectName,
			'customer_name'			 => $customerName,
			'start_date' 		 	 => $startDate,
			'project_manager_id' 	 => $managerId,
			'status' 			 	 => $status,
			'attachment'		 	 => serialize($projectFileList),
			'contract_attachment' 	 => serialize($projectContractFileList),
			'proposal_attachment' 	 => serialize($projectProposalFileList),
			'meetingnote_attachment' => serialize($projectMeetingNoteFileList),
			'contract_no' 		 	 => $contractNo,
			'contract_amount' 	 	 => $contractAmount,
			'real_amount' 		 	 => $realAmount,
			'other_amount' 		 	 => $otherAmount,
			'project_desc' 		 	 => $projectDesc,
			'comment' 			 	 => $comment,
			'last_mod_user_id' 	 	 => $this->userId,
			'last_mod_time' 	 	 => $time,
			'ip' 				 	 => $ip,
			'time' 				 	 => $time,
			'valid'			 		 => '1'
		);

		$execUsersIds = array();
		$personnelObj = array();
		if (!empty($personnelList)) {
			$personnelList = json_decode($personnelList, true);
			if (!empty($personnelList)) {
				foreach ($personnelList as $v) {
					if (empty($v['userNoId']) || empty($v['userId']) || empty($v['userType']) 
						|| empty($v['userNo']) || empty($v['startTime']) || empty($v['endTime'])
						|| $v['workUnit'] == '' || $v['planScore'] == '') {
						continue;
					}
					$execUsersIds[] = $v['userId'];
					
					$personnelObj[] = array(
						'execuser_id' => $v['userNoId'],
						'user_no'     => $v['userNo'],
						'project_id'  => 0,
						'user_id' 	  => $v['userId'],
						'type'        => $v['userType'],
						'start_time'  => $v['startTime'],
						'end_time'    => $v['endTime'],
						'work_unit'   => $v['workUnit'] * 1,
						'plan_score'  => $v['planScore'] * 1,
						'real_score'  => '',
						'valid'       => '1'
					);
				}
			}
		}
		$object['exec_users_ids'] = '';
		if (!empty($execUsersIds)) {
			$object['exec_users_ids'] = implode(',', $execUsersIds);
		}

		if (empty($object['exec_users_ids'])) {
			die(json_encode(array('code'=> '1003', 'message' => '请添加执行人员', 'data' => '')));
		}

		$model = new Fuse_Model();
		if ($projectNo && $projectId) {
			$model->update($this->model->getTableProjectName(), $object, " `project_no` = '{$projectNo}' ");
			$returnId = $projectNoId;
		} else {
			$returnId = $model->store($this->model->getTableProjectName(), $object);
			if (!$returnId) {
				die(json_encode(array('code'=> '1004', 'message' => '创建失败', 'data' => '')));
			}
		}

		// 保存修改反馈
		/*if (!empty($feedback)) {
			$objArray = array(
				'project_id' => $projectNoId,
				'content' 	 => $feedback,
				'user_id' 	 => $this->userId,
				'ip' 	  	 => $ip,
				'time' 	  	 => $time
			);
			$model->store('project_feedback', $objArray);
		}*/

		if (!empty($personnelObj)) {
			foreach ($personnelObj as $obj) {
				$obj['project_id'] = $returnId;
				$userNo = $obj['user_no'];
				$execuserId = $obj['execuser_id'];
				unset($obj['user_no']);
				unset($obj['execuser_id']);

				$model->update($this->model->getTableExecUserName(), $obj, " `job_no` = '{$userNo}' AND `execuser_id` = '{$execuserId}' ");

				// 生成工单号对应的文件夹
				$rootDir = Config_App::rootdir();
				$saveDir = $rootDir . '/' . $projectNo . '/';
				if (!is_dir($saveDir . $userNo)) {
					@mkdir($saveDir . $userNo, 0777, true);
				}
			}
		}

		// $this->initFileList();
		die(json_encode(array('code'=> '0000', 'message' => '创建成功', 'data' => '')));
	}

	public function upload1()
	{
		$name = Fuse_Request::getVar('name');
		if (!in_array($name, $this->fileUploadList)) {
			die(json_encode(array('code'=> '1111', 'message' => '上传失败，参数错误', 'data' => '')));
		}

		/*include_once(dirname(__FILE__) . '/UploadHelper.php');
		$uploadRoot = Config_App::rootdir() . '/upload/';
		UploadHelper::setDir($uploadRoot);
		$fileInfo = UploadHelper::upload();

		if ($fileInfo['status'] == 'fail') {
			die(json_encode(array('status'=> 'FAIL', 'msg' => '上传失败')));
			die(json_encode(array('code'=> '2222', 'message' => '上传失败', 'data' => '')));
		}

		$list = Fuse_Cookie::getInstance()->$name;
		if (!empty($list)) {
			$list = unserialize($list);
			$list[] = $fileInfo['fileName'];
		} else {
			$list = array($fileInfo['fileName']);
		}
		Fuse_Cookie::getInstance()->$name = serialize($list);

		die(json_encode(array('code'=> '0000', 'message' => '上传成功', 'data' => array('file' => $fileInfo['fileName']))));*/
	}

	public function upload()
	{
		$projectNo = Fuse_Request::getFormatVar($this->params, 'no');
		if (empty($projectNo)) {
			die(json_encode(array('code'=> '9001', 'message' => '请先填写项目编号', 'data' => '')));
		}

		$name = Fuse_Request::getFormatVar($this->params, 'name');
		if (!in_array($name, $this->fileUploadList)) {
			die(json_encode(array('code'=> '9002', 'message' => '上传失败，参数错误', 'data' => '')));
		}

		$model = new Fuse_Model();
		$info = $model->getRow("SELECT * FROM `{$this->model->getTableProjectName()}`
								WHERE `project_no` = '{$projectNo}' 
								AND `company_id` = '{$this->companyId}'");
		if (empty($info)) {
			die(json_encode(array('code'=> '9003', 'message' => '项目编号参数错误', 'data' => '')));
		}

		$rootDir = Config_App::rootdir();
		$filePrefixDir = '/projectUpoadFile/' . $projectNo . '/';
		$saveDir = $rootDir . $filePrefixDir;

		if ($name == 'projectFile') {
			$fileDir = 'project_info';
		} else if ($name == 'projectContractFile') {
			$fileDir = 'contract_info';
		} else if ($name == 'projectProposalFile') {
			$fileDir = 'proposal_info';
		} else if ($name == 'projectMeetingNoteFile') {
			$fileDir = 'meetingnote_info';
		}

		$saveDir = $saveDir . $fileDir;
		if (!is_dir($saveDir)) {
			@mkdir($saveDir, 0777, true);
			@chmod($saveDir, 0777);
		}

		require_once dirname(__FILE__) . '/upload/handler.php';
		$uploader = new UploadHandler();
		$uploader->sizeLimit = 1000 * (1024 * 1024);
		$uploader->inputName = 'qqfile';

		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == 'POST') {
			$result = $uploader->handleUpload($saveDir);
			if (isset($result['error'])) {
				die(json_encode(array('success' => false, 'error' => $result['error'])));
			}
			
			$result['uploadName'] = $uploader->getUploadName();
			@chmod($saveDir . '/' . $result['uploadName'], 0777);
			$result['uploadFile'] = Config_App::homeurl() . $filePrefixDir . $fileDir;
			// $this->saveAttach($name, $result["uploadName"]);
			echo json_encode($result);
		} else if ($method == 'DELETE') {
			$file = Fuse_Request::getFormatVar($this->params, 'uuid');
			if (strpos($delDir, '.') !== false || strpos($delDir, '..') !== false) {
				die(json_encode(array('success' => false, 'uuid' => $file)));
			}
			
			$file = str_replace('/', '', $file);
			$file1 = iconv('UTF-8', 'GBK', $file);
			$delDir = $saveDir . '/' . $file1;
			$result = $uploader->handleDelete($delDir);
			/*if ($result['success']) {
				$this->delAttach($name, $file);
			}*/
			echo json_encode($result);
		} else {
			header('HTTP/1.0 405 Method Not Allowed');
		}
		exit();
	}

	private function saveAttach($name, $file)
	{
		/*$list = Fuse_Cookie::getInstance()->$name;
		if (!empty($list)) {
			$list = unserialize($list);
			$list[] = $file;
		} else {
			$list = array($file);
		}

		$list = array_unique($list);
		Fuse_Cookie::getInstance()->$name = serialize($list);*/

		if (!empty($_SESSION[$name])) {
			$_SESSION[$name][] = $file;
		} else {
			$_SESSION[$name] = array($file);
		}

		$_SESSION[$name] = array_filter($_SESSION[$name]);
	}

	private function delAttach($name, $file)
	{
		/*$list = Fuse_Cookie::getInstance()->$name;
		if (!empty($list)) {
			$list = unserialize($list);
			$newFile = array();
			foreach ($list as $value) {
				if ($value != $file) {
					$newFile[] = $value;
				}
			}
			unset($list);
			Fuse_Cookie::getInstance()->$name = serialize($newFile);
		}*/

		if (!empty($_SESSION[$name])) {
			$newFile = array();
			foreach ($_SESSION[$name] as $value) {
				if ($value != $file) {
					$newFile[] = $value;
				}
			}
			unset($_SESSION[$name]);
			$_SESSION[$name] = $newFile;
		}
	}

	public function getProjectNo()
	{
		$location = Fuse_Request::getFormatVar($this->params, 'location');

		$locaList = array_keys($this->locationList);
		if (!in_array($location, $locaList)) {
			die(json_encode(array('code'=> '1111', 'message' => '请选择创建项目区域', 'data' => '')));
		}
		
		$no = $this->model->getProjectNo($this->locationList[$location]['en']);

		if (empty($no)) {
			die(json_encode(array('code'=> '2222', 'message' => '生成失败，请重新生成', 'data' => '')));
		}

		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => array('project_no' => $no))));
	}

	public function getUserNo()
	{
		$list = $this->model->getUserNo($this->companyId);

		if (empty($list['no'])) {
			die(json_encode(array('code'=> '1111', 'message' => '生成失败，请重新生成', 'data' => '')));
		}
		
		die(json_encode(array('code'=> '0000', 'message' => '删除成功', 'data' => array('user_no' => $list['no'], 'user_no_id' => $list['id']))));
	}

	public function detail()
	{
		// $this->initFileList();

		$projectNo = Fuse_Request::getFormatVar($this->params, 'productNo');
		if (empty($projectNo)) {
			Fuse_Response::redirect($this->url);
		}

		$detail = $this->model->getProjectDetail($projectNo, $this->typeList);
		if (empty($detail)) {
			die(json_encode(array('code'=> '1111', 'message' => '项目编号不存在', 'data' => '')));
		}

		$model1 = $this->createModel('Model_Member', dirname( __FILE__ ));
		$managerList = $model1->getMemberList('2');
		$userList = $model1->getMemberList('3');

		

		$lenLimitList = array(
			'projectDescLen'  => $this->projectDescLen,
			'commentLen' 	  => $this->commentLen,
			'customerNameLen' => $this->customerNameLen,
			'feedbackLen' 	  => $this->feedbackLen
		);

		$statList = array();
		foreach ($this->projectStatList as $k => $v) {
			$statList[$k] = $v['statusName'];
		}
        
        $data = array(
			'title' 	   => '项目详情',
			'detail' 	   => $detail,
			'roleId' 	   => $roleId,
			'username' 	   => $this->username,
			'projectStat'  => $projectStat,
			'typeList'     => json_encode($this->typeList),
			'typeList2'    => $this->typeList,
			'userList' 	   => $userList,
			'managerList'  => $managerList,
			'lenLimitList' => $lenLimitList,
			
        );
        
        die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => $data)));
	}

	public function delUser()
	{
		if ($this->roleId != '0') {
			die(json_encode(array('code'=> '1111', 'message' => '无权限操作', 'data' => '')));
		}

		$productNo = Fuse_Request::getVar('product_no', 'post');
		$productId = intval(Fuse_Request::getVar('product_id', 'post'));
		$userId    = intval(Fuse_Request::getVar('user_id', 'post'));
		$jobNo 	   = Fuse_Request::getVar('job_no', 'post');

		$this->model->delUser($productId, $userId, $jobNo);

		// 更新项目表执行人
		$this->model->updateProjectUserData($productId);

		// 删除执行人对应文件夹及文件
		$dir = Config_App::rootdir() . '/' . $productNo . '/' . $jobNo;
		$this->deldir($dir);

		die(json_encode(array('code'=> '0000', 'message' => '删除成功', 'data' => '')));
	}

	public function delFeedback()
	{
		if ($this->roleId != '1') {
			die(json_encode(array('code'=> '1111', 'message' => '无权限操作', 'data' => '')));
		}

		$feedbackId = intval(Fuse_Request::getVar('feedback_id', 'post'));
		$projectId  = intval(Fuse_Request::getVar('project_id', 'post'));

		if (!$this->model->delFeedback($feedbackId, $projectId)) {
			die(json_encode(array('code'=> '2222', 'message' => '删除失败', 'data' => '')));
		}

		die(json_encode(array('code'=> '0000', 'message' => '删除成功', 'data' => '')));
	}

	/**
	 * 删除指定文件夹及其下所有文件
	 */
	private function deldir($dir)
	{
		if (!is_dir($dir)) {
			return false;
		}

		// 先删除目录下的文件
		$dh = opendir($dir);
		while ($file = readdir($dh)) {
			if($file != '.' && $file != '..') {
				$fullpath = $dir . '/' . $file;
				if (!is_dir($fullpath)) {
					@unlink($fullpath);
				} else {
					$this->deldir($fullpath);
				}
			}
		}

		closedir($dh);

		// 删除当前文件夹
		if (!rmdir($dir)) {
			return false;
		}

		return true;
	}

	private function initFileList()
	{
		$_SESSION['projectContractFile'] 	= '';
		$_SESSION['projectFile']			= '';
		$_SESSION['projectProposalFile'] 	= '';
		$_SESSION['projectMeetingNoteFile'] = '';
	}

	public function file1()
	{
		ini_set('memory_limit', '500M');
		set_time_limit(0);
		include_once(dirname(__FILE__) . '/UploadHelper.php');

		$uploadRoot = Config_App::rootdir() . '/upload/';
		UploadHelper::setDir($uploadRoot);
		$fileInfo = UploadHelper::upload();

		die(json_encode(array('code'=> '0000', 'message' => '成功', 'data' => array('msg' => $fileInfo['fileName']))));
	}
}
